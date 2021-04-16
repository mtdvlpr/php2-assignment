<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Fpdf\Fpdf;
use chillerlan\QRCode\QRCode;

/**
 * A class that generates PDF files
 */
class PDF extends Fpdf
{

  public function __construct(
    private string $title
  )
  {
    parent::__construct(); // Initialize the PDF file
    parent::AliasNbPages(); // Link the total amount of pages to the variable in the footer
    parent::AddPage(); // Create the first page
  }

  /**
   * The header of every PDF page
   *
   * @return void
   */
  function Header(): void
  {
    $this->Image(__DIR__ . '/../public/img/logo/android-chrome-512x512.png', 10, 6, 30);
    $this->Ln();
    $this->SetFont('Arial', 'B', 15);
    $this->Cell(80);
    $this->Cell(100, 10, $this->title, 1, 0, 'C');
    $this->Ln(20);
  }

  /**
   * The footer of every PDF page
   *
   * @return void
   */
  function Footer(): void
  {
    $this->SetY(-15);
    $this->SetFont('Arial', 'I', 8);
    $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
  }

  /**
   * A personalized version of the MultiCell function
   *
   * This function also converts every special character in $txt into a version readable for the MultiCell function.
   *
   * @param string $txt The text to be displayed in a cell
   * @param mixed $border The border of the cell
   * @param string $align The alignment of the cell
   * @param mixed $w The width of the cell
   * @param mixed $h The height of the cell
   * @param bool $fill The fill of the cell
   *
   * @return void
   */
  private function print(string $txt, mixed $border = 0, string $align = 'L', mixed $w = 0, mixed $h = 10, bool $fill = false): void
  {
     $this->MultiCell($w, $h, iconv('UTF-8', 'windows-1252', $txt), $border, $align, $fill);
  }
}
