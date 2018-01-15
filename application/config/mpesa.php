<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->ci 	 =	& get_instance();
$config['ConsumerKey']			=	'2SEPZfDFlcBh5pvfQofxcei72Igflotd';
$config['ConsumerSecret']		=	'oDqu2qEYv9mMJMr8';
$config["token_link"]			=	'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$config["certpass"]				=	'caydeesoft123';

//Mpesa Checkout
$config['checkout_processlink']	=	'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; 
$config['checkout_querylink']	=	'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
$config['checkout_shortcode']	=	'174379';
$config['checkout_passkey']		=	'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$config['checkout_callbackurl']	=	'https://www.standardmedia.co.ke/magazines/callback';

// Mpesa Reversal
$config["reversal_link"]		=	'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
$config["reversal_resultUrl"]	=	'';
$config["reversal_timeoutURL"]	=	'';

// Mpesa Balance
$config["balance_link"]			=	'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
$config["balance_timeoutUrl"]	=	'';
$config["balance_resultUrl"]	=	'';

// Mpesa C2B 
$config["c2b_regiterUrl"]		=  	'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
$config["c2b_transactionUrl"]	=	'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
$config["c2b_confirmationURL"]	= 	'';
$config["c2b_validationURL"]	= 	'';
$config["c2b_shortcode"]		=	'';

// Mpesa Transaction Status
$config["transtat_link"]		=	'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
$config["transtat_resultURL"]	=	'';
$config["transtat_timeoutURL"]	=	'';

// Mpesa B2B
$config["b2b_link"]				= 	'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
$config["b2b_timeoutURL"]		=	'';
$config["b2b_resultURL"]		=	'';

// Mpesa B2C
$config["b2c_link"]				=	'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
$config["b2c_timeoutURL"]		=	'';
$config["b22_resultURL"]		=	'';
