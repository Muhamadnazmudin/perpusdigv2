<?php

if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal)
    {
        if (!$tanggal) return '-';

        $bulan = [
            1 => 'Januari','Februari','Maret','April','Mei','Juni',
            'Juli','Agustus','September','Oktober','November','Desember'
        ];

        $tgl = date('d', strtotime($tanggal));
        $bln = (int)date('m', strtotime($tanggal));
        $thn = date('Y', strtotime($tanggal));

        return $tgl . ' ' . ($bulan[$bln] ?? '') . ' ' . $thn;
    }
}

