<?php

namespace App\Libraries;

class CI3UploadCompat
{
    protected array $config = [];
    protected array $fileData = [];
    protected array $errors = [];

    public function __construct(array $config = [])
    {
        $this->initialize($config);
    }

    public function initialize(array $config = [], bool $reset = true): self
    {
        if ($reset) {
            $this->config = $config;
        } else {
            $this->config = array_merge($this->config, $config);
        }
        $this->errors = [];
        return $this;
    }

    public function do_upload(string $field = 'userfile'): bool
    {
        $request = service('request');
        $file = $request->getFile($field);

        if (!$file || !$file->isValid()) {
            $this->errors[] = $file ? $file->getErrorString() : 'No file uploaded or upload error';
            return false;
        }

        $allowedTypes = $this->config['allowed_types'] ?? '*';
        $maxSizeKb    = (float)($this->config['max_size'] ?? 0);
        $uploadPath   = $this->config['upload_path'] ?? './uploads/';
        $encryptName  = !empty($this->config['encrypt_name']);

        $ext = strtolower($file->getClientExtension());
        if ($allowedTypes !== '*') {
            $allowedList = array_map('trim', explode('|', strtolower($allowedTypes)));
            if (!in_array($ext, $allowedList, true)) {
                $this->errors[] = 'The filetype you are attempting to upload is not allowed (' . $ext . ').';
                return false;
            }
        }

        if ($maxSizeKb > 0 && ($file->getSize() / 1024) > $maxSizeKb) {
            $this->errors[] = 'The file you are attempting to upload is larger than the permitted size.';
            return false;
        }

        $destPath = rtrim($uploadPath, '/\\') . '/';
        if (!is_dir($destPath)) {
            mkdir($destPath, 0777, true);
        }

        if ($encryptName) {
            $newName = $file->getRandomName();
        } else {
            $newName = $file->getName();
        }

        $file->move($destPath, $newName, true);

        $fullPath = realpath($destPath . $newName);
        if (!$fullPath) {
            $fullPath = $destPath . $newName;
        }

        $this->fileData = [
            'file_name'    => $newName,
            'file_type'    => $file->getClientMimeType(),
            'file_path'    => $destPath,
            'full_path'    => str_replace('\\', '/', $fullPath),
            'raw_name'     => pathinfo($newName, PATHINFO_FILENAME),
            'orig_name'    => $file->getClientName(),
            'client_name'  => $file->getClientName(),
            'file_ext'     => '.' . $ext,
            'file_size'    => round($file->getSize() / 1024, 2),
            'is_image'     => in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true),
        ];

        return true;
    }

    public function data(?string $index = null)
    {
        if ($index !== null) {
            return $this->fileData[$index] ?? null;
        }
        return $this->fileData;
    }

    public function display_errors(string $open = '<p>', string $close = '</p>'): string
    {
        if (empty($this->errors)) {
            return '';
        }
        return $open . implode($close . $open, $this->errors) . $close;
    }
}

