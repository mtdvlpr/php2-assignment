<?php
require_once __DIR__ . '/../db/contactDB.php';

class ContactController
{
  private ContactDB $contactDB;

  public function __construct()
  {
    $this->contactDB = new ContactDB();
  }
}
