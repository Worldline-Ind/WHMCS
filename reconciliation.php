<?php
require_once 'includes/gatewayfunctions.php';
require_once 'includes/invoicefunctions.php';

use WHMCS\ClientArea;
use WHMCS\Database\Capsule;

global $CONFIG;

define('CLIENTAREA', true);
require __DIR__ . '/init.php';

$ca = new ClientArea();

$ca->setPageTitle('Reconciliation');
$ca->initPage();
$datapg = getGatewayVariables('ingenico');
//print_r($datapg); die;
if($_POST){

	$query ="SELECT
                    i.id,
                    th.transaction_id,
                    DATE(i.created_at) AS mydate
                    
                    FROM
                    tblinvoices i
                    RIGHT JOIN tbltransaction_history th
                    ON i.id = th.invoice_id
                    WHERE i.status = 'Unpaid' AND i.paymentmethod = 'ingenico'
                    AND i.created_at BETWEEN '" . $_POST['fromdate'] .' 00:00:00'. "' AND '" . $_POST['todate'] .' 23:59:59'. "';";
	$result = full_query($query);
	//$data = mysql_fetch_array($result);
	while($row = mysql_fetch_array($result))
             {
           $rows[] = $row;

        }
        $successFullOrdersIds = [];
        if($rows != ''){

            foreach ($rows as $order_array) {
            $order_id = $order_array['id'];
            $currency = 'INR';           
            $date_input = $order_array['mydate'];            
            $merchantTxnRefNumber = $order_array['transaction_id'];
            $request_array = array("merchant"=>array("identifier"=>$datapg['merchantCode']),
                                    "transaction"=>array(
                                        "deviceIdentifier"=>"S",
                                        "currency"=>$currency,
                                        "identifier"=>$merchantTxnRefNumber,
                                        "dateTime"=>$date_input,
                                        "requestType"=>"O"          
                                ));
            //print_r($request_array); die;
            $refund_data = json_encode($request_array);
            $url = "https://www.paynimo.com/api/paynimoV2.req";
            $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode($request_array),
                'header' =>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
                )
            );
            $context     = stream_context_create($options);
            $response_array = json_decode(file_get_contents($url, false, $context));
            //print_r($response_array); die;
            $status_code = $response_array->paymentMethod->paymentTransaction->statusCode; 
            $status_message = $response_array->paymentMethod->paymentTransaction->statusMessage;
            $txn_id = $response_array->paymentMethod->paymentTransaction->identifier;
            $amount = $response_array->paymentMethod->paymentTransaction->amount; 
            if($status_code=='0300'){
                $success_ids = $order_array['id'];
                addInvoicePayment(
                $order_array['id'],
                $txn_id,
                $amount,
                0,
                'ingenico'
    );

                array_push($successFullOrdersIds, $success_ids);
                    
            }else if($status_code=="0397" || $status_code=="0399" || $status_code=="0396" || $status_code=="0392"){
                $success_ids = $order_array['id'];
               	$query1 ="UPDATE tblinvoices SET status = 'Cancelled' WHERE id = '" . $order_array['id'] ."';";
               	$result1 = full_query($query1);

                array_push($successFullOrdersIds, $success_ids);
               
            }else{
                null;
            }
                        
        }

        if($successFullOrdersIds){
            $datapg['message'] = "Updated Order Status for Order ID:  " . implode(", ", $successFullOrdersIds);
        }else{
            $datapg['message'] = "Updated Order Status for Order ID: None";
        }
    }
    else{
        $datapg['message'] = "Updated Order Status for Order ID: None";
    }
}


# Define the template filename to be used without the .tpl extension
$datapg['url'] = $CONFIG['SystemURL'].'/reconciliation.php';
$ca->assign('data', $datapg);
$ca->setTemplate('reconciliation');
$ca->output();