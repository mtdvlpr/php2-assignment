<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../model/PDF.php';
require_once __DIR__ . '/../../model/Mailer.php';
require_once __DIR__ . '/../../controller/paymentController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $paymentController = new PaymentController();
  $mollieId = $_POST['id'] ?? '';

  if (!empty($mollieId))
  {
    $status = $paymentController->getMollieOrderStatus($mollieId);
    $paymentController->setOrderStatus($mollieId, $status);

    if ($status == "paid")
    {
      // Get donation
      $donation = $paymentController->getDonationByMollieId($mollieId);

      // Create PDF
      $pdf = new PDF('Invoice for donation #' . $donation->getId());

      //$pdf->makeInvoice($donation);

      // Set PDF location and name
      $dir = __DIR__ . '/../../src/pdf/';
      $name = 'donation' . $donation->getId() . '.pdf';

      // Check if file  already exists, remove it if he does
      if (file_exists($dir . $name)) {
        unlink($dir . $name);
      }

      // Save PDF
      $pdf->Output('F', $dir . $name);


      //Mailer creation
      $mailer = new mailer();
      /*$mailer->sendMail(
        subject: "Haarlem Festival tickets",
        body: "In the attachment is your proof of donation!",
        address: $donation->getEmail(),
        pdfAttachmentPath: $dir . $name,
        pdfAttachmentName: "donation"
      );*/
    }
  }
}
?>
