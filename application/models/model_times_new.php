<?php
class Model_times_new extends Model_template
{ 
   public $VALID='Valid';
   public $OBSERVED='Observed';
   public $CORRECTED='Correted';
   
   function __construct()
   {
       parent::__construct();
       $this->load->helper('my_dates_helper');       
   }
   #############################################################################
   function add_time($data)
   {
      $this->db->insert('times_new', $data); 
   }
}