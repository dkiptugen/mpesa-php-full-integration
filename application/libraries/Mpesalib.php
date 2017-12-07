<?php
class Mpesalib
	{
		public $data;
		protected $ci;
		public function __construct()
			{
				$this->ci=& get_instance();
				$this->ci->config->load('Mpesa',TRUE,TRUE);
				$this->data=$this->ci->config->config["Mpesa"];				
			}
		public function generatetoken()
			{				
				$url = $this->data["token_link"];
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				$credentials = base64_encode($this->data["ConsumerKey"].':'.$this->data["ConsumerSecret"]);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$curl_response = curl_exec($curl);
				return json_decode($curl_response);				
			}
	}