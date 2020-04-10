<?php
// # Delete CreditCard Sample
// This sample code demonstrate how you can
// delete a saved credit card.
// API used: /v1/vault/credit-card/{<creditCardId>}
// NOTE: HTTP method used here is DELETE

/** @var CreditCard $card */
require __DIR__ . '/../bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
//$card = require 'CreateCreditCard.php';

$card_id = $_REQUEST['CardId'];
try {
    // ### Delete Card
    // Lookup and delete a saved credit card.
    // (See bootstrap.php for more on `ApiContext`)
    $card_id->delete($apiContext);
    $status = "success";
	return $status;
    echo json_encode($status); exit;
} catch (Exception $ex) 
{
    exit(1);
}



