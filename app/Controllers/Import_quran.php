<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Import_quran extends CI_Controller {

    public function index()
    {
        $folder = APPPATH . 'quran-json/surah/';

        for ($i = 1; $i <= 114; $i++) {

            $file = $folder . $i . '.json';

            if (!file_exists($file)) {
                continue;
            }

            $json = file_get_contents($file);
            $data = json_decode($json, true);

            // Ambil data surah berdasarkan nomor
            if (!isset($data[$i])) {
                continue;
            }

            $surah = $data[$i];

            if (!isset($surah['text']) || 
                !isset($surah['translations']['id']['text'])) {
                continue;
            }

            foreach ($surah['text'] as $nomor => $arab) {

                $arti = $surah['translations']['id']['text'][$nomor];

                $insert = [
                    'surah_id' => $i,
                    'nomor'    => $nomor,
                    'arab'     => $arab,
                    'arti'     => $arti
                ];

                $this->db->insert('quran_ayat', $insert);
            }
        }

        echo "Import 114 surah selesai 🔥";
    }
}