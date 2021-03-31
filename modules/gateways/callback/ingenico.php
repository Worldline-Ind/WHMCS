<?php
/**
 * WHMCS Sample Payment Callback File
 *
 * This sample file demonstrates how a payment gateway callback should be
 * handled within WHMCS.
 *
 * It demonstrates verifying that the payment gateway module is active,
 * validating an Invoice ID, checking for the existence of a Transaction ID,
 * Logging the Transaction for debugging and Adding Payment to an Invoice.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/callbacks/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);
//s2sverification
if(isset($_GET['action'])){

$response = $_GET;
$str = $response['msg'];
$response1 = explode('|', $str);
$status = $response1[0];
$transaction_id = $response1[5];
$status2 = $response1[7];
$response_cart = explode('orderid:', $status2);
$oid_1=$response_cart[1];
$oid_2 = explode('}', $oid_1);
$order_id =$oid_2[0];
$amount = $response1[6];
//Hash Verification 
$salt = $gatewayParams['salt'];
$responseData_1 = explode('|', $str);
$verificationHash = array_pop($responseData_1);
$hashableString = join('|', $responseData_1) . "|" . $salt;
$hashedString = hash('sha512',  $hashableString);
if($hashedString != $verificationHash){
exit('Hash Verification Failed');
}
$invoiceId = checkCbInvoiceID($order_id, $gatewayParams['name']);
checkCbTransID($transaction_id);
if($status == '0300'){

    logTransaction($gatewayParams['name'], $_GET, 'Success');

    addInvoicePayment(
        $order_id,
        $transaction_id,
        $amount,
        0,
        $gatewayParams['name']
    );
    echo json_encode($response1[3] . "|" . $response1[5] . "|1");
    die;
    
} else {

    logTransaction($gatewayParams['name'], $_GET, 'Failure');
    echo json_encode($response1[3] . "|" . $response1[5] . "|0");
    die;

}
    die;

}
// Die if module is not active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

// Retrieve data returned in payment gateway callback
// Varies per payment gateway

$response = $_POST;
$str = $response['msg'];
//die;
$response1 = explode('|', $str);
$status = $response1[0];
$transaction_id = $response1[5];
$status2 = $response1[7];
$response_cart = explode('orderid:', $status2);
$oid_1=$response_cart[1];
$oid_2 = explode('}', $oid_1);
$order_id =$oid_2[0];
$amount = $response1[6];
//Hash Verification 
$salt = $gatewayParams['salt'];
$responseData_1 = explode('|', $str);
$verificationHash = array_pop($responseData_1);
$hashableString = join('|', $responseData_1) . "|" . $salt;
$hashedString = hash('sha512',  $hashableString);

$responsedate = explode(' ', $response1[8]);
                $data_array = array(
                    "merchant" => array(
                        "identifier" => $gatewayParams['merchantCode']
                    ),
                    "transaction" => array(
                        "deviceIdentifier" => "S",
                        "currency" => $_GET['currency'],
                        "dateTime" => $responsedate[0],
                        "token" => $response1[5],
                        "requestType" => "S"
                    )
                );
                $url = "https://www.paynimo.com/api/paynimoV2.req";
                $options = array(
                    'http' => array(
                        'method'  => 'POST',
                        'content' => json_encode($data_array),
                        'header' =>  "Content-Type: application/json\r\n" .
                            "Accept: application/json\r\n"
                    )
                );
                $context     = stream_context_create($options);
                $result      = file_get_contents($url, false, $context);
                $response    = json_decode($result);
                $scallstatuscode = $response->paymentMethod->paymentTransaction->statusCode;


/**
 * Validate Callback Invoice ID.
 *
 * Checks invoice ID is a valid invoice number. Note it will count an
 * invoice in any status as valid.
 *
 * Performs a die upon encountering an invalid Invoice ID.
 *
 * Returns a normalised invoice ID.
 *
 * @param int $invoiceId Invoice ID
 * @param string $gatewayName Gateway Name
 */
$invoiceId = checkCbInvoiceID($order_id, $gatewayParams['name']);

/**
 * Check Callback Transaction ID.
 *
 * Performs a check for any existing transactions with the same given
 * transaction number.
 *
 * Performs a die upon encountering a duplicate.
 *
 * @param string $transactionId Unique Transaction ID
 */
checkCbTransID($transaction_id);

    /**
     * Add Invoice Payment.
     *
     * Applies a payment transaction entry to the given invoice ID.
     *
     * @param int $invoiceId         Invoice ID
     * @param string $transactionId  Transaction ID
     * @param float $paymentAmount   Amount paid (defaults to full balance)
     * @param float $paymentFee      Payment fee (optional)
     * @param string $gatewayModule  Gateway module name
     */

    if($status == '0300' && $scallstatuscode == '0300' && $hashedString == $verificationHash){

    logTransaction($gatewayParams['name'], $_POST, 'Success');

    addInvoicePayment(
        $order_id,
        $transaction_id,
        $amount,
        0,
        $gatewayParams['name']
    );
    
} else {

	logTransaction($gatewayParams['name'], $_POST, 'Failure');
    $query1 ="UPDATE tblinvoices SET status = 'Cancelled' WHERE id = '" . $order_id ."';";
    $result1 = full_query($query1);

}
header("Location: ".$gatewayParams['systemurl']."/viewinvoice.php?id=" . $order_id);




