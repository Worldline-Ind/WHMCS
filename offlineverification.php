<?php
require_once 'includes/gatewayfunctions.php';
use WHMCS\ClientArea;
use WHMCS\Database\Capsule;

global $CONFIG;

define('CLIENTAREA', true);
require __DIR__ . '/init.php';

$ca = new ClientArea();

$ca->setPageTitle('Offline Verification');
$ca->initPage();
$datapg = getGatewayVariables('ingenico');

# Define the template filename to be used without the .tpl extension
$data =array();
$data['merchantCode'] = $datapg['merchantCode'];
$data['url'] = $CONFIG['SystemURL'].'/reconciliation.php';
$ca->assign('data', $data);
$ca->setTemplate('offlineverification');
$ca->output();