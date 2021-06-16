<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../model/donation.php";

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

  public function makeInvoice(DonationModel $donation): void
  {
    // Set the font style
    parent::SetFont('Arial', '', 12);

    // New line
    parent::Ln(30);

    // Add text
    $this->print('Dear user,');

    // New line
    parent::Ln();

    // Add text
    $this->print("This is your donation invoice: ");

    // New line
    parent::Ln();

    // Show donation information
    $this->print('Email: ' . $donation->getEmail());
    $this->print('Name: ' . $donation->getName());
    $this->print('Order Date: ' . $donation->getDonationDate()->format('d-m-Y'));
    $this->print($donation->getAmount());

    // New line
    parent::Ln();

    // Set the style and show description of qr code
    parent::SetFont('Arial', '', 20);
    $this->print('This qr code can be used to retrieve your donation receipt online.');

    // Create a QR-code and set some attributes
    $qrcode = new QRCode();
    $filename = __DIR__ . '/../src/pdf/' . $donation->getId() . '.png';
    $url = sprintf('http://128.199.61.77:3000/donation?donationid=%shash=%s', $donation->getId(), $donation->getHash());

    // Create a QR-code image with the specified url and add it to the PDF
    $qrcode->render($url, $filename);
    parent::Image($filename);

    // Remove the image
    unlink($filename);
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
