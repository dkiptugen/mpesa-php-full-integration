# Documentation #
This is an integration of the mpesaG2 api using CodeIgniter

### Configuration ###
Configuration is stored at /application/config/mpesa.php .
```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->ci 	 =	& get_instance();
$this->ci->load->helper("url");

// credentials
$config['ConsumerKey']			=	'<consumer-key>';
$config['ConsumerSecret']		=	'<consumer secret>';
$config["token_link"]			=	'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$config["initiator"]			=	'';
$config["credential"]			=	'';
$config["partyA_shortcode"]		=	'';
$config["partyB_shortcode"]		= 	'';
$config["test_msisdn"]			=	'';
$config["test_link"]			=	site_url();

//Mpesa Checkout
$config['checkout_processlink']	=	'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; 
$config['checkout_querylink']	=	'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
$config['checkout_shortcode']	=	'';
$config['checkout_passkey']		=	'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$config['checkout_rcallbackurl']=	site_url("RequestStkCallback");
$config['checkout_qcallbackurl']=	site_url("QueryStkCallback");

// Mpesa Reversal
$config["reversal_link"]		=	'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
$config["reversal_resultUrl"]	=	site_url("ReversalCallback");
$config["reversal_timeoutURL"]	=	site_url("ReversalCallback");

// Mpesa Balance
$config["balance_link"]			=	'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
$config["balance_timeoutUrl"]	=	site_url("AccountBalCallback");
$config["balance_resultUrl"]	=	site_url("AccountBalCallback");

// Mpesa C2B 
$config["c2b_regiterUrl"]		=  	'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
$config["c2b_transactionUrl"]	=	'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
$config["c2b_confirmationUrl"]	= 	site_url("C2BConfirmation");
$config["c2b_validationUrl"]	= 	site_url("C2BValidation");
$config["c2b_shortcode"]		=	$config["partyA_shortcode"];//'';

// Mpesa Transaction Status
$config["transtat_link"]		=	'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
$config["transtat_resultURL"]	=	site_url("TransStatCallback");
$config["transtat_timeoutURL"]	=	site_url("TransStatCallback");

// Mpesa B2B
$config["b2b_link"]				= 	'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
$config["b2b_timeoutURL"]		=	site_url("B2BCallback");
$config["b2b_resultURL"]		=	site_url("B2BCallback");

// Mpesa B2C
$config["b2c_link"]				 =	'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
$config["b2c_timeoutURL"]		=	site_url("B2CCallback");
$config["b2c_resultURL"]		=	site_url("B2CCallback");

```
The library is at /application/libraries/mpesa.php .It can be extennded to whatever way you want
