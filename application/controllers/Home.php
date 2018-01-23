<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
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
                
                var_dump($this->mpesa->B2C("SalaryPayment","5000","salary","salaries deployment"));
            }
		public function encrypt()
            {
                echo $this->mpesa->encryptPassword("This is me");
            }
        public function token()
        	{
        		echo $this->mpesa->generatetoken();
        	}
	}
