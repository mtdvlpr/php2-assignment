<?php
  require_once '../view/router.php';
  require_once '../model/PDF.php';
  require_once '../model/donation.php';
  require_once '../model/mailer.php';

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $router = new Router();
  //$router->handleRoute();
$pdf = new PDF('Invoice for donation #1');
$pdf->makeInvoice(new DonationModel(
  1,
  new DateTime(),
  20,
  'Manoah',
  '149895ja@gmail.com',
  'created',
  'hash'
));

// Set PDF location and name
$dir = __DIR__ . '/../src/pdf/';
$name = 'donation1.pdf';

// Check if file  already exists, remove it if he does
if (file_exists($dir . $name)) {
  unlink($dir . $name);
}

// Save PDF
$pdf->Output('F', $dir . $name);


//Send email
$mailer = new Mailer();
$mailer->sendMail(
  subject: "Donation Invoice",
  body: "In the attachment is your proof of donation!",
  address: '149895ja@gmail.com',
  pdfAttachmentPath: $dir . $name,
  pdfAttachmentName: "donation"
);
