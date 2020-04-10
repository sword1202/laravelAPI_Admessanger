<?php

require __DIR__ . '/../bootstrap.php';

$paypalEmail  = $_REQUEST['paypal_email'];
$paypalAmount = $_REQUEST['redeem_amount'];

$payouts = new \PayPal\Api\Payout();

$senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();

$senderBatchHeader->setSenderBatchId(uniqid())
    ->setEmailSubject("You have a Payout from AdMessage!");

$senderItem = new \PayPal\Api\PayoutItem();
$senderItem->setRecipientType('Email')
    ->setNote('Your Redeem Amount')
    ->setReceiver($paypalEmail)
    ->setSenderItemId("item_1" . uniqid())
    ->setAmount(new \PayPal\Api\Currency('{
                        "value":"'.$paypalAmount.'",
                        "currency":"USD"
                    }'));

$payouts->setSenderBatchHeader($senderBatchHeader)
    ->addItem($senderItem);

$request = clone $payouts;

$output = $payouts->create(null, $apiContext);

echo $output; exit;

try 
{
    $output = $payouts->create(null, $apiContext);
}
catch (Exception $ex) 
{
    //ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);
    exit(1);
}

//ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
//echo $output->getBatchHeader()->getPayoutBatchId(), $output; exit;
return $output;
