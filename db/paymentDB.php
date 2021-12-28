<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
if (getenv('ENV') != 'production') {
  $dotenv->load();
}
use \Mollie\Api\MollieApiClient;
use Mollie\Api\Types\PaymentMethod;

require_once __DIR__ . '/../controller/utils.php';
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/baseDB.php";
require_once __DIR__ . "/../model/donation.php";

class PaymentDB extends baseDB
{
  private MollieApiClient $mollie;
  private string $domain;

  public function __construct()
  {
    $this->mollie = new MollieApiClient();
    $this->mollie->setApiKey(getenv('MOLLIE_API_KEY'));
    $this->domain = getenv('BASE_URL');
  }

  /**
   * Create a mollie order
   *
   * @param DonationModel $donation The donation that's made
   * @param string $paymentMethod The method of payment (iDEAL, PayPal, CreditCard)
   */
  public function createMollieOrder(DonationModel $donation, string $paymentMethod): void
  {
    $donationId = $donation->getId();

    // Create the mollie order
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
        "redirectUrl" => "https://$this->domain/donation/$donationId",
        "webhookUrl"  => "https://$this->domain/api/orderWebhook.php",
        "method" => $paymentMethod,
        "locale" => "en_US"
      ]
    );

    // Store the Mollie order ID in the database.
    $this->setMollieOrderId($donation->getId(), $mollieOrder->id);

    // Redirect the user to the Mollie checkout
    $checkoutUrl = $mollieOrder->getCheckoutUrl();
    header("Location: $checkoutUrl");
  }

  /**
   * Set the mollie id of the order in mollie in the database.
   *
   * @param string $donationId The ID of the donation
   * @param string $mollieId The ID of the mollie order
   */
  public function setMollieOrderId(string $donationId, string $mollieId): void
  {
    // Create the mutation for storing the mollieID
    $mutation = "UPDATE `donation`
    SET mollie_id = ?
    WHERE donation_id = ?";

    // Execute the mutation
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
    // Create the mutation for updating the order status
    $mutation = "UPDATE `donation`
    SET `status` = ?
    WHERE mollie_id = ?";

    // Execute the mutation
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
   * @param int $donationId the id of the donation
   * @return DonationModel The donation made
   */
  public function getDonationById(int $donationId): DonationModel
  {
    // Get the data from the database
    $donationQuery = "SELECT
      `status`,
      `name`,
      email,
      amount,
      `hash`
    FROM `donation`
    WHERE donation_id = ?";

    $this->executeQuery(
      $donationQuery,
      "s",
      [$donationId],
      $status,
      $name,
      $email,
      $amount,
      $hash
    );


    // Create the DonationModel
    $donation = new DonationModel(
      $donationId,
      new DateTime(),
      $amount,
      $name,
      $email,
      $status,
      $hash
    );

    // Return the donation model
    return $donation;
  }

  public function getDonationByIdAndHash(int $donationId, string $hash): DonationModel|null
  {
    // Get the data from the database
    $donationQuery = "SELECT
      `status`,
      `name`,
      email,
      amount,
      `hash`
    FROM `donation`
    WHERE donation_id = ? AND `hash` = ?";

    $this->executeQuery(
      $donationQuery,
      "ss",
      [$donationId, $hash],
      $status,
      $name,
      $email,
      $amount,
      $hash
    );

    if ($amount == null) {
      return null;
    }

    // Create the DonationModel
    $donation = new DonationModel(
      $donationId,
      new DateTime(),
      $amount,
      $name,
      $email,
      $status,
      $hash
    );

    // Return the donation model
    return $donation;
  }

  /**
   * Store an donation to the database
   *
   * @param DonationModel $donation The donation to store
   * @return string The ID of the donation
   */
  public function storeDonation(DonationModel $donation): string
  {

    $hash = md5(rand(0, 1000));

    // Create the mutation for storing the donation.
    $mutation = "INSERT INTO `donation` (
      amount,
      `status`,
      donation_date,
      `name`,
      email,
      `hash`
    )
    VALUES (?, ?, now(), ?, ?, ?)";

    // Store the donation in the database
    $this->executeMutation(
      $mutation,
      "sssss",
      [
        $donation->getAmount(),
        "created",
        $donation->getName(),
        $donation->getEmail(),
        $hash
      ]
    );

    // Save the ID of the donation
    $donationId = connectionDB::getConnection()->insert_id;

    // Return the donation_id
    return $donationId;
  }

  public function getDonationIdByMollieId(string $mollieId): int
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
