<?php

// # Get Credit Card Sample
// The CreditCard resource allows you to
// retrieve previously saved CreditCards.
// API called: '/v1/vault/credit-card'
// The following code takes you through
// the process of retrieving a saved CreditCard
/** @var CreditCard $card */
//echo "<pre>"; print_r($_POST); die;
//$card = require 'CreateCreditCard.php';

$card_id = $_POST['cardid'];
// $card_id = isset($_REQUEST['cardid']) ? $_REQUEST['cardid'] : "";
//$id = $card->getId();
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

try 
{
    $card = CreditCard::get($card_id, $apiContext);

    $fina_arr[]	=	array( 	"id" 			=> $card->getId(),
				            "type" 			=> $card->getType(),
				            "number" 		=> $card->getNumber(),
				            "expire_month" 	=> $card->getExpireMonth(),
				            "expire_year" 	=> $card->getExpireYear(),
				             );
  	echo json_encode($fina_arr);
	exit;; 
} 
catch (Exception $ex) 
{
    echo "<pre>"; print_r($ex);
    exit(1);
}

echo $card; 
return $card;
