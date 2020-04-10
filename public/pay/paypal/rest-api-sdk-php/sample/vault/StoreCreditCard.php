<?php


// # Create Credit Card Sample
// You can store credit card details securely
// with PayPal. You can then use the returned
// Credit card id to process future payments.
// API used: POST /v1/vault/credit-card


require __DIR__ . '/../bootstrap.php';

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

// ### CreditCard
// A resource representing a credit card that is 
// to be stored with PayPal.
//echo "<pre>"; print_r($_POST); die;
// $getCard = json_decode($_POST);
//echo "<pre>"; print_r($_POST); die;
$card = new CreditCard();
$card->setType($_POST['type'])
    ->setNumber($_POST['number'])
    ->setExpireMonth($_POST['expire_month'])
    ->setExpireYear($_POST['expire_year'])
    ->setCvv2($_POST['cvv2'])
    ->setFirstName($_POST['first_name'])
    ->setLastName($_POST['first_name']);

// ### Additional Information
// Now you can also store the information that could help you connect
// your users with the stored credit cards.
// All these three fields could be used for storing any information that could help merchant to point the card.
// However, Ideally, MerchantId could be used to categorize stores, apps, websites, etc.
// ExternalCardId could be used for uniquely identifying the card per MerchantId. So, combination of "MerchantId" and "ExternalCardId" should be unique.
// ExternalCustomerId could be userId, user email, etc to group multiple cards per user.
//$card->setMerchantId($_POST['type']);
$card->setExternalCardId($_POST['external_card_id'] . uniqid());
$card->setExternalCustomerId($_POST['external_customer_id']);

// For Sample Purposes Only.
$request = clone $card;

// ### Save card
// Creates the credit card as a resource
// in the PayPal vault. The response contains
// an 'id' that you can use to refer to it
// in future payments.
// (See bootstrap.php for more on `ApiContext`)
try 
{
    $card->create($apiContext);
}
catch (Exception $ex) 
{
    echo "<pre>"; print_r($ex);
     exit(1);
}

echo $card;
return $card;
