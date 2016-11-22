<?php
class Pm extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      $this->load->library('session');
      $this->load->library('tank_auth');
      $this->load->library('template');      
      $this->load->helper('my_views_helper');
      $this->load->helper('my_files_helper');
      $this->load->helper('my_dates_helper');
      $this->load->helper('my_messages_helper');      
      $this->load->helper('file_helper');      
      $this->load->helper('my_toolkits_helper');      
      $this->load->helper('text');      
      $this->load->model('model_template');
      $this->load->model('model_projects');
      $this->load->model('tank_auth/users');
      $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
   }
   #############################################################################
   private function validate_task_status($task_id, $new_action_status_select_id, 
                                         $new_user_id, 
                                         & $array_messages=array())
   {      
      $task_before_update  = $this->model_projects->get_task($task_id);                         
      
      $last_action_status_select_id = $task_before_update['action_status_select_id'];
      $last_action_user_id = $task_before_update['action_user_id'];
      
      $last_status = $this->model_template->get_selectable_by($last_action_status_select_id);            
      $new_status = $this->model_template->get_selectable_by($new_action_status_select_id);
      
      $attemp_modify_other_people ="";
      
      if( strcasecmp( $new_action_status_select_id, $last_action_status_select_id ) != 0)
      {
         $array_status_sequence = array();         
         
         if(strcasecmp( trim($new_user_id), trim($last_action_user_id) ) == 0)
         {
            //$array_status_sequence_one_users
            $array_status_sequence = array(
                                            array("created", "in_process"),
                                            array("in_process", "paused"),
                                            array("in_process", "completed"),
                                            array("paused", "completed"),
                                            array("paused", "in_process"),
                                            array("completed", "in_process"));
         }
         else
         {
            //$array_sequence_status_more_users
            $array_status_sequence = array(
                                            array("created", "in_process"),                                              
                                            array("paused", "completed"),
                                            array("paused", "in_process"),
                                            array("completed", "in_process"));
            
            $attemp_modify_other_people = "State can not change another person's work";
         }
         
         for($i=0; $i<count($array_status_sequence);$i++)
         {  
            $status_sequence_last = $array_status_sequence[$i][0];
            $status_sequence_new = $array_status_sequence[$i][1];
                        
            
            if(strcasecmp($last_status['value_select'], $status_sequence_last)==0 AND
               strcasecmp($new_status['value_select'], $status_sequence_new)==0)
            {
               return true;
            }
         }
      }      
      $status_lang = $this->lang->line('coco_pm_action_status');
      
      $array_messages[] = $this->lang->line('coco_msg_pm_not_valid_task_status').
                          " [".$status_lang[$last_status['value_select']]." --> ".$status_lang[$new_status['value_select']]."] ".$attemp_modify_other_people;
      return false;
   }
   #############################################################################
   /*
    prerequisite on over task active
    */
   function pause_task_active_by_user($active_object_id, $new_user_id, $new_user_role_id, &$array_messages=array())
   {
      $action_status_paused_id = $this->model_template->get_id_selectable_by("user_objects", "action_status", "paused", "TA");            
      
      
         $data_paused = array('action_status_select_id' => $action_status_paused_id,
                              'object_id' => $active_object_id,
                              'user_id' => $new_user_id,
                              'user_role_id' => $new_user_role_id);

         $this->model_projects->add_user_object($data_paused, "paused", "TA");      
      
   }
   #############################################################################
   function get_performance($project_id)
   {
      $list_tasks = $this->model_projects->get_list_tasks_by_project($project_id);      
            
      $k=0;      
      $list_tasks_users = array();      
      for($i=0;$i<count($list_tasks);$i++)
      {
         $list_user_objects = $this->model_projects->get_list_user_objects_by_task($list_tasks[$i]['id_object']);   

         $total_hours = 0;         
         for($j=0; $j<count($list_user_objects); $j++)
         {
            $date_time_in = $list_user_objects[$j]['register_date'];
            $date_time_out = null;
            
            if(($j+1)<count($list_user_objects))
            {
               $date_time_out = $list_user_objects[$j+1]['register_date'];
            }
            else
            {
               $date_time_out = $this->model_template->get_system_time();
            }            
            $row = $this->model_projects->get_effective_hours_by_task_action($list_user_objects[$j]['user_id'], 
                                                                   $date_time_in, 
                                                                   $date_time_out);
            
            if( strcasecmp( $list_user_objects[$j]['action'] ,"in_process")==0)
            {
               $list_tasks_users[$k] = $list_tasks[$i];                           
               $list_tasks_users[$k]['effective_hours'] = $row['effective_hours'];  

               $list_tasks_users[$k]['id_time'] = $row['id_time'];  
               $list_tasks_users[$k]['time_in'] = $row['time_in'];  
               $list_tasks_users[$k]['time_out'] = $row['time_out'];  

               $list_tasks_users[$k]['action_date'] = $list_user_objects[$j]['register_date'];
               
               $list_tasks_users[$k]['user_name'] = $list_user_objects[$j]['user_name'];
               $list_tasks_users[$k]['user_last_name'] = $list_user_objects[$j]['user_last_name'];

               $list_tasks_users[$k]['worker_user_id'] = $list_user_objects[$j]['user_id'];                        
               $total_hours += $row['effective_hours'];  
               
               $k++;
            }
         }
         if($k > 0)
         {
            $list_tasks_users[$k-1]['total_hours'] = $total_hours;         
         }
      } 
      return $list_tasks_users;
   }
   #############################################################################
   function performance()
   {   
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];      
      $array_messages = array();
            
      if($this->session->flashdata('my_messages')!=null)
      {
         $array_messages[] = $this->session->flashdata('my_messages');            
      }      
      $user_id = $this->session->userdata('user_id');      
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_view_project_view_labels');
      
      $view_data['list_tasks_users'] = $this->get_performance($project_id);      
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%      
      $this->load->view("projects/performance", $view_data);     
   }
  
   #############################################################################
   function add_remove_members()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_save_comment_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      $user_role = $this->users->get_last_activated_user_role($user_id);      
      $company_id = $user_role['company_id'];
      #-------------------------------------------------------------------------
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      #-------------------------------------------------------------------------      
      $object_id = null;
      if(isset($_REQUEST['object_id']))
      {
         $view_data['object_id'] = $object_id = $_REQUEST['object_id'];   
      }      
      #-------------------------------------------------------------------------      
      if(isset($_REQUEST['save']))
      {  
         $quantity_membership = $this->input->get_post("quantity_membership");
         for($i=0; $i<$quantity_membership; $i++)
         {
            if(isset($_REQUEST['member_id_'.$i.'_is_checked']) AND isset($_REQUEST['member_id_'.$i.'_not_member']))
            {
               $this->model_projects->add_member($_REQUEST['member_id_'.$i], $object_id);
            }
            else if(!isset($_REQUEST['member_id_'.$i.'_is_checked']) AND isset($_REQUEST['member_id_'.$i.'_member']))
            {
               $this->model_projects->inactive_member($_REQUEST['member_id_'.$i], $object_id);
            }         
         }
         $this->session->set_flashdata("exchange_messages",array( $this->lang->line("coco_msg_pm_save_project_successfully")));
      }
      #-------------------------------------------------------------------------
      
      $project = $this->model_projects->get_project($project_id);      
      if( ! empty( $project ) )
      {         
         $this->load->view("projects/menu_projects", $view_data);
         if(isset($object_id) AND  ! empty($object_id) )
         {
            $view_data['object']= $object = $this->model_projects->get_object($object_id);  
            
            $view_data['list_membership'] = $this->model_projects->get_list_membership($object['parent_id'], $object_id);
            
            $this->load->view("projects/add_remove_members_1", $view_data);
         }
      }
      $this->load->view('template/footer');
   }
   #############################################################################
   private function save_add_remove_members($user_id, $object_id, $is_task=false, $is_private=false, $is_new_object=true, $is_discussion=false, $postfix_status_members="")
   { 
      if( !$is_task AND  !$is_private AND !$is_new_object AND  !$is_discussion)
      {
         $this->model_projects->inactive_all_member_by_object($object_id);             
         return;
      }
      //current user or owner  ALWAYS for TASKS
      if($is_task OR (!$is_task AND $is_private) OR $is_discussion)
      {
         $this->model_projects->add_member($user_id, $object_id, $postfix_status_members);
      }
      $quantity_membership = $this->input->get_post($postfix_status_members."quantity_membership");
      
      for($i=0; $i<$quantity_membership; $i++)
      {         
         if(isset($_REQUEST[$postfix_status_members.'member_id_'.$i.'_is_checked']) AND isset($_REQUEST[$postfix_status_members.'member_id_'.$i.'_not_member']))
         {
            $this->model_projects->add_member($_REQUEST[$postfix_status_members.'member_id_'.$i], $object_id, $postfix_status_members);
         }
         else if( ! isset($_REQUEST[$postfix_status_members.'member_id_'.$i.'_is_checked']) AND 
                    isset($_REQUEST[$postfix_status_members.'member_id_'.$i.'_member']) )
         {
            $this->model_projects->inactive_member($_REQUEST[$postfix_status_members.'member_id_'.$i], $object_id, $postfix_status_members);            
         }         
      }      
   }
   #############################################################################
   function save_task_comment()
   {
      if(isset($_REQUEST['redirect']))
      {
         redirect(urldecode($_REQUEST['redirect']));
      }
   }
   #############################################################################
   function send_task_change_status_by_email($task_id, $project_id, $parent_id, $status)
   {
      $company = null;      
      $view_data = $this->tank_auth->get_header_data();      
      if(isset($task_id))
      {
         $view_data['task'] = $task = $this->model_projects->get_task($task_id);            
         if( ! isset($project_id))
         {
            $project_id = $task['project_id'];
         }        
      }
      if(isset($parent_id))
      {
         $view_data['discussion'] = $discussion = $this->model_projects->get_comment($parent_id); 
         if( ! isset($project_id) )
         {
            $project_id = $discussion['project_id'];
         }      
      }      
      
      if(isset($task))
      {
         $view_data['company'] = $company = $this->template->get_company_by_id($task['company_id']);
      }      
      if(isset($project_id))
      {
         $view_data['project'] = $project = $this->model_projects->get_project($project_id);
      }
      #-------------------------------------------------------------------------
      $user_id = $this->session->userdata("user_id");      
      
      
      $member = $this->model_projects->get_member($user_id);
      #-------------------------------------------------------------------------      
      
      
      $view_data['parent_id'] = $parent_id;     
      $view_data['project_id'] = $project_id;     
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_send_task_change_status_by_email_view_labels');      
      #-------------------------------------------------------------------------
      #-------------------------------------------------------------------------
      #-------------------------------------------------------------------------  
      $to="";
      $name_to="";
      
      $list_members = $this->model_projects->get_list_membership($parent_id);            
      		
				
      $to="";
      $name_to="";
      
      for($i=0; $i<count($list_members); $i++)
      {
         if(strcasecmp($to,"")!=0)
         {
            $to .=",";
            $name_to .=", ";
         }
         $to .= $list_members[$i]['email'];
         $name_to .= $list_members[$i]['name']." ".$list_members[$i]['last_name'];
      }
      
      $view_data['name_to'] = $name_to;
      $view_data['status'] = $status;
      
      
      $str_comment="";
      
      
      ob_start();      
      $this->load->view('projects/send_task_change_status_by_email', $view_data);
      $str_comment = ob_get_clean();      
      #-------------------------------------------------------------------------         
      $from="info@onebolivia.com";       
      $part_subject = strtoupper("[".$project['name']."]: ".$discussion['name']);      
      $subject = $part_subject; //." ".$view_labels['by'].": ".$member['name']." ".$member['last_name'];            
      $this->template->multi_attach_mail($to, $from, $subject, $str_comment);
   }
   #############################################################################
   function send_task_by_email($task_id, $project_id, $parent_id=null)
   {
      $company = null;    
      $view_data = $this->tank_auth->get_header_data();      
      
      if(isset($task_id))
      {
         $view_data['task'] = $task = $this->model_projects->get_task($task_id);            
         if( ! isset($project_id))
         {
            $project_id = $task['project_id'];
         }        
      }
      if(isset($parent_id))
      {
         $view_data['discussion'] = $discussion = $this->model_projects->get_comment($parent_id); 
         if( ! isset($project_id) )
         {
            $project_id = $discussion['project_id'];
         }      
      }      
      
      if(isset($task))
      {
         $view_data['company'] = $company = $this->template->get_company_by_id($task['company_id']);
      }      
      if(isset($project_id))
      {
         $view_data['project'] = $project = $this->model_projects->get_project($project_id);
      }
            
      
      #-------------------------------------------------------------------------
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $member = $this->model_projects->get_member($user_id);
      #-------------------------------------------------------------------------      
      $list_attachment_files = $this->model_projects->get_list_attachment_files($task_id);      
      $view_data['list_files'] = $list_attachment_files;      
      
      $view_data['parent_id'] = $parent_id;     
      $view_data['project_id'] = $project_id;     
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_send_task_by_email_view_labels');      
      #-------------------------------------------------------------------------
      #-------------------------------------------------------------------------
      #-------------------------------------------------------------------------  
      $to="";
      $name_to="";
      
      
      
      $list_members = $this->model_projects->get_list_membership($parent_id);            
      
				
				
      $to="";
      $name_to="";
      
      for($i=0; $i<count($list_members); $i++)
      {
         if(strcasecmp($to,"")!=0)
         {
            $to .=",";
            $name_to .=", ";
         }
         $to .= $list_members[$i]['email'];
         $name_to .= $list_members[$i]['name']." ".$list_members[$i]['last_name'];
      }
      
      $view_data['name_to'] = $name_to;
      
      
      $str_comment="";
      
      
      ob_start();      
      $this->load->view('projects/send_task_by_email', $view_data);
      $str_comment = ob_get_clean();          
      #-------------------------------------------------------------------------         
      $array_uri_files = array();      
      foreach($list_attachment_files AS $item)
      {
         $array_uri_files[] = ".".$this->config->item('uri_comment_files')."/".$task_id."/".$item['name'];
      }
      #-------------------------------------------------------------------------         
      $from="info@onebolivia.com";       
      $part_subject = strtoupper("[".$project['name']."]: ".$discussion['name']);      
      $subject = $part_subject; //." ".$view_labels['by'].": ".$member['name']." ".$member['last_name'];            
      $this->template->multi_attach_mail($to, $from, $subject, $str_comment, $array_uri_files);
   }
   #############################################################################
   function save_task_by_assigned()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_save_task_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
            
      
      $task_id = null;
      if(isset($_REQUEST['task_id']))
      {
         $task_id = $_REQUEST['task_id'];            
      }
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $parent_id = null;
      if(isset($_REQUEST['parent_id']))
      {
         $view_data['parent_id'] = $parent_id = $_REQUEST['parent_id'];   
      }
      
      #-------------------------------------------------------------------------
      if( isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list']) )
      { 
         $this->form_validation->set_rules('save_go_to_list', 'save_go_to_list', 'trim|required');         
         
         
         if($this->form_validation->run())
         {
            $task_data["priority_select_id"] = $this->input->get_post("priority_select_id");
            $task_data['percent_completed']= $this->input->get_post("percent_completed");            
            #------------------------------------------------------------------------------
            if( (isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list'])) AND isset($task_id))
            {   
               $this->process_save_task_by_assigned($task_data,                                                    
                                                    $task_id, 
                                                    $this->input->get_post("status_select_id"),
                                                    $project_id, 
                                                    $parent_id, 
                                                    $user_role, 
                                                    $array_messages);
               
               
               if(isset($_REQUEST['save_go_to_list']))
               {  
                  $this->session->set_flashdata("exchange_messages",array( $this->lang->line("coco_msg_pm_save_task_successfully")));
                  redirect("pm/view_comment/?comment_id=".$parent_id."&project_id=".$project_id);
               }
            }
            $array_messages[] = $this->lang->line("coco_msg_pm_save_task_successfully");
            #------------------------------------------------------------------------------
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
         #----------------------------------------------------------------------
      }            
      #-------------------------------------------------------------------------
      
          
      $task = $this->model_projects->get_task( $task_id);
      
     
      
      if(!empty($task))
      {
         $view_data['project_id'] = $project_id = $task['project_id'];
         $view_data['parent_id'] = $parent_id = $task['parent_id'];
      }
      
      #-------------------------------------------------------------------------
      
      $view_data['current_date_time'] = $this->model_projects->get_system_time();
      
      $project = $this->model_projects->get_project($project_id);      
      if( ! empty($project))
      {
         $view_data['project'] = $project;
         
         $list_members = $this->model_projects->get_list_members($project_id);
         
         $view_data['list_priorities'] = $list_priorities = $this->model_projects->get_list_priorities();      
         $view_data['list_status'] = $list_status = $this->model_template->get_list_selectable_by("user_objects", "action_status",null, "TA");
         
        



         if( ! empty($task))
         {
            $list_member_tasks = $this->model_projects->get_list_member_tasks($task['id_object']);
            
            for($j=0; $j<count($list_member_tasks); $j++)
            {
               for($i=0; $i<count($list_members); $i++)
               {
                  if(  strcasecmp( $list_members[$i]['user_id'], $list_member_tasks[$j]['member_id'] )==0)
                  {
                     $list_member_tasks[$j] = $list_members[$i];
                     break;
                  }
               }
            }
            $view_data['list_members'] = $list_member_tasks;
         }         
         
         
         if( ! empty($task) )
         {
            $view_data['task'] = $task;
         }
         else if( isset($task_id))
         {
            $array_messages[] = $this->lang->line('coco_msg_pm_task_not_exists');
         }
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      $view_data['array_messages'] = $array_messages;            
      $this->load->view('template/header', $view_data);
      
      if( ! empty($project) )
      {         
         $this->load->view("projects/menu_projects", $view_data);
         if( ! isset($task_id) OR ( isset($task_id) AND  ! empty($task) ) )
         {
            $this->load->view("projects/save_task_by_assigned", $view_data);
         }
      }
      $this->load->view('template/footer');
   }
   #############################################################################
   private function process_save_task_by_assigned($task_data,                                                   
                                                  $task_id, 
                                                  $new_action_status_select_id,
                                                  $project_id, 
                                                  $parent_id, 
                                                  $user_role,
                                                  &$array_messages=array())
   {
      $action_status_select = $this->model_template->get_selectable_by($new_action_status_select_id);               
      #-------------------------------------------------------------                                 
      $task_before_update  = $this->model_projects->get_task($task_id);                         
      $last_action_status_select_id = $task_before_update['action_status_select_id'];
      
      if( ! $this->model_projects->is_member( $user_role['user_id'], $task_id) )
      {
         $this->model_projects->add_member( $user_role['user_id'], $task_id );
      }
      
      if( $this->validate_task_status($task_id, $new_action_status_select_id, $user_role['user_id'], $array_messages ) )
      {
         $data_user_object = array("user_id"=>$user_role['user_id'], 
                                "user_role_id" => $user_role['id'],                                         
                                "object_id" => $task_id);      
         
         $this->model_projects->add_user_object($data_user_object, $action_status_select['value_select'],"TA");                  
         if(strcasecmp($action_status_select['value_select'], "completed")==0)
         {
            $task_data['percent_completed']=100;            
         }
         $this->model_projects->update_project_percent_completed($project_id);
         
         $this->send_task_change_status_by_email($task_id, $project_id, $parent_id, $action_status_select['value_select']);
      }
      #-------------------------------------------------------------                                       
      if( ! empty($task_data) )
      {
         $this->model_projects->update_task($task_data, array("object_id"=>$task_id));
      }      
   }
   #############################################################################
   function save_task()
   {
      $view_data = array();      
      $array_messages = array();
      $exchange_messages = array();
      
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_save_task_view_labels');
      
      $user_id = $this->session->userdata("user_id");
            
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);            
      $company_id = $user_role['company_id'];
            
      $redirect = null;
      if(isset($_REQUEST['redirect']))
      {
         $view_data['redirect'] = $redirect = $_REQUEST['redirect'];   
      }
      
      $task_id = null;
      if(isset($_REQUEST['task_id']))
      {
         $task_id = $_REQUEST['task_id'];            
      }
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $parent_id = null;
      if(isset($_REQUEST['parent_id']))
      {
         $view_data['parent_id'] = $parent_id = $_REQUEST['parent_id'];   
      }
      
      
      #-------------------------------------------------------------------------
      if( isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list']) )
      {  
         $this->form_validation->set_rules('description', 'Name', 'trim|required');         
         $this->form_validation->set_rules('points', 'Name', 'trim|integer');         
         
         
         if($this->form_validation->run())
         {
            $is_private = $this->input->get_post("is_private");
            $is_private_boolean = false;
            if(isset($is_private) AND strcasecmp( $is_private, "")!=0)
            {
               $is_private = 1;
               $is_private_boolean = true;
            }
            else
            {
               $is_private = null;
            }
         
            $description = $this->input->get_post("description");
                                      
            $task_data["priority_select_id"] = $this->input->get_post("priority_select_id");
            $task_data['percent_completed']= $this->input->get_post("percent_completed");                  
            
            $task_data['points']= $this->input->get_post("points");                  
                  
            
            $start_time = $this->input->get_post("start_hour").":".$this->input->get_post("start_minute").":00";
            $end_time = $this->input->get_post("end_hour").":".$this->input->get_post("end_minute").":00";
            
            $task_data['start_date'] = $this->input->get_post("start_date")." ".$start_time;                  
            $task_data['end_date'] = $this->input->get_post("end_date")." ".$end_time;                  
            
        
            $new_action_status_select_id = $this->input->get_post('status_select_id');
            $action_status = $this->model_template->get_selectable_by($new_action_status_select_id);
            #------------------------------------------------------------------------------
            $type_object_id = $this->model_template->get_id_selectable_by("objects", "type", "file");
            
            $pre_data_file = array("type_select_id"=>$type_object_id,
                                   "user_id"=>$user_id,
                                   "company_id"=>$company_id,
                                   'parent_id' => $task_id,               
                                   "project_id"=>$project_id);
                        
            #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%                  
            if( $this->validate_task_status($task_id, 
                                               $new_action_status_select_id, 
                                               $user_role['user_id'], 
                                               $exchange_messages ) )
            {
               
               if(strcasecmp($action_status['value_select'], "in_process")==0)
               {
                  $task_actived = $view_data['task_actived'];
                  if(!empty($task_actived))
                  {
                     $this->pause_task_active_by_user($task_actived['object_id'], 
                                                      $task_actived['user_id'],
                                                      $task_actived['user_role_id'],
                                                      $exchange_messages);
                  }
               }
                      
               $this->process_save_task_by_assigned(null,                                                    
                                                    $task_id, 
                                                    $new_action_status_select_id,
                                                    $project_id, 
                                                    $parent_id, 
                                                    $user_role,
                                                    $exchange_messages);               
            }
            $view_data['task_actived'] = $this->model_projects->get_task_active_by_user($user_id); 
            #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%                  
            if(isset($_REQUEST['add']))
            {
               $task_object_data = array("description"=>$description,
                                      "company_id"=>$company_id,                                      
                                      "project_id"=>$project_id,
                                      "parent_id"=>$parent_id,
                                      "type_select_id" => $this->model_template->get_id_selectable_by("objects", "type", "task"),
                                      "user_id"=>$user_id,
                                      "is_private"=>$is_private,
                                      );
            
               
                  $task_id = $this->model_projects->add_object($task_object_data);
                  
                  $task_data['object_id'] = $task_id;
                  
                  $this->model_projects->add_task($task_data);
                  
                  
                  /*
                  $data_user_object_task = array("user_id"=>$user_id, 
                                         "user_role_id" => $user_role['id'],                                         
                                         "object_id" => $task_id);
                  
                  $this->model_projects->add_user_object($data_user_object_task, $action_status['value_select'], "TA");
                   */
                  #------------------------------------------------------------------------------
                  
                  $this->upload_object_file_batch($pre_data_file, "comment", $array_messages, $user_role);                  
                  #------------------------------------------------------------------------------
                  $this->send_task_by_email($task_id, $project_id, $parent_id);               
            }
            #-------------------------------------------------------------
            $this->save_add_remove_members($user_id, $task_id, $is_task_boolean=true, $is_private_boolean, $is_new=false);
            #-------------------------------------------------------------
            if( (isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list'])) AND isset($task_id))
            {   
               if( ! isset($_REQUEST['add']) )
               {
                  #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                  #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                  #-------------------------------------------------------------                  
                  $task_object_data = array("description"=>$description,
                                             "is_private"=>$is_private,);
                     
                  $this->model_projects->update_object($task_object_data, array("id_object"=>$task_id));
                  
                  $this->model_projects->update_task($task_data, array("object_id"=>$task_id));
                  
                  if(isset($task_data['points']))
                  {
                     $this->model_projects->update_project_points($project_id);
                  }
                  if(isset($task_data['percent_completed']))
                  {
                     $this->model_projects->update_project_percent_completed($project_id);
                  }
                  
                  if(isset($_REQUEST['status_select_id']))
                  {
                     $this->send_task_change_status_by_email($task_id, $project_id, $parent_id, $action_status['value_select']);
                  }
                  
                  #-------------------------------------------------------------                  
                  $this->upload_object_file_batch($pre_data_file, "comment", $array_messages, $user_role);
                  #-------------------------------------------------------------                  

               }
               if(isset($_REQUEST['save_go_to_list']))
               {  
                  $exchange_messages[] = $this->lang->line("coco_msg_pm_save_task_successfully");
                  
                  $this->session->set_flashdata("exchange_messages", $exchange_messages);
                  redirect("pm/view_comment/?comment_id=".$parent_id);
               }
            }
            $array_messages[] = $this->lang->line("coco_msg_pm_save_task_successfully");
            #------------------------------------------------------------------------------
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
         #----------------------------------------------------------------------
      }
      else if(isset($_REQUEST['delete']))
      {
         $this->model_projects->delete_object_recursively($task_id, $user_id, $user_role['id']);         
         
         $this->model_projects->update_project_percent_completed($project_id);
         
         redirect( urldecode($redirect) );
      }
      #-------------------------------------------------------------------------
      $task = $this->model_projects->get_task( $task_id);      
      if( ! empty($task))
      {
         $view_data['project_id'] = $project_id = $task['project_id'];
         $view_data['list_attachment_files'] = $this->model_projects->get_list_attachment_files( $task_id);         
         #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         $view_data['has_privilege_edit_task_points'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'pm/save_task/save_points');         
      }      
      #-------------------------------------------------------------------------      
      $view_data['current_date_time'] = $this->model_template->get_system_time();      
      $view_data['this_class'] = $this;
      
      $project = $this->model_projects->get_project($project_id);      
      if( ! empty($project))
      {
         $view_data['project'] = $project;
         
         $view_data['list_priorities'] = $list_priorities = $this->model_projects->get_list_priorities();      
         $view_data['list_status'] = $list_status = $this->model_template->get_list_selectable_by("user_objects", "action_status",null, "TA");
         
         $view_data['list_membership'] = $this->model_projects->get_list_membership($parent_id, $task_id);         
         
         if( ! empty($task) )
         {
            $view_data['task'] = $task;
         }
         else if( isset($task_id))
         {
            $array_messages[] = $this->lang->line('coco_msg_pm_task_not_exists');
         }
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      $view_data['array_messages'] = $array_messages;            
      $this->load->view('template/header', $view_data);
      
      if( ! empty($project) )
      {         
         $this->load->view("projects/menu_projects", $view_data);
         if( ! isset($task_id) OR ( isset($task_id) AND  ! empty($task) ) )
         {
            $this->load->view("projects/save_task", $view_data);
         }
      }
      $this->load->view('template/footer');
   }
   #############################################################################
   function get_object_link_url($activity)
   {
      $url = "";
      $title = "";
      
      $page_target = "";
      
      switch( $activity['type'] )
      {
         case "project":
         {
            $url = site_url("pm/view_project/?project_id=".$activity['id_object']);
            $title = nl2br( word_limiter (decode_chars_special(  $activity['name']), 7) );
            break;
         }
         case "task":
         {
            
            $url = site_url("pm/view_comment/?project_id=".$activity['project_id']."&comment_id=".$activity['parent_id']."#item_comment_".$activity['id_object']);                                                               
            
            if(isset($activity['name']))
            {
               $title = nl2br( word_limiter (decode_chars_special( $activity['name'] ), 7) );
            }
            else
            {
               $title = nl2br( word_limiter (decode_chars_special( $activity['description'] ), 7) );
            }
            break;
         }
         case "discussion":
         {
            $url = site_url("pm/view_comment/?project_id=".$activity['project_id']."&comment_id=".$activity['id_object']);
            $title = nl2br( word_limiter (decode_chars_special(  $activity['name']), 7) );
            break;
         }
         case "comment":
         {
            $parent_comment = $this->model_projects->get_comment($activity['parent_id']);   
            
            $name="";
            if(isset($parent_comment['name']))
            {
               $name = $parent_comment['name'];
            }
            
            $url = site_url("pm/view_comment/?project_id=".$activity['project_id']."&comment_id=".$activity['parent_id']."#item_comment_".$activity['id_object']);                                                               
            $title = "Re: ".nl2br( word_limiter (decode_chars_special(  $name ), 7) );
            break;
         }
         case "file":
         {
            $parent_comment = $this->model_projects->get_object($activity['parent_id']);            
            
            $url = site_url($this->config->item("uri_comment_files")."/".$parent_comment['id_object']."/".get_filename_uploaded($activity['name']));            
            $title = nl2br( word_limiter (decode_chars_special(  $activity['name']), 7) );
            
            $page_target = " target='_blank' ";
            break;
         }
         case "time_record":
         {
            $url = site_url("pm/time_records/?project_id=".$activity['project_id']."&object_id=".$activity['id_object'])."#item_time_record_".$activity['id_object'];
            
            $title = nl2br( word_limiter (decode_chars_special(  $activity['name']), 7) );
            break;
         }
         case "fee":
         {
            $url = site_url("pm/view_project/?project_id=".$activity['project_id']."&project_id=".$activity['id_object']);
            $title = nl2br( word_limiter (decode_chars_special(  $activity['name']), 7) );
            break;
         }
      }

      return "<a href='".$url."' ".$page_target." >".$title."</a>";
   }
   #############################################################################
   function send_comment_by_email($comment_id, $project_id, $parent_id=null)
   {
      $company=null;
      
      $view_data = $this->tank_auth->get_header_data();
      
      if(isset($comment_id))
      {
         $comment = $this->model_projects->get_comment($comment_id);            
         if( ! isset($project_id))
         {
            $project_id = $comment['project_id'];
         }        
      }
      
      if(isset($parent_id) AND strcasecmp( $comment_id, $parent_id )!=0)
      {
         $discussion = $this->model_projects->get_comment($parent_id);            
        
         $view_data['discussion'] = $discussion;
         
         if( ! isset($project_id) )
         {
            $project_id = $discussion['project_id'];
         }      
      }      
      
      if(empty($discussion))
      {
         $view_data['discussion'] = $discussion = $comment;
      }
      
           
      if(isset($comment))
      {
         $view_data['company'] = $company = $this->template->get_company_by_id($comment['company_id']);
      }      
      if(isset($project_id))
      {
         $view_data['project'] = $project = $this->model_projects->get_project($project_id);
      }
            
      $list_members = $this->model_projects->get_list_members($project_id);            
      #-------------------------------------------------------------------------
      $user_id = $this->session->userdata("user_id");      
      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      
      $member = $this->model_projects->get_member($user_id);
      #-------------------------------------------------------------------------      
      $list_attachment_files = $this->model_projects->get_list_attachment_files($comment_id);      
      $view_data['list_files'] = $list_attachment_files;      
      $view_data['comment'] = $comment;     
      
      $view_data['parent_id'] = $parent_id;     
      $view_data['project_id'] = $project_id;     
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_send_comment_by_email_view_labels');      
      #-------------------------------------------------------------------------
      #-------------------------------------------------------------------------
      #-------------------------------------------------------------------------  
      $to="";
      $name_to="";
      
      for($i=0; $i<count($list_members); $i++)
      {
         if(strcasecmp($to,"")!=0)
         {
            $to .=",";
            $name_to .=", ";
         }
         $to .= $list_members[$i]['email'];
         $name_to .= $list_members[$i]['name']." ".$list_members[$i]['last_name'];
      }
      
      $view_data['name_to'] = $name_to;
      
      
      $url_view_comment = "";
                                                
      $text_reply = "";

      if(isset($parent_id))
      {
         $url_view_comment = site_url("pm/view_comment/?comment_id=".$parent_id."&project_id=".$project_id."#item_comment_".$comment['id_object']);                                                   
         $text_reply = "Re: ";
      }
      else
      {
         $url_view_comment = site_url("pm/view_comment/?comment_id=".$comment['id_object']."&project_id=".$project_id);
      }  
      
      $view_data['url_view_comment'] = $url_view_comment;
      $view_data['text_reply'] = $text_reply;
      
      $str_comment="";
      ob_start();      
      $this->load->view('projects/send_comment_by_email', $view_data);
      $str_comment = ob_get_clean();
      
      #-------------------------------------------------------------------------      
      $array_uri_files = array();      
      foreach($list_attachment_files AS $item)
      {
         $array_uri_files[] = ".".$this->config->item('uri_comment_files')."/".$comment_id."/".$item['name'];
      }      
      #-------------------------------------------------------------------------      
      $from="info@onebolivia.com";       
      $part_subject = strtoupper("[".$project['name']."]: ".$discussion['name']);      
      $subject=$part_subject; //." ".$view_labels['by'].": ".$member['name']." ".$member['last_name'];            
      $this->template->multi_attach_mail($to, $from, $subject, $str_comment, $array_uri_files);
   }
   
   #############################################################################
   function add_member()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_add_member_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
                  
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $object_id = null;
      if(isset($_REQUEST['object_id']))
      {
         $view_data['object_id'] = $object_id = $_REQUEST['object_id'];   
      }
            
      if(isset($_REQUEST['save']))
      {  
         $quantity_membership = $this->input->get_post("quantity_membership");
         for($i=0; $i<$quantity_membership; $i++)
         {
            if(isset($_REQUEST['user_id_'.$i.'_is_checked']) AND isset($_REQUEST['user_id_'.$i.'_not_member']))
            {
               $this->model_projects->add_member($_REQUEST['user_id_'.$i], $project_id);
            }
            else if(!isset($_REQUEST['user_id_'.$i.'_is_checked']) AND isset($_REQUEST['user_id_'.$i.'_member']))
            {
               $this->model_projects->inactive_member_recursively($_REQUEST['user_id_'.$i], $project_id);
            }         
         }
         
         $this->session->set_flashdata("exchange_messages",array( $this->lang->line("coco_msg_pm_save_project_successfully")));
         
         redirect("pm/members?project_id=".$project_id);
      }               
      
      $project = $this->model_projects->get_project( $project_id );      
      
      if( ! empty($project))
      {
         $view_data['project'] = $project;
         #------------------------------------------------------------------
         $current_ts = $this->model_template->get_system_time();      
         list($current_date, $current_time) = explode(" ", $current_ts);            
         
         //$current_year = date('Y',strtotime($current_ts));      
         $dt_range_current = get_week_interval_arround_date($current_date);
         #------------------------------------------------------------------
         
         $view_data['list_membership'] = $this->model_projects->get_list_present_membership_by($company_id, $project_id, $dt_range_current['begin'], $dt_range_current['end']);
         
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      $view_data['array_messages'] = $array_messages;            
      $this->load->view('template/header', $view_data);
      if( ! empty($project))
      {
         $this->load->view("projects/menu_projects", $view_data);      
         $this->load->view("projects/add_member", $view_data);      
      }
      $this->load->view("template/footer");      
   }   
   #############################################################################
   function members()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_members_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $view_data['company_id'];
      
      $messages_received = $this->session->flashdata("exchange_messages");      
      if(!empty($messages_received))
      {
         $array_messages = array_merge($array_messages, $messages_received); 
      }
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
         
      $project = $this->model_projects->get_project( $project_id );
      if(!empty($project))
      {
         $view_data['project'] = $project;         
         #------------------------------------------------------------------
         $current_ts = $this->model_template->get_system_time();      
         list($current_date, $current_time) = explode(" ", $current_ts);            
         
         $dt_range_current = get_week_interval_arround_date($current_date);
         #------------------------------------------------------------------         
         
         /*
         $view_data['list_project_peoples'] = $this->model_projects->get_list_members($project_id, $dt_range_current['begin'], $dt_range_current['end']);
         */
         $view_data['list_project_peoples'] = $this->model_projects->get_list_members($project_id);
         $view_data['has_privilege_add_member'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'], 'pm/add_member');
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }      
      $view_data['array_messages'] = $array_messages;
      
      $this->load->view('template/header', $view_data);      
      if(!empty($project))
      {
         $this->load->view("projects/menu_projects", $view_data);
         $this->load->view("projects/members", $view_data);
      }
      $this->load->view('template/footer');      
   }
   #############################################################################
   function index()
   {
      redirect('pm/home');
   }
   #############################################################################
   function restore()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      
      if(isset($_REQUEST['object_id']))
      {
         $object_id = $_REQUEST['object_id'];
         
         $this->model_projects->restore_object_recursively($user_id, $user_role['id'], $object_id);
         
         redirect("pm/trash");
      }
      /*
      else if($_REQUEST['restore_all'])
      {
         $this->model_projects->restore_object_recursively($user_id, $user_role['id']);
         redirect("pm/trash");
      }
      */   
   }
   #############################################################################
   function time_records()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      #-------------------------------------------------------------------------
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_time_records_view_labels');      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
      
      $messages_received = $this->session->flashdata("exchange_messages");      
      #-------------------------------------------------------------------------
      if(!empty($messages_received))
      {
         $array_messages=array_merge($array_messages, $messages_received); 
      }
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $project = $this->model_projects->get_project( $project_id );                  
      
      if(!empty($project))
      {
         $view_data['project'] = $project;
         $list_time_records = $this->model_projects->get_list_time_records( $project_id );
         $view_data['list_time_records'] = $list_time_records;
         
         $view_data['has_privilege_save_time_record'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'], 'pm/save_time_record');
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      $view_data['array_messages'] = $array_messages;
      $this->load->view('template/header', $view_data);
      if(!empty($project))
      {
         $this->load->view("projects/menu_projects", $view_data);
         $this->load->view("projects/time_records", $view_data);
      }
      $this->load->view('template/footer');
   }
   #############################################################################
   function save_time_record()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_save_time_record_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
            
      
      $time_record_id = null;
      if(isset($_REQUEST['time_record_id']))
      {
         $time_record_id = $_REQUEST['time_record_id'];            
      }
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $parent_object_id = null;
      if(isset($_REQUEST['parent_object_id']))
      {
         $parent_object_id = $_REQUEST['parent_object_id'];   
      }
      else
      {
         $parent_object_id = $project_id;
      }
      
      #-------------------------------------------------------------------------
      if( isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list']) )
      {  
         $this->form_validation->set_rules('name', 'Name', 'trim|required');         
         $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');
         $this->form_validation->set_rules('user_id', 'User', 'trim|required');
         
         if(isset($_REQUEST['ids_delete_object']) AND count($_REQUEST['ids_delete_object'])>0)
         {
            $ids_delete_object = $_REQUEST['ids_delete_object'];
            $names_delete_object = $_REQUEST['names_delete_object'];
            
            for($i=0;$i<count($ids_delete_object);$i++)
            {
               if(strcasecmp($ids_delete_object[$i],"")!=0)
               {
                  $uri_delete = $this->config->item("uri_comment_files")."/".$time_record_id."/".$names_delete_object[$i];               
                  $this->model_projects->delete_object($ids_delete_object[$i]);
               }
            }
         }
         
         
         if($this->form_validation->run())
         {
            
            $type_object_id = $this->model_template->get_id_selectable_by("objects", "type", "time_record");
            $data_object = array("name"=>$this->input->get_post("name"),
                                 "project_id"=>$project_id,
                                 "company_id"=>$company_id,                                 
                                 "type_select_id"=>$type_object_id,
                                 "parent_id"=>$parent_object_id,
                                 "user_id"=>$user_id);
            
            
            
            $data_time_record = array("billable_status_select_id"=>$this->input->get_post("billable_status_select_id"),
                                      "quantity"=>$this->input->get_post("quantity"),
                                      "user_id"=>$this->input->get_post("user_id"));
            #-------------------------------------------------------------------                        
            if(isset($_REQUEST['add']))
            {
               $object_id = $this->model_projects->add_object($data_object);
               
               $data_time_record['object_id'] = $object_id;
               
               $this->model_projects->add_time_record($data_time_record);
               #-------------------------------------------------------------                                 
               
               
               
               $data_user_object = array("user_id"=>$user_id, 
                                         "user_role_id" => $user_role['id'],
                                         "object_id" => $object_id);               
               $this->model_projects->add_user_object($data_user_object,"created");
               
               
               
               #-------------------------------------------------------------                  
               $time_record_id = $object_id;               
            }            
            if( (isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list'])) AND isset($time_record_id) )
            {   
               if( ! isset($_REQUEST['add']) )
               {  
                  $data_user_object = array("user_id"=>$user_id, 
                                            "user_role_id" => $user_role['id'],
                                            "object_id" => $time_record_id);
                  $this->model_projects->add_user_object($data_user_object,"modified");
                  
                  #-------------------------------------------------------------                  
                  $this->model_projects->update_object($data_object, array("id_object"=>$time_record_id));
                  $this->model_projects->update_time_record($data_time_record, array("object_id"=>$time_record_id));
                  #-------------------------------------------------------------                  
               }               
               if(isset($_REQUEST['save_go_to_list']))
               {                     
                  $this->session->set_flashdata("exchange_messages",array( $this->lang->line("coco_msg_pm_save_project_successfully")));
                  redirect("pm/time_records/?project_id=".$project_id);
               }
            }
            $array_messages[] = $this->lang->line("coco_msg_pm_save_project_successfully");
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
         #----------------------------------------------------------------------
      }            
      #-------------------------------------------------------------------------
      $time_record = $this->model_projects->get_time_record( $time_record_id);
      
      if(!empty($time_record))
      {
         $view_data['project_id'] = $project_id = $time_record['project_id'];
      }
      #-------------------------------------------------------------------------
      if(isset($_REQUEST['delete']))
      {
         $this->model_projects->delete_object_recursively($time_record_id, $user_id, $user_role['id']);         
         redirect("pm/time_records/?project_id=".$project_id);                  
      }
      #-------------------------------------------------------------------------
      $project = $this->model_projects->get_project($project_id);      
      if( ! empty($project))
      {
         $view_data['project'] = $project;
         $view_data['list_members'] = $this->model_projects->get_list_members($project_id);
         $view_data['list_billable_status'] = $this->model_template->get_list_selectable_by("time_records","billable_status");
         
         
         if( ! empty($time_record) )
         {
            $view_data['time_record'] = $time_record;
         }
         else if( isset($time_record_id))
         {
            $array_messages[] = $this->lang->line('coco_msg_pm_time_record_not_exists');
         }
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      $view_data['array_messages'] = $array_messages;            
      $this->load->view('template/header', $view_data);
      
      if( ! empty($project) )
      {         
         $this->load->view("projects/menu_projects", $view_data);
         if( ! isset($time_record_id) OR ( isset($time_record_id) AND  ! empty($time_record) ) )
         {
            $this->load->view("projects/save_time_record", $view_data);
         }
      }
      $this->load->view('template/footer');
   }
   #############################################################################
   function delete_permanently()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      if(isset($_REQUEST['object_id']))
      {
         $object_id = $_REQUEST['object_id'];
         
         $object = $this->model_projects->get_object($object_id);
         
         
         if(isset($object['type']) AND strcasecmp($object['type'],"project")==0)
         {
            $this->model_projects->delete_project_permanently($object_id);            
         }
         
         if((isset($object['type']) AND strcasecmp($object['type'],"project")==0) OR
            (isset($object['type']) AND strcasecmp($object['type'],"task")==0)
            )
         {
            $this->model_projects->delete_task_permanently($object_id);            
         }
         
         if((isset($object['type']) AND strcasecmp($object['type'],"project")==0) OR
            (isset($object['type']) AND strcasecmp($object['type'],"task")==0) OR
            (isset($object['type']) AND strcasecmp($object['type'],"discussion")==0)
           )
         {
            $path_directory = ".".$this->config->item("uri_comment_files")."/".$object_id;            
            delete_directory($path_directory);
         }         
         $this->model_projects->delete_object_permanently_recursively($object_id);         
         
         $uri_project="";
         if(isset($_REQUEST['project_id']))
         {
            $uri_project = "project_id=".$_REQUEST['project_id'];
         }
         
         redirect(site_url("pm/trash?".$uri_project));
      }
   }
   #############################################################################
   function trash()
   {  
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      #---------------------------------------------------------------------------
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_trash_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      
      $view_data['has_privilege_delete_permanently'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'], 'pm/delete_permanently');
      $view_data['has_privilege_restore'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'], 'pm/restore');
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $project = $this->model_projects->get_project($project_id);      
      if(!empty($project))
      {
         $view_data['project'] = $project;         
      }
      else if(isset($_GET['project_id']))
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      $list_trash_objects = $this->model_projects->get_list_trash_objects(isset($_GET['project_id'])?$_GET['project_id']:null, 
                                                                          $view_data['company_id']);
      $view_data['list_trash_objects'] = $list_trash_objects;
      #---------------------------------------------------------------------------
      $view_data['array_messages'] = $array_messages;
      $this->load->view('template/header', $view_data);
      if(!empty($project))
      {         
         $this->load->view("projects/menu_projects", $view_data);         
      }
      $this->load->view("projects/trash", $view_data);
      $this->load->view("template/footer");
   }
   #############################################################################
   function save_comment()
   {  
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_save_comment_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      $comment_id = null;
      if(isset($_REQUEST['comment_id']))
      {
         $comment_id = $_REQUEST['comment_id'];            
      }      
      $is_discussion = null;
      $is_discussion_boolean = false;
      if(isset($_REQUEST['is_discussion']))
      {
         $view_data['is_discussion'] = $is_discussion = $_REQUEST['is_discussion'];
         $is_discussion_boolean = true;
      }      
      $parent_comment_id = null;
      if(isset($_REQUEST['parent_comment_id']))
      {
         $parent_comment_id = $_REQUEST['parent_comment_id'];   
      }
      
     
      #-------------------------------------------------------------------------
      if( isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list']) )
      {
         if(isset($parent_comment_id ))
         {
            $this->form_validation->set_rules('content', $view_labels['content'], 'trim|required|xss_clean');         
         }
         else
         {
            $this->form_validation->set_rules('content', $view_labels['content'], 'trim');         
         }
         
         if(isset($is_discussion) AND $is_discussion==true)
         {         
            $this->form_validation->set_rules('title', $view_labels['title'], 'trim|required|xss_clean');         
         }
         
         #-------------------------------------------------------------------------
         if(isset($_REQUEST['ids_delete_object']) AND count($_REQUEST['ids_delete_object'])>0)
         {
            $ids_delete_object = $_REQUEST['ids_delete_object'];
            $names_delete_object = $_REQUEST['names_delete_object'];
            
            for($i=0;$i<count($ids_delete_object);$i++)
            {
               if(strcasecmp($ids_delete_object[$i],"")!=0)
               {
                  $uri_delete = $this->config->item("uri_comment_files")."/".$comment_id."/".$names_delete_object[$i];               
                  $this->model_projects->delete_object($ids_delete_object[$i], $user_id, $user_role['id']);
               }
            }          
         }
         
         if($this->form_validation->run())
         {
            $is_private = $this->input->get_post("is_private");
            $is_private_boolean = false;
            if(isset($is_private) AND strcasecmp( $is_private, "")!=0)
            {
               $is_private = 1;
               $is_private_boolean = true;
            }
            else
            {
               $is_private = null;
            }
            $is_new_boolean = false;
            if(isset($_REQUEST['add']))
            {
               $is_new_boolean = true;
            }
            
            $title = $this->input->get_post("title");            
            if(!isset($title) OR (isset($title) AND strcasecmp(trim($title),"")==0) )
            {
               $title = null;
            }
            
               
            $data_comment = array("project_id"=>$project_id,
                                 "company_id"=>$company_id,
                                 
                                 "user_id"=>$user_id,
                                 "name"=>$title,
                                 "description"=>$this->input->get_post("content"),
                                 "is_private" => $is_private,
                                 );
            
            if(isset($_REQUEST['is_discussion']))
            {               
               $data_comment["parent_id"]=$project_id;
               $data_comment['type_select_id'] = $this->model_template->get_id_selectable_by("objects", "type", "discussion");
            }
            
            else if( ! isset($comment_id))
            {
               $data_comment["parent_id"]=$parent_comment_id;
               $data_comment['type_select_id'] = $this->model_template->get_id_selectable_by("objects", "type", "comment");
            }    
            #-------------------------------------------------------------------
            $type_object_id = $this->model_template->get_id_selectable_by("objects", "type", "file");
            
            $pre_data_file = array("type_select_id"=>$type_object_id,
                                   "user_id"=>$user_id,
                                   "company_id"=>$company_id,
                                   'parent_id' => $comment_id,
                                   "project_id"=>$project_id);            
            
            $action_status_id = $this->model_template->get_id_selectable_by("user_objects", "action_status", "started");
            
            $action_status = $this->model_template->get_selectable_by($action_status_id);
            
            #-------------------------------------------------------------------
            #-------------------------------------------------------------------
            if(isset($_REQUEST['add']))
            {

               $object_id = $this->model_projects->add_object($data_comment);                              
               
               #-------------------------------------------------------------
               $set_as_task_also = $this->input->get_post('set_as_task_also');
               if(isset($set_as_task_also) AND strcasecmp( trim($set_as_task_also), "") != 0)
               {
                  $priority_select_id = $this->model_template->get_id_selectable_by("projects", "priority", "1");
                  
                  $task_object_data = array("project_id"=>$project_id,
                                    "company_id"=>$company_id,
                                    "parent_id"=>$object_id,
                                    
                                    "user_id"=>$user_id,
                                    "name"=>$title);
                  
                  $task_object_data['type_select_id'] = $this->model_template->get_id_selectable_by("objects", "type", "task");
                  
                  
                  
                  $task_id = $this->model_projects->add_object($task_object_data);
                  
                  $task_data['object_id'] = $task_id;
                  $task_data['priority_select_id']=$priority_select_id;
                  $task_data['percent_completed']=0;
                  
                  $this->model_projects->add_task($task_data);
                  
                  $data_user_object_task = array("user_id"=>$user_id, 
                                         "user_role_id" => $user_role['id'],
                                         "object_id" => $task_id);
               
                  $this->model_projects->add_user_object($data_user_object_task, $action_status['value_select']);
               }
               #-------------------------------------------------------------
               $data_user_object = array("user_id"=>$user_id, 
                                         "user_role_id" => $user_role['id'],
                                         "object_id" => $object_id);
               
               $this->model_projects->add_user_object($data_user_object,"created");
               #-------------------------------------------------------------                  
               $comment_id = $object_id;               
               $pre_data_file['parent_id'] = $comment_id;
               $this->upload_object_file_batch($pre_data_file, "comment", $array_messages, $user_role);
               
               if(isset($parent_comment_id))
               {
                  $this->send_comment_by_email($object_id, $project_id, $parent_comment_id);
               }
               else
               {
                  $this->send_comment_by_email($object_id, $project_id);               
               }
            }
            #-------------------------------------------------------------            
            
            $this->save_add_remove_members($user_id, $comment_id, $is_task_boolean=false, $is_private_boolean, $is_new_boolean, $is_discussion_boolean);
            #-------------------------------------------------------------
            if( (isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list'])) AND isset($comment_id))
            {   
               if( ! isset($_REQUEST['add']) )
               {
                  $data_user_object = array("user_id"=>$user_id, 
                                            "user_role_id" => $user_role['id'],
                                            "object_id" => $comment_id,                                            
                     );
                  $this->model_projects->add_user_object($data_user_object, $action_status['value_select']);                  
                  #-------------------------------------------------------------                  

                  $this->model_projects->update_object($data_comment, array("id_object"=>$comment_id));
                  #-------------------------------------------------------------
                  $this->upload_object_file_batch($pre_data_file, "comment", $array_messages, $user_role);
               }
               if(isset($_REQUEST['save_go_to_list']))
               {
                  if(isset($parent_comment_id))
                  {
                     $this->session->set_flashdata("exchange_messages",array( $this->lang->line("coco_msg_pm_save_project_successfully") ));
                     redirect("pm/view_comment/?comment_id=".$parent_comment_id);
                  }
                  else
                  {                     
                     $this->session->set_flashdata("exchange_messages",array( $this->lang->line("coco_msg_pm_save_project_successfully") ));
                     redirect("pm/discussions/?project_id=".$project_id);
                  }
               }               
            }
            $array_messages[] = $this->lang->line("coco_msg_pm_save_project_successfully");
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
         #----------------------------------------------------------------------
      }
      else if(isset($_REQUEST['delete']))
      {
         $this->model_projects->delete_object_recursively($comment_id, $user_id, $user_role['id']);         
         $uri_delete = "./".$this->config->item("uri_comment_files")."/".$comment_id;                  
         if(isset($parent_comment_id))
         {
            redirect("pm/view_comment/?comment_id=".$parent_comment_id);
         }
         else
         {
            redirect("pm/discussions/?project_id=".$project_id);
         }         
      }
      
      if(isset($parent_comment_id))
      {
         $view_data['parent_comment'] = $parent_comment = $this->model_projects->get_comment($parent_comment_id);         
         $view_data['parent_comment_id'] = $parent_comment_id;         
         if(!empty($parent_comment))
         {
            $view_data['project_id'] = $project_id = $parent_comment['project_id'];
         }
      }
      
      $comment = $this->model_projects->get_comment( $comment_id);  
      
      if(!empty($comment))
      {
         $view_data['project_id'] = $project_id = $comment['project_id'];                  
      }
      ##########<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
      if(isset($is_discussion) AND $is_discussion==true)
      {
         $parent_comment_id_only_to_members = $project_id;
      }
      else
      {
         $parent_comment_id_only_to_members = $parent_comment_id;
      }
      $view_data['list_membership'] = $this->model_projects->get_list_membership($parent_comment_id_only_to_members, $comment_id);
      ###########>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
      $project = $this->model_projects->get_project($project_id);      
      
      if( ! empty($project) )
      {
         $view_data['project'] = $project;
         $view_data['list_priorities'] = $list_priorities = $this->model_projects->get_list_priorities();      
         
         if( ! empty($comment))
         {
            $view_data['comment'] = $comment;
            $view_data['list_attachment_files'] = $list_attachment_files = $this->model_projects->get_list_attachment_files($comment_id);         
         }         
         else if( isset($comment_id))
         {
            $array_messages[] = $this->lang->line('coco_msg_pm_comment_not_exists');
         }
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      $view_data['array_messages'] = $array_messages;  
      $view_data['this_class'] = $this;
      
      
      $view_data['max_upload_filesize'] = $this->template->max_upload_filesize;
      $view_data['max_total_send_filesize_mb'] = $this->template->max_total_send_filesize_mb;
      
      $this->load->view('template/header', $view_data);
      if( ! empty($project) )
      {  
         $this->load->view("projects/menu_projects", $view_data);
                  
         if( ! isset($comment_id) OR ( isset($comment_id) AND  ! empty($comment) ) )
         {
            $this->load->view("projects/save_comment", $view_data);
         }
      }      
      $this->load->view('template/footer');
   }
   #############################################################################
   private function upload_object_file_batch($pre_data, $parent_type_object="comment", & $array_messages = null, $user_role)
   {
      $quantity_files = $_REQUEST['quantity_files'];
            
      if($quantity_files>0)
      {
         $path_directory = ".".$this->config->item("uri_".$parent_type_object."_files")."/".$pre_data['parent_id'];
                           
         create_directory($path_directory);
         $view_labels = $this->lang->line('coco_pm_header_view_labels');
         
         $total_filesize = 0;
         
         for($i=1;$i<=$quantity_files;$i++)
         {
            $source_file_name = 'file_'.$i;
            $total_filesize += $_FILES['file_'.$i]['size'];             
            
            if(isset($_FILES['file_'.$i]['name']) AND strcasecmp($_FILES['file_'.$i]['name'],"")!=0)            
            {
               $total_filesize += $_FILES['file_'.$i]['size']; 
               
               if($total_filesize <= $this->template->max_total_send_filesize)
               {
                  $destination_file_name = $_FILES['file_'.$i]['name'];
                  $this->template->upload_file($path_directory, $destination_file_name, $source_file_name, $array_messages);
                  
                  $pre_data['name'] = get_filename_uploaded($_FILES['file_'.$i]['name']);

                  $file_id = $this->model_projects->add_object($pre_data, "uploaded");
                  #----------------------------------------------------------------
                  $data_user_object = array("user_id"=>$user_role['user_id'], 
                                            "user_role_id" => $user_role['id'],
                                            "object_id" =>$file_id);
                  $this->model_projects->add_user_object($data_user_object,"uploaded");
                  #----------------------------------------------------------------

                  
                  
               }
               else
               {   
                  $array_messages[] = $msg = $view_labels['msg_filesize_part1'].
                                      $total_filesize.$view_labels['msg_filesize_part2'].
                                      $this->template->max_total_send_filesize.
                                      $total_filesize.$view_labels['msg_filesize_part3'];
                  
                  
                  
               }
            }
         }
      }
   }      
   #############################################################################
   function view_comment()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_view_comment_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      $view_data['user_id'] = $user_id;
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
      
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
      if(isset($_REQUEST['click_view_project_comment']))
      {
         $data = $this->model_projects->get_statistics("click_count_project_comments");         
         $data['quantity_uses'] = $data['quantity_uses'] + 1;         
         $this->model_projects->update_statistics($data, array("var_name"=>"click_count_project_comments"));                  
      }
      if(isset($_REQUEST['click_view_my_comment']))
      {
         $data = $this->model_projects->get_statistics("click_count_my_comments");         
         $data['quantity_uses'] = $data['quantity_uses'] + 1;         
         $this->model_projects->update_statistics($data, array("var_name"=>"click_count_my_comments"));                  
      }
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
      $messages_received = $this->session->flashdata("exchange_messages");      
      if(!empty($messages_received))
      {
         $array_messages = array_merge($array_messages, $messages_received); 
      }
      
      $comment_id = null;
      if(isset($_REQUEST['comment_id'] ))
      {
         $comment_id = $_REQUEST['comment_id'];         
      }
      
      $reply_object_id = null;
      if(isset($_REQUEST['reply_object_id'] ))
      {
         $reply_object_id = $_REQUEST['reply_object_id'];         
      }
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
      if(isset($_REQUEST['action_status_id']))
      {
         $aux_action_status = $this->model_template->get_selectable_by($_REQUEST['action_status_id']);
         
         if( $this->validate_task_status($reply_object_id, $_REQUEST['action_status_id'], $user_role['user_id'], $array_messages ) )
         {
            if(strcasecmp($aux_action_status['value_select'], "in_process")==0)
            {
               $task_actived = $view_data['task_actived'];

               if(!empty($task_actived))
               {
                  $this->pause_task_active_by_user($task_actived['object_id'], 
                                                        $task_actived['user_id'], 
                                                        $task_actived['user_role_id'],
                                                        $array_messages);
               }
            }        
            $this->process_save_task_by_assigned(null,                                                    
                                                 $reply_object_id, 
                                                 $_REQUEST['action_status_id'],
                                                 $project_id, 
                                                 $comment_id, 
                                                 $user_role,
                                                 $array_messages);
         }          
         $view_data['task_actived'] = $this->model_projects->get_task_active_by_user($user_id);         
      }
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
      
      #-------------------------------------------------------------------      
      $this->form_validation->set_rules('content', $view_labels['content'], 'trim|required|xss_clean');               
         
      if($this->form_validation->run())
      {
         $is_private = $this->input->get_post("is_private");
         $is_private_boolean = false;
         if(isset($is_private) AND strcasecmp( $is_private, "")!=0)
         {
            $is_private = 1;
            $is_private_boolean = true;
         }
         else
         {
            $is_private = null;
         }
         
         $data_comment = array(
                              "project_id"=>$project_id,
                              "company_id"=>$company_id,
                              "parent_id"=>$comment_id,
                              "user_id"=>$user_id,                                    
                              "description"=>$this->input->get_post("content"),
                              "is_private"=>$is_private,
                              );      
         #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         
         $is_task = $this->input->get_post("is_task");
         
         $is_task_boolean = false;
         
         if(isset($is_task) AND strcasecmp( $is_task, "ok" )==0)
         {
            $is_task_boolean = true;
            
            $data_comment['type_select_id'] = $this->model_template->get_id_selectable_by("objects", "type", "task");
         }
         else
         {
            $data_comment['type_select_id'] = $this->model_template->get_id_selectable_by("objects", "type", "comment");
         }
         #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         
         #-----------------------------------------------------------------------------
         $type_object_id = $this->model_template->get_id_selectable_by("objects", "type", "file");         
         #-----------------------------------------------------------------------------
         $object_id = $this->model_projects->add_object($data_comment);         
         #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
         #-------------------------------------------------------------
         
         $this->save_add_remove_members($user_id, $object_id, $is_task_boolean, $is_private_boolean, $is_new=true);        
         #-------------------------------------------------------------
         if(isset($is_task) AND strcasecmp($is_task, "ok")==0)
         {
            $priority_select_id = $this->model_template->get_id_selectable_by("projects", "priority","1");
            
            $this->model_projects->add_task(array("object_id"=>$object_id, "priority_select_id"=>$priority_select_id));
         }
         
         #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%                  
         $pre_data_file = array("type_select_id"=>$type_object_id,
                                "user_id"=>$user_id,
                                "company_id"=>$company_id,
                                "parent_id"=>$object_id,
                                "project_id"=>$project_id);           
         #----------------------------------------------------------------
         $data_user_object = array("user_id"=>$user_id, 
                                      "user_role_id" => $user_role['id'],
                                      "object_id" => $object_id);
         
         if(isset($is_task) AND strcasecmp( $is_task, "ok" )==0)
         {
            $this->model_projects->add_user_object($data_user_object,"created","TA");
            $this->model_projects->update_project_percent_completed($project_id);
         }
         else
         {
            $this->model_projects->add_user_object($data_user_object,"created","OB");
         }
         #------------------------------------------         
         $this->upload_object_file_batch($pre_data_file, "comment", $array_messages, $user_role);                  
         
         if(isset($is_task) AND strcasecmp( $is_task, "ok")==0)
         {
            $this->send_task_by_email($object_id, $project_id, $comment_id);
         }
         else
         {
            $this->send_comment_by_email($object_id, $project_id, $comment_id);
         }
      }
      #-------------------------------------------------------------------      
      $comment = $this->model_projects->get_comment( $comment_id );      
      if( ! empty($comment))
      {
         $view_data['project_id'] = $project_id = $comment['project_id'];
      }
      $project = $this->model_projects->get_project( $project_id );
      
      if( ! empty($project))
      {
         $view_data['project'] = $project;
         
         if( ! empty($comment))
         {
            $view_data['comment'] = $comment;   
            $view_data['comment_id'] = $comment_id;
            $list_comments_reply = $this->model_projects->get_list_comments_reply( $comment_id, $user_id );
            
            #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
            $view_data['list_task_status'] = $this->model_template->get_list_selectable_by("user_objects", "action_status", null, "TA");
            $view_data['selectable_type_object_task_id'] = $this->model_template->get_id_selectable_by("objects", "type", "task");
            
            $view_data['list_membership'] = $this->model_projects->get_list_membership($comment_id, null);         
         
            $view_data['list_priorities'] = $list_priorities = $this->model_projects->get_list_priorities();      
            $view_data['list_status'] = $list_status = $this->model_template->get_list_selectable_by("user_objects", "action_status",null, "TA");

            
            #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%            
            
            $list_comments_reply_modified = null;
            $k=0;            
            for($i=0; $i<count($list_comments_reply); $i++)
            {
               $is_show = false;
               if(isset($list_comments_reply[$i]['is_private']))              
               {
                  if(isset($list_comments_reply[$i]['have_access']))
                  {
                     $is_show = true;
                  }
               }
               else 
               {
                  $is_show = true;
               }
               
               if($is_show)
               {
                  $list_comments_reply_modified[$k] = $list_comments_reply[$i];
                  
                  $list_comments_reply_modified[$k]['index'] = $k+1;               
                  $list_comments_reply_modified[$k]['list_attachment_files'] = $this->model_projects->get_list_attachment_files($list_comments_reply[$i]['id_object']);               
                  $list_comments_reply_modified[$k]['list_member_tasks'] = $this->model_projects->get_list_membership($list_comments_reply[$i]['id_object']);
                  $k++;
               }               
            }
            
            $view_data['list_tasks'] = $this->model_projects->get_list_tasks_by($comment['id_object']);         
            
            $view_data['list_discussion_peoples'] = $list_object_peoples = $this->model_projects->get_list_object_peoples($comment_id);         
            $view_data['list_comments_reply'] = $list_comments_reply_modified;         
            $view_data['list_attachment_files'] = $list_attachment_files = $this->model_projects->get_list_attachment_files( $comment_id);         
         }
         else if( isset($comment_id))
         {
            $array_messages[] = $this->lang->line('coco_msg_pm_comment_not_exists');
         }         
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      
      #-------------------------------------------------------------------------
      $view_data['this_class'] = $this;
      $view_data['max_upload_filesize'] = $this->template->max_upload_filesize;
      $view_data['max_total_send_filesize_mb'] = $this->template->max_total_send_filesize_mb;
      #-------------------------------------------------------------------------
      $view_data['array_messages'] = $array_messages;
      
      $this->load->view('template/header', $view_data);
      
      if( ! empty($project) )
      {         
         $this->load->view("projects/menu_projects", $view_data);
         if( ! isset($comment_id) OR ( isset($comment_id) AND  ! empty($comment) ) )
         {
            $this->load->view("projects/view_comment", $view_data);      
         }
      }
      $this->load->view("template/footer");
   }
   #############################################################################
   function discussions()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_discussions_view_labels');      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      
      $messages_received = $this->session->flashdata("exchange_messages");      
      if(!empty($messages_received))
      {
         $array_messages = array_merge($array_messages, $messages_received); 
      }
            
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
         
      $project = $this->model_projects->get_project( $project_id );      
      #-------------------------------------------------------------------------
      if( ! empty($project))
      {
         $view_data['project'] = $project;         
         $list_discussions = $this->model_projects->get_list_discussions( $project_id );
         if(!empty($list_discussions) AND count($list_discussions)>0)
         {
            for($i=0; $i<count($list_discussions); $i++)
            {
               $list_discussions[$i]['count_replies'] = $this->model_projects->get_counts_reply_comments($list_discussions[$i]['id_object']);
               $list_discussions[$i]['last_comment_reply'] = $this->model_projects->get_last_comment_reply($list_discussions[$i]['id_object']);
            }
         }
         $view_data['list_discussions'] = $list_discussions;
      }
      else
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
      #-------------------------------------------------------------------------
      $view_data['array_messages'] = $array_messages;      
      
      $this->load->view('template/header', $view_data);      
      if( ! empty($project))
      {
         $this->load->view("projects/menu_projects", $view_data);
         $this->load->view("projects/discussions", $view_data);
      }
      $this->load->view('template/footer');      
   }
   #############################################################################
   function save_project()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_save_project_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      $company_id = $user_role['company_id'];
      
      $project_id = null;
      if(isset($_REQUEST['project_id']))
      {
         $view_data['project_id'] = $project_id = $_REQUEST['project_id'];   
      }
      
      if( isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list']) )
      {  
         $this->form_validation->set_rules('name', $view_labels['title'], 'trim|required|xss_clean');
         $this->form_validation->set_rules('start_date', $view_labels['start_date'], 'trim|required');
         $this->form_validation->set_rules('end_date', $view_labels['end_date'], 'trim');
         

         if($this->form_validation->run())
         {
            $start_time = $this->input->get_post("start_hour").":".$this->input->get_post("start_minute").":00";
            $end_time = $this->input->get_post("end_hour").":".$this->input->get_post("end_minute").":00";               
            $start_dt = $this->input->get_post("start_date")." ".$start_time;
            
            $end_date = $this->input->get_post("end_date");
            
            $end_dt = isset($end_date)?$end_date." ".$end_time:null;
            
            $type_select_id = $this->model_template->get_id_selectable_by("objects","type","project");
            
            $data_object = array("name"=>$this->input->get_post("name"),                                                                                                      
                                 "type_select_id"=>$type_select_id,
                                 "company_id"=>$company_id,
                                 "user_id"=>$user_id,
                                 //"status_select_id"=>$this->input->get_post("status_select_id"),
                                 "description"=>$this->input->get_post("description"),
                                );

            $project_data = array("start_date"=>$start_dt,
                                  "end_date"=>$end_dt,                  
                                  "priority_select_id"=>$this->input->get_post("priority_select_id"));
            
            $points=$this->input->get_post("points");
            $project_data['points']= isset($points)?$points:"0";                  
            
            if(isset($_REQUEST['add']))
            {
               $object_id = $this->model_projects->add_object($data_object); 
                              
               $data_user_object = array("user_id"=>$user_id, 
                                            "user_role_id" => $user_role['id'],
                                            "object_id" => $object_id
                                           );
               
               $action_status = $this->model_template->get_selectable_by( $this->input->get_post("status_select_id") );
               $this->model_projects->add_user_object($data_user_object, $action_status['value_select'], "PR" );
                              
               $project_data['object_id'] = $object_id;                  
               $this->model_projects->add_project($project_data);
               #----------------------------------------------------------------------------------
               $list_company_users_owner = $this->users->get_list_users_by_role_type("CEO", $company_id);
               
               for($i=0; $i<count($list_company_users_owner); $i++)
               {
                  if(strcasecmp($list_company_users_owner[$i]['user_id'] , $user_id)!=0)
                  {
                     $this->model_projects->add_member($list_company_users_owner[$i]['user_id'], $object_id);
                  }
               }
               $this->model_projects->add_member($user_id, $object_id);  
               #----------------------------------------------------------------------------------
               
               $project_id = $object_id;
            }
            if( (isset($_REQUEST['save']) OR isset($_REQUEST['save_go_to_list'])) AND isset($project_id) )
            {
               if( ! isset($_REQUEST['add']))
               {     
                  $data_user_object = array("user_id"=>$user_id, 
                                            "user_role_id" => $user_role['id'],
                                            "object_id" => $project_id
                                           );
                  
                  
                  $action_status = $this->model_template->get_selectable_by( $this->input->get_post("status_select_id") );
                  
                  
                  
                  $this->model_projects->add_user_object($data_user_object, $action_status['value_select'], "PR" );
               
                  $this->model_projects->update_object($data_object, array("id_object"=>$project_id));
                  $this->model_projects->update_project($project_data, array("object_id"=>$project_id));                  
               }
               
               if(isset($_REQUEST['save_go_to_list']))
               {  
                  $this->session->set_flashdata("exchange_messages", array( $this->lang->line("coco_msg_pm_save_project_successfully") ));                  
                  redirect("pm/add_member?project_id=".$project_id);
               }               
            }
            
            $array_messages[] = $this->lang->line("coco_msg_pm_save_project_successfully");
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
      }
      else if(isset($_REQUEST['delete']))
      {  
         $this->model_projects->delete_object_recursively($_REQUEST['project_id'], $user_id, $user_role['id']);
         redirect("pm/home");
      }    
      
      
      $project = $this->model_projects->get_project( $project_id );
      if( ! empty($project) )
      {
         $view_data['project'] = $project;                  
      }
      else if( isset($project_id))
      {
         $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
      }
         
      $view_data['has_privilege_edit_project_points'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'pm/save_project/save_points');
      
      $view_data['current_date_time'] = $current_date_time = $this->model_template->get_system_time();         
      $view_data['array_messages'] = $array_messages;      
      $view_data['list_priorities'] = $list_priorities = $this->model_projects->get_list_priorities();      
      $view_data['list_status'] = $list_status = $this->model_template->get_list_selectable_by("user_objects", "action_status", null, "PR");
      
      $this->load->view('template/header', $view_data);      
      if( ! isset($project_id) OR ( isset($project_id) AND  ! empty($project) ) )
      {
         
         $this->load->view("projects/menu_projects");      
         $this->load->view("projects/save_project", $view_data);
      }      
      $this->load->view('template/footer');
   }
   #############################################################################
   function view_project()
   {
      $view_data = array();      
      $array_messages = array();
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_view_project_view_labels');
      
      $user_id = $this->session->userdata("user_id");
      
      if(isset($_REQUEST['project_id']))
      {
         $project_id = $_REQUEST['project_id'];         
         $this->session->set_userdata("project_id", $project_id);
         
         $project = $this->model_projects->get_project( $project_id );         
         
         if(!empty($project))
         {
            $view_data['project'] = $project;         
            $view_data['owner'] = $owner = $this->users->get_user_complete_by_id( $project['user_id'] );         
            $view_data['list_activities'] = $this->model_projects->get_list_last_objects_from_project( $project_id );
            
         }
         else
         {
            $array_messages[] = $this->lang->line('coco_msg_pm_project_not_exists');
         }
         
         #--------------------------------------------------------
         #@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
         $view_data['my_class'] = $this;
         
         $view_data['array_messages'] = $array_messages;         
         $this->load->view('template/header', $view_data);
         
         $view_data['list_tasks_users'] = $this->get_performance($project_id);
         
         if(! empty($project))
         {  
            $view_data['has_privilege_edit_project'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'pm/save_project');
            
            $this->load->view("projects/menu_projects", $view_data);
            
            $this->load->view("projects/view_project", $view_data);
         }         
      }
      $this->load->view('template/footer');
   }
   #############################################################################
   function projects_completed()
   {  
      $view_data = array();      
      $array_messages = array();
      
      $this->session->unset_userdata("project_id");
      
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_home_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      
      $messages_received = $this->session->flashdata("exchange_messages");
      
      if(!empty($messages_received))
      {
         $array_messages=array_merge($array_messages, $messages_received); 
      }
      
      $view_data['array_messages'] = $array_messages;      
      $view_data['list_projects'] = $this->model_projects->get_list_projects_by( $user_role['company_id'], "completed" );
      
      $this->load->view('template/header', $view_data);
      $this->load->view("projects/menu_projects", $view_data);
      $this->load->view("projects/projects_completed", $view_data);
      $this->load->view("template/footer");
   }
   #############################################################################
   function home()
   {  
      $view_data = array();      
      $array_messages = array();
      
      $is_logged_in = $this->tank_auth->is_logged_in();if(!$is_logged_in){redirect("auth/login");}      
      $view_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($view_data);      
      $view_data['is_logged_in'] = $is_logged_in;
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_pm_home_view_labels');
      
      $user_id = $this->session->userdata("user_id");      
      
      $messages_received = $this->session->flashdata("exchange_messages");
      if(!empty($messages_received))
      {
         $array_messages=array_merge($array_messages, $messages_received); 
      }
      $view_data['array_messages'] = $array_messages;      
      
      $user_role = $this->users->get_last_activated_user_role($user_id, $view_data['company_id']);      
      
      if(!empty($user_role))
      {
         $list_projects = $this->model_projects->get_list_projects_by( $user_role['company_id'], "started", $user_id );
         for($i=0; $i<count($list_projects);$i++)
         {
            $row_statistics = $this->model_projects->get_tasks_pendings_total_by($list_projects[$i]['id_object']);

            $list_projects[$i]['pendings'] = $row_statistics['pendings'];
            $list_projects[$i]['completeds'] = $row_statistics['completeds'];
            $list_projects[$i]['total'] = $row_statistics['total'];
            
            $list_projects[$i]['list_user_tasks'] = $this->model_projects->get_list_user_tasks_actived_by_project($list_projects[$i]['id_object']);
            
            $list_projects[$i]['last_task_by_project_user'] = $this->model_projects->get_last_task_by_project_user($list_projects[$i]['id_object'], $user_id);
            
            $list_projects[$i]['last_task_by_project'] = $this->model_projects->get_last_task_by_project($list_projects[$i]['id_object']);
            
         }
         $view_data['has_privilege_save_project'] = $this->template->is_have_privilege_by_uri($view_data['list_privileges'],'pm/save_project');
         $view_data['list_projects'] = $list_projects;
      }
      
      $this->load->view('template/header', $view_data);
      $this->load->view("projects/menu_projects", $view_data);
      $this->load->view("projects/home", $view_data);
      $this->load->view("template/footer");
   }
}
   