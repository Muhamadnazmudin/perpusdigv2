<?php

namespace App\Libraries;

class CI3LoaderCompat
{
    protected object $controller;

    public function __construct(object $controller)
    {
        $this->controller = $controller;
    }

    public function view(string $view, array $data = [], bool $return = false)
    {
        if (isset($this->controller->data) && is_array($this->controller->data)) {
            $data = array_merge($this->controller->data, $data);
        }

        $html = view($view, $data);
        if ($return) {
            return $html;
        }

        echo $html;
        return null;
    }

    public function model($model, string $name = '', $db_conn = false): void
    {
        if (is_array($model)) {
            foreach ($model as $m) {
                $this->model($m);
            }
            return;
        }

        $className = ucfirst($model);
        $propName  = !empty($name) ? $name : $className;

        $fullClass = '\\App\\Models\\' . $className;
        if (!class_exists($fullClass)) {
            $file = APPPATH . 'Models/' . $className . '.php';
            if (is_file($file)) {
                require_once $file;
            }
        }

        if (class_exists($fullClass)) {
            $this->controller->{$propName} = new $fullClass();
        } elseif (class_exists($className)) {
            $this->controller->{$propName} = new $className();
        }
    }

    public function library($library, $params = null, ?string $object_name = null): void
    {
        if (is_array($library)) {
            foreach ($library as $l) {
                $this->library($l);
            }
            return;
        }

        $libName  = strtolower($library);
        $propName = !empty($object_name) ? $object_name : $libName;

        switch ($libName) {
            case 'upload':
                $this->controller->{$propName} = new \App\Libraries\CI3UploadCompat((array)$params);
                break;
            case 'pagination':
                $this->controller->{$propName} = new \App\Libraries\CI3PaginationCompat((array)$params);
                break;
            case 'pdf':
                $this->controller->{$propName} = new \App\Libraries\Pdf();
                break;
            case 'qrcode':
                $this->controller->{$propName} = new \App\Libraries\Qrcode();
                break;
            case 'session':
                $this->controller->{$propName} = new \App\Libraries\CI3SessionCompat();
                break;
            case 'database':
                $this->controller->db = new \App\Libraries\CI3DBCompat();
                break;
            default:
                $class = '\\App\\Libraries\\' . ucfirst($library);
                if (class_exists($class)) {
                    $this->controller->{$propName} = new $class($params);
                }
                break;
        }
    }

    public function helper($helpers): void
    {
        helper($helpers);
    }

    public function database(): void
    {
        if (!isset($this->controller->db)) {
            $this->controller->db = new \App\Libraries\CI3DBCompat();
        }
    }

    public function dbutil()
    {
        return new class {
            public function backup(array $prefs = [])
            {
                $db = \Config\Database::connect();
                $tables = $db->listTables();
                $sql = "-- Database Backup\n-- Date: " . date('Y-m-d H:i:s') . "\n\nSET FOREIGN_KEY_CHECKS = 0;\n\n";

                foreach ($tables as $table) {
                    $createRow = $db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
                    if ($createRow) {
                        $createSql = $createRow['Create Table'] ?? array_values($createRow)[1] ?? '';
                        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n" . $createSql . ";\n\n";
                    }

                    $rows = $db->table($table)->get()->getResultArray();
                    if (!empty($rows)) {
                        foreach ($rows as $row) {
                            $cols = array_map(function ($c) {
                                return '`' . $c . '`';
                            }, array_keys($row));
                            $vals = array_map(function ($v) use ($db) {
                                if ($v === null) {
                                    return 'NULL';
                                }
                                return $db->escape($v);
                            }, array_values($row));
                            $sql .= "INSERT INTO `{$table}` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
                        }
                        $sql .= "\n";
                    }
                }

                $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

                $format = $prefs['format'] ?? 'txt';
                if ($format === 'zip') {
                    $filename = $prefs['filename'] ?? 'backup.sql';
                    $zip = new \ZipArchive();
                    $tmpZip = tempnam(sys_get_temp_dir(), 'bak_');
                    if ($zip->open($tmpZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                        $zip->addFromString($filename, $sql);
                        $zip->close();
                        $content = file_get_contents($tmpZip);
                        @unlink($tmpZip);
                        return $content;
                    }
                }

                return $sql;
            }
        };
    }
}

