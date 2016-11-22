<?php
class Dashboard extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      
      $this->load->library('session');
      $this->load->library('tank_auth');
      
      $this->load->helper('my_dates_helper');
      $this->load->helper('my_messages_helper');
      
      $this->load->helper('my_toolkits_helper');
      
      $this->load->model('model_times');      
      $this->load->model('tank_auth/users');
      
      $this->load->helper('url');
   }
   #############################################################################
   function index()
   {
      if ( ! $this->tank_auth->is_logged_in() )
      {
         $this->tank_auth->attempt_login_included_company();
         
         $uri_company="";
         
         $company_logged = $this->session->userdata("company_logged");
         if(isset($company_logged) AND strcasecmp($company_logged['name'],"")!=0)
         {
            $uri_company ="?company=".$company_logged['name'];
         }
         
         redirect('/auth/login/'.$uri_company);
      }
      
      $data_header = $this->tank_auth->get_header_data();      
      $this->load->view('template/header', $data_header);
      $this->load->view('dashboard', null);
      $this->load->view('template/footer', null);
   }
}