<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../controller/utils.php';

/**
 * The base class for database interactions.
 *
 * Contains base methods for executing MYSQL queries and mutations
 */
abstract class BaseDB
{
  public function __construct()
  {
    // Initialize the connection
    ConnectionDB::getConnection();
  }

  /**
   * Check if the user provided the same amount of types as arguments
   *
   * @param string $argTypes - The provided types, ex: "ssss"
   * @param array  $args - The array of arguments
   *
   * @throws InvalidArgumentException Not the same amount of types as arguments
   */
  private function validateArguments(
    string $argTypes,
    array $args
  ): void
  {
    // The user didn't provide the same amount of types as arguments
    if (strlen($argTypes) !== count($args)) {
      throw new InvalidArgumentException(
        "The provided amount of types doesn't match the provided arguments"
      );
    }
  }

  /**
   * Execute an SQL query and store them in the results variables.
   * This should be used for queries that return 1 result.
   *
   * @param string $queryStatement    - The SQL query to execute
   * @param string $argTypes - The types of the arguments
   * @param array  $args     - Array of the arguments
   * @param mixed  ...$resultVariables
   */
  protected function executeQuery(
    string $queryStatement,
    string $argTypes,
    array $args = [],
    mixed &...$resultVariables
  ): void
  {
    // Validate the arguments
    $this->validateArguments($argTypes, $args);

    // Prepare the query
    $query = ConnectionDB::getConnection()->prepare($queryStatement);

    // Query is invalid, inform the user
    if (!$query) {
      UtilsController::printArray(ConnectionDB::getConnection()->error_list);
      throw new Exception(ConnectionDB::getConnection()->error_list[0]['error']);
    }

    $query->bind_param($argTypes, ...$args);
    $query->execute();
    $query->bind_result(...$resultVariables);
    $query->fetch();
  }

  /**
   * Execute a query that returns an array/list.
   * This should be used when firing a query that returns multiple rows.
   *
   * @param string $queryStatement - The SQL query to execute
   * @param string $argTypes - The types of the arguments
   * @param array  $args - Array of the arguments
   *
   * @return mysqli_result The results of the query
   */
  protected function executeQueryList(
    string $queryStatement,
    string $argTypes = '',
    array $args = []
  ): mysqli_result
  {
    // Validate the arguments
    $this->validateArguments($argTypes, $args);

    // Prepare the query
    $query = ConnectionDB::getConnection()->prepare($queryStatement);

    // Query is invalid, inform the user
    if (!$query) {
      UtilsController::printArray(ConnectionDB::getConnection()->error_list);
      throw new Exception(ConnectionDB::getConnection()->error_list[0]['error']);
    }

    // User provided arguments
    if (!empty($argTypes)) {
      $query->bind_param($argTypes, ...$args);
    }
    // Execute the query
    $query->execute();

    return $query->get_result();
  }

  /**
   * Execute a mutation on the database.
   *
   * @param string $mutationStatement - The SQL statement to execute
   * @param string $argTypes - The types of the arguments
   * @param array  $args - Array of the arguments
   */
  protected function executeMutation(
    string $mutationStatement,
    string $argTypes,
    array $args
  ): void
  {
    // Validate the arguments
    $this->validateArguments($argTypes, $args);

    // Prepare the mutation
    $mutation = ConnectionDB::getConnection()->prepare($mutationStatement);

    // Mutation is invalid, inform the user
    if (!$mutation) {
      UtilsController::printArray(ConnectionDB::getConnection()->error_list);
      throw new Exception(ConnectionDB::getConnection()->error_list[0]['error']);
    }

    $mutation->bind_param($argTypes, ...$args);
    $mutation->execute();
    $mutation->close();
  }
}
