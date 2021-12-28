<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
if (getenv('ENV') != 'production') {
  $dotenv->load();
}

define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASS'));
define('DB_DB', getenv('DB_DB'));

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
