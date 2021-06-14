<?php
use \Mollie\Api\MollieApiClient;
use Mollie\Api\Types\PaymentMethod;

require_once __DIR__ . '/../controller/utils.php';
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/baseDB.php";
require_once __DIR__ . "/../model/donation.php";

class paymentDB extends baseDB
{
  private MollieApiClient $mollie;
  private string $domain;

  public function __construct()
  {
    $this->mollie = new MollieApiClient();
    $this->mollie->setApiKey("test_P96BGMN6kkFRyKadnkN4ANUNQ6yBGw");
    $this->domain = '643622.infhaarlem.nl';
  }

  /**
   * Create a order entry in Mollie
   *
   * @return void
   */
  public function createMollieOrder(donationModel $donation, string $paymentMethod): void
  {
    $donationId = $donation->getId();
    $mollieOrder = $this->mollie->orders->create(
      [
        "amount" => [
          "currency" => "EUR",
          "value" => number_format($donation->getAmount(), 2, '.', '')
        ],
        "orderNumber" => strval($donation->getId()),
        "lines" => [
          [
            "type" => "digital",
            "name" => $donation->getName(),
            "quantity" => 1,
            "unitPrice" => [
              "currency" => "EUR",
              "value" => number_format($donation->getAmount(), 2, '.', '')
            ],
            "totalAmount" => [
              "currency" => "EUR",
              "value" => number_format($donation->getAmount(), 2, '.', '')
            ],
            "vatRate" => "00.00",
            "vatAmount" => [
              "currency" => "EUR",
              "value" => number_format(0, 2, '.', '')
            ]
          ]
        ],
        "billingAddress" => [
          "givenName" => $donation->getName(),
          "familyName" => $donation->getName(),
          "email" => $donation->getEmail(),
          "streetAndNumber" => 'Street nr',
          "city" => 'Haarlem',
          "country" => 'NL',
          "postalCode" => '1234AA'
        ],
        "redirectUrl" => "http://$this->domain/donation/$donationId",
        "webhookUrl"  => "http://$this->domain/api/orderWebhook.php",
        "method" => $paymentMethod,
        "locale" => "en_US"
      ]
    );

    // Store the Mollie order ID in our database.
    $this->setMollieOrderId($donation->getId(), $mollieOrder->id);

    // Redirect the user to the Mollie checkout
    $checkoutUrl = $mollieOrder->getCheckoutUrl();
    header("Location: $checkoutUrl");
  }

  /**
   * Set the mollie id of the order in mollie in our database.
   *
   * @param string $donationId
   * @param string $mollieId
   */
  public function setMollieOrderId(string $donationId, string $mollieId): void
  {
    // 1. Create the mutation for storing the mollieID
    $mutation = "UPDATE `donation`
    SET mollie_id = ?
    WHERE donation_id = ?";

    // 2. Execute the mutation
    $this->executeMutation($mutation, "ss", [$mollieId, $donationId]);
  }

  /**
   * Set the status of a Mollie order.
   *
   * @param string $mollieId The ID of of the order in Mollie
   * @param string $status The new updated status of the order
   */
  public function setOrderStatus(string $mollieId, string $status): void
  {
    // 1. Create the mutation for updating the order status
    $mutation = "UPDATE `donation`
    SET status = ?
    WHERE mollie_id = ?";

    // 2. Execute the mutation
    $this->executeMutation($mutation, "ss", [$status, $mollieId]);
  }

  /**
   * Get the status of the order of a mollie order.
   *
   * @param string $mollieId
   * @return string The status of the order
   */
  public function getMollieOrderStatus(string $mollieId): string
  {
    $order = $this->mollie->orders->get($mollieId);
    return $order->status;
  }

  /**
   * Get a donation from the database, based on the donationId provided.
   *
   * @param string $donationId
   * @return donationModel
   */
  public function getDonationById(string $donationId): donationModel
  {
    // 1. Get the data from the database
    $donationQuery = "SELECT
      `status`,
      `name`,
      email,
      amount
    FROM `donation`
    WHERE donation_id = ?";

    $this->executeQuery(
      $donationQuery,
      "s",
      [$donationId],
      $status,
      $name,
      $email,
      $amount
    );

    // 2. Convert the data into our models

    // 2.2 Create the donationModel
    $donation = new donationModel(
      $donationId,
      new DateTime(),
      $status,
      $amount,
      $name,
      $email
    );

    // 3. Return the donation model
    return $donation;
  }

  /**
   * Store an donation to the database
   *
   * @param donationModel $donation The donation to store
   * @return string The ID of the donation
   */
  public function storeDonation(donationModel $donation): string
  {
    // 1. Create the mutation for storing the donation.
    $mutation = "INSERT INTO `donation` (
      amount,
      `status`,
      donation_date,
      `name`,
      email
    )
    VALUES (?, ?, now(), ?, ?)";

    // 2. Store the donation in the database
    $this->executeMutation(
      $mutation,
      "ssss",
      [
        $donation->getAmount(),
        "created",
        $donation->getName(),
        $donation->getEmail()
      ]
    );

    // 3. Save the ID of the donation
    $donationId = connectionDB::getConnection()->insert_id;

    // 5. Return the donation_id
    return $donationId;
  }

  public function getDonationIdByMollieId(string $mollieId): string
  {
    $query = "SELECT donation_id
    FROM `donation`
    WHERE mollie_id = ?";

    $this->executeQuery(
      $query,
      "s",
      [$mollieId],
      $donationId
    );

    return $donationId;
  }
}