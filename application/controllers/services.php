<?php
class Services extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      
      $this->load->library('session');
      $this->load->library('tank_auth');
      
      $this->load->helper('my_dates_helper');
      $this->load->helper('my_files_helper');
      $this->load->helper('my_messages_helper');      
      $this->load->helper('my_toolkits_helper');      
      
      $this->load->model('model_times_new');      
      $this->load->model('model_accounts');      
      
      $this->load->model('model_template');      
      $this->load->model('model_projects');     
      $this->load->model('tank_auth/users');
      $this->load->helper('url');      
      $this->load->library('template');
      
   }
   ############################################################
   function add_account_items()
   {
      ob_start();
      $response = "";
      
      if(isset($_REQUEST['xml_account_items']))
      {
         $data_received = (array)simplexml_load_string($_REQUEST['xml_account_items']);                     

         if(isset($data_received['account_item']))
         {
            if(is_object($data_received['account_item']))
            {
               $times[0] = $data_received['account_item'];
            }
            else
            {
               $times = $data_received['account_item'];
            }
            
            for($i=0; $i<count($times); $i++)
            { 
               $data['user_id'] = "".$times[$i]->user_id;
               $data['company_id'] = "".$times[$i]->company_id;
               $data['description'] = "".$times[$i]->description;
               $data['register_date'] = "".$times[$i]->register_date;
               
               $data['account_in'] = "".$times[$i]->account_in;
               $data['account_out'] = "".$times[$i]->account_out;
               $data['account_category_id'] = "".$times[$i]->account_category_id;
               
               $this->model_accounts->add_account_item($data);
            }            
            $response = "successful";
         }
         else
         {
            $response = "empty";
         }
      }
      else 
      {
         $response = "failed";
      }
      $str_xml_return  = array_to_xml(array("xml_response"=>array("response"=>$response)))->asXML();      
      $this->test_saved_xml($str_xml_return); 

      $buff = ob_get_clean();

      if(strcasecmp($buff,"")!=0) file_append_contents(getcwd()."/coco_log.txt", __FILE__." ".__LINE__." ".__FUNCTION__."() ".date("Y-m-d H:i:s")."\n".$buff."\n\n");     
      echo $str_xml_return;
   }
   /*
    * ##################################################################
    * @param: company_id
    * @param: limit
    */
   function list_account_items_limited()
   {
      $list_account_items = array();      
      $str_xml_return="";
      
      if(isset($_REQUEST["company_id"]))
      {
         $list_account_items_formated = array();
            
         $list_account_items = $this->model_accounts->get_list_account_items_limited($_REQUEST["company_id"], $_REQUEST["limit"]);      
         
         for($i=0;$i<count($list_account_items);$i++)
         {
            $list_account_items_formated[$i]["account_item"] = $list_account_items[$i];
         }
         
         $str_xml_return = array_to_xml($list_account_items_formated)->asXML();
      }
      else 
      {
         $str_xml_return  = array_to_xml(array("response"=>"insufficient parameters"))->asXML(); 
      }
      
      ob_start();      
      $buff = ob_get_clean();      
      
      if(strcasecmp($buff,"")!=0) file_append_contents(getcwd()."/coco_log.txt", __FILE__." ".__LINE__." ".__FUNCTION__."() ".date("Y-m-d H:i:s")."\n".$buff."\n\n");      
      echo $str_xml_return;
   }
   
   
   //list_account_categories
   function list_account_categories()
   {
      ob_start();      
      $list_accounts_tree = array();      
      
      if(isset($_REQUEST["company_id"]))
      {
         $this->model_accounts->generate_list_accounts_tree2($list_accounts_tree, $_REQUEST["company_id"]);    
      }
      
      $buff = ob_get_clean();      
      if(strcasecmp($buff,"")!=0) file_append_contents(getcwd()."/coco_log.txt", __FILE__." ".__LINE__." ".__FUNCTION__."() ".date("Y-m-d H:i:s")."\n".$buff."\n\n");      
      echo array_to_xml($list_accounts_tree)->asXML();       
   }
   
   #############################################################################
   function list_projects()
   {
      ob_start();
      $quantity_projects = 7;      
      $list_last_projects = $this->model_projects->get_list_projects_by($_REQUEST['company_id'], $status=null, $_REQUEST['user_id'], $quantity_projects);
	  //$list_last_projects = $this->model_projects->get_list_projects_by(6, $status=null, 47, $quantity_projects);
      
      $array_to_xml = array();
	  
      for($i =0; $i<count($list_last_projects);$i++)
      {
		 
         $last_activity = $this->model_projects->get_list_last_objects_from_project($list_last_projects[$i]['id_object'], $limit=1);
      
         $list_last_projects[$i]['last_activity_ago'] = ucwords(ez_date($last_activity[0]['register_date'])).' ago.';
         $list_last_projects[$i]['last_activity_type'] = $last_activity[0]['type'];
         $list_last_projects[$i]['last_activity_action_status'] = $last_activity[0]['action_status'];
         
         $array_to_xml[$i]['MyXmlProject'] = $list_last_projects[$i];
      }
      
 	   $str_xml_return = array_to_xml($array_to_xml)->asXML();       
     

      $buff = ob_get_clean();

      if(strcasecmp($buff,"")!=0) file_append_contents(getcwd()."/coco_log.txt", __FILE__." ".__LINE__." ".__FUNCTION__."() ".date("Y-m-d H:i:s")."\n".$buff."\n\n");

      echo $str_xml_return;
   }
   private function test_saved_xml($str_xml_return)
   {
      $doc = new DOMDocument();      
      @$doc->loadXML( utf8_decode($str_xml_return));      
      @$doc->save("test_xml.xml");
   }
   #############################################################################
   function add_times()
   {  
      ob_start();
      $response = "";
      if(isset($_REQUEST['xml_times']))
      {
         $data_received = (array)simplexml_load_string($_REQUEST['xml_times']);                     
         if(isset($data_received['time']))
         {
            if(is_object($data_received['time']))
            {
               $times[0] = $data_received['time'];
            }
            else
            {
               $times = $data_received['time'];
            }
            
            for($i=0; $i<count($times); $i++)
            { 
               $data['user_id'] = "".$times[$i]->user_id;
               $data['register_time'] = "".$times[$i]->register_time;               
               $this->model_times_new->add_time($data);
            }            
            $response = "successful";
         }
         else
         {
            $response = "empty";
         }
      }
      else 
      {
         $response = "failed";
      }
      $str_xml_return  = array_to_xml(array("xml_response"=>array("response"=>$response)))->asXML();      
      $this->test_saved_xml($str_xml_return); 

      $buff = ob_get_clean();

      if(strcasecmp($buff,"")!=0) file_append_contents(getcwd()."/coco_log.txt", __FILE__." ".__LINE__." ".__FUNCTION__."() ".date("Y-m-d H:i:s")."\n".$buff."\n\n");     
      echo $str_xml_return;
   }
   
   #############################################################################
   function login_bkp()
   { 
      ob_start();
      echo $_REQUEST["login"];
      $str_login = ob_get_clean();
      
      $str_xml_return = array_to_xml(array("MyXmlUserData"=>$str_login))->asXML(); 
      
      echo $str_xml_return; 
   }
   
   #############################################################################
   function login()
   { 
      //print_r($_REQUEST);
      
      ob_start();

      $data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND $this->config->item('use_username', 'tank_auth'));
      $data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

     
      $remember = null;     
     
      $response = array("error"=>"error");
      
      if(isset($_REQUEST["login"]) AND isset($_REQUEST["password"]) AND
            $this->tank_auth->login(urldecode($_REQUEST["login"]), 
                                 $_REQUEST["password"], 
                                 $remember, 
                                 $data['login_by_username'], 
                                 $data['login_by_email'], $autologin=false))
      {
          $view_data = $this->tank_auth->get_header_data();
          $response = array( 
                           'user_id' => $view_data['user_id'],
                           'profile_name' => $view_data['profile_name'],
                           'profile_last_name' => $view_data['profile_last_name'],
                           'company_id' => $view_data['company_id'],
                           'username'=> $view_data['username'],
                           'email'=> $view_data['email'],
                           'company' => $view_data['company']);
      }
      else if(isset($_REQUEST["user_id"]))
      {
         $view_data = $this->tank_auth->get_header_data(false, $_REQUEST["user_id"]);
         //echo json_encode($view_data);
         $response = array(
                           'user_id' => $view_data['user_id'],
                           'profile_name' => $view_data['profile_name'],
                           'profile_last_name' => $view_data['profile_last_name'],
                           'company_id' => $view_data['company_id'],
                           'username'=> $view_data['username'],
                           'email'=> $view_data['email'],
                           'company' => $view_data['company']);
      }

      $str_xml_return = array_to_xml(array("xml_user"=>$response))->asXML();       
      //$this->test_saved_xml($str_xml_return);      

      //echo $str_xml_return;

      $buff = ob_get_clean();

      if(strcasecmp($buff,"")!=0) file_append_contents(getcwd()."/coco_log.txt", __FILE__." ".__LINE__." ".__FUNCTION__."() ".date("Y-m-d H:i:s")."\n".$buff."\n\n");

      echo $str_xml_return; 
   }
}