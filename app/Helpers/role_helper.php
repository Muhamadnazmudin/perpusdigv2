<?php

if (!function_exists('is_admin')) {
    function is_admin(): bool
    {
        $user = session()->get('user');
        $role = $user['id_role'] ?? session()->get('role');
        return (int)$role === 1 || $role === 'admin';
    }
}

if (!function_exists('is_guru')) {
    function is_guru(): bool
    {
        $user = session()->get('user');
        $role = $user['id_role'] ?? session()->get('role');
        return (int)$role === 2 || $role === 'guru';
    }
}

if (!function_exists('is_siswa')) {
    function is_siswa(): bool
    {
        $user = session()->get('user');
        $role = $user['id_role'] ?? session()->get('role');
        return (int)$role === 3 || $role === 'siswa';
    }
}

