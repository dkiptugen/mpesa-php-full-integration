# Documentation #

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/b884326515d14bf99cda3cb718c0baef)](https://app.codacy.com/manual/caydee/mpesa-php-full-integration?utm_source=github.com&utm_medium=referral&utm_content=caydee/mpesa-php-full-integration&utm_campaign=Badge_Grade_Dashboard)

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
```<?php
class Mpesa
	{
		public $mpesa;
		protected $ci;
		public function __construct()
			{
				$this->ci 	 =	& get_instance();
				$this->ci->config->load('mpesa',TRUE);
				$this->mpesa =	(object)$this->ci->config->item('mpesa');				
			}
		public function generatetoken()
			{				
				$url 	= 	$this->mpesa->token_link;
				$curl 	= 	curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				$credentials = base64_encode($this->mpesa->ConsumerKey.':'.$this->mpesa->ConsumerSecret);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); 
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$curl_response = curl_exec($curl);
				return json_decode($curl_response)->access_token;				
			}
		public function cert($plaintext)
			{
				$fp=fopen(APPPATH."cert/cert.cer","r");
				$publicKey = fread($fp,filesize(APPPATH."cert/cert.cer"));
				fclose($fp);
				openssl_get_publickey($publicKey);
				openssl_public_encrypt($plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
				return  base64_encode($encrypted);				
			}		
		public function getIdentifier($type)
			{
				$type=strtolower($type);
				switch($type)
					{
						case "msisdn":
						        $x = 1;
						        break;
						case "tillnumber":
								$x = 2;
								break;
						case "shortcode":
								$x = 4;
								break;
					}
				return $x;
			}
		public function checkout($msisdn,$amount,$ref,$desc)
			{
				$url 	= $this->mpesa->checkout_processlink;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken()));
                $timestamp 	=	date('YmdHis');
                $password 	=	base64_encode($this->mpesa->checkout_shortcode.$this->mpesa->checkout_passkey.$timestamp);
				$curl_post_data = array(				  
										  	'BusinessShortCode' 	=> $this->mpesa->checkout_shortcode,
										  	'Password' 				=> $password,
										  	'Timestamp' 			=> $timestamp,
										  	'TransactionType' 		=> 'CustomerPayBillOnline',
										  	'Amount' 				=> $amount,
										  	'PartyA' 				=> $msisdn,
										  	'PartyB' 				=> $this->mpesa->checkout_shortcode,
										  	'PhoneNumber' 			=> $msisdn,
										  	'CallBackURL' 			=> $this->mpesa->checkout_rcallbackurl,
										  	'AccountReference' 		=> $ref,
										  	'TransactionDesc' 		=> $desc
										);
				$data_string 	= 	json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response 	= 	curl_exec($curl);
				$data 			=	(array)json_decode($curl_response);
				$data["refno"]	=	$curl_post_data['AccountReference'];
				return $curl_response;

			}
		public function checkout_query($CheckoutRequestID)
			{
				$url 	= $this->mpesa->checkout_querylink;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken())); //setting custom header
                $timestamp 		=	date('YmdHis');
                $password 		=	base64_encode($this->mpesa->checkout_shortcode.$this->mpesa->checkout_passkey.$timestamp);
				$curl_post_data = array(
										  	'BusinessShortCode' 	=> $this->mpesa->checkout_shortcode,
										  	'Password' 				=> $password,
										  	'Timestamp'				=> $timestamp,
										  	'CheckoutRequestID' 	=> $CheckoutRequestID
										);
				$data_string 	= json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		public function reversal($TransID,$amount,$receiver,$remarks,$receiverType,$ocassion)
			{
				$url 	= $this->mpesa->reversal_link;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken()));
				$curl_post_data = array(
										  	'Initiator' 				=> $this->mpesa->initiator,
										  	'SecurityCredential' 		=> $this->cert($this->mpesa->credential),
										  	'CommandID' 				=> 'TransactionReversal',
										  	'TransactionID' 			=> $TransID,
										  	'Amount' 					=> $amount,
										  	'ReceiverParty' 			=> $receiver,
										  	'RecieverIdentifierType' 	=> $this->getIdentifier($receiverType),
										  	'ResultURL' 				=> $this->mpesa->reversal_resultUrl,
										  	'QueueTimeOutURL' 		=> $this->mpesa->reversal_timeoutURL,
										  	'Remarks' 				=> $remarks,
										  	'Occasion' 				=> $ocassion
										);

				$data_string = json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		public function accountbalance($remark)
			{
				$url 	= $this->mpesa->balance_link;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken()));

				$curl_post_data = array(
										   	'Initiator' 			=> $this->mpesa->initiator,
										  	'SecurityCredential' 	=> $this->cert($this->mpesa->credential),
										  	'CommandID' 			=> 'AccountBalance',
										  	'PartyA' 				=> $this->mpesa->partyA_shortcode,
										  	'IdentifierType' 		=> $this->getIdentifier("Shortcode"),
										  	'Remarks' 				=> $remark,
										  	'QueueTimeOutURL' 		=> $this->mpesa->balance_timeoutUrl,
										  	'ResultURL' 			=> $this->mpesa->balance_resultUrl
										);
				$data_string = json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		public function C2B_REGISTER($status='Completed')
			{
				$url 	= 	$this->mpesa->c2b_regiterUrl;
				$curl 	= 	curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken())); 
				$curl_post_data = array(				  
										  	'ShortCode' 		=> $this->mpesa->c2b_shortcode,
										  	'ResponseType' 		=> $status,
										  	'ConfirmationURL' 	=> $this->mpesa->c2b_confirmationUrl,
										  	'ValidationURL' 	=> $this->mpesa->c2b_validationUrl
										);
				$data_string = json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		public function C2B($amount,$msisdn,$ref)
			{
				$url 	= $this->mpesa->c2b_transactionUrl;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken()));
				$curl_post_data = array(
										    "ShortCode"		=>	$this->mpesa->c2b_shortcode,
										    "CommandID"		=>	"CustomerPayBillOnline",
										    "Amount"		=> 	$amount,
										    "Msisdn"		=>	$msisdn,
										    "BillRefNumber"	=>	$ref
										);
				$data_string = json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		public function B2B($CommandID,$amount,$accountref,$remarks)
			{
				$url 	= 	$this->mpesa->b2b_link;
				$curl 	= 	curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken())); 
				$curl_post_data = array(
										  	'Initiator' 				=> $this->mpesa->initiator,
										  	'SecurityCredential' 		=> $this->cert($this->mpesa->credential),
										  	'CommandID' 				=> $CommandID,
										  	'SenderIdentifierType' 		=> $this->getIdentifier("shortcode"),
										  	'RecieverIdentifierType' 	=> $this->getIdentifier("shortcode"),
										  	'Amount' 					=> $amount,
										  	'PartyA' 					=> $this->mpesa->partyA_shortcode,
										  	'PartyB' 					=> $this->mpesa->partyB_shortcode,
										  	'AccountReference' 			=> $accountref,
										  	'Remarks' 					=> $remarks,
										  	'QueueTimeOutURL' 			=> $this->mpesa->b2b_timeoutURL,
										  	'ResultURL' 				=> $this->mpesa->b2b_resultURL
										);
				$data_string = json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		public function B2C($CommandID,$amount,$remarks,$ocassion)
			{
			    $url 	= $this->mpesa->b2c_link;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken())); 
				$curl_post_data = array(
										  	'InitiatorName' 		=> 	$this->mpesa->initiator,
										  	'SecurityCredential' 	=> 	$this->cert($this->mpesa->credential),
										  	'CommandID' 			=> 	$CommandID,
										  	'Amount' 				=> 	$amount,
										  	'PartyA' 				=> 	$this->mpesa->partyA_shortcode,
										  	'PartyB' 				=> 	$this->mpesa->test_msisdn,
										  	'Remarks' 				=> 	$remarks,
										  	'QueueTimeOutURL' 		=>  $this->mpesa->b2c_timeoutURL,
										  	'ResultURL' 			=> 	$this->mpesa->b2c_resultURL,
										  	'Occasion' 				=> 	$ocassion
										);

				$data_string = json_encode($curl_post_data);

				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

				$curl_response = curl_exec($curl);
				print_r($curl_response);

				echo $curl_response;
			}
		public function transactionstatus($transID,$conversionID,$msisdn,$identifier,$remarks,$ocassion)
			{
				$url 	=	$this->mpesa->transtat_link;
				$curl 	= 	curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken()));
				$curl_post_data = array(
										  	'Initiator' 			=> $this->mpesa->initiator,
										  	'SecurityCredential' 	=> $this->cert($this->mpesa->credential),
										  	'CommandID' 			=> 'TransactionStatusQuery',
										  	'TransactionID' 		=> $transID,
										  	'PartyA' 				=> $msisdn,
										  	'IdentifierType' 		=> $this->getIdentifier($identifier),
										  	'ResultURL' 			=> $this->mpesa->transtat_resultURL,
										  	'QueueTimeOutURL' 		=> $this->mpesa->transtat_timeoutURL,
										  	'Remarks' 				=> $remarks,
										  	'Occasion' 				=> $ocassion,
                                            'OriginalConversationID'=> $conversionID
										);
				$data_string = json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response = curl_exec($curl);
				return $curl_response;
			}
		
	}

```
