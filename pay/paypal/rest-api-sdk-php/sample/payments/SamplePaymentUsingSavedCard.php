<?php

// # Create payment using a saved credit card
// This sample code demonstrates how you can process a 
// Payment using a previously stored credit card token.
// API used: /v1/payments/payment

/** @var CreditCard $card */
require __DIR__ . '/../bootstrap.php';

//$card = require __DIR__ . '/../vault/CreateCreditCard.php';

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

// ### Credit card token
// Saved credit card id from a previous call to
// CreateCreditCard.php

// print_r($_REQUEST);  die;
$card_id  = $_REQUEST['cardid'];
$Currency = $_REQUEST['currency'];
$Amount   = $_REQUEST['amount'];

$creditCardToken = new CreditCardToken();
$creditCardToken->setCreditCardId($card_id);

// ### FundingInstrument
// A resource representing a Payer's funding instrument.
// For stored credit card payments, set the CreditCardToken
// field on this object.
$fi = new FundingInstrument();
$fi->setCreditCardToken($creditCardToken);

// ### Payer
// A resource representing a Payer that funds a payment
// For stored credit card payments, set payment method
// to 'credit_card'.
$payer = new Payer();
$payer->setPaymentMethod("credit_card")
    ->setFundingInstruments(array($fi));

// ### Itemized information
// (Optional) Lets you specify item wise
// information
// $item1 = new Item();
// $item1->setName('Ground Coffee 40 oz')
//     ->setCurrency('USD')
//     ->setQuantity(1)
//     ->setPrice(7.5);
// $item2 = new Item();
// $item2->setName('Granola bars')
//     ->setCurrency('USD')
//     ->setQuantity(5)
//     ->setPrice(2);

// $itemList = new ItemList();
// $itemList->setItems(array($item1, $item2));

// ### Additional payment details
// Use this optional field to set additional
// payment information such as tax, shipping
// charges etc.
// $details = new Details();
// $details->setShipping(0.0)
//     ->setTax(0.0)
//     ->setSubtotal(20.0);

// ### Amount
// Lets you specify a payment amount.
// You can also specify additional details
// such as shipping, tax.
$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal(5);

// ### Transaction
// A transaction defines the contract of a
// payment - what is the payment for and who
// is fulfilling it. 
$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setDescription("Pay Using Vault")
    ->setInvoiceNumber(uniqid());

// ### Payment
// A Payment Resource; create one using
// the above types and intent set to 'sale'
$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setTransactions(array($transaction));


// For Sample Purposes Only.
$request = clone $payment;

$transaction_id =   "";
$ress = $payment->create($apiContext);

 echo "<pre>"; print_r($ress); exit;
   
$trans_data =   $ress->getTransactions();    
foreach($trans_data as $trans_data_row)
{
    // echo "<pre>"; print_r($trans_data_row->getRelatedResources());
    $trans_res_data     =   $trans_data_row->getRelatedResources();
    foreach($trans_res_data as $trans_data_res_row)
    {
        // print_r($trans_data_res_row->getSale()->getId());    
        $transaction_id =   $trans_data_res_row->getSale()->getId();    
    }
    //  echo $trans_data_res_row->getSale()->getId();
    // print_r($trans_data_row);
}

$pay_data   =   array("transaction_id"=>$transaction_id);
echo json_encode($pay_data); exit;

// ###Create Payment
// Create a payment by calling the 'create' method
// passing it a valid apiContext.
// (See bootstrap.php for more on `ApiContext`)
// The return object contains the state.

try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    // ResultPrinter::printError("Create Payment using Saved Card", "Payment", null, $request, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 // ResultPrinter::printResult("Create Payment using Saved Card", "Payment", $payment->getId(), $request, $payment);
echo $payment;
return $payment;
// return $card;
