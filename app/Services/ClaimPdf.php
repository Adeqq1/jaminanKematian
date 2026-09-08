<?php

namespace App\Services;

use App\Models\PengajuanKlaim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use setasign\Fpdi\Fpdi;

class ClaimPdf
{
    private const MAX_PAGES = 10;

    public function generate(PengajuanKlaim $pengajuan): string
    {
        $pengajuan->load(['peserta', 'nomorAntrian']);
        $documents = [
            ['label' => 'KTP peserta', 'path' => $pengajuan->peserta->foto_ktp],
            ['label' => 'Akta kematian', 'path' => $pengajuan->akta_kematian],
            ['label' => 'Kartu BPJS', 'path' => $pengajuan->kartu_bpjs],
            ['label' => 'Buku rekening ahli waris', 'path' => $pengajuan->buku_rekening],
        ];
        $pages = [];
        foreach ($documents as $document) {
            $path = $this->privatePath($document['path']);
            $mime = mime_content_type($path) ?: '';
            if (in_array($mime, ['image/jpeg', 'image/png'], true)) {
                $pages[] = ['label' => $document['label'], 'type' => 'image', 'path' => $path];
                continue;
            }
            if ($mime !== 'application/pdf') {
                throw new RuntimeException('Dokumen '.$document['label'].' tidak didukung.');
            }
            $source = new Fpdi;
            $count = $source->setSourceFile($path);
            if ($count > self::MAX_PAGES) {
                throw new RuntimeException('Dokumen '.$document['label'].' melebihi batas halaman.');
            }
            for ($page = 1; $page <= $count; $page++) {
                $pages[] = ['label' => $document['label'], 'type' => 'pdf', 'path' => $path, 'page' => $page];
            }
        }

        $cover = Pdf::loadView('pengajuan-klaim.pdf', [
            'pengajuan' => $pengajuan,
            'documentPages' => $this->documentRanges($pages),
        ])->setPaper('a4');
        $pdf = new Fpdi;
        $this->importPdfBytes($pdf, $cover->output());
        foreach ($pages as $page) {
            if ($page['type'] === 'pdf') {
                $pdf->setSourceFile($page['path']);
                $template = $pdf->importPage($page['page']);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($template);
            } else {
                $this->addImagePage($pdf, $page['path']);
            }
        }

        return $pdf->Output('S');
    }

    private function privatePath(string $storedPath): string
    {
        $disk = Storage::disk('local');
        abort_unless($storedPath !== '' && $disk->exists($storedPath), 404);
        $root = realpath($disk->path(''));
        $path = realpath($disk->path($storedPath));
        abort_unless($root && $path && str_starts_with($path, $root.DIRECTORY_SEPARATOR), 404);
        return $path;
    }

    private function importPdfBytes(Fpdi $pdf, string $bytes): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'claim-cover-');
        try {
            file_put_contents($tmp, $bytes);
            $pdf->setSourceFile($tmp);
            $template = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($template);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($template);
        } finally {
            if (is_string($tmp) && file_exists($tmp)) {
                unlink($tmp);
            }
        }
    }

    private function addImagePage(Fpdi $pdf, string $path): void
    {
        $size = getimagesize($path);
        abort_unless($size, 422);
        $orientation = $size[0] > $size[1] ? 'L' : 'P';
        $pdf->AddPage($orientation, 'A4');
        $width = $orientation === 'L' ? 267 : 180;
        $height = $orientation === 'L' ? 180 : 267;
        $scale = min($width / $size[0], $height / $size[1]);
        $drawWidth = $size[0] * $scale;
        $drawHeight = $size[1] * $scale;
        $pageWidth = $orientation === 'L' ? 297 : 210;
        $pageHeight = $orientation === 'L' ? 210 : 297;
        $pdf->Image($path, ($pageWidth - $drawWidth) / 2, ($pageHeight - $drawHeight) / 2, $drawWidth, $drawHeight);
    }

    private function documentRanges(array $pages): array
    {
        $ranges = [];
        foreach ($pages as $index => $page) {
            $number = $index + 2;
            if (! isset($ranges[$page['label']])) {
                $ranges[$page['label']] = [$number, $number];
            } else {
                $ranges[$page['label']][1] = $number;
            }
        }
        return $ranges;
    }
}
