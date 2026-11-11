<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\BaseBuilder;

class CI3DBCompat
{
    public BaseConnection $db;
    public ?BaseBuilder $builder = null;
    protected ?string $currentTable = null;
    protected array $deferredCalls = [];

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?: \Config\Database::connect();
    }

    protected function queueCall(string $method, array $args): self
    {
        if ($this->builder !== null) {
            $this->builder->{$method}(...$args);
        } else {
            $this->deferredCalls[] = [$method, $args];
        }
        return $this;
    }

    protected function applyDeferred(BaseBuilder $builder): void
    {
        foreach ($this->deferredCalls as [$method, $args]) {
            $builder->{$method}(...$args);
        }
        $this->deferredCalls = [];
    }

    public function table(string $tableName): BaseBuilder
    {
        $this->currentTable = $tableName;
        $this->builder = $this->db->table($tableName);
        $this->applyDeferred($this->builder);
        return $this->builder;
    }

    protected function getBuilder(?string $table = null): BaseBuilder
    {
        if ($table !== null && $table !== '') {
            $this->currentTable = $table;
            $this->builder = $this->db->table($table);
            $this->applyDeferred($this->builder);
        } elseif ($this->builder === null) {
            if ($this->currentTable !== null && $this->currentTable !== '') {
                $this->builder = $this->db->table($this->currentTable);
                $this->applyDeferred($this->builder);
            } else {
                throw new \RuntimeException('No database table specified for query builder');
            }
        }
        return $this->builder;
    }

    public function from(string $from): self
    {
        $this->currentTable = $from;
        $this->builder = $this->db->table($from);
        $this->applyDeferred($this->builder);
        return $this;
    }

    public function select($select = '*', ?bool $escape = null): self
    {
        return $this->queueCall('select', [$select, $escape]);
    }

    public function join(string $table, string $cond, string $type = '', ?bool $escape = null): self
    {
        return $this->queueCall('join', [$table, $cond, $type, $escape]);
    }

    public function where($key, $value = null, ?bool $escape = null): self
    {
        return $this->queueCall('where', [$key, $value, $escape]);
    }

    public function or_where($key, $value = null, ?bool $escape = null): self
    {
        return $this->queueCall('orWhere', [$key, $value, $escape]);
    }

    public function where_in($key = null, $values = null, ?bool $escape = null): self
    {
        return $this->queueCall('whereIn', [$key, $values, $escape]);
    }

    public function or_where_in($key = null, $values = null, ?bool $escape = null): self
    {
        return $this->queueCall('orWhereIn', [$key, $values, $escape]);
    }

    public function where_not_in($key = null, $values = null, ?bool $escape = null): self
    {
        return $this->queueCall('whereNotIn', [$key, $values, $escape]);
    }

    public function or_where_not_in($key = null, $values = null, ?bool $escape = null): self
    {
        return $this->queueCall('orWhereNotIn', [$key, $values, $escape]);
    }

    public function like($field, string $match = '', string $side = 'both', ?bool $escape = null, bool $insensitiveSearch = false): self
    {
        return $this->queueCall('like', [$field, $match, $side, $escape, $insensitiveSearch]);
    }

    public function or_like($field, string $match = '', string $side = 'both', ?bool $escape = null, bool $insensitiveSearch = false): self
    {
        return $this->queueCall('orLike', [$field, $match, $side, $escape, $insensitiveSearch]);
    }

    public function not_like($field, string $match = '', string $side = 'both', ?bool $escape = null, bool $insensitiveSearch = false): self
    {
        return $this->queueCall('notLike', [$field, $match, $side, $escape, $insensitiveSearch]);
    }

    public function group_start(): self
    {
        return $this->queueCall('groupStart', []);
    }

    public function group_end(): self
    {
        return $this->queueCall('groupEnd', []);
    }

    public function order_by($orderBy, string $direction = '', ?bool $escape = null): self
    {
        return $this->queueCall('orderBy', [$orderBy, $direction, $escape]);
    }

    public function group_by($by, ?bool $escape = null): self
    {
        return $this->queueCall('groupBy', [$by, $escape]);
    }

    public function limit(?int $value = null, ?int $offset = 0): self
    {
        return $this->queueCall('limit', [$value, $offset ?? 0]);
    }

    public function distinct(bool $val = true): self
    {
        return $this->queueCall('distinct', [$val]);
    }

    public function get(?string $table = null, ?int $limit = null, ?int $offset = null): CI3ResultCompat
    {
        $builder = $this->getBuilder($table);

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        $res = $builder->get();
        $this->builder = null;
        $this->currentTable = null;
        $this->deferredCalls = [];

        return new CI3ResultCompat($res);
    }

    public function get_where(string $table, $where = null, ?int $limit = null, ?int $offset = null): CI3ResultCompat
    {
        $builder = $this->db->table($table);
        $this->applyDeferred($builder);

        if ($where !== null) {
            $builder->where($where);
        }
        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        $res = $builder->get();
        $this->builder = null;
        $this->currentTable = null;
        $this->deferredCalls = [];

        return new CI3ResultCompat($res);
    }

    public function insert(string $table, $set = null, ?bool $escape = null): bool
    {
        $builder = $this->db->table($table);
        $this->applyDeferred($builder);
        $res = $builder->insert($set, $escape);
        $this->builder = null;
        $this->currentTable = null;
        $this->deferredCalls = [];
        return (bool)$res;
    }

    public function insert_batch(string $table, ?array $set = null, ?bool $escape = null, int $batchSize = 100): int
    {
        if (empty($set)) {
            return 0;
        }
        $builder = $this->db->table($table);
        $this->applyDeferred($builder);
        $res = (int)$builder->insertBatch($set, $escape, $batchSize);
        $this->builder = null;
        $this->currentTable = null;
        $this->deferredCalls = [];
        return $res;
    }

    public function update(?string $table = null, $set = null, $where = null, ?int $limit = null): bool
    {
        if ($table !== null && $this->builder === null) {
            $builder = $this->db->table($table);
            $this->applyDeferred($builder);
        } else {
            $builder = $this->getBuilder($table);
        }

        if ($where !== null) {
            $builder->where($where);
        }

        $res = $builder->update($set);
        $this->builder = null;
        $this->currentTable = null;
        $this->deferredCalls = [];
        return (bool)$res;
    }

    public function delete(?string $table = null, $where = '', ?int $limit = null, bool $resetData = true): bool
    {
        if ($table !== null && $this->builder === null) {
            $builder = $this->db->table($table);
            $this->applyDeferred($builder);
        } else {
            $builder = $this->getBuilder($table);
        }

        if (!empty($where)) {
            $builder->where($where);
        }

        $res = $builder->delete();
        $this->builder = null;
        $this->currentTable = null;
        $this->deferredCalls = [];
        return (bool)$res;
    }

    public function count_all(string $table): int
    {
        return $this->db->table($table)->countAll();
    }

    public function count_all_results(?string $table = null, bool $reset = true): int
    {
        if ($table !== null && $this->builder === null) {
            $builder = $this->db->table($table);
            $this->applyDeferred($builder);
        } else {
            $builder = $this->getBuilder($table);
        }

        $count = $builder->countAllResults($reset);
        if ($reset) {
            $this->builder = null;
            $this->currentTable = null;
            $this->deferredCalls = [];
        }
        return $count;
    }

    public function query(string $sql, $binds = false): CI3ResultCompat
    {
        $res = $this->db->query($sql, $binds);
        return new CI3ResultCompat($res);
    }

    public function insert_id(): int
    {
        return (int)$this->db->insertID();
    }

    public function affected_rows(): int
    {
        return (int)$this->db->affectedRows();
    }

    public function trans_start(): bool
    {
        return $this->db->transStart();
    }

    public function trans_complete(): bool
    {
        return $this->db->transComplete();
    }

    public function trans_status(): bool
    {
        return $this->db->transStatus();
    }

    public function trans_begin(): bool
    {
        return $this->db->transBegin();
    }

    public function trans_commit(): bool
    {
        return $this->db->transCommit();
    }

    public function trans_rollback(): bool
    {
        return $this->db->transRollback();
    }

    public function __call(string $method, array $args)
    {
        if ($this->builder !== null && method_exists($this->builder, $method)) {
            $res = $this->builder->{$method}(...$args);
            return ($res === $this->builder) ? $this : $res;
        }

        if (method_exists($this->db, $method)) {
            return $this->db->{$method}(...$args);
        }

        return $this->queueCall($method, $args);
    }
}
