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
				var_dump($this->mpesa->checkout("254713154085",50,"DEN2017","main description"));
			}
	}
