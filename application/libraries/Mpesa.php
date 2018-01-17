<?php
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
		public static function encryptPassword($password)
			{

		        //$pub_key 			= openssl_pkey_get_public(file_get_contents('Certs/apicrypt-staging.safaricom.co.ke.cer'));
		        $pub_key 			= openssl_pkey_get_public(file_get_contents(APPPATH."cert/cert.cer"));
		        $pubKeyData			= openssl_pkey_get_details($pub_key);
		        $rsa 				= new Crypt_RSA();
		        $rsa->loadKey($pubKeyData['key']); // public key
		        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		        $ciphertext 		= $rsa->encrypt($password);
		        $securityCredential = base64_encode($ciphertext);
		        return $securityCredential;
    		}
		public function getIdentifier($type)
			{
				switch($type)
					{
						case "MSISDN":
						        $x = 1;
						        break;
						case "Till Number":
								$x = 2;
								break;
						case "Shortcode":
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
										  	'CallBackURL' 			=> $this->mpesa->checkout_callbackurl,
										  	'AccountReference' 		=> $ref,
										  	'TransactionDesc' 		=> $desc
										);
				$data_string 	= 	json_encode($curl_post_data);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				$curl_response 	= 	curl_exec($curl);
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
		public function reversal($Initiator,$cred,$TransID,$amount,$receiver,$remarks,$receiverType,$ocassion)
			{
				$url 	= $this->mpesa->reversal_link;
				$curl 	= curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken()));
				$curl_post_data = array(
										  	'Initiator' 				=> $Initiator,
										  	'SecurityCredential' 		=> $cred,
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
		public function B2B($CommandID,$accountref,$remarks)
			{
				$url 	= 	$this->mpesa->b2b_link;
				$curl 	= 	curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->generatetoken())); 
				$curl_post_data = array(
										  	'Initiator' 				=> $this->mpesa->initiator,
										  	'SecurityCredential' 		=> $this->cert($this->mpesa->credential),
										  	'CommandID' 				=> $CommandID,
										  	'SenderIdentifierType' 		=> $this->getIdentifier("Shortcode"),
										  	'RecieverIdentifierType' 	=> $this->getIdentifier("MSISDN"),
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
				$url = $this->mpesa->b2c_link;
				$curl = curl_init();
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
										  	'QueueTimeOutURL' 		=> 	$this->mpesa->b2c_timeoutURL,
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