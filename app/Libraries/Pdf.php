<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    protected Dompdf $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml(string $html): self
    {
        $this->dompdf->loadHtml($html);
        return $this;
    }

    public function setPaper(string $size = 'A4', string $orientation = 'portrait'): self
    {
        $orientation = strtolower(trim($orientation));
        if ($orientation === 'potrait') {
            $orientation = 'portrait';
        }
        $this->dompdf->setPaper($size, $orientation);
        return $this;
    }

    public function render(): self
    {
        $this->dompdf->render();
        return $this;
    }

    public function stream(string $filename = 'document.pdf', array $options = []): void
    {
        $this->dompdf->stream($filename, $options);
        exit;
    }

    public function output(): string
    {
        return $this->dompdf->output();
    }

    public function getDompdf(): Dompdf
    {
        return $this->dompdf;
    }
}
