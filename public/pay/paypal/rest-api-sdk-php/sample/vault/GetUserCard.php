<?php

// # Get Credit Card Sample
// The CreditCard resource allows you to
// retrieve previously saved CreditCards.
// API called: '/v1/vault/credit-card'
// The following code takes you through
// the process of retrieving a saved CreditCard
/** @var CreditCard $card */

//echo "<pre>"; print_r($_POST); die;
$card = require 'CreateCreditCard.php';
$id = $card->getId();


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;

/// ### Retrieve card

try 
{
	$card = CreditCard::get($card->getId(), $apiContext);
} 
catch (Exception $ex) 
{
     echo "<pre>"; print_r($ex);
     exit(1);
}

print_r($card);

return $card;
