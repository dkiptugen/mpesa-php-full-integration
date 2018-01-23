<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller']    = 'home';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;
$route["B2CCallback"]           = 'callbacks/processB2CRequestCallback';
$route["B2BCallback"]           = 'callbacks/processB2BRequestCallback';
$route["C2BValidation"]         = 'callbacks/processC2BRequestValidation';
$route["C2BConfirmation"]       = 'callbacks/processC2BRequestConfirmation';
$route["AccountBalCallback"]    = 'callbacks/processAccountBalanceRequestCallback';
$route["ReversalCallback"]      = 'callbacks/processReversalRequestCallBack';
$route["RequestStkCallback"]    = 'callbacks/processSTKPushRequestCallback';
$route["QueryStkCallback"]      = 'callbacks/processSTKPushQueryRequestCallback';
$route["TransStatCallback"]     = 'callbacks/processTransactionStatusRequestCallback';

