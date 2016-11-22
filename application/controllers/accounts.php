<?php
class Accounts extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      
      $this->load->library('form_validation');
      
      $this->load->model('model_accounts');
      
      $this->load->library('tank_auth');
      $this->load->library('template');
      $this->load->helper('my_messages_helper');  
      $this->load->helper('my_dates_helper');  
      $this->load->helper('my_toolkits_helper');  
      $this->load->helper('my_views_helper');  
   }
   #############################################################################################
   function delete_budget_range()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data(); 
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;      
      $company_id = $view_data['company_id'];  
      
      if(isset($_REQUEST['id_budget_range_delete']))
      {
         $this->model_accounts->delete_budget_range($_REQUEST['id_budget_range_delete']);
      }
      redirect(  urldecode( $_REQUEST['url_redirect']));
   }
   #############################################################################################
   function save_budget_range()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data(); 
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;      
      $company_id = $view_data['company_id']; 
      
      #---------------------------------------------------------------------------------      
      $view_labels = $this->lang->line('coco_accounts_save_budget_range_view_labels');      
      $view_labels['coco_msg_sure_to_delete'] = $this->lang->line('coco_msg_sure_to_delete');
      $view_data['view_labels'] = $view_labels;
      #---------------------------------------------------------------------------------      
      $this->form_validation->set_rules('title', $view_labels['title_label'], 'trim|required');
      $this->form_validation->set_rules('date_from', $view_labels['date_from_label'], 'trim|required');
      $this->form_validation->set_rules('date_to', $view_labels['date_to_label'], 'trim|required');
      #---------------------------------------------------------------------------------      
      if($this->form_validation->run())
      {
         $data_budget_range['title']=$this->input->get_post("title");
         $data_budget_range['date_from']=$this->input->get_post("date_from");
         $data_budget_range['date_to']=$this->input->get_post("date_to");
         $data_budget_range['user_id'] = $view_data['user_id'];
         $data_budget_range['company_id'] = $company_id;
         
         $this->model_accounts->add_budget_range($data_budget_range);
      }
      else
      {
         $str_validation = validation_errors();
         if(strcasecmp(trim($str_validation),"")!=0)
         {
            $array_messages[] = $str_validation;
         }
      }
      #---------------------------------------------------------------------------------      
      $view_data['array_messages'] = $array_messages;
      $view_data['list_budget_ranges'] = $this->model_accounts->get_list_budget_ranges($company_id);
      $this->load->view('template/header', $view_data);
      $this->load->view("accounts/save_budget_range", $view_data);             
      $this->load->view('template/footer');
   }
   #############################################################################################
   function budgets()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data(); 
      $this->tank_auth->has_not_privilege($view_data); 
      
      $view_data['is_logged_in'] = $is_logged_in;      
      $company_id = $view_data['company_id'];      
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_accounts_budgets_view_labels');
      #---------------------------------------------------------------------------------      
      
      $list_accounts = $this->model_accounts->get_list_accounts($company_id);
      
      #---------------------------------------------------------------------------------      
      $current_budget_range_id = null;
      if(isset($_REQUEST['id_budget_range']))
      {
         $current_budget_range_id = $_REQUEST['id_budget_range'];
      }
      
      
      if(isset($_REQUEST['save_budget']))
      {
         for($i=0;$i<count($list_accounts);$i++)
         {
            $budget_in = null;
            $budget_out = null;
            if(isset($_REQUEST['budget_in_of_id_account_category_'.$list_accounts[$i]['id_account_category']]) AND 
               strcasecmp($_REQUEST['budget_in_of_id_account_category_'.$list_accounts[$i]['id_account_category']], "" )!=0)
            {
               $budget_in = $_REQUEST['budget_in_of_id_account_category_'.$list_accounts[$i]['id_account_category']];
            }
            if(isset($_REQUEST['budget_out_of_id_account_category_'.$list_accounts[$i]['id_account_category']]) AND
               strcasecmp($_REQUEST['budget_out_of_id_account_category_'.$list_accounts[$i]['id_account_category']], "" )!=0)
            {
               $budget_out = $_REQUEST['budget_out_of_id_account_category_'.$list_accounts[$i]['id_account_category']];
            }
            
            if(isset($budget_in) OR isset($budget_out))
            {
               $data_budget_item['budget_in'] = isset($budget_in)?$budget_in:null;
               $data_budget_item['budget_out'] = isset($budget_out)?$budget_out:null;
               
               $data_budget_item['budget_range_id'] = $current_budget_range_id;
               $data_budget_item['account_category_id'] = $list_accounts[$i]['id_account_category'];
               $data_budget_item['user_id'] = $view_data['user_id'];
               
               
               $this->model_accounts->update_budget_item($data_budget_item, $list_accounts[$i]['id_account_category'], $current_budget_range_id);
            }
         }
      }
      $view_data['list_budget_ranges'] = $list_budget_ranges = $this->model_accounts->get_list_budget_ranges($company_id);
      
      if(!isset($current_budget_range_id) AND count($list_budget_ranges)>0)
      {
         $current_budget_range_id = $list_budget_ranges[0]['id_budget_range'];
      }
      else if(count($list_budget_ranges)<=0)
      {
         $array_messages[] = $view_labels['msg_no_has_ranges'];
      }
      
      $view_data['current_budget_range_id'] = $current_budget_range_id;
      
      $list_budget_items = $this->model_accounts->get_list_budget_items($current_budget_range_id);
      
      
      
      $list_accounts_budget_items = array();
      for($i=0; $i<count($list_accounts);$i++)
      {
         $list_accounts_budget_items[$i] = $list_accounts[$i];         
         for($j=0; $j<count($list_budget_items);$j++)
         {
            if(strcasecmp($list_accounts[$i]['id_account_category'], $list_budget_items[$j]['account_category_id']) ==0)
            {
               $list_accounts_budget_items[$i]['budget_in'] = $list_budget_items[$j]['budget_in'];
               $list_accounts_budget_items[$i]['budget_out'] = $list_budget_items[$j]['budget_out'];
               break;
            }
         }
      }
      
      $view_data['array_messages'] = $array_messages;      
      $view_data['list_accounts_budget_items'] = $list_accounts_budget_items;      
      $this->load->view('template/header', $view_data);
      $this->load->view("accounts/budgets", $view_data);             
      $this->load->view('template/footer');
   }
   
   #############################################################################################
   function delete_account()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in(); if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data(); 
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
            
      if(isset($_REQUEST['id_account_category_delete']))
      {
         $this->model_accounts->delete_account($_REQUEST['id_account_category_delete'],$view_data['company_id']);
      }
      redirect(  urldecode( $_REQUEST['url_redirect']));
   }
   #############################################################################################
   function save_account_item()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data(); 
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;      
      $company_id = $view_data['company_id'];
            
               
      #---------------------------------------------------------------------------------
      $view_labels = $this->lang->line('coco_accounts_save_account_item_view_labels');      
      $view_labels['coco_msg_sure_to_delete'] = $this->lang->line('coco_msg_sure_to_delete');
      $view_data['view_labels'] = $view_labels;
      #---------------------------------------------------------------------------------
      $date_time_from = null;
      $date_time_to = null;
      $id_account_category_add = null;
      #---------------------------------------------------------------------------------
      $id_account_item_update = null;
      if(isset($_REQUEST['id_account_item_update']))
      {
         $id_account_item_update = $_REQUEST['id_account_item_update'];
         $view_data['id_account_item_update'] = $id_account_item_update;
      }
      
      if(isset($_REQUEST['save_account_item']))
      {
         $this->form_validation->set_rules('description', $view_labels['description'], 'trim|required');         
         $this->form_validation->set_rules('id_account_category', $view_labels['account'], 'trim|required');         
         $this->form_validation->set_rules('account_in', $view_labels['account_in'], 'trim|numeric');           
         $this->form_validation->set_rules('account_out', $view_labels['account_out'], 'trim|numeric');           
         
         $is_validation = true;         
         if(empty($_REQUEST["account_in"]) AND empty($_REQUEST["account_out"]))
         {
            $array_messages[] = $view_labels['msg_in_out_required'];
            $is_validation = false;            
         }   
      }

      if($this->form_validation->run() AND $is_validation === true)      
      {  
         $data_account_item['description'] = $_REQUEST['description'];
         $data_account_item['register_date'] = $_REQUEST['register_date']." ".date('H:i:s', strtotime($this->template->get_system_time()));
         $data_account_item['account_in'] = $_REQUEST['account_in'];         
         $data_account_item['account_out'] = $_REQUEST['account_out'];
         $data_account_item['account_category_id'] = $_REQUEST['id_account_category'];  
         
         $this->model_accounts->update_account_item($data_account_item, array("id_account_item"=>$id_account_item_update));
         redirect('accounts/home');
      }
      else
      {
         $str_validation = validation_errors();
         if(strcasecmp(trim($str_validation),"")!=0)
         {
            $array_messages[] = $str_validation;
         }
      }
      #---------------------------------------------------------------------------------
      $account_item = $this->model_accounts->get_account_item($id_account_item_update);
      $view_data['account_item'] = $account_item;
      #---------------------------------------------------------------------------------
      
      $list_accounts_tree = array();      
      $this->model_accounts->generate_list_accounts_tree($list_accounts_tree, $company_id);      
      $view_data['list_accounts_tree'] = $list_accounts_tree;
      
      $view_data['system_time'] = strtotime($this->template->get_system_time());
      
      $view_data['array_messages'] = $array_messages;
      
      $id_account_category = null;      
      if(isset($id_account_category_update))
      {
         $id_account_category = $id_account_category_update;
      }
      
      $this->load->view('template/header', $view_data);
      $this->load->view("accounts/save_account_item", $view_data); 
            
      $this->load->view('template/footer');
   }
   #############################################################################################
   function index()
   {
      redirect('accounts/home');      
   }
   function delete_account_item()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in(); if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data();       
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
    
      if(isset($_REQUEST['id_account_item_delete']))
      {
         $this->model_accounts->delete_account_item($_REQUEST['id_account_item_delete']);
      }
      redirect(  urldecode( $_REQUEST['url_redirect']));
   }
  
   #############################################################################################
   function home()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data(); 
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;      
      $company_id = $view_data['company_id'];
      #---------------------------------------------------------------------------------
      $view_labels = $this->lang->line('coco_accounts_home_view_labels');      
      $view_labels['coco_msg_sure_to_delete'] = $this->lang->line('coco_msg_sure_to_delete');
      $view_data['view_labels'] = $view_labels;
      #---------------------------------------------------------------------------------
      $date_time_from = null;
      $date_time_to = null;
      $id_account_category_search = null;
      $id_account_category_add = null;
      
      if(isset($_REQUEST['search']))
      {
         $date_time_from = $_REQUEST['date_from']." 00:00:00";
         $date_time_to = $_REQUEST['date_to']." 23:59:59";
         $view_data['date_from'] = $_REQUEST['date_from'];
         $view_data['date_to'] = $_REQUEST['date_to'];          
      }
      
      if(empty($_REQUEST['validate']))
      {
         $min_date = $this->model_accounts->min_max_date_item_for_company($company_id,"min");
         $max_date = $this->model_accounts->min_max_date_item_for_company($company_id,"max");
         $view_data['date_from'] = date("Y-m-d",strtotime($min_date));
         $view_data['date_to'] = date("Y-m-d",strtotime($max_date));
      }
      
      if(isset($_REQUEST['id_account_category_search']))
      {
         $id_account_category_search = $_REQUEST['id_account_category_search'];
      }
      
      if(isset($_REQUEST['id_account_category_add']))
      {
         $view_data['id_account_category_add'] = $id_account_category_add = $_REQUEST['id_account_category_add'];
      }
      
      $view_data['id_account_category_search'] = $id_account_category_search;
      #---------------------------------------------------------------------------------
      $_POST = array_merge($_POST, $_GET);
      if(isset($_REQUEST['save_account_item']))
      {
         $this->form_validation->set_rules('description', $view_labels['column_item'], 'trim|required');         
         $this->form_validation->set_rules('id_account_category_add', $view_labels['column_account'], 'trim|required');                  
         $this->form_validation->set_rules('account_in', $view_labels['column_account_in'], 'trim|numeric');           
         $this->form_validation->set_rules('account_out', $view_labels['column_account_out'], 'trim|numeric');           
         
         $is_validation = true;         
         if(empty($_REQUEST["account_in"]) AND empty($_REQUEST["account_out"]))
         {
            $array_messages[] = $view_labels['msg_in_out_required'];
            $is_validation = false;
         }         
                
      }
      
      $is_saved = false;

      if($this->form_validation->run() AND $is_validation === true)      
      {
         $data_account_item['description'] = $_REQUEST['description'];
         $data_account_item['register_date'] = $_REQUEST['register_date']." ".date('H:i:s', strtotime($this->template->get_system_time()));
         $data_account_item['account_in'] = $_REQUEST['account_in'];         
         $data_account_item['account_out'] = $_REQUEST['account_out'];
         $data_account_item['account_category_id'] = $_REQUEST['id_account_category_add'];                  
         $data_account_item['user_id'] = $view_data['user_id'];
         $data_account_item['company_id'] = $company_id;
         
         $this->model_accounts->add_account_item($data_account_item); 
         
         $id_account_category_select = null;
         $_REQUEST['description'] = "";
         $_REQUEST['account_in'] = "";
         $_REQUEST['account_out'] = "";
         
         $id_account_category_add = null;
         
         $is_saved = true;
      }
      else
      {
         $str_validation = validation_errors();
         //echo "$str_validation ".$str_validation;
         if(strcasecmp(trim($str_validation),"")!=0)
         {
            $array_messages[] = $str_validation;
         }         
      }
      
      $list_accounts_tree = array();      
      $this->model_accounts->generate_list_accounts_tree($list_accounts_tree, $company_id);      
      $view_data['list_accounts_tree'] = $list_accounts_tree;
      
      $view_data['system_time'] = strtotime($this->template->get_system_time());
      
      $view_data['array_messages'] = $array_messages;
      
      $id_account_category = null;      
      if(isset($id_account_category_search))
      {
         $id_account_category = $id_account_category_search;
      }
      else if(isset($id_account_category_add))
      {
         $id_account_category = $id_account_category_add;
      }
      
      $view_data['is_saved'] = $is_saved;
      
      $view_data['id_account_category'] = $id_account_category;
      $view_data['list_account_items'] = $this->model_accounts->get_list_account_items($company_id, $date_time_from, $date_time_to, $id_account_category);
      
      $this->load->view('template/header', $view_data);
      $this->load->view("accounts/home", $view_data); 
            
      $this->load->view('template/footer');
   }
   ###############################################################
   function save_account()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      
      $view_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($view_data);            
      $view_data['is_logged_in'] = $is_logged_in;            
      #---------------------------------------------------------------------------------
      $view_labels = $this->lang->line('coco_accounts_save_account_view_labels');      
      $view_labels['coco_msg_sure_to_delete'] = $this->lang->line('coco_msg_sure_to_delete');
      $view_data['view_labels'] = $view_labels;
      #---------------------------------------------------------------------------------
      if(isset($_REQUEST['save_account']))
      {
         $this->form_validation->set_rules('account_name', $view_labels['name'], 'trim|required');      
      }
      $is_validation = true; 
      if(isset($_REQUEST['account_name']) AND $this->model_accounts->is_account_exists($_REQUEST['account_name'], $view_data['company_id']))
      {
         $array_messages[] = "Account Name duplicate";
         $is_validation = false;
      }
      
      if($this->form_validation->run() AND $is_validation === true)
      {           
         if(isset($_REQUEST['parent_id']) AND strcasecmp($_REQUEST['parent_id'], "")!=0 )
         {
            $data_account['parent_id'] = $_REQUEST['parent_id'];
         }

         $data_account['company_id'] = $view_data['company_id'];
         $data_account['name'] = $_REQUEST['account_name'];
         $data_account['description'] = $_REQUEST['account_description'];

         $this->model_accounts->add_account($data_account);
         
         $view_data['is_saved_account'] = true;
      }
      else
      {
         $str_validation = validation_errors();
         if(strcasecmp(trim($str_validation),"")!=0)
         {
            $array_messages[] = $str_validation;
         }
      }
      
      $list_accounts_tree = array();      
      $this->model_accounts->generate_list_accounts_tree($list_accounts_tree, $view_data['company_id']);      
      for($i=0;$i<count($list_accounts_tree);$i++)
      {
         $list_accounts_tree[$i]['count_items'] = $this->model_accounts->get_count_account_items_by_account($list_accounts_tree[$i]['id_account_category']);
      }
      
      $view_data['array_messages'] = $array_messages;
       
      $view_data['list_accounts_tree'] = $list_accounts_tree;
      
      $this->load->view('template/header', $view_data);
      $this->load->view("accounts/save_account", $view_data);       
            
      $this->load->view('template/footer');
   }
  
}
