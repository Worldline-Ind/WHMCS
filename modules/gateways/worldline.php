<?php

/**
 * WHMCS Sample Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 * This sample file demonstrates how a payment gateway module for WHMCS should
 * be structured and all supported functionality it can contain.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "worldline" and therefore all functions
 * begin "worldline_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _config
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */



if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function worldline_MetaData()
{
    return array(
        'DisplayName' => 'Worldline',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCreditCardInput' => true,
        'TokenisedStorage' => false,
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function worldline_config()
{
    global $CONFIG;

    $offlineurl = $CONFIG['SystemURL'] . '/offlineverification.php';
    $reconciliationurl = $CONFIG['SystemURL'] . '/reconciliation.php';

    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Cards / UPI / Netbanking / Wallets',
        ),
        'description' => array(
            'FriendlyName' => 'Description',
            'Type' => 'href',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Worldline ePayments is Indias leading digital payment solutions company. Being a company with more than 45 years of global payment experience, we are present in India for over 20 years and are powering over 550,000 businesses with our tailored payment solution',
        ),
        // a text field type allows for single line text input
        'merchantCode' => array(
            'FriendlyName' => 'Merchant Code',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your Merchant Code here',
        ),

        'salt' => array(
            'FriendlyName' => 'SALT',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your SALT here',
        ),
        'schemeCode' => array(
            'FriendlyName' => 'Scheme Code',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your Scheme Code here',
        ),
        'testMode' => array(
            'FriendlyName' => 'Type Of Payment',
            'Type' => 'dropdown',
            'Options' => array(
                'test' => 'Test',
                'live' => 'Live',
            ),
            'Description' => 'For TEST mode amount will be charge 1',
        ),
        'pcolorcode' => array(
            'FriendlyName' => 'Primary Color Code',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '#3977b7',
            'Description' => 'Color value can be hex, rgb or actual color name',
        ),
        'scolorcode' => array(
            'FriendlyName' => 'Secondary Color Code',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '#FFFFFF',
            'Description' => 'Color value can be hex, rgb or actual color name',
        ),
        'button1' => array(
            'FriendlyName' => 'Button Color Code 1',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '#1969bb',
            'Description' => 'Color value can be hex, rgb or actual color name',
        ),
        'button2' => array(
            'FriendlyName' => 'Button Color Code 2',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '#FFFFFF',
            'Description' => 'Color value can be hex, rgb or actual color name',
        ),
        'merchantlogourl' => array(
            'FriendlyName' => 'Merchant Logo Url',
            'Type' => 'text',
            'Size' => '25',
            'Default' => 'https://www.paynimo.com/CompanyDocs/company-logo-md.png',
            'Description' => 'An absolute URL pointing to a logo image of merchant which will show on checkout popup',
        ),

        'enableExpressPay' => array(
            'FriendlyName' => 'Enable ExpressPay',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => 'To enable saved payments set its value to yes',
        ),
        'separatecardmode' => array(
            'FriendlyName' => 'Separate Card Mode',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => 'If this feature is enabled checkout shows two separate payment mode(Credit Card and Debit Card)',
        ),
        'merchantmsg' => array(
            'FriendlyName' => 'Merchant Message',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Customize message from merchant which will be shown to customer in checkout page',
        ),
        'disclaimermsg' => array(
            'FriendlyName' => 'Disclaimer Message',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Customize disclaimer message from merchant which will be shown to customer in checkout page',
        ),
        'paymentMode' => array(
            'FriendlyName' => 'Payment Mode',
            'Type' => 'dropdown',
            'Options' => array(
                'all'              => 'all',
                'cards'         => 'cards',
                'netBanking'    => 'netBanking',
                'UPI'              => 'UPI',
                'imps'          => 'imps',
                'wallets'       => 'wallets',
                'cashCards'     => 'cashCards',
                'NEFTRTGS'      => 'NEFTRTGS',
                'emiBanks'      => 'emiBanks',
            ),
            'Description' => 'If Bank selection is at worldline ePayments India Pvt. Ltd. end then select all, if bank selection at Merchant end then pass appropriate mode respective to selected option',
        ),

        'paymentmodeorder' => array(
            'FriendlyName' => 'Payment Mode Order',
            'Type' => 'textarea',
            'Rows' => '3',
            'Cols' => '60',
            'Default' => 'cards,netBanking,imps,wallets,cashCards,UPI,MVISA,debitPin,NEFTRTGS,emiBanks',
            'Description' => 'Please pass order in this format: cards,netBanking,imps,wallets,cashCards,UPI,MVISA,debitPin,NEFTRTGS,emiBanks',
        ),
        'enableInstrumentDeRegistration' => array(
            'FriendlyName' => 'Enable InstrumentDeRegistration',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => 'If this feature is enabled, you will have an option to delete saved cards',
        ),
        'txnType' => array(
            'FriendlyName' => 'Transaction Type',
            'Type' => 'dropdown',
            'Options' => array(
                'SALE' => 'SALE',

            ),
            'Description' => '',
        ),
        'hidesavedinstruments' => array(
            'FriendlyName' => 'Hide SavedInstruments',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => 'If enabled checkout hides saved payment options even in case of enableExpressPay is enabled.',
        ),
        'saveInstrument' => array(
            'FriendlyName' => 'Save Instrument',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => 'Enable this feature to vault instrument',
        ),
        'embedpopup' => array(
            'FriendlyName' => 'Embed Payment Gateway On Page',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => '',
        ),
        'erroronpopup' => array(
            'FriendlyName' => 'Display Error Message on popup',
            'Type' => 'dropdown',
            'Options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'Description' => '',
        ),
        'offline' => array(
            'FriendlyName' => 'Offline Verification',
            'Type' => 'href',
            'Size' => '25',
            'Default' => '',
            'Description' => '<p><a target="_blank" href=' . $offlineurl . '>Click Here</a></p>',
        ),
        'reconciliation' => array(
            'FriendlyName' => 'Reconciliation',
            'Type' => 'href',
            'Size' => '25',
            'Default' => '',
            'Description' => '<p><a target="_blank" href=' . $reconciliationurl . '>Click Here</a></p>',
        ),
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 *
 * @return string
 */
function worldline_link($params)
{
    // Gateway Configuration Parameters
    $merchantCode = $params['merchantCode'];
    $salt = $params['salt'];
    $schemeCode = $params['schemeCode'];
    $secretKey = $params['secretKey'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    $url = 'https://www.demopaymentgateway.com/do.payment';

    $postfields = array();
    $postfields['username'] = $username;
    $postfields['invoice_id'] = $invoiceId;
    $postfields['description'] = $description;
    $postfields['amount'] = $amount;
    $postfields['currency'] = $currencyCode;
    $postfields['first_name'] = $firstname;
    $postfields['last_name'] = $lastname;
    $postfields['email'] = $email;
    $postfields['address1'] = $address1;
    $postfields['address2'] = $address2;
    $postfields['city'] = $city;
    $postfields['state'] = $state;
    $postfields['postcode'] = $postcode;
    $postfields['country'] = $country;
    $postfields['phone'] = $phone;
    $postfields['callback_url'] = $systemUrl . 'modules/gateways/callback/' . $moduleName . '.php?currency=' . $currencyCode;
    $postfields['return_url'] = $returnUrl;

    $merchant_txn_id = $invoiceId;
    $CustomerId = 'cons' . rand(1, 1000000);
    $callbackurl = $postfields['callback_url'];
    $callbackurl2 = $postfields['callback_url'];


    if ($params['pcolorcode']) {
        $pcolorcode = $params['pcolorcode'];
    } else {
        $pcolorcode = '#3977b7';
    }
    if ($params['scolorcode']) {
        $scolorcode = $params['scolorcode'];
    } else {
        $scolorcode = '#FFFFFF';
    }
    if ($params['button1']) {
        $button1 = $params['button1'];
    } else {
        $button1 = '#1969bb';
    }
    if ($params['button2']) {
        $button2 = $params['button2'];
    } else {
        $button2 = '#FFFFFF';
    }
    $logo_url = $params['merchantlogourl'];
    if (!empty($logo_url) && @getimagesize($logo_url)) {
        $logourl1 = $logo_url;
    } else {
        $logourl1 = 'https://www.paynimo.com/CompanyDocs/company-logo-md.png';
    }
    if ($params['embedpopup'] == '1') {
        $embedpopup = '#worldlinepayment';
    } else {
        $embedpopup = '';
    }
    if ($params['erroronpopup'] == '1' && $params['enableNewWindowFlow'] == '1') {
        $callbackurl = '';
    }

    $enableExpressPay = checkTrueOrFalse($params['enableExpressPay']);
    $separatecardmode = checkTrueOrFalse($params['separatecardmode']);
    $enableNewWindowFlow = checkTrueOrFalse($params['enableNewWindowFlow']);
    $enableInstrumentDeRegistration = checkTrueOrFalse($params['enableInstrumentDeRegistration']);
    $hidesavedinstruments = checkTrueOrFalse($params['hidesavedinstruments']);
    $saveInstrument = checkTrueOrFalse($params['saveInstrument']);
    $merchantmsg = $params['merchantmsg'];
    $disclaimermsg = $params['disclaimermsg'];
    $paymentMode = $params['paymentMode'];
    $txnType = $params['txnType'];
    if ($params['testMode'] == 'test') {
        $amount = '1';
    }
    if ($params['paymentmodeorder']) {
        $paymentModeOrder = $params['paymentmodeorder'];
        $paymentorderarray = explode(',', $paymentModeOrder);
        $paymentModeOrder_1 = isset($paymentorderarray[0]) ? $paymentorderarray[0] : null;
        $paymentModeOrder_2 = isset($paymentorderarray[1]) ? $paymentorderarray[1] : null;
        $paymentModeOrder_3 = isset($paymentorderarray[2]) ? $paymentorderarray[2] : null;
        $paymentModeOrder_4 = isset($paymentorderarray[3]) ? $paymentorderarray[3] : null;
        $paymentModeOrder_5 = isset($paymentorderarray[4]) ? $paymentorderarray[4] : null;
        $paymentModeOrder_6 = isset($paymentorderarray[5]) ? $paymentorderarray[5] : null;
        $paymentModeOrder_7 = isset($paymentorderarray[6]) ? $paymentorderarray[6] : null;
        $paymentModeOrder_8 = isset($paymentorderarray[7]) ? $paymentorderarray[7] : null;
        $paymentModeOrder_9 = isset($paymentorderarray[8]) ? $paymentorderarray[8] : null;
        $paymentModeOrder_10 = isset($paymentorderarray[9]) ? $paymentorderarray[9] : null;
    } else {
        $paymentModeOrder_1 = "cards";
        $paymentModeOrder_2 = "netBanking";
        $paymentModeOrder_3 = "imps";
        $paymentModeOrder_4 = "wallets";
        $paymentModeOrder_5 = "cashCards";
        $paymentModeOrder_6 =  "UPI";
        $paymentModeOrder_7 =  "MVISA";
        $paymentModeOrder_8 = "debitPin";
        $paymentModeOrder_9 = "emiBanks";
        $paymentModeOrder_10 = "NEFTRTGS";
    }

    $datastring = $merchantCode . "|" . $merchant_txn_id . "|" . $amount . "|" . "|" . $CustomerId . "|" . $phone . "|" . $email . "||||||||||" . $salt;

    $hashed = hash('sha512', $datastring);

    $table = "tbltransaction_history";
    $fields = "invoice_id";
    $where = array("invoice_id" => $invoiceId);
    $result = select_query($table, $fields, $where);
    $data = mysql_fetch_array($result);
    if ($data == '') {
        logTransaction($moduleName, $datastring, 'Request');
        $table = "tbltransaction_history";
        $insert_array = [
            "invoice_id" => $invoiceId,
            "gateway" => "worldline",
            "transaction_id" => $merchant_txn_id,
            "description" => "Merchant Reference No",
            "additional_information" => "merchantRefNo",
            "updated_at" => date('Y-m-d H:i:s'),
        ];
        $newid = insert_query($table, $insert_array);
    }


    return <<<EOT
    <form action="$callbackurl2" id="response-form" method="POST" onSubmit="return validate()">
    <input type="hidden" name="msg" value="" id="response-string">
    <input type="hidden" name="worldline-orderid" value="$invoiceId" id="response-string">
    </form>
    <button id="btnSubmit" type="button">$langPayNow</button>
<script type="text/javascript" src="https://www.paynimo.com/Paynimocheckout/server/lib/checkout.js"></script>
<script type="text/javascript">
jQuery("#btnSubmit").click(function(e){
    function handleResponse(res) {
            if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0300') {
            // success blockaler
                let stringResponse = res.stringResponse;
                            console.log(stringResponse);
                            jQuery("#response-string").val(stringResponse);
                            jQuery("#response-form").submit();
            //alert('success');
                } else if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0398') {
            // initiated block
            //alert('398');
                } else {
            // error block
            //alert('fail');
                }   
            };

    if ('$enableExpressPay' == 1) {
            var enableExpressPay = true;
            } else {
            var enableExpressPay = false;    
            }

            if ('$enableNewWindowFlow' == 1) {
            var enableNewWindowFlow = true;
            } else {
            var enableNewWindowFlow = false;    
            }

            if ('$hidesavedinstruments' == 1) {
            var hideSavedInstruments = true;
            } else {
            var hideSavedInstruments = false;    
            }

            if ('$enableInstrumentDeRegistration' == 1) {
            var enableInstrumentDeRegistration = true;
            } else {
            var enableInstrumentDeRegistration = false;    
            }

            if ('$separatecardmode' == 1) {
            var separateCardMode = true;
            } else {
            var separateCardMode = false;    
            }

            if ('$saveInstrument' == 1) {
            var saveInstrument = true;
            } else {
            var saveInstrument = false;    
            }


    var configJson = {
        'tarCall': false,
        'features': {
            'showPGResponseMsg': true,
                'enableNewWindowFlow': true,   //for hybrid applications please disable this by passing false
                'enableAbortResponse': false,
                'enableExpressPay': enableExpressPay,  //if unique customer identifier is passed then save card functionality for end  end customer
                'enableInstrumentDeRegistration': enableInstrumentDeRegistration,  //if unique customer identifier is passed then option to delete saved card by end customer
                'enableMerTxnDetails': true,
                'hideSavedInstruments': hideSavedInstruments,
                'separateCardMode': separateCardMode
                
                
            },
            'consumerData': {
                'deviceId': 'WEBSH2',
                   //possible values 'WEBSH1', 'WEBSH2' and 'WEBMD5'
                'token': '$hashed',              
                'returnUrl': '$callbackurl',          
               'responseHandler': handleResponse,
                'paymentMode': '$paymentMode',
                'merchantLogoUrl': '$logourl1',  //provided merchant logo will be displayed
                'merchantId': '$merchantCode',
                'currency': '$currencyCode',
                'txnType': 'SALE',
                'saveInstrument':saveInstrument,
                'disclaimerMsg': '$disclaimermsg',
                'merchantMsg': '$merchantmsg',
                'checkoutElement': '$embedpopup',
                'consumerId': '$CustomerId',  //Your unique consumer identifier to register a eMandate/eNACH
                'consumerMobileNo': '$phone',
                'consumerEmailId': '$email',
                'txnId': '$merchant_txn_id',   //Unique merchant transaction ID
                'items': [{
                    'itemId': '$schemeCode',
                    'amount': '$amount',
                    'comAmt': '0'
                }],
                'paymentModeOrder': [
                            '$paymentModeOrder_1',
                            '$paymentModeOrder_2',
                            '$paymentModeOrder_3',
                            '$paymentModeOrder_4',
                            '$paymentModeOrder_5',
                            '$paymentModeOrder_6',
                            '$paymentModeOrder_7',
                            '$paymentModeOrder_8',
                            '$paymentModeOrder_9',
                            '$paymentModeOrder_10'
                        ],
                'cartDescription': '}{custname:'+'$firstname'+'}{orderid:'+'$invoiceId',
                'merRefDetails': [
                    {"name": "Txn. Ref. ID", "value": '$merchant_txn_id'}
                ],
                'customStyle': {
                    'PRIMARY_COLOR_CODE': '$pcolorcode',   //merchant primary color code
                    'SECONDARY_COLOR_CODE': '$scolorcode',   //provide merchant's suitable color code
                    'BUTTON_COLOR_CODE_1': '$button1',   //merchant's button background color code
                    'BUTTON_COLOR_CODE_2': '$button2'   //provide merchant's suitable color code for button text
                },
                
            }
        };
        
    jQuery.pnCheckout(configJson);
    if(configJson.features.enableNewWindowFlow){
            pnCheckoutShared.openNewWindow();
        }
    });
</script>
EOT;
}

function checkTrueOrFalse($data)
{

    if ($data == '1') {
        return "1";
    } else {
        return "0";
    }
}

/**
 * Refund transaction.
 *
 * Called when a refund is requested for a previously successful transaction.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/refunds/
 *
 * @return array Transaction response status
 */
function worldline_refund($params)
{
    // Gateway Configuration Parameters

    //logActivity("WHMCS Debug: refund function called");
    $merchantCode = $params['merchantCode'];
    $secretKey = $params['secretKey'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Transaction Parameters
    $transactionIdToRefund = $params['transid'];
    $refundAmount = $params['amount'];
    $currencyCode = $params['currency'];
    $date = explode(' ', $params['dueDate']);

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    $request_array = array(
        "merchant" => array("identifier" => $merchantCode),
        "cart" => (object) null,
        "transaction" => array(
            "deviceIdentifier" => "S",
            "amount" => $refundAmount,
            "currency" => $currencyCode,
            "dateTime" => $date[0],
            "token" => $transactionIdToRefund,
            "requestType" => "R"
        )
    );

    $refund_data = json_encode($request_array);
    $refund_url = "https://www.paynimo.com/api/paynimoV2.req";
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => $refund_data,
            'header' =>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
        )
    );
    $context = stream_context_create($options);
    $response_array = json_decode(file_get_contents($refund_url, false, $context));
    $status_code = $response_array->paymentMethod->paymentTransaction->statusCode;
    $status_message = $response_array->paymentMethod->paymentTransaction->statusMessage;
    $error_message = $response_array->paymentMethod->paymentTransaction->errorMessage;
    if ($status_code == '0400') {
        return array(
            // 'success' if successful, otherwise 'declined', 'error' for failure
            'status' => 'success',
            // Data to be recorded in the gateway log - can be a string or array
            'rawdata' => $response_array,
            // Unique Transaction ID for the refund transaction
            'transid' => $transactionIdToRefund,
            // Optional fee amount for the fee value refunded
            'fees' => '0',
        );
    } else {
        return array(
            // 'success' if successful, otherwise 'declined', 'error' for failure
            'status' => 'error',
            // Data to be recorded in the gateway log - can be a string or array
            'rawdata' => $response_array,
            // Unique Transaction ID for the refund transaction
            'transid' => $transactionIdToRefund,
            // Optional fee amount for the fee value refunded
            'fees' => '0',
        );
    }
}
