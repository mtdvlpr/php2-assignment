<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
if (getenv('ENV') != 'production') {
  $dotenv->load();
}

/* Exception class. */
require_once __DIR__ . "/../vendor/phpmailer/phpmailer/src/Exception.php";

/* The main PHPMailer class. */
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';

/* SMTP class, needed if you want to use SMTP. */
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

/**
 * The mailer class which sends email, with the option of the subject, body and sender
 * An email address was previously created, which is where the emails are being sent from
 * Pre-existing attachments are possible, path and name needs to be written
 */
class Mailer
{
  public function __construct()
  {
  }

  public function sendMail(
    string $subject,
    string $body,
    string $address,
    ?string $pdfAttachmentPath = null,
    ?string $pdfAttachmentName = null
  )
  {
    try {
      // Server settings
      $mail = new PHPMailer(TRUE);
      $mail->isSMTP();
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = PHPMAILER::ENCRYPTION_SMTPS;
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = '465';
      $mail->Username = getenv('MAIL_SENDER');
      $mail->Password = getenv('MAIL_PASS');

      // Recipients
      $mail->SetFrom('noreply@moviesforyou.com');
      $mail->AddAddress($address);

      // Content
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $body;

      // Attachment
      if($pdfAttachmentPath != null && $pdfAttachmentName != null)
      {
        $mail->addAttachment($pdfAttachmentPath, $pdfAttachmentName);
      }

      $mail->Send();
    } catch (Exception $e) {
      throw new Exception("Something went wrong while sending the email: " . $mail->ErrorInfo);
    }
  }
}
