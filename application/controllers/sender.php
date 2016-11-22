<?php
class Sender extends CI_Controller
{
   function __construct()
   {
      parent::__construct(); 
      $this->load->library('session');
      $this->load->library('tank_auth');
   }
   #############################################################################
   function send_mail()
   {
       $headers = "From: info@onebolivia.com";
      
       mail($to="arguitos7@gmail.com", $subject="test", $message="test ".__FILE__, $headers); 
      
      echo "<br>" . __FILE__ . " " . __LINE__ . "<BR>";
   }
}