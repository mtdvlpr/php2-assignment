<?php

class donationModel
{
  public function __construct(
    private int $id,
    private DateTime $donationDate,
    private float $amount,
    private string $name,
    private string $email,
    private string $status = 'created'
  ) {
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
   * Get the value of donationDate
   */
  public function getDonationDate(): DateTime
  {
    return $this->donationDate;
  }

  /**
   * Set the value of donationDate
   */
  public function setDonationDate(DateTime $donationDate): void
  {
    $this->donationDate = $donationDate;
  }

  /**
   * Get the value of status
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * Set the value of status
   */
  public function setStatus(string $status): void
  {
    $this->status = $status;
  }

    /**
     * Get the value of amount
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
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
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
