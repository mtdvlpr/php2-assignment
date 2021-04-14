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

  public function checkPassword(string $password): bool
  {
      return $this->password == $password;
  }

  /**
   * Get the value of id
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId(int $id): void
  {
    $this->id = $id;
  }

  /**
   * Get the value of name
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the value of name
   */
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  /**
   * Get the value of username
   */
  public function getUsername(): string
  {
    return $this->username;
  }

  /**
   * Set the value of username
   */
  public function setUsername(string $username): void
  {
    $this->username = $username;
  }

  /**
   * Get the value of profilePicture
   */
  public function getProfilePicture(): string
  {
    return $this->profilePicture;
  }

  /**
   * Set the value of profilePicture
   */
  public function setProfilePicture(string $profilePicture): void
  {
    $this->profilePicture = $profilePicture;
  }

  /**
   * Get the value of role
   */
  public function getRole(): int
  {
    return $this->role;
  }

  /**
   * Set the value of role
   */
  public function setRole(int $role): void
  {
    $this->role = $role;
  }

  /**
   * Get the value of isActive
   */
  public function getIsActive(): bool
  {
    return $this->isActive;
  }

  /**
   * Set the value of isActive
   */
  public function setIsActive(bool $isActive): void
  {
    $this->isActive = $isActive;
  }

  /**
   * Get the value of hash
   */
  public function getHash(): string
  {
    return $this->hash;
  }

  /**
   * Set the value of hash
   */
  public function setHash(string $hash): void
  {
    $this->hash = $hash;
  }
}
