<?php
class UserModel
{
  public function __construct(
  private int $id,
  private string $name,
  private string $username,
  private string $password,
  private string $profilePicture = '/img/fillerface.png',
  private int $role = 0,
  private bool $isActive = false,
  private ?string $hash = null
    )
  {
  }

  public function checkPassword(string $password)
  {
      return $this->password == $password;
  }
}
