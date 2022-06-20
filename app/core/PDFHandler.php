<?php

declare(strict_types=1);

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFHandler {

  private Options $options;
  private Dompdf $dompdf;

  public function __construct()
  {
    $this->options = new Options();
    
    $this->options->setChroot(__DIR__);
    $this->options->setIsRemoteEnabled(true);

    $this->dompdf = new Dompdf($this->options);
    $this->dompdf->setPaper("A4", "portrait ");
  }

  public function populateTemplate(string $templatePath, array $parameters) : string {
    $html = file_get_contents($templatePath);
    
    // Try to unpack array keys and values, so the foreach loop is not needed/
    // $html = str_replace(["{{ name }}", "{{ quantity }}"], [$name, $quantity], $html);

    foreach ($parameters as $key => $value) {
      $html = str_replace("{{ $key }}", $value, $html);
    }

    return $html; 
  }
  
  public function generatePDF(string $html) : void {
    $this->dompdf->loadHtml($html);
    $this->dompdf->render();
    $this->dompdf->stream("Расходы.pdf", ["Attachment" => 0]);
  }
}
