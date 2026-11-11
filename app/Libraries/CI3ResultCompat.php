<?php

namespace App\Libraries;

use CodeIgniter\Database\ResultInterface;

class CI3ResultCompat
{
    protected ?ResultInterface $result;

    public function __construct(?ResultInterface $result = null)
    {
        $this->result = $result;
    }

    public function result(string $type = 'object')
    {
        if (!$this->result) {
            return [];
        }
        return $this->result->getResult($type);
    }

    public function result_array(): array
    {
        if (!$this->result) {
            return [];
        }
        return $this->result->getResultArray();
    }

    public function row($n = 0, string $type = 'object')
    {
        if (!$this->result) {
            return null;
        }

        if (is_string($n)) {
            $row = $this->result->getRow();
            if (!$row) {
                return null;
            }
            return $row->$n ?? null;
        }

        return $this->result->getRow($n, $type);
    }

    public function row_array($n = 0)
    {
        if (!$this->result) {
            return null;
        }
        return $this->result->getRowArray($n);
    }

    public function num_rows(): int
    {
        if (!$this->result) {
            return 0;
        }
        return $this->result->getNumRows();
    }

    public function free_result(): void
    {
        // No-op for modern PDO/MySQLi
    }

    public function getResultInterface(): ?ResultInterface
    {
        return $this->result;
    }

    public function __call(string $method, array $args)
    {
        if ($this->result && method_exists($this->result, $method)) {
            return $this->result->{$method}(...$args);
        }
        return null;
    }
}

