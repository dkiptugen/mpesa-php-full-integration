<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
	{
		public function __construct()
			{
				parent::__construct();
			}
		public function index()
			{
				$this->load->view('welcome_message');
			}
		public function test()
            {
                //echo $this->mpesa->generatetoken();
                var_dump($this->mpesa->transactionstatus("testapi771", "5PW7nppk", "MAG21H3Z7Y","AG_20180116_00004e8c9955ebfd8ccf", 600771, "Shortcode", "return", "payment"));
            }
		public function encrypt()
            {
                var_dump($this->mpesa->B2C("testapi771","5PW7nppk","BusinessPayment",300,600771,254708374149,"return","payment of goods and services"));
            }
	}
