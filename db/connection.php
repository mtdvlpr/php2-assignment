<?php
define('DB_HOST', 's643622.infhaarlem.nl');
define('DB_USER', 's643622');
define('DB_PASSWORD', 'FBzPJ9x4');
define('DB_DB', 's643622_db');

/**
 * The connection with the database of the application.
 *
 * Implements the singleton pattern to make sure only one connection is open.
 */
class ConnectionDB
{
  private static mysqli | bool | null $connection = null;

  private function __construct()
  {
  }

  /**
   * Get the connection to the database, using the singleton pattern.
   *
   * @return mysqli The connection to the database
   */
  public static function getConnection(): mysqli
  {
    if (self::$connection == null) {
      self::$connection = mysqli_connect(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_DB,
      ) or die('<br/>Could not connect to MySQL server' . mysqli_connect_error());
    }

    return self::$connection;
  }
}
?>
