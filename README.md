# WHMCS Sample Remote Input Gateway Module #

## Summary ##

Payment Gateway modules allow you to integrate payment solutions with the WHMCS
platform.

There are two types of gateway module:

* Third Party Gateways - these are payment solutions where checkout occurs
on a remote website, usually hosted by the payment gateway themselves.

* Merchant Gateways - these are payment solutions where credit card details
are collected - usually within the WHMCS application, though more and more
often this will be done remotely, typically via an iframe, with a page hosted
remotely by the payment gateway enabling tokenised storage.

The sample files here demonstrate how we suggest a Merchant Gateway that uses
a remotely hosted payment page should be created for WHMCS.

For more information, please refer to the documentation at:
https://developers.whmcs.com/payment-gateways/remote-input-gateway/

## Remote Input Module ##

A remote input module is a type of merchant gateway that accepts input of pay
method data remotely within an iFrame so that it appears transparent to the end
user, and then exchanges it for a token that is returned back to WHMCS to be
stored for future billing attempts.

Within WHMCS, sensitive payment data such as a card number is not stored
locally when a remote input module is used.

For the purposes of this sample, a demo of a remotely hosted payment page is
provided within the `demo` directory.

In a real world scenario, this file/page would be hosted by the payment gateway
being implemented. On submission they would validate the input and return the
user to the callback file with a success confirmation.

## Recommended Module Content ##

The recommended structure of a remote input gateway module is as follows.

```
 modules/gateways/
  |- callback/remoteinputgateway.php
  |  remoteinputgateway.php
```

## Minimum Requirements ##

For the latest WHMCS minimum system requirements, please refer to
https://docs.whmcs.com/System_Requirements

We recommend your module follows the same minimum requirements wherever
possible.

## Useful Resources
* [Developer Resources](https://developers.whmcs.com/)
* [Hook Documentation](https://developers.whmcs.com/hooks/)
* [API Documentation](https://developers.whmcs.com/api/)

[WHMCS Limited](https://www.whmcs.com)
