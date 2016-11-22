<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

      $this->load->library('session');
      $this->load->library('tank_auth');
      
		$this->load->helper('url');
		
	}

	function index()
	{
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      //modify config/routes.php
      $this->load->view('welcome',null);
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */