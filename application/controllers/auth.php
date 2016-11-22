<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
      $this->load->library('session');      
      $this->load->library('tank_auth');
      
		$this->load->helper(array('form', 'url'));
      
      $this->load->helper(array('my_views_helper'));
      
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('template');
		
      $this->lang->load('tank_auth');
      
      if(! $this->tank_auth->is_logged_in() )
      { 
         $this->lang->load('coco');
      }
            
      $this->load->helper('my_dates_helper');
      $this->load->helper('my_toolkits_helper');
      $this->load->helper('my_messages_helper');
      
      $this->load->model('users');
      $this->load->model('model_times');
      $this->load->model('model_accounts');
	}
   #############################################################################
   private function upload_company_logo($company_id, $uri_images, $source_file_name="", &$my_messages = "")
   {  
      $config['upload_path'] = '.'.$uri_images;
      $config['file_name'] = $company_id.'_main-logo.jpg';                  
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size']	= '100';
      $config['max_width']  = '500';
      $config['max_height']  = '500';

      $path_file = $config['upload_path']."/".$config['file_name'];      
      
      if(file_exists($path_file))
      {
         unlink($path_file);                  
      }
            
      $this->load->library('upload');
      $this->upload->initialize($config);

      if(! $this->upload->do_upload($source_file_name))
      {
        $my_messages .= get_message_warning($this->upload->display_errors());         
        return false;
      }
      else{
         //$my_messages .= get_message_information("Logo Upload Successfull !!");         
      }
      
      $this->load->library('image_lib');

      
      /****************** CREATE THUMB-NAIL thumb medium **********************/     
      $config_thumb_medium['image_library'] = 'gd2';      
      $config_thumb_medium['source_image'] = $config['upload_path']."/".$company_id.'_main-logo.jpg';        
      $config_thumb_medium['new_image'] = $config['upload_path']."/".$company_id.'_header-logo.jpg';  
      $config_thumb_medium['create_thumb'] = false;
      $config_thumb_medium['maintain_ratio'] = TRUE;
      $config_thumb_medium['width'] = 32;
      $config_thumb_medium['height'] = 32;
      
      //$this->load->library('image_lib', $config_thumb_medium); 
      
      $this->image_lib->initialize($config_thumb_medium); 
      
      if ( ! $this->image_lib->resize() )
      {
         $my_messages .= get_message_warning($this->image_lib->display_errors());
      }
      
      $this->image_lib->clear();
       
      /****************** CREATE THUMB-NAIL thumb small **********************/     
      
      $config_thumb['image_library'] = 'gd2';      
      $config_thumb['source_image'] = $config['upload_path']."/".$company_id.'_main-logo.jpg';  
      $config_thumb['new_image'] = $config['upload_path']."/".$company_id.'_login-logo.jpg';
      $config_thumb['create_thumb'] = false;
      $config_thumb['maintain_ratio'] = TRUE;
      $config_thumb['width'] = 200;
      $config_thumb['height'] = 70;
      
      
      $this->image_lib->initialize($config_thumb); 
      
      if ( ! $this->image_lib->resize() )
      {
         $my_messages .= get_message_warning($this->image_lib->display_errors());
      }
      
      return true;
   }
   ###########################################################################
   function manager_clients_companies()
   { /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }            
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);         
      $company_id = $header_data['company_logged']['id'];
    
      $view_data['has_privilege_save_client_company'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'auth/save_client_company');      
      $view_data['has_privilege_manager_clients'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'auth/manager_clients');
      
      if(isset($_REQUEST['client_company_id']))
      {
         $client_company_id =$_REQUEST['client_company_id'];
      }
      
      $is_new = false;
      if(isset($_REQUEST['new']) AND strcasecmp($_REQUEST['new'] , "")!=0)
      {
         $is_new = true;
      }
      
      $array_messages = array();      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_manager_clients_companies_view_labels');
      
    
      $view_data['list_clients_companies'] = $this->users->get_list_clients_companies($company_id);
      
      
      $header_data['array_messages'] = $array_messages;
      $view_data['array_uri_logo'] = $this->config->item('uri_clients_companies');
      
      $this->load->view('template/header', $view_data);
      
      $this->load->view('auth/manager_clients_companies', $view_data);
      $this->load->view('template/footer');
   }
   ###########################################################################
   function delete_client()
   {
      if(isset($_REQUEST['client_user_id']))
      {
         $this->users->delete_client($_REQUEST['client_user_id']);
         
         $this->delete_user_picture($_REQUEST['client_user_id']);         
         if(isset($_REQUEST['client_company_id']))
         {
            redirect(site_url("auth/manager_clients?client_company_id=".$_REQUEST['client_company_id']));
         }
      }
   }
   ###########################################################################
   function delete_client_company()
   {
      if(isset($_REQUEST['client_company_id']))
      {
         $this->users->delete_client_company($_REQUEST['client_company_id']);         
         
         $this->delete_client_company_picture($_REQUEST['client_company_id']);
         
         
         redirect(site_url("auth/manager_clients_companies"));
      }
   }
     #############################################################################
   private function delete_client_company_picture($client_company_id)
   {
      $array_uri_client_company =$this->config->item('uri_clients_companies');      
      $preffix_file_name = "." . $array_uri_client_company['uri']."/".$client_company_id;
         
      if(file_exists($preffix_file_name."_header-logo.jpg"))
      {
         unlink($preffix_file_name."_header-logo.jpg");            
      }
      if(file_exists($preffix_file_name."_login-logo.jpg"))
      {
         unlink($preffix_file_name."_login-logo.jpg");            
      }
      if(file_exists($preffix_file_name."_main-logo.jpg"))
      {
         unlink($preffix_file_name."_main-logo.jpg");
      }
   }
   ###########################################################################
   function save_client_company()
   {
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }            
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);         
      $company_id = $header_data['company_logged']['id'];
    
      
      $view_data['has_privilege_delete_client_company'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'auth/delete_client_company');
    
      $client_company_id = null;
      if(isset($_REQUEST['client_company_id']))
      {
         $client_company_id = $_REQUEST['client_company_id'];
      }
      
      $is_new = false;
      if(isset($_REQUEST['new']) AND strcasecmp($_REQUEST['new'] , "")!=0)
      {
         $is_new = true;
      }
      
      $is_new = false;
      if(isset($_REQUEST['new']) AND strcasecmp($_REQUEST['new'] , "")!=0)
      {
         $is_new = true;
      }
      
      $array_messages = array();      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_save_client_company_view_labels');
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/      
      $this->form_validation->set_rules('name', $view_labels['name'], 'trim|required');      
      $this->form_validation->set_rules('description', $view_labels['description'], 'trim');      
      
      if($this->form_validation->run()) // validation ok
      {	   
            $data = array(
                     "name" => $this->form_validation->set_value('name'),
                     "description" => $this->form_validation->set_value('description'),
                     "company_id"=>$company_id,
                     ); 
            
            if( $is_new )
            {
               $client_company_id = $this->users->add_client_company($data);
            }
            else
            {               
               $this->users->update_client_company($data, array("id_client_company"=>$client_company_id) );               
            }
            
            $array_uri_logo = $this->config->item('uri_clients_companies');            
            
            
            $this->upload_company_logo($client_company_id, $array_uri_logo['uri'], "logo", $messages);
            
            redirect(site_url("auth/manager_clients_companies"));
      }
      if(isset($client_company_id))
      {
         $view_data['client_company'] = $this->users->get_client_company($client_company_id);
      }
      
      $header_data['array_messages'] = $array_messages;
      
      $this->load->view('template/header', $view_data);
      
      $this->load->view('auth/save_client_company', $view_data);
      $this->load->view('template/footer');
   }
   
   ###########################################################################
   function save_client()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
    
      $my_messages = "";
      $array_messages = array();      
      $view_labels = $this->lang->line('coco_auth_save_client_view_labels');
      
      
      $client_company_id=null;
      if(isset($_REQUEST['client_company_id']))
      {
         $client_company_id = $_REQUEST['client_company_id'];
      }

      $user_id_edit=null;
      if(isset($_REQUEST['user_id_edit']))
      {
         $user_id_edit = $_REQUEST['user_id_edit'];
         
      }
      
      $is_edit = false;
      if(isset($_REQUEST['is_edit']))
      {
         $is_edit = true;
      }
    
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/      
      $this->form_validation->set_rules('username', $view_labels['user_name'], 'trim|required|xss_clean');
      
      if(!$is_edit)
      {
         $this->form_validation->set_rules('password', $view_labels['password'], 'trim|required|xss_clean');      
      }
      else
      {
         $this->form_validation->set_rules('password', $view_labels['password'], 'trim|xss_clean');      
      }
      
      $this->form_validation->set_rules('email', $view_labels['email'], 'trim|valid_email|required');      
      $this->form_validation->set_rules('profile_name', $view_labels['names'], 'trim');
      $this->form_validation->set_rules('profile_last_name', $view_labels['last_name'], 'trim');
      $this->form_validation->set_rules('language', $view_labels['language'], 'trim');
      
      
      if ($this->form_validation->run() AND isset($_POST['save_client'])) // validation ok
      {	   
            if($is_edit)
            {
               $user_data = array('username'=>$_POST['username'],                            
                            'email'=>$_POST['email']);
         
               $user_pro_data = array('name'=>$_POST['profile_name'],
                                      'last_name'=>$_POST['profile_last_name'],
                                      'language'=>$_POST['language'],);
               $changeable = TRUE;
               if(isset($_POST['username']) AND  ! $this->users->is_available_username($user_id_edit, $_POST['username'])){
                  $changeable = FALSE;
                  $array_messages[] = $this->lang->line('coco_msg_username_not_available');
               }

               if(isset($_POST['email']) AND  ! $this->users->is_available_email($user_id_edit, $_POST['email'])){
                  $changeable=FALSE;
                  $array_messages[] = $this->lang->line('coco_msg_email_not_available');
               }

               if($changeable AND 
                  isset($_POST['new_password']) AND 
                  strcasecmp(trim($_POST['new_password']),"")!=0)
               {
                  if($this->tank_auth->set_new_password($user_id_edit, $_POST['new_password']))
                  {
                     //$my_messages .= get_message_information("Password Changed Successfull !");
                  }
               }

               if($changeable)
               {
                  $row_affects = $this->users->update_user($user_data, array('id'=> $user_id_edit ));
                  $row_affects2 = $this->users->update_user_profile($user_pro_data, array('user_id'=> $user_id_edit ));

                  if(isset($_FILES['photo']['name']) AND 
                     strcasecmp($_FILES['photo']['name'],"")!=0)
                  {
                     $this->upload_user_picture( $user_id_edit, "photo" );
                  }
               }

               if($changeable)
               {
                  $array_messages[] = $this->lang->line('coco_msg_gral_was_success_saved');
                  $this->session->set_flashdata("my_messages",$my_messages);         
                  //redirect(site_url(urldecode($redirect)));
               }
            }
            else
            {
                     #-----------------------------------------------------------------------------------------------
                     $role_data = array('company_id'=>$company_logged['id'],
                                   'role_type'=>'CLI',
                                   'name'=>'client');


                     $recovery_role = $this->users->get_role_by_name_and_company($role_data['name'], 
                                                                               $company_logged['id']);
                     
                     
                     
                     if(count($recovery_role)<=0)
                     {
                        $role_invited = $this->users->create_role($role_data);
                        #######################################################################
                        $privilege_edit_profile = $this->users->get_privilege_by_name("Edit Profile");
                        
                        $this->users->assign_privilege_to_role($privilege_edit_profile['id'], $role_invited['id']);
                     }
                     else
                     {
                        $role_invited = $recovery_role;
                     }
                     #----------------------------------------------------------------------------------------
                     if( ! $this->users->is_assigned_privilege_to_role($role_invited['id'], null, "Active Projects"))
                     {
                        #----------------------------------------------------------------------------------------
                        $privilege_edit_profile = $this->users->get_privilege_by_name("Active Projects");                        
                        $this->users->assign_privilege_to_role($privilege_edit_profile['id'], $role_invited['id']);
                        #----------------------------------------------------------------------------------------
                     }
                     
                     $email_activation = $this->config->item('email_activation', 'tank_auth');            

                     if (!is_null($data = $this->tank_auth->create_user2(
                           $this->form_validation->set_value('username'),
                           $this->form_validation->set_value('email'),
                           $this->form_validation->set_value('password'),
                           $email_activation,                  

                           array("name"=>$this->form_validation->set_value('profile_name'),
                                 "last_name"=>$this->form_validation->set_value('profile_last_name'),
                                 "language"=>$this->form_validation->set_value('language')
                                ),

                           $company_logged['id'],
                           $role_invited['id']
                           )))  
                     {
                        #----------------------------------------------------------------                              
                        $client_data['user_id'] = $data['user_id'];
                        $client_data['client_company_id'] = $client_company_id;
                        $this->users->add_client($client_data);
                        #----------------------------------------------------------------               
                        $data_sendmail['site_name'] = $this->config->item('website_name', 'tank_auth');
                        $data_sendmail['username'] = $this->form_validation->set_value('username');

                        $data_sendmail['password'] = $this->form_validation->set_value('password');

                        $data_sendmail['email'] = $this->form_validation->set_value('email');
                        if ($email_activation) 
                        {									// send "activate" email
                           $data_sendmail['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;
                           $this->_send_email('activate', $this->form_validation->set_value('email'), $data_sendmail);                  
                           $this->_show_message($this->lang->line('auth_message_registration_completed_1'));
                        }
                        else 
                        {                                          
                           if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email
                              $this->_send_email('welcome', $this->form_validation->set_value('email'), $data_sendmail);
                           }
                        }
                        #----------------------------------------------------------------
                        $messages_picture="";
                        $this->upload_user_picture($data['user_id'], 'photo', $messages_picture);               
                        $this->session->set_flashdata("exchange_messages",$messages_picture);

                        redirect('/auth/manager_clients?client_company_id='.$client_company_id);
                     }
                     else
                     {
                        $array_messages[] = $view_labels['msg_cannot_register'];
                     }
         }
      }
      else
      {
         $this->form_validation->_field_data = array();
         
         $str_validation = validation_errors();
         if(strcasecmp(trim($str_validation),"")!=0)
         {
            $array_messages[] = $str_validation;
         }
      }
      
      
      $data['my_messages'] = $my_messages;
      
      $data_view_labels['view_labels'] = $view_labels;
      
      $view_data = array_merge($data, $data_view_labels, $header_data);      
      
      $view_data['client'] = $client = $this->users->get_user_complete_by_id($user_id_edit);
      $view_data['array_uri_logo'] = $this->config->item('uri_clients_companies');
      
      $header_data['array_messages'] = $array_messages;      
      $view_data['client_company'] = $this->users->get_client_company($client_company_id);
      
      $view_data['has_privilege_delete_client'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'auth/delete_client');            
      $view_data['has_privilege_manager_clients'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'auth/manager_clients');            
      
      
      



      $view_data['user_id_edit'] = $user_id_edit;
      
      $this->load->view('template/header', $header_data);      
      $this->load->view('auth/save_client', $view_data);
      $this->load->view('template/footer', $data);
   }
   #############################################################################
   public function manager_clients()
   {
       if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
      
      $array_messages = array();
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_manager_clients_view_labels');
      
      $view_data['has_privilege_save_client'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'auth/save_client');            
      
      $client_company_id=null;
      if(isset($_REQUEST['client_company_id']))
      {
         $client_company_id = $_REQUEST['client_company_id'];
      }
      
      # [user_id, name, last_name, email, username, max_time_in, num_corrections, role, present]
      $view_data['list_clients'] = $this->users->get_list_clients_by($client_company_id);
      $view_data['array_uri_logo'] = $this->config->item('uri_clients_companies');
      $view_data['client_company'] = $this->users->get_client_company($client_company_id);
      
      $this->load->view('template/header', $header_data);
      
      $this->load->view('auth/manager_clients', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function public_profile()
   {
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}            
      $view_data = $this->tank_auth->get_header_data();      
      
      $this->load->view('template/header', $view_data);      
      if(isset($_GET['user_id']))
      {
         $user_id = $_REQUEST['user_id'];                  
         $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_public_profile_view_labels');
         $view_data['user'] = $user = $this->users->get_user_complete_by_id($user_id);
         $this->load->view('auth/public_profile', $view_data);
         $this->load->view('template/footer', $view_data);
      }
   }
   #############################################################################
   public function jump_login()
   {
      if(! $this->tank_auth->is_logged_in()) { redirect('/auth/login/'); }
      $data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND $this->config->item('use_username', 'tank_auth'));
      $data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');
      /******************* ATTEMPT LOGIN **************************************/
      $user = (array)$this->users->get_user_by_id($_GET['user_id'], $activated=1);
      $login = $user['email'];
      $password = $user['password'];
      $remember = null;//1;
      if($this->tank_auth->login($login, $password, $remember, $data['login_by_username'], $data['login_by_email'], $autologin=true))
      {
       
         
         redirect("times/home");
      }
   }
   #############################################################################
   public function delete_role()
   {
      if (! $this->tank_auth->is_logged_in()) { redirect('/auth/login/'); }      
            
      if($_GET['role_id'])
      {
         $this->users->delete_role($_GET['role_id']);         
      }
      redirect(site_url(urldecode($_GET['redirect']) ));
   }
   #############################################################################
   public function list_users()
   {
      if ( ! $this->tank_auth->is_logged_in() ) 
      {
         //RESEND flashdata why have other ways/can be multiples ways
         $this->session->set_flashdata("message", $this->session->flashdata('message'));            
         redirect('/auth/login/');
      }
      
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
      $company_logged = $header_data['company_logged'];
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_list_users_view_labels');
      
      if(isset($_GET['user_id']) )
      {
         $this->users->delete_user($_GET['user_id']);
      }
      
      $view_data['list_users'] = $this->users->get_list_users_by_company($company_logged['id']);
            
      $this->load->view('template/header', $header_data);      
      $this->load->view('auth/list_users', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function redirect_has_not_privilege()
   {
      if( !$this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $header_data = $this->tank_auth->get_header_data();      
      
      $view_labels = $this->lang->line('coco_auth_redirect_has_not_privilege_view_labels');
      
      $array_messages = null;      
      $array_messages[] = $view_labels['msg1']." '".urldecode($_GET['seg_priv'])."'";
      
      
      $header_data['array_messages'] =$array_messages;       
      $this->load->view('template/header', $header_data);         
      $this->load->view('template/footer', $header_data);      
   }
   #############################################################################
   public function edit_company()
   {
      $my_messages="";
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if( !$this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
      #-------------------------------------------------------------------------
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_edit_company_view_labels');
      
      $company_logged = $header_data['company_logged'];
         
      $this->form_validation->set_rules('name', 'Nombre de Compania', 'trim|required');
      if(isset($_POST['save']))
      {
         $properties['name'] = $_POST['company_name'];
         $properties['description'] = $_POST['description'];

         $conditions['id'] = $company_logged['id'];

         if($this->users->update_company($properties, $conditions)>0)
         {
            
         }

         if(isset($_FILES['main-logo']['name']) AND 
            strcasecmp($_FILES['main-logo']['name'],"")!=0)
         {  
            $this->upload_company_logo( $company_logged['id'], $this->config->item('uri_images_companies'), 'main-logo', $my_messages);

         }
      }

      $this->load->view('template/header', $header_data);

      $company = $this->users->get_company_by_id($company_logged['id']);
      $view_data['company'] = $company;
      $view_data['my_messages'] = $my_messages;
      $this->load->view('auth/edit_company', $view_data);
      
      $this->load->view('template/footer', $view_data);      
   }
   #############################################################################
   public function edit_user()
   {
      $my_messages="";
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_edit_user_view_labels');
      
      if(isset($_GET['user_id'])){
         $user_id = $_GET['user_id'];
         $redirect = $_GET['redirect'];         
      }
      else if(isset($_POST['user_id'])){
         $user_id = $_POST['user_id'];
         $redirect = $_POST['redirect'];         
      }
      $this->form_validation->set_rules('username', 'Login', 'trim|required|xss_clean');
      $this->form_validation->set_rules('new_password', 'New Password', 'trim|xss_clean');      
      $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|required');      
      $this->form_validation->set_rules('profile_name', 'Name', 'trim');
      $this->form_validation->set_rules('profile_last_name', 'Last Name', 'trim');
      
      if($this->form_validation->run() AND isset($_POST['save_changes']))
      {
         $user_data = array('username'=>$_POST['username'],                            
                            'email'=>$_POST['email']);
         
         $user_pro_data = array('name'=>$_POST['profile_name'],
                                'last_name'=>$_POST['profile_last_name'],
                                'language'=>$_POST['language'],
                                );
         $changeable = TRUE;
         
         if(isset($_POST['username']) AND  ! $this->users->is_available_username($user_id, $_POST['username']))
         {
            $changeable=FALSE;
            $my_messages = get_message_warning($this->lang->line('coco_msg_username_not_available'));
         }
         
         
         if(isset($_POST['email']) AND  ! $this->users->is_available_email($user_id, $_POST['email']))
         {
            $changeable = FALSE;
            $my_messages = get_message_warning($this->lang->line('coco_msg_email_not_available'));
         }
         
         
         if($changeable AND strcasecmp(trim($_POST['new_password']),"")!=0)
         {
            if($this->tank_auth->set_new_password($user_id, $_POST['new_password']))
            {
               //$my_messages .= get_message_information("Password Changed Successfull !");
            }
         }
         
         if($changeable)
         {
            $row_affects = $this->users->update_user($user_data, array('id'=> $user_id ));
            $row_affects2 = $this->users->update_user_profile($user_pro_data, array('user_id'=> $user_id ));
            
            $row_affects2 = $this->users->update_user_profile($user_pro_data, array('user_id'=> $user_id ));
            
            $this->users->update_user_status($user_id, $_POST['status']);

            if(isset($_FILES['photo']['name']) AND 
               strcasecmp($_FILES['photo']['name'],"")!=0)
            {
               $this->upload_user_picture( $user_id, "photo" );
            }
         }
         
         if($changeable)
         {
            $my_messages .= get_message_information($this->lang->line('coco_msg_gral_was_success_saved'));
            
            $this->session->set_flashdata("my_messages",$my_messages);
         
            redirect(site_url(urldecode($redirect)));
         }
      }
      if(isset($user_id))
      {
         
         
         $this->load->view('template/header', $header_data);
         $user = $this->users->get_user_complete_by_id($user_id);
         
         $view_data['redirect'] = $redirect;

         $view_data['user'] = $user;
         
         $last_role_status = $this->users->get_user_status($user_id);         
         $view_data['user_status'] = $last_role_status['status'];
            
         $view_data['my_messages'] = $my_messages;
         $this->load->view('auth/edit_user', $view_data);
         $this->load->view('template/footer', $view_data);
      }
   }
   #############################################################################
   public function delete_user()
   {
      if (! $this->tank_auth->is_logged_in()) { redirect('/auth/login/'); }
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
            
      if($_GET['user_id'])
      {
         $this->users->delete_user($_GET['user_id']);         
         $this->delete_user_picture($_GET['user_id']);
      }
      redirect(site_url(urldecode($_GET['redirect']) ));
   }
   #############################################################################
   private function delete_user_picture($user_id)
   {
      $preffix_file_name = "." . $this->config->item('uri_images_users')."/".$user_id;
         
      if(file_exists($preffix_file_name.".jpg"))
      {
         unlink($preffix_file_name.".jpg");            
      }
      if(file_exists($preffix_file_name."_thumb_small.jpg"))
      {
         unlink($preffix_file_name."_thumb_small.jpg");            
      }
      if(file_exists($preffix_file_name."_thumb_medium.jpg"))
      {
         unlink($preffix_file_name."_thumb_medium.jpg");
      }
   }
   #############################################################################
   public function manager_users()
   {
       if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      
      $header_data = $this->tank_auth->get_header_data();       
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
      
      $array_messages=array();
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_manager_users_view_labels');
      
      $current_ts = $this->model_template->get_system_time(); 
      
      $dt_range_current = get_week_interval_arround_date($current_ts);
      
      # [user_id, name, last_name, email, username, max_time_in, num_corrections, role, present]
      $list_users = $this->model_times->get_list_present_users_by($company_logged['id'],
                                                                  $dt_range_current['begin'], 
                                                                  $dt_range_current['end']);
      
      $TIME_IN_INDEX = 'time_in';
      $TIME_OUT_INDEX = 'time_out';
      
      $KEY_SUB_TOTAL_TEMP = 'sub_total';
      
      for($j=0; $j<count($list_users); $j++)
      {
         $list_times = $this->model_times->get_list_times($dt_range_current['begin'], 
                                                       $dt_range_current['end'],
                                                       $list_users[$j]['user_id'],
                                                       $company_logged['id']);
         
         $i=0;
         
         $total_hours=0;
         
         foreach($list_times as $key => $value)
         {
               if(isset($list_times[$i]['status_in']) AND strcasecmp($list_times[$i]['status_in'],"Observed") != 0 AND
                  isset($list_times[$i]['status_out']) AND strcasecmp($list_times[$i]['status_out'],"Observed") != 0 
                 )
               {
                  $list_times[$i][$KEY_SUB_TOTAL_TEMP] = abs((strtotime($list_times[$i][$TIME_IN_INDEX]) - strtotime($list_times[$i][$TIME_OUT_INDEX]))/(3600));
               }
               else
               {
                  $list_times[$i][$KEY_SUB_TOTAL_TEMP] = 0;
               }

               if( date_is_cross_interval($list_times[$key][$TIME_IN_INDEX], $dt_range_current['begin'], $dt_range_current['end']))
               {
                  if($this->model_times->is_date_cross_range( $list_times[$key]['id_time'], $list_users[$j]['user_id']) === TRUE)
                  { 
                     
                     
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = 0 ;
                  }
                  else//only over week interval**************************************
                  {
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = abs((strtotime($dt_range_current['begin']) - strtotime($list_times[$i][$TIME_OUT_INDEX]))/(3600)); 
                  }
               }
               else if( date_is_cross_interval($list_times[$key][$TIME_OUT_INDEX], $dt_range_current['begin'], $dt_range_current['end']) )
               {
                  if($this->model_times->is_date_cross_range($list_times[$key]['id_time'], $list_users[$j]['user_id']) === TRUE)
                  {  
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = 0 ;
                  }
                  else//only over week interval**************************************
                  {
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = abs((strtotime($list_times[$i][$TIME_IN_INDEX]) - strtotime($current_ts))/(3600));                                    
                  }
               }     
               
               
               
               
               $total_hours += $list_times[$i][$KEY_SUB_TOTAL_TEMP];
               $i++;
         }
         $list_users[$j]['total_hours'] = number_format($total_hours, 2, '.', ''); //get_total_sum($list_times, $KEY_SUB_TOTAL_TEMP);         
         $row_last_time = $this->model_times->get_row_time_last_by_user($list_users[$j]['user_id']);
         
         
         if(isset($row_last_time))
         {
            if(isset($row_last_time['status_out']))
            {
               $list_users[$j]['last_time'] = $row_last_time['time_out'];
            }
            else
            {
               $list_users[$j]['last_time'] = $row_last_time['time_in'];
            }
         }     
         
         $list_users[$j]['task_actived'] = $this->model_projects->get_task_active_by_user($list_users[$j]['user_id']);
         
      }
      
      $view_data['list_users'] = $list_users;
      
      
      
      $this->load->view('template/header', $header_data);
      $this->load->view('auth/manager_users', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function manager_users1()
   {
      $my_messages="";
      
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
            
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_manager_users_view_labels');
      
      $company_logged = $header_data['company_logged'];
      
      if(strcasecmp($this->session->flashdata("my_messages"),"")!==0)
      {
         $my_messages .= $this->session->flashdata("my_messages");
      }
      
      $exchange_messages_received = $this->session->flashdata("exchange_messages");
      if(isset($exchange_messages_received) AND strcasecmp($exchange_messages_received,"")!=0)
      {
         $my_messages .= $exchange_messages_received;
      }
      
      if(isset($_GET['user_id']) )
      {
         $this->users->delete_user($_GET['user_id']);
      }            
      
      $view_data['list_users'] = $this->users->get_list_users_by_company($company_logged['id']);
            
      
      
      $this->load->view('template/header', $header_data);
      
      $view_data['my_messages'] = $my_messages;
      
      $this->load->view('auth/manager_users', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function edit_profile()
   {
      $my_messages="";
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      
      $header_data = $this->tank_auth->get_header_data(true);
      $this->tank_auth->has_not_privilege($header_data);
         
      
      
      
      
      $this->form_validation->set_rules('username', 'Login', 'trim|required|xss_clean');
      $this->form_validation->set_rules('new_password', 'New Password', 'trim|xss_clean');      
      $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|required');      
      $this->form_validation->set_rules('profile_name', 'Name', 'trim');
      $this->form_validation->set_rules('profile_last_name', 'Last Name', 'trim');
      
      if($this->form_validation->run() AND isset($_POST['save_changes']))
      {
         $user_data = array('email'=>$_POST['email'],
                            'username'=>$_POST['username']);
         
         $user_pro_data = array('name'=>$_POST['profile_name'],
                                'last_name'=>$_POST['profile_last_name'],
                                'language'=>$_POST['language']);
         
         $changeable = TRUE;
         
         if(strcasecmp(trim($_POST['password']),"")!=0 AND 
         strcasecmp(trim($_POST['new_password']),"")!=0)
         {
            if( ! $this->tank_auth->change_password($_POST['password'], $_POST['new_password']) )
            {
               //$changeable = FALSE;
               $my_messages .= get_message_information($this->lang->line('coco_msg_gral_could_not_change_password'));
            }
         }
         
         if($changeable)
         {
            if($this->users->is_available_username($this->session->userdata('user_id'),
                                                   $_POST['username']))
            {
               $row_affects = $this->users->update_user($user_data, array('id'=> $this->session->userdata('user_id') ));
               $user = (array)$this->users->get_user_by_username($this->session->userdata('username') );

            }
            else
            {
               $my_messages .= get_message_warning($this->lang->line('coco_msg_username_not_available'));               
            }
            
            
            $row_affects2 = $this->users->update_user_profile($user_pro_data, array('user_id'=> $this->session->userdata('user_id') ));
            
                        
            if(isset($_FILES['picture']['name']) AND 
               strcasecmp($_FILES['picture']['name'],"")!=0)
            {
               if($this->upload_user_picture( $this->session->userdata('user_id'),'picture', $my_messages ))
               {
                  $my_messages .= get_message_information($this->lang->line('coco_msg_gral_image_was_success_uploaded'));         
               }
            }
         }
      }
      
      //$user['username'] = $this->session->userdata('username');
      
      $header_data = $this->tank_auth->get_header_data();
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_edit_profile_view_labels');
      $this->load->view('template/header', $header_data);
      
      $view_data['my_messages'] = $my_messages;
      
      $view_data['username'] = $header_data['username'];
      
      $view_data['email'] = $header_data['email'];
      $view_data['profile_name'] = $header_data['profile_name'];
      $view_data['profile_last_name'] = $header_data['profile_last_name'];
      
      $this->load->view('auth/edit_profile', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   
   
   private function create_thumbnail($config, &$my_messages="")
   {
      $this->load->library('image_lib', $config); 
      
      if ( ! $this->image_lib->resize())
      {
         $my_messages .= $this->image_lib->display_errors();
      }
      $this->image_lib->clear();
      
   }
   
   
   #############################################################################
   private function upload_user_picture($user_id, $source_file_name, &$my_messages = "")
   {  
      $config['upload_path'] = '.'.$this->config->item('uri_images_users'); 
      $config['file_name'] = $user_id.'.jpg';                  
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size']	= '3000';
      /*$config['max_width']  = '500';
      $config['max_height']  = '500';
      */
      
      
      $path_file = $config['upload_path']."/".$config['file_name']; 
      
      
      if(file_exists($path_file))
      {
         unlink( $path_file );
      }
      
      
      $this->load->library('upload');

      $this->upload->initialize($config);
      
      $is_well = true;

      if( ! $this->upload->do_upload($source_file_name) )
      {
         $my_messages .= get_message_warning($this->upload->display_errors());         
         return FALSE;
      }
      
         
      
      
      
      $this->load->library('image_lib');

      
      /****************** CREATE THUMB-NAIL thumb medium **********************/     
      $config_thumb_medium['image_library'] = 'gd2';      
      $config_thumb_medium['source_image'] = $config['upload_path']."/".$user_id.'.jpg';        
      $config_thumb_medium['new_image'] = $config['upload_path']."/".$user_id.'_thumb_medium.jpg';      
      $config_thumb_medium['create_thumb'] = false;
      $config_thumb_medium['maintain_ratio'] = TRUE;
      $config_thumb_medium['width'] = 232;
      $config_thumb_medium['height'] = 232;
      
      //$this->load->library('image_lib', $config_thumb_medium); 
      
      $this->image_lib->initialize($config_thumb_medium); 
      
      if ( ! $this->image_lib->resize() )
      {
         $my_messages .= get_message_warning($this->image_lib->display_errors());
      }
      
      $this->image_lib->clear();
       
      /****************** CREATE THUMB-NAIL thumb small **********************/     
      
      $config_thumb['image_library'] = 'gd2';      
      $config_thumb['source_image'] = $config['upload_path']."/".$user_id.'.jpg';        
      $config_thumb['new_image'] = $config['upload_path']."/".$user_id.'_thumb_small.jpg';      
      $config_thumb['create_thumb'] = false;
      $config_thumb['maintain_ratio'] = TRUE;
      $config_thumb['width'] = 40;
      $config_thumb['height'] = 40;
      
      
      $this->image_lib->initialize($config_thumb); 
      
      if ( ! $this->image_lib->resize() )
      {
         $my_messages .= get_message_warning($this->image_lib->display_errors());
      }
      return true;
   }
   
   
   #############################################################################
   //public function home()
   public function index()
   {  
      //$this->list_users();
      $this->manager_users();
	}
   #############################################################################
   function assign_roles()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
      
      $company_logged = $header_data['company_logged'];
      
      $this->load->view('template/header', $view_data);
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_assign_roles_view_labels');
      
      if(isset($_POST['save']))
      {
         $list_roles = $this->users->get_list_roles_by_company( $company_logged['id']);       
         $list_users = $this->users->get_list_users_by_company( $company_logged['id']);
                  
         for($i=0; $i<count($list_users); $i++)         
         {
            for($j=0; $j<count($list_roles); $j++)
            {
               if( isset( $_POST['user_role_id_'.$list_users[$i]['id']] )
                   AND
                   strcasecmp($_POST['user_role_id_'.$list_users[$i]['id']], $list_users[$i]['id']."|".$list_roles[$j]['id']) == 0 )
               {
                  
                  $was_assigned = FALSE;
                  $was_assigned = $this->users->is_assigned_role_to_user($list_roles[$j]['id'], $list_users[$i]['id']);
              
                  if( ! $was_assigned )
                  {
                     //attempt unassign, if Assigned already had
                     $this->users->unassign_role_to_user(null, $list_users[$i]['id']);
                     
                     $data=array('user_id'=>$list_users[$i]['id'],
                                 'role_id'=>$list_roles[$j]['id'],
                                 'status'=>'active');
                     
                     $this->users->assign_role_to_user($data);
                  }
                  break;
               }
            }
         }
      }
      
      $list_roles =$this->users->get_list_roles_by_company( $company_logged['id'] );      
      $list_users =$this->users->get_list_users_by_company( $company_logged['id'] );
      
      $list_users_roles_activated = $this->users->get_list_users_roles_activated_by_company( $company_logged['id'] );
      
      $view_data['user_id_current'] = $this->session->userdata('user_id');
      
      $view_data['list_roles'] = $list_roles;
      $view_data['list_users_roles_activated'] = $list_users_roles_activated;
      $view_data['list_users'] = $list_users;
      
      
      $this->load->view('auth/assign_roles', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #####################################################################
   function assign_privileges()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
      
      $this->load->view('template/header', $view_data);
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_assign_privileges_view_labels');
      
      if(isset($_POST['save']))
      {
         $list_roles = $this->users->get_list_roles_by_company( $company_logged['id'] );      
       
         $list_privileges =$this->users->get_list_privileges();
         
         for($i=0;$i<count($list_roles);$i++)
         {
            for($j=0;$j<count($list_privileges);$j++)
            {
               $was_assigned = FALSE;
               
               $was_assigned = $this->users->is_assigned_privilege_to_role($list_roles[$i]['id'], $list_privileges[$j]['id']);
               
               if(isset($_POST['role_privilege_ids_'.$list_roles[$i]['id'].'_'.$list_privileges[$j]['id']]))
               {  
                  if( ! $was_assigned)
                  {
                     $this->users->assign_privilege_to_role($list_privileges[$j]['id'], $list_roles[$i]['id']);
                  }
               }
               else
               {
                  if( $was_assigned )
                  {  
                     $this->users->unassign_privilege_to_role($list_privileges[$j]['id'], $list_roles[$i]['id']);
                  }
               }
            }
         }         
      }
      
      
      
      $list_roles =$this->users->get_list_roles_by_company( $company_logged['id']);      
      $list_roles_privileges_actived =$this->users->get_list_roles_privileges_actived_by_company($company_logged['id']);      
      $list_privileges =$this->users->get_list_privileges();
      
      for($i=0; $i<count($list_roles_privileges_actived); $i++)
      {
         for($j=0; $j<count($list_privileges); $j++)
         {
            if(strcasecmp($list_roles_privileges_actived[$i]['privilege_id'], $list_privileges[$j]['id'])==0)
            {
               $list_roles_privileges_actived[$i]['module_id'] = $list_privileges[$j]['module_id'];
               break;
            }
         }
      }
      
      //$list_privileges = $this->users->get_list_privileges_role($company_logged['id']);
      
      
      $view_data['list_modules'] = $this->users->get_list_modules();
      $view_data['list_roles']=$list_roles;
      $view_data['list_roles_privileges_actived'] = $list_roles_privileges_actived;
      
      $view_data['list_privileges'] = $list_privileges;
      
      $this->load->view('auth/assign_privileges', $view_data);
      //$this->load->view('auth/assign_privileges_1', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   
   #####################################################################
   ###########################################################################
   function add_role()
   {
     /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
      
      $this->load->view('template/header', $view_data);
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_auth_add_role_view_labels');
      
      $this->form_validation->set_rules('name', 'Company Name', 'required|trim');
      $this->form_validation->set_rules('role_type', 'Name', 'trim');
      
      if ($this->form_validation->run()) // validation ok
      {
         $data = array('name'=>$this->form_validation->set_value('name'),
                       'role_type'=>$this->form_validation->set_value('role_type'),
                       'company_id'=>$company_logged['id']
                       );
         
         $this->users->create_role($data);
      }
      
      $view_data['list_roles'] = $this->users->get_list_roles_by_company( $company_logged['id'] );
      
      
      $this->load->view('auth/add_role', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   ###########################################################################
   function add_user()
   {
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }            
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
    
      $my_messages = "";
      
      $view_labels = $this->lang->line('coco_auth_add_user_view_labels');
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/      
      $this->form_validation->set_rules('username', $view_labels['user_name'], 'trim|required|xss_clean');
      $this->form_validation->set_rules('password', $view_labels['password'], 'trim|required|xss_clean');      
      $this->form_validation->set_rules('email', $view_labels['email'], 'valid_email|trim|required');      
      $this->form_validation->set_rules('profile_name', $view_labels['names'], 'trim');
      $this->form_validation->set_rules('profile_last_name', $view_labels['last_name'], 'trim');
      $this->form_validation->set_rules('language', $view_labels['language'], 'trim');
      
      
      if ($this->form_validation->run() AND isset($_POST['add_user'])) // validation ok
      {	   
            $role_data = array('company_id'=>$company_logged['id'],
                          'role_type'=>'GUEST',
                          'name'=>'guest'); 
            
            $recovery_role = $this->users->get_role_by_name_and_company($role_data['name'], 
                                                                      $company_logged['id']);
            if(count($recovery_role)<=0)
            {
               $role_invited = $this->users->create_role($role_data);
               #######################################################################
               $privilege_edit_profile = $this->users->get_privilege_by_name("Edit Profile");
               
               $this->users->assign_privilege_to_role($privilege_edit_profile['id'], $role_invited['id']);
            }
            else
            {
               $role_invited = $recovery_role;
            }
            
            $email_activation = $this->config->item('email_activation', 'tank_auth');            
            
				if (!is_null($data = $this->tank_auth->create_user2(
						$this->form_validation->set_value('username'),
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$email_activation,                  
               
                  array("name"=>$this->form_validation->set_value('profile_name'),
                        "last_name"=>$this->form_validation->set_value('profile_last_name'),
                        "language"=>$this->form_validation->set_value('language')
                       ),
               
                  $company_logged['id'],
                  $role_invited['id']
                  )))  
            {
               #----------------------------------------------------------------               
               $this->users->add_time_default_to_user($data['user_id']);
               #----------------------------------------------------------------               
               $data_sendmail['site_name'] = $this->config->item('website_name', 'tank_auth');
               $data_sendmail['username'] = $this->form_validation->set_value('username');
               $data_sendmail['password'] = $this->form_validation->set_value('password');
               
               $data_sendmail['email'] = $this->form_validation->set_value('email');
               if ($email_activation) 
               {									// send "activate" email
                  $data_sendmail['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;
                  $this->_send_email('activate', $this->form_validation->set_value('email'), $data_sendmail);                  
                  $this->_show_message($this->lang->line('auth_message_registration_completed_1'));
               }
               else 
               {                                          
                  if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email
                     $this->_send_email('welcome', $this->form_validation->set_value('email'), $data_sendmail);
                  }
               }               
               #----------------------------------------------------------------
               $messages_picture="";
               $this->upload_user_picture($data['user_id'], 'photo', $messages_picture);
               
               $this->session->set_flashdata("exchange_messages",$messages_picture);
               
               redirect('/auth/manager_users/');
            }
            else
            {
               $my_messages .= $view_labels['msg_cannot_register'];
            }
      }
      
      
      
      
      $data['list_users'] = $this->users->get_list_users_by_company($company_logged['id']);      
      $data['my_messages'] = $my_messages;
      
      $data_view_labels['view_labels'] = $view_labels;
      
      $view_data = array_merge($data, $data_view_labels);      
      
      $this->load->view('template/header', $header_data);
      $this->load->view('auth/add_user', $view_data);
      $this->load->view('template/footer', $data);
   }
   
	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{  
            
      $this->tank_auth->attempt_login_included_company();
      
      $data['company_logged'] = $this->session->userdata('company_logged');
      
      $data['uri_images_companies'] = $this->config->item('uri_images_companies');
      
		if ($this->tank_auth->is_logged_in()) {// was logged in
			redirect('');
		}
      
      elseif($this->tank_auth->is_logged_in(FALSE)) {// was logged in, but not activated
			redirect('/auth/send_again/');
		} 
      else // first login
      {
         
         $view_labels = $this->lang->line('coco_auth_login_view_labels');
         
			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

			$this->form_validation->set_rules('login', $view_labels['email'], 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', $view_labels['password'], 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', $view_labels['remember_password'], 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			$data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
			if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
				if ($data['use_recaptcha'])
					$this->form_validation->set_rules('recaptcha_response_field', $view_labels['captcha_confirmation'], 'trim|xss_clean|required|callback__check_recaptcha');
				else
					$this->form_validation->set_rules('captcha', $view_labels['captcha_confirmation'], 'trim|xss_clean|required|callback__check_captcha');
			}
         
			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email'])) {								// success
					redirect('');

				} 
            else {
					$errors = $this->tank_auth->get_error_message();
					if (isset($errors['banned'])) {								// banned user
						$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

					} 
               elseif (isset($errors['not_activated'])) {				// not activated user
						redirect('/auth/send_again/');

					} 
               else 
               {									// fail
						foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					}
				}
			}
			$data['show_captcha'] = FALSE;
			if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
				$data['show_captcha'] = TRUE;
				if ($data['use_recaptcha']) {
					$data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$data['captcha_html'] = $this->_create_captcha();
				}
			}
         ########message reset change password and send email
         $data['message']=$this->session->flashdata('message');
         
         $data_view_labels['view_labels'] = $view_labels;         
         $view_data = array_merge($data, $data_view_labels);      
         
         $this->load->view('auth/login_header', $data);
         $this->load->view('auth/login_form', $view_data);
         $this->load->view('auth/login_footer', $data);
		}
	}
   
	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();

		$this->_show_message($this->lang->line('auth_message_logged_out'));
	}
   
   /**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} elseif (!$this->config->item('allow_registration', 'tank_auth')) {	// registration is off
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));

		} else {
         
         $view_labels = $this->lang->line('coco_auth_register_view_labels');
         
                
			$use_username = $this->config->item('use_username', 'tank_auth');
			if ($use_username) {
				$this->form_validation->set_rules('username', $view_labels['username'], 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
			}
         
         
         
			$this->form_validation->set_rules('email', $view_labels['email'], 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('password', $view_labels['password'], 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', $view_labels['re_password'], 'trim|required|xss_clean|matches[password]');
         ##################################################################################
                  
         $this->form_validation->set_rules('company_name', $view_labels['company_name'], 'trim|required');
         $this->form_validation->set_rules('profile_name', $view_labels['name'], 'trim|required');
         $this->form_validation->set_rules('profile_last_name', $view_labels['last_name'], 'trim|required');         
         $this->form_validation->set_rules('language', $view_labels['language'], 'trim|required');         
         

			$captcha_registration	= $this->config->item('captcha_registration', 'tank_auth');
			$use_recaptcha			= $this->config->item('use_recaptcha', 'tank_auth');
			if ($captcha_registration) {
				if ($use_recaptcha) {
					$this->form_validation->set_rules('recaptcha_response_field',$view_labels['captcha_confirmacion'], 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', $view_labels['captcha_confirmacion'], 'trim|xss_clean|required|callback__check_captcha');
				}
			}
			$data['errors'] = array();

			$email_activation = $this->config->item('email_activation', 'tank_auth');
         
         ######################################################################
         
         
         ######################################################################
         //$this->form_validation->set_rules('company_logo', 'Logo de Compania', 'trim|required');
         
         
         
			if ($this->form_validation->run()) // validation ok
         {	
            $company_data = array('name'=>$this->form_validation->set_value('company_name'));         
            $company = $this->users->create_company($company_data);
            
            
            if(isset($_FILES['company_logo']['name']) AND 
               strcasecmp($_FILES['company_logo']['name'],"")!=0)
            {
               $this->upload_company_logo($company['id'], $this->config->item('uri_images_companies'), 'company_logo');
            }
                     
            #<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
            $role_data = array('company_id'=>$company['id'],
                          'role_type'=>'CEO',
                          'name'=>'Company Owner');
                        
            $role_company_owner = $this->users->create_role($role_data);
            $this->users->assign_privileges_to_role_admin($role_company_owner['id']);
            
            $data_account = array('company_id'=> $company['id'],
                                    'name' => "UNCATEGORIZED",
                                    'description' => "UNCATEGORIZED",
                                    'deletable' => "no"
                                 );
            $uncategorized_account = $this->model_accounts->add_account($data_account);
            #----------------------------------------------------------
            
            #----------------------------------------------------------
            #>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            
            
				if (!is_null($data = $this->tank_auth->create_user2(
						$use_username ? $this->form_validation->set_value('username') : '',
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$email_activation,                  
                  array("name"=>$this->form_validation->set_value('profile_name'),
                        "last_name"=>$this->form_validation->set_value('profile_last_name'),
                        "language"=>$this->form_validation->set_value('language')),
                  $company['id'],
                  $role_company_owner['id']
                  ))) 
            {									// success
               #----------------------------------------------------------------               
               $this->users->add_time_default_to_user($data['user_id']);
               #----------------------------------------------------------------               
               
                  $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                  if ($email_activation) {									// send "activate" email
                     $data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

                     $this->_send_email('activate', $data['email'], $data);

                     unset($data['password']); // Clear password (just for any case)

                     $this->_show_message($this->lang->line('auth_message_registration_completed_1'));

                  } else {
                     if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email

                        $this->_send_email('welcome', $data['email'], $data, $view_labels);
                     }
                     unset($data['password']); // Clear password (just for any case)

                     $this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
                  }
				} 
            else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			if ($captcha_registration) {
				if ($use_recaptcha) {
					$data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$data['captcha_html'] = $this->_create_captcha();
				}
			}
         
         $data_view_labels['view_labels'] = $view_labels;
         
         $view_data = array_merge($data, $data_view_labels);      
         
			$data['use_username'] = $use_username;
			$data['captcha_registration'] = $captcha_registration;
			$data['use_recaptcha'] = $use_recaptcha;
			$this->load->view('auth/login_header', $data);
         $this->load->view('auth/register_form', $view_data);
         $this->load->view('auth/login_footer', $data);
		}
	}
  
	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$data['site_name']	= $this->config->item('website_name', 'tank_auth');
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

					$this->_send_email('activate', $data['email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/send_again_form', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Activate user
		if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
			$this->form_validation->set_rules('login', 'Email', 'trim|required|xss_clean');

         $data['view_labels'] = $view_labels = $this->lang->line('coco_auth_forgot_password_view_labels');
         
			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data, $view_labels);

               
               
               //echo "<BR>MESSAGE = ".$this->lang->line('auth_message_new_password_sent');
               
               
					$this->_show_message($this->lang->line('auth_message_new_password_sent'));
               
				} 
            else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
         $this->load->view('auth/login_header', $data);
			$this->load->view('auth/forgot_password_form', $data);
         $this->load->view('auth/login_footer', $data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password()
	{
		$user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);

      $data['view_labels'] = $view_labels = $this->lang->line('coco_auth_reset_password_view_labels');
      
		$this->form_validation->set_rules('new_password', $view_labels['new_password'], 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', $view_labels['confirm_new_password'], 'trim|required|xss_clean|matches[new_password]');

      
      
		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success

				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				$this->_send_email('reset_password', $data['email'], $data, $view_labels);

				$this->_show_message($this->lang->line('auth_message_new_password_activated').' '.anchor('/auth/login/', 'Login'));

			} else {														// fail
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
      else {
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation', 'tank_auth')) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
      
      ##########################################
      $data['uri_images_companies'] = $this->config->item('uri_images_companies');
      
      $this->load->view('auth/login_header', $data);
      $this->load->view('auth/reset_password_form', $data);
      $this->load->view('auth/login_footer', $data);
	}

	/**
	 * Change user password
	 *
	 * @return void
	 */
	function change_password()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
					$this->_show_message($this->lang->line('auth_message_password_changed'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_password_form', $data);
		}
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */
	function change_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $data['new_email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_email_form', $data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$this->_show_message($this->lang->line('auth_message_unregistered'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/unregister_form', $data);
		}
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);      
      
      /*echo "<br>".__FILE__." ".__LINE__."<BR>";
      echo "<br>SHOW_MESSAGE ".$this->session->flashdata('message');
      exit;
      */
		redirect('/auth/');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data, $view_labels=null)
	{
      $data['view_labels']= $view_labels;
      
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

}

