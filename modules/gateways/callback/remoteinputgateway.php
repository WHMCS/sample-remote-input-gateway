<?php
/**
 * WHMCS Remote Input Gateway Callback File
 *
 * The purpose of this file is to demonstrate how to handle the return post
 * from a Remote Input and Remote Update Gateway
 *
 * It demonstrates verifying that the payment gateway module is active,
 * validating an Invoice ID, checking for the existence of a Transaction ID,
 * Logging the Transaction for debugging, Adding Payment to an Invoice and
 * adding or updating a payment method.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @copyright Copyright (c) WHMCS Limited 2019
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

require_once __DIR__ . '/../../../init.php';

App::load_function('gateway');
App::load_function('invoice');

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Verify the module is active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

$apiUsername = $gatewayParams['apiUsername'];
$apiPassword = $gatewayParams['apiPassword'];
$testMode = $gatewayParams['testMode'];

// Retrieve data returned in redirect
$success = isset($_REQUEST['success']) ? $_REQUEST['success'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$invoiceId = isset($_REQUEST['invoice_id']) ? $_REQUEST['invoice_id'] : '';
$customerId = isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : '';
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
$fees = isset($_REQUEST['fees']) ? $_REQUEST['fees'] : '';
$currencyCode = isset($_REQUEST['currency']) ? $_REQUEST['currency'] : '';
$transactionId = isset($_REQUEST['transaction_id']) ? $_REQUEST['transaction_id'] : '';
$cardLastFour = isset($_REQUEST['card_last_four']) ? $_REQUEST['card_last_four'] : '';
$cardType = isset($_REQUEST['card_type']) ? $_REQUEST['card_type'] : '';
$cardExpiryDate = isset($_REQUEST['card_expiry_date']) ? $_REQUEST['card_expiry_date'] : '';
$cardToken = isset($_REQUEST['card_token']) ? $_REQUEST['card_token'] : '';
$verificationHash = isset($_REQUEST['verification_hash']) ? $_REQUEST['verification_hash'] : '';
$payMethodId = isset($_REQUEST['custom_reference']) ? (int) $_REQUEST['custom_reference'] : 0;

// Validate Verification Hash. Uncomment for production use.
// $comparisonHash = sha1(
//     implode('|', [
//         $apiUsername,
//         $customerId,
//         $invoiceId,
//         $amount,
//         $currencyCode,
//         $apiPassword,
//         $token,
//     ])
// );
// if ($verificationHash !== $comparisonHash) {
//     logTransaction($gatewayParams['paymentmethod'], $_REQUEST, "Invalid Hash");
//     die('Invalid hash.');
// }

if ($action == 'payment') {
    if ($success) {
        // Validate invoice id received is valid.
        $invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['paymentmethod']);

        // Log to gateway log as successful.
        logTransaction($gatewayParams['paymentmethod'], $_REQUEST, "Success");

        // Create a pay method for the newly created remote token.
        invoiceSaveRemoteCard($invoiceId, $cardLastFour, $cardType, $cardExpiryDate, $cardToken);

        // Apply payment to the invoice.
        addInvoicePayment($invoiceId, $transactionId, $amount, $fees, $gatewayModuleName);

        // Redirect to the invoice with payment successful notice.
        callback3DSecureRedirect($invoiceId, true);
    } else {
        // Log to gateway log as failed.
        logTransaction($gatewayParams['paymentmethod'], $_REQUEST, "Failed");

        sendMessage('Credit Card Payment Failed', $invoiceId);

        // Redirect to the invoice with payment failed notice.
        callback3DSecureRedirect($invoiceId, false);
    }
}

if ($action == 'create') {
    if ($success) {
        try {
            // Function available in WHMCS 7.9 and later
            createCardPayMethod(
                $customerId,
                $gatewayModuleName,
                $cardLastFour,
                $cardExpiryDate,
                $cardType,
                null, //start date
                null, //issue number
                $cardToken
            );

            // Log to gateway log as successful.
            logTransaction($gatewayParams['paymentmethod'], $_REQUEST, 'Create Success');

            // Show success message.
            echo 'Create successful.';
        } catch (Exception $e) {
            // Log to gateway log as unsuccessful.
            logTransaction($gatewayParams['paymentmethod'], $_REQUEST, $e->getMessage());

            // Show failure message.
            echo 'Create failed. Please try again.';
        }
    } else {
        // Log to gateway log as unsuccessful.
        logTransaction($gatewayParams['paymentmethod'], $_REQUEST, 'Create Failed');

        // Show failure message.
        echo 'Create failed. Please try again.';
    }
}

if ($action == 'update') {
    if ($success) {
        try {
            // Function available in WHMCS 7.9 and later
            updateCardPayMethod(
                $customerId,
                $payMethodId,
                $cardExpiryDate,
                null, // card start date
                null, // card issue number
                $cardToken
            );

            // Log to gateway log as successful.
            logTransaction($gatewayParams['paymentmethod'], $_REQUEST, 'Update Success');

            // Show success message.
            echo 'Update successful.';
        } catch (Exception $e) {
            // Log to gateway log as unsuccessful.
            logTransaction($gatewayParams['paymentmethod'], $_REQUEST, $e->getMessage());

            // Show failure message.
            echo 'Update failed. Please try again.';
        }
    } else {
        // Log to gateway log as unsuccessful.
        logTransaction($gatewayParams['paymentmethod'], $_REQUEST, 'Update Failed');

        // Show failure message.
        echo 'Update failed. Please try again.';
    }
}
