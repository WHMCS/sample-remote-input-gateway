<?php
/**
 * Remote iFrame Demo.
 *
 * This sample file demonstrates how a payment gateway might render a
 * payment form to be displayed via iFrame within WHMCS.
 *
 * In a real world scenario, this file/page would be hosted by the payment
 * gateway being implemented. On submission they would validate the input
 * and return the user to the callback file with a success confirmation.
 *
 * @see https://developers.whmcs.com/payment-gateways/remote-input-gateway/
 *
 * @copyright Copyright (c) WHMCS Limited 2019
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

// Parameters posted from remote input gateway module.
$apiUsername = isset($_POST['api_username']) ? $_POST['api_username'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
$customerId = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
$cardToken = isset($_POST['card_token']) ? $_POST['card_token'] : '';
$invoiceId = isset($_POST['invoice_id']) ? $_POST['invoice_id'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';
$currencyCode = isset($_POST['currency']) ? $_POST['currency'] : '';
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
$returnUrl = isset($_POST['return_url']) ? $_POST['return_url'] : '';
$customReference = isset($_POST['custom_reference']) ? $_POST['custom_reference'] : '';
$verificationHash = isset($_POST['verification_hash']) ? $_POST['verification_hash'] : '';

// Validate Verification Hash. Uncomment for production use.
// $apiPassword = 'sharedsecret';
// $comparisonHash = sha1(
//     implode('|', [
//         $apiUsername,
//         $customerId,
//         $invoiceId,
//         $amount,
//         $currencyCode,
//         $apiPassword,
//     ])
// );
// if ($verificationHash !== $comparisonHash) {
//     die('Invalid hash.');
// }

if ($action === 'payment') {
    $title = 'Make a payment';
    $buttonLabel = "Pay {$amount} {$currencyCode} Now";
} else {
    $title = 'Add/Update card details';
    $buttonLabel = 'Save Changes';
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?= $title ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <form method="post" action="submit-handler.php" style="margin:0 auto;width:80%;">
        <input type="hidden" name="action" value="<?= $action ?>">
        <input type="hidden" name="card_token" value="<?= $cardToken ?>">
        <input type="hidden" name="invoice_id" value="<?= $invoiceId ?>">
        <input type="hidden" name="customer_id" value="<?= $customerId ?>">
        <input type="hidden" name="return_url" value="<?= $returnUrl ?>">
        <input type="hidden" name="custom_reference" value="<?= $customReference ?>">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="firstname" class="form-control" value="<?= $firstname ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastname" class="form-control" value="<?= $lastname ?>" required>
        </div>
        <div class="form-group">
            <label>Address 1</label>
            <input type="text" name="address1" class="form-control" value="<?= $address1 ?>" required>
        </div>
        <div class="form-group">
            <label>Address 2</label>
            <input type="text" name="address2" class="form-control" value="<?= $address2 ?>">
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" class="form-control" value="<?= $city ?>" required>
        </div>
        <div class="form-group">
            <label>State</label>
            <input type="text" name="state" class="form-control" value="<?= $state ?>" required>
        </div>
        <div class="form-group">
            <label>Postcode</label>
            <input type="text" name="postcode" class="form-control" value="<?= $postcode ?>" required>
        </div>
        <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" class="form-control" value="<?= $country ?>" required>
        </div>
        <div class="form-group">
            <label>Card Type</label>
            <select name="card_type" class="form-control">
                <option>Visa</option>
                <option>MasterCard</option>
                <option>American Express</option>
                <option>Discover</option>
            </select>
        </div>
        <div class="form-group">
            <label>Card Number</label>
            <input type="text" name="card_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Expiry Date</label>
            <div class="row">
                <div class="col-sm-6">
                    <select name="card_exp_month" class="form-control">
                        <?php for ($x = 1; $x <= 12; $x++) { ?>
                            <option><?= str_pad($x, 2, STR_PAD_LEFT, '0') ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select name="card_exp_year" class="form-control">
                        <?php for ($x = date('y'); $x <= date('y') + 12; $x++) { ?>
                            <option><?= $x ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>CVV Number</label>
            <input type="text" name="card_cvv" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
  </body>
</html>
