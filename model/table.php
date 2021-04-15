<?php

/**
 * The data model for a table on the website.
 */
class TableModel
{
  public function __construct(
  private array $users
  )
  {
  }

    /**
     * Get the value of users
     */
  public function getUsers(): array
  {
      return $this->users;
  }

    /**
     * Set the value of users
     */
  public function setUsers(array $users): void
  {
      $this->users = $users;
  }
}
