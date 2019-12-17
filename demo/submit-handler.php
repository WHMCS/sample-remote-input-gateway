<?php
/**
 * Remote iFrame Demo Submit Handler.
 *
 * This sample file demonstrates how a payment gateway might process a
 * payment form submission from an iFrame displayed within WHMCS.
 *
 * In a real world scenario, this file/page would be hosted by the payment
 * gateway being implemented. On submission they would:
 *  * Validate the input
 *  * Create a token
 *  * Process the payment (if applicable)
 *  * Redirect back to WHMCS with the newly created token
 *
 * @see https://developers.whmcs.com/payment-gateways/remote-input-gateway/
 *
 * @copyright Copyright (c) WHMCS Limited 2019
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

$apiUsername = isset($_POST['api_username']) ? $_POST['api_username'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$invoiceId = isset($_POST['invoice_id']) ? $_POST['invoice_id'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';
$currencyCode = isset($_POST['currency']) ? $_POST['currency'] : '';
$customerId = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
$firstname = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$lastname = isset($_POST['last_name']) ? $_POST['last_name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$address1 = isset($_POST['address1']) ? $_POST['address1'] : '';
$address2 = isset($_POST['address2']) ? $_POST['address2'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$state = isset($_POST['state']) ? $_POST['state'] : '';
$postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
$country = isset($_POST['country']) ? $_POST['country'] : '';
$phonenumber = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';

$cardType = isset($_POST['card_type']) ? $_POST['card_type'] : '';
$cardNumber = isset($_POST['card_number']) ? $_POST['card_number'] : '';
$cardExpiryMonth = isset($_POST['card_exp_month']) ? $_POST['card_exp_month'] : '';
$cardExpiryYear = isset($_POST['card_exp_year']) ? $_POST['card_exp_year'] : '';
$cardCvv = isset($_POST['card_cvv']) ? $_POST['card_cvv'] : '';
$customReference = isset($_POST['custom_reference']) ? $_POST['custom_reference'] : '';

$returnUrl = isset($_POST['return_url']) ? $_POST['return_url'] : '';

// Payment gateway performs input validation, creates a token, process the
// payment (if applicable).

// Redirect back to WHMCS.
$redirectUri .= $returnUrl . '?' . http_build_query([
    'success' => true,
    'action' => $action,
    'invoice_id' => $invoiceId,
    'customer_id' => $customerId,
    'amount' => $amount,
    'currency' => $currencyCode,
    'transaction_id' => rand(100000, 999999),
    'card_token' => 'abc' . rand(100000, 999999),
    'card_type' => $cardType,
    'card_last_four' => substr($cardNumber, -4, 4),
    'card_expiry_date' => $cardExpiryMonth . $cardExpiryYear,
    'custom_reference' => $customReference,
]);

header('Location: ' . $redirectUri);
exit;
