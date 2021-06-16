<?php
require_once __DIR__ . "/../db/paymentDB.php";

class PaymentController
{
  private PaymentDB $paymentDB;

  public function __construct()
  {
    $this->paymentDB = new PaymentDB();
  }

  /**
   * This method handles the creation of the donation to the Mollie API.
   * It also stores the donation in the database.
   *
   * @param DonationModel $donation
   * @param string $paymentMethod (ideal, creditcard, paypal)
   */
  public function createDonation(DonationModel $donation, string $paymentMethod): void
  {
    $donationId = $this->paymentDB->storeDonation($donation);

    // 2.1 Update the donation to use the proper ID.
    $donation->setId($donationId);

    // 3. Create the donation request to Mollie.
    $this->paymentDB->createMollieOrder($donation, $paymentMethod);
  }

  /**
   * Get an donation by the donationId
   *
   * @param string $donationId The ID of the donation
   * @return DonationModel The donation data
   */
  public function getDonationById(string $donationId): DonationModel
  {
    return $this->paymentDB->getDonationById($donationId);
  }

  /**
   * Get the status of a Mollie order
   *
   * @param string $mollieId Get the status of an order in mollie
   * @return string The current status of the order
   */
  public function getMollieOrderStatus(string $mollieId): string
  {
    return $this->paymentDB->getMollieOrderStatus($mollieId);
  }

  /**
   * Set the order status in our database
   *
   * @param string $mollieId The mollie ID of the order
   * @param string $status The current status in Mollie
   */
  public function setOrderStatus(string $mollieId, string $status): void
  {
    $this->paymentDB->setOrderStatus($mollieId, $status);
  }

  public function getDonationByMollieId(string $mollieId): DonationModel
  {
    $donationId = $this->paymentDB->getDonationIdByMollieId($mollieId);
    return $this->paymentDB->getDonationById($donationId);
  }
}
?>
