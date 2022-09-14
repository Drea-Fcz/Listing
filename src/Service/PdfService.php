<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
 private $domPdf;

 public function __construct()
 {
     $this->domPdf = new Dompdf();

     // définir la configuration de DomPdf
     $pdfOptions = new Options();
     $pdfOptions->set('defaultFont', 'Garamond');

     $this->domPdf->setOptions($pdfOptions);
 }

 private function configDomPdf($html): void
 {
     $this->domPdf->loadHtml($html);
     $this->domPdf->render();
 }

 public function showPdfFile($html): void
 {
     $this->configDomPdf($html);
     $this->domPdf->stream("details.pdf", [
         'Attachment' => false
     ]);
 }

 public function generateBinaryPdf($html): void
 {
     $this->configDomPdf($html);
     $this->domPdf->output();
 }
}
