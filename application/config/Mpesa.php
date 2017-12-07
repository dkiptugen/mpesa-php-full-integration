<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['ConsumerKey']='2SEPZfDFlcBh5pvfQofxcei72Igflotd';
$config['ConsumerSecret']='oDqu2qEYv9mMJMr8';
$config["token_link"]='https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
//Mpesa Checkout
$config['checkout_requestlink']='https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'; 
$config['checkout_querylink']='https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
$config['shortcode']='';
$config['passkey']='';
$config['callbackurl']='';