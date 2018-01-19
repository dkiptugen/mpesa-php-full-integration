<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller
	{
	    private $data;
		public function __construct()
			{
				parent::__construct();
				$this->data["msg"]=NULL;
			}
		public function is_logged_in()
			{
                if($this->session->userdata("loggedin")===TRUE)
                    {
                        return (boolean)TRUE;
                    }
                else
                    {
                        return (boolean)FALSE;
                    }
			}
		public function index()
			{
			    if($this->is_logged_in())
                    {
                        redirect("home");
                    }
                else
                    {
                        $this->data["view"]="login";
                        $this->load->view("LOGIN/structure",(object)$this->data);
                    }
			}
	}