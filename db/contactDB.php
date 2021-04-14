<?php
require_once __DIR__ . '/baseDB.php';

class ContactDB extends BaseDB
{
  public function __construct()
  {
  }

    /**
     * Insert the contact form details into the database
     *
     * @param string @email The email address of the user
     * @param string @name The name of the user
     * @param string @subject The subject of the message
     * @param string @message The message
     */
  public function addInformation(string $email, string $name, string $subject, string $message): void
  {
      $this->executeQuery(
        'INSERT INTO contact_information (email_address, `name`, `subject`, `message`) VALUES (?, ?, ?, ?)',
        'ssss',
        [$email, $name, $subject, $message]
      );
  }
}
