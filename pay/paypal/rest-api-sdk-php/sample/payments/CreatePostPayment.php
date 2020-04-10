<?php

// # CreatePaymentSample
//
// This sample code demonstrate how you can process
// a direct credit card payment. Please note that direct
// credit card payment and related features using the
// REST API is restricted in some countries.
// API used: /v1/payments/payment


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
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;


// ### PaymentCard
// A resource representing a payment card that can be
// used to fund a payment.
//echo "<pre>7777"; print_r($_POST); die;
$card = new PaymentCard();
$card->setType($_POST['card_type'])
    ->setNumber($_POST['card_number'])
    ->setExpireMonth($_POST['expiry_month'])
    ->setExpireYear($_POST['expiry_year'])
    ->setCvv2($_POST['cvv'])
    ->setFirstName($_POST['first_name'])
    ->setBillingCountry("US")
    ->setLastName($_POST['first_name']);

// ### FundingInstrument
// A resource representing a Payer's funding instrument.
// For direct credit card payments, set the CreditCard
// field on this object.
$fi = new FundingInstrument();
$fi->setPaymentCard($card);

// ### Payer
// A resource representing a Payer that funds a payment
// For direct credit card payments, set payment method
// to 'credit_card' and add an array of funding instruments.
$payer = new Payer();
$payer->setPaymentMethod("credit_card")
    ->setFundingInstruments(array($fi));


// ### Amount
// Lets you specify a payment amount.
// You can also specify additional details
// such as shipping, tax.
$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal($_POST['amount']);
//    ->setDetails($details);

// ### Transaction
// A transaction defines the contract of a
// payment - what is the payment for and who
// is fulfilling it.
$transaction = new Transaction();
$transaction->setAmount($amount)
   // ->setItemList($itemList)
    ->setDescription("Payment description")
    ->setInvoiceNumber(uniqid());

// ### Payment
// A Payment Resource; create one using
// the above types and intent set to sale 'sale'
$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setTransactions(array($transaction));

// For Sample Purposes Only.
$request = clone $payment;

$transaction_id =   "";
$ress = $payment->create($apiContext);

//echo "<pre>7777"; print_r($ress); exit;
   
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

// ### Create Payment
// Create a payment by calling the payment->create() method
// with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
// The return object contains the state.
try {
    $payment->create($apiContext);
    
    // $ress = $payment->create($apiContext);

    // echo "<pre>"; print_r($ress); exit; 

} catch (Exception $ex) {
    // // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    // ResultPrinter::printError('Create Payment Using Credit Card. If 500 Exception, try creating a new Credit Card using <a href="https://www.paypal-knowledge.com/infocenter/index?page=content&widgetview=true&id=FAQ1413">Step 4, on this link</a>, and using it.', 'Payment', null, $request, $ex);
    exit(1);
}

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 //ResultPrinter::printResult('Create Payment Using Credit Card', 'Payment', $payment->getId(), $request, $payment);

echo $payment;
return $payment;
