<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support;

use Dompdf\Dompdf as PDF;
use Dompdf\Options;

class domPDF
{
    protected $paperSize;

    protected $paperOrientation;

    public function __construct()
    {
        $this->paperSize = config('bt.paperSize') ?: 'letter';
        $this->paperOrientation = config('bt.paperOrientation') ?: 'portrait';
    }

    public function setPaperSize($paperSize)
    {
        $this->paperSize = $paperSize;
    }

    // used in reports
    public function setPaperOrientation($paperOrientation)
    {
        $this->paperOrientation = $paperOrientation;
    }

    private function getPdf($html)
    {
        $options = new Options;

        $options->setTempDir(storage_path('/'));
        $options->setFontDir(storage_path('/'));
        $options->setFontCache(storage_path('/'));
        $options->setLogOutputFile(storage_path('dompdf_log'));
        $options->setIsRemoteEnabled(true);
        //        $options->setIsHtml5ParserEnabled(true);
        $options->setIsFontSubsettingEnabled(true);

        $pdf = new PDF($options);

        $pdf->setPaper($this->paperSize, $this->paperOrientation);

        // if batch
        $batch = '';
        if (is_array($html)) {
            foreach ($html as $doc) {
                $batch .= $doc; // . "<div style=\"page-break-after: always;\"></div>"; //not needed with dompdf 2
            }
            $pdf->loadHtml($batch);
        } else {
            $pdf->loadHtml($html);
        }
        $pdf->render();

        return $pdf;
    }

    public function getOutput($html)
    {
        $pdf = $this->getPdf($html);

        return $pdf->output();
    }

    public function save($html, $filename)
    {
        file_put_contents($filename, $this->getOutput($html));

    }

    public function download($html, $filename)
    {
        $pdf = $this->getPdf($html);

        if (config('bt.pdfDisposition') == 'attachment') {
            $pdf->stream($filename);
        } else {
            $pdf->stream($filename, ['Attachment' => false]);
        }

    }
}
