<?php
class Times extends CI_Controller
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
      $this->load->model('model_template');      
      $this->load->model('model_projects');      
      
      $this->load->model('tank_auth/users');
      
      
      
      $this->load->helper('url');
      
      $this->load->library('template');
      
   }
   #############################################################################
   function index()
   {  
      //$this->home();
      //redirect('times/home');      
      redirect('pm/home');      
   }
   function view_users()
   {
      $list_ceo_members = $this->users->get_list_users_activated_by_company(1);
      echo "<pre>";
      print_r($list_ceo_members);
      echo "</pre>";
   }
   #############################################################################
   private function get_list_present_users_by($company_id, $datetime_begin, $datetime_end, $current_ts)
   {
      $list_users = $this->model_times->get_list_present_users_by($company_id,
                                                                  $datetime_begin, 
                                                                  $datetime_end);
      
      
      $TIME_IN_INDEX = 'time_in';
      $TIME_OUT_INDEX = 'time_out';
      
      $KEY_SUB_TOTAL_TEMP = 'sub_total';
      
      for($j=0; $j<count($list_users); $j++)
      {
         $list_times = $this->model_times->get_list_times($datetime_begin, 
                                                       $datetime_end,
                                                       $list_users[$j]['user_id'],
                                                       $company_id);
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

               if( date_is_cross_interval($list_times[$key][$TIME_IN_INDEX], $datetime_begin, $datetime_end))
               {
                  if($this->model_times->is_date_cross_range( $list_times[$key]['id_time'], $list_users[$j]['user_id']) === TRUE)
                  { 
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = 0 ;
                  }
                  else//only over week interval**************************************
                  {
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = abs((strtotime($datetime_begin) - strtotime($list_times[$i][$TIME_OUT_INDEX]))/(3600)); 
                  }
               }
               else if( date_is_cross_interval($list_times[$key][$TIME_OUT_INDEX], $datetime_begin, $datetime_end) )
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
      
      return $list_users;
   }
   #############################################################################
   public function present_users()
   {
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      
      $header_data = $this->tank_auth->get_header_data();       
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
      
      $array_messages=array();
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_present_users_view_labels');
      
      
      
      $current_ts = $this->model_template->get_system_time();      
      list($current_date, $current_time) = explode(" ", $current_ts);      
      
      
      $dt_range_current = get_week_interval_arround_date($current_date);
      
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
      $this->load->view('times/present_users', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function manager_times()
   {
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $company_logged = $header_data['company_logged'];
      
      $array_messages=array();
      
      if($this->session->flashdata('my_messages')!=null)
      {
         $array_messages[] = $this->session->flashdata('my_messages');            
      }
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_manager_times_view_labels');
      
      $user_id = $this->session->userdata('user_id');      
      
      list($current_date, $current_time) = explode(" ", $this->model_template->get_system_time());
      
      
      
      if(isset($_POST['date_begin']) AND 
         isset($_POST['date_end']) AND
         strcasecmp($_POST['date_begin'],"")!=0 AND
         strcasecmp($_POST['date_end'],"")!=0)
      {
         $date_range_result['date_begin'] = $_POST['date_begin'];         
         $date_range_result['date_end'] = $_POST['date_end'];
         
         
         $dt_range_result['begin'] = $_POST['date_begin'].' 00:00:00';
         $dt_range_result['end'] = $_POST['date_end'].' 23:59:59';
      }
      else if(isset($_GET['date_begin']) AND isset($_GET['date_end']) AND
         strcasecmp($_GET['date_begin'],"")!=0 AND
         strcasecmp($_GET['date_end'],"")!=0)
      {
         $date_range_result['date_begin'] = $_GET['date_begin'];         
         $date_range_result['date_end'] = $_GET['date_end'];
         
         $dt_range_result['begin'] = $_GET['date_begin'].' 00:00:00';
         $dt_range_result['end'] = $_GET['date_end'].' 23:59:59';
      }
      else
      {
         $week_number = get_week_number($current_date);
         $year = date("Y");

         
         
         if(isset($_GET['week']))
         {
            $week_number = $_GET['week'];
            $year = $_GET['year'];
         }

         $interval_current = get_week_interval_dates($week_number, $year); 
         $dt_range_result['begin'] = $interval_current['begin'] .' 00:00:00';
         $dt_range_result['end'] = $interval_current['end'] .' 23:59:59';
         
         $date_range_result['date_begin'] = $interval_current['begin'];         
         $date_range_result['date_end'] = $interval_current['end'];         
      }
      
      $view_data['date_begin'] = $date_range_result['date_begin'];
      $view_data['date_end'] = $date_range_result['date_end'];
                  
      $list_times = $this->model_times->get_list_times($dt_range_result['begin'], 
                                                       $dt_range_result['end'],
                                                       $user_id=null,
                                                       $company_logged['id']);
      
      

      $list_users = $this->users->get_list_users_by_company_with_times_in_range(
                                  $company_logged['id'],
                                  $dt_range_result['begin'],
                                  $dt_range_result['end']);
      
      $view_data['list_users'] = $list_users;
      
      
      $week_diff = ceil(abs(strtotime($dt_range_result['end'])- strtotime($dt_range_result['begin']))/(60*60*24*7));      
      /****** ^_^  ^_^  ^_^ indexing constants for readability ^_^  ^_^  ^_^
       */
      $ERROR_CROSS = 'status_temp_error_cross';
      $ERROR_CROSS_WEEK = 'status_temp_error_cross_week';
      $STATUS_IN_TEMP = 'status_in_temp_index_k';
      $STATUS_OUT_TEMP = 'status_out_temp_index_k';
      $SUB_TOTAL_TEMP = 'sub_total_temp';
      $TIME_IN_INDEX_K = 'time_in';
      $TIME_OUT_INDEX_K = 'time_out';
      
      $STYLE_TIME_OBSERVED = 'time_observed_link';
      $STYLE_TIME_CORRECTED = 'time_corrected_link';
      $STYLE_FUNCTION_CROSS_INCOHERENT = 'function_cross';      
      $STYLE_FUNCTION_CROSS_WEEK = 'function_cross_week';
      $STYLE_FUNCTION_LINK = 'function_link';
      
      $str_output="";     
      $k=0;      
      for($i=0; $i<count($list_users) AND $k<count($list_times) ;$i++)
      {
         
         $str_output .= "<div class='container_user_box'>";//BEGIN USER
         $str_output .= "<a  name='user_id_".$list_users[$i]['id']."'></a>";
         $str_output .= "<fieldset  class='user_box clear_both'>";
         //$str_output .= "<legend>Leyendas</legend>";
         //$str_output .= "<div >".$list_users[$i]['name']."</div>";
         $str_output .= "<legend class='user_data_manager'>".$list_users[$i]['name']." ".$list_users[$i]['last_name']."</legend>";
         
         $date_time_begin = strtotime($dt_range_result['begin']);
         
         
         
         ##################################################################
         $row_time_last = $this->model_times->get_row_time_last_by_user($list_users[$i]['id']);
         
         for($j=0; $j<$week_diff; $j++)
         {  
            
            $interval_week = get_week_interval_arround_date(date("Y-m-d",$date_time_begin));
            
            $week_j = $interval_week['week_number'];
            $year_j = $interval_week['year'];
            
                                    
            $interval_week_dt['begin'] = $interval_week['begin']." 00:00:00";
            $interval_week_dt['end'] = $interval_week['end']." 23:59:59";
                                   
            $exists_row_time=false;
            $total_hours = 0;            
            $row_times_per_week="";
            
            /*
            echo "<br>DATE IS CROSS INTERVAL = ".$list_times[$k][$TIME_IN_INDEX_K]." USER_ID = ".$list_times[$k]['user_id']." BEGIN = ".$interval_week_dt['begin']." END = ".$interval_week_dt['end'];
            echo "<br>DATE IS CROSS INTERVAL = ".$list_times[$k][$TIME_OUT_INDEX_K]." USER_ID = ".$list_times[$k]['user_id']." BEGIN = ".$interval_week_dt['begin']." END = ".$interval_week_dt['end'];
            */
            
            
            while($k<count($list_times) AND 
                     strcasecmp($list_users[$i]['id'], $list_times[$k]['user_id'])==0                     
                     AND
                     (
                        ! date_is_cross_interval($list_times[$k][$TIME_IN_INDEX_K], $interval_week_dt['begin'], $interval_week_dt['end'])
                        OR 
                        ! date_is_cross_interval($list_times[$k][$TIME_OUT_INDEX_K], $interval_week_dt['begin'], $interval_week_dt['end'])
                     ))
            {
               
                  
               
                  if( ! $exists_row_time )
                  {
                     $exists_row_time = true;
                  }
                  
                  /*backup per row time [k] duplicate to last row
                  */
                  $time_in_k_backup  = $list_times[$k][$TIME_IN_INDEX_K];
                  $time_out_k_backup = $list_times[$k][$TIME_OUT_INDEX_K];
                  
                  $tag_begin_interval  = "";
                  $tag_end_interval = "";
                  
                  $title_in ="";
                  $title_out ="";
                  

                  $diff_hours_week = 0;
                  $diff_hours_gral = $list_times[$k]['sub_total'];
                  $is_week_end = false;
                  
                  
                  
                  $url_edit_in_link = " href='".site_url("times/edit_time_gral/?id_time=".$list_times[$k]['id_time'])."&record=in&redirect=".urlencode("date_begin=".$date_range_result['date_begin']."&date_end=".$date_range_result['date_end'])."'";
                  $url_edit_out_link = " href='".site_url("times/edit_time_gral/?id_time=".$list_times[$k]['id_time'])."&record=out&redirect=".urlencode("date_begin=".$date_range_result['date_begin']."&date_end=".$date_range_result['date_end'])."'";
                     
                  if( ! date_is_cross_interval($list_times[$k][$TIME_IN_INDEX_K], $interval_week_dt['begin'], $interval_week_dt['end']) AND
                      ! date_is_cross_interval($list_times[$k][$TIME_OUT_INDEX_K], $interval_week_dt['begin'], $interval_week_dt['end']))
                  {
                     $diff_hours_week = abs((strtotime($list_times[$k][$TIME_OUT_INDEX_K]) - strtotime( $list_times[$k][$TIME_IN_INDEX_K] ))/(60*60));
                  }
                  else//BEGIN WEEK
                  if( date_is_cross_interval($list_times[$k][$TIME_IN_INDEX_K], $interval_week_dt['begin'], $interval_week_dt['end']))
                  {
                     $diff_hours_week = abs((strtotime($list_times[$k][$TIME_OUT_INDEX_K]) - strtotime( $interval_week_dt['begin'] ))/(60*60));
                  }
                  else//END WEEK
                  if(date_is_cross_interval($list_times[$k][$TIME_OUT_INDEX_K], $interval_week_dt['begin'], $interval_week_dt['end']))
                  {
                     if(isset($list_times[$k]['satus_out']) AND strcasecmp ($list_times[$k]['satus_out'], "")==0)
                     {
                        $diff_hours_week = abs((strtotime($list_times[$k][$TIME_IN_INDEX_K]) - strtotime( $interval_week_dt['end'] ))/(60*60));
                        
                        $time_k = $list_times[$k];                     
                        $list_times[$k][$TIME_OUT_INDEX_K] = $interval_week_dt['end'];

                        //duplicate row time
                        array_splice($list_times, $k+1, 0, array( 'any_key'=>                           
                                                                  array('id_time'=>$time_k['id_time'], 
                                                                           'time_in'=>date('Y-m-d H:i:s' , 
                                                                                      strtotime('+1 seconds', strtotime($interval_week_dt['end']))
                                                                                                                   ),
                                                                           'time_out'=>$time_k['time_out'],
                                                                           'status_in' => 'Valid', 
                                                                           'status_out' => 'Valid', 
                                                                           'user_id' =>$time_k['user_id'], 
                                                                           'week_of_year' =>$time_k['week_of_year'],                                            
                                                                           'sub_total'=> '0' ) ) );                           
                     }
                     else
                     {
                        //$url_edit_out_link ="";
                     }
                  }
                  /****************************************************************************************************/
                  /****************************************************************************************************/
                  /****************************************************************************************************/
                  /****************************************************************************************************/
                  $list_times[$k][$STATUS_IN_TEMP]='';
                  $list_times[$k][$STATUS_OUT_TEMP]='';
                  
                  ############################################################
                  if( date_is_cross_interval($list_times[$k][$TIME_IN_INDEX_K], $dt_range_result['begin'], $dt_range_result['end']))
                  {
                     if($this->model_times->is_date_cross_range( $list_times[$k]['id_time'], $list_times[$k]['user_id']) === TRUE)
                     { 
                        $list_times[$k][$SUB_TOTAL_TEMP] = 0 ;
                        $list_times[$k][$STATUS_IN_TEMP] = $ERROR_CROSS;//$ERROR_CROSS_WEEK;
                     }
                     else//only over week interval**************************************
                     {
                        $list_times[$k][$SUB_TOTAL_TEMP] = abs((strtotime($dt_range_result['begin']) - strtotime($list_times[$k][$TIME_OUT_INDEX_K]))/(60*60));                  
                        $list_times[$k][$STATUS_IN_TEMP] = $ERROR_CROSS_WEEK;//$ERROR_CROSS_WEEK;   
                     }
                  }
                  ############################################################
                  if( date_is_cross_interval($list_times[$k][$TIME_OUT_INDEX_K], $dt_range_result['begin'], $dt_range_result['end']) )
                  {
                        if($this->model_times->is_date_cross_range($list_times[$k]['id_time'], $list_times[$k]['user_id']) === TRUE)
                        {  
                           $list_times[$k][$STATUS_OUT_TEMP] = $ERROR_CROSS;                  
                           $list_times[$k][$SUB_TOTAL_TEMP] = 0 ;
                        }
                        else//only over week interval**************************************
                        {
                           $list_times[$k][$SUB_TOTAL_TEMP] = abs((strtotime($list_times[$k][$TIME_IN_INDEX_K]) - strtotime($dt_range_result['end']))/(60*60));
                           $list_times[$k][$STATUS_OUT_TEMP] = $ERROR_CROSS_WEEK;                  
                        }
                  }
                  
                  ############################################################
                  if(strcasecmp($list_times[$k]['status_in'],"Observed")==0)
                  {
                     $style_in_link = $STYLE_TIME_OBSERVED; //"time_observed_link";
                  }
                  else if(strcasecmp($list_times[$k][$STATUS_IN_TEMP],$ERROR_CROSS)==0)
                  {
                     $style_in_link = $STYLE_FUNCTION_CROSS_INCOHERENT;
                  }
                  else if(strcasecmp($list_times[$k][$STATUS_IN_TEMP],$ERROR_CROSS_WEEK)==0)
                  {
                     $style_in_link = $STYLE_FUNCTION_CROSS_WEEK;                    
                     $title_in = call_user_func('get_date_literal_'.$this->config->item('language'), $time_in_k_backup);                      
                     $time_in_k_backup = $dt_range_result['begin'];                      
                     $tag_begin_interval = '<< ';
                  }
                  else if(strcasecmp($list_times[$k]['status_in'],"Corrected")==0)
                  {                     
                     $style_in_link = $STYLE_TIME_CORRECTED;
                  }
                  else
                  {
                     $style_in_link = $STYLE_FUNCTION_LINK;
                  } 
                  ############################################################
                  if(strcasecmp($list_times[$k]['status_out'],"Observed")==0)
                  {
                     $style_out_link = $STYLE_TIME_OBSERVED;                   
                  }
                  else if(strcasecmp($list_times[$k][$STATUS_OUT_TEMP],$ERROR_CROSS)==0)
                  {
                     $style_out_link = $STYLE_FUNCTION_CROSS_INCOHERENT;
                  }
                  else if(strcasecmp($list_times[$k][$STATUS_OUT_TEMP],$ERROR_CROSS_WEEK)==0)
                  {
                     $style_out_link = $STYLE_FUNCTION_CROSS_WEEK;
                     $title_out =  $time_out_k_backup; 
                     
                     if(strcasecmp($list_times[$k]['id_time'], $row_time_last['id_time'])==0)
                     {
                        //$diff_hours_week =
                        
                        $time_out_k_backup = $current_date." ".$current_time;                        
                        $diff_hours_week = abs((strtotime($list_times[$k][$TIME_IN_INDEX_K]) - strtotime( $time_out_k_backup ))/(60*60));
                     }
                     else
                     {
                        $time_out_k_backup = $dt_range_result['end'];
                     }
                     $tag_end_interval = ' >>';                      
                  }
                  //else if(strcasecmp($list_times[$k]['status_out'],$this->model_times->CORRECTED)==0)
                  else if(strcasecmp($list_times[$k]['status_out'],"Corrected")==0)
                  {                     
                     $style_out_link = $STYLE_TIME_CORRECTED;
                  }
                  else
                  {
                     $style_out_link = $STYLE_FUNCTION_LINK;
                  }
                  //echo "<br>".$list_times[$k]['status_out'];

                  $row_time = "<div id='row_id_".$k."' class='clear_both row_time'>";
                  $row_time .= "<div class='enqueue_by_right column2_in_manager'>";
                  $row_time .= "<a ".$url_edit_in_link." title='".$title_in."' class='".$style_in_link."'>".$tag_begin_interval.call_user_func('get_date_literal_'.$this->config->item('language'), $time_in_k_backup)."</a>";
                     
                  
                  $row_time .= "</div>";
                  
                  $row_time .= "<div class='enqueue_by_right column3_out_manager'>";
                  
                  $row_time .= "<a ".$url_edit_out_link." title='".$title_out."' class='".$style_out_link."'>".call_user_func('get_date_literal_'.$this->config->item('language'), $time_out_k_backup).$tag_end_interval."</a>";
                  
                  $row_time .= "</div>";
                  $row_time .= "<div class='enqueue_by_right column4_sub_total_manager'>".number_format($diff_hours_week, 2, '.', '')."</div>";
                  

                  $row_time .= "<div class='enqueue_by_right column_delete_manager'>";                  
                  
                  $row_time .= "<a class='delete_time' id='".$k."'";
                  $row_time .= "onclick=\"var is_deleted = confirm ('".$view_labels['msg_delete_these_insurance']."'); if(!is_deleted){return false;}\"";
                  
                  $row_time .= " href='".site_url("times/delete_time/?id_time=".$list_times[$k]['id_time'])."&
                       redirect=".urlencode("times/manager_times/?date_begin=".$date_range_result['date_begin']."&date_end=".$date_range_result['date_end'])."'>";
                  $row_time .= "<div class='icon_delete_time'></div>";
                  $row_time .= "</a>";
                  
                  $row_time .= "</div>";
                  
                  //$row_time .= "<div class='enqueue_by_right column5_sub_total_gral_manager'>".number_format($diff_hours_gral, 2, '.', '')."</div>";
                  $row_time .= "</div>";

                  $row_times_per_week .= $row_time;
                  $total_hours += $diff_hours_week;
                
                  $k++;
            }
            
            
        
            
            if( $exists_row_time )
            {
               $row_week_begin = "<div class='clear_both week_begin bg_week_header'>
                            <div class='enqueue_by_right width_header_week_box'>".$view_labels['week']." (".$week_j."), de 
                               ".call_user_func('get_date_literal_'.$this->config->item('language'), $interval_week_dt['begin'])."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
                               ".call_user_func('get_date_literal_'.$this->config->item('language'), $interval_week_dt['end'])."
                            </div>                               
                          </div>";
               
               $str_header_week = "<div class='clear_both week_box'>";//BEGIN BOX WEEK
               $str_header_week .= $row_week_begin;
               $str_header_week .= "<div class='clear_both times_header'>";
               $str_header_week .= "<div class='enqueue_by_right column_manager column2_in_manager'>".$view_labels['in']."</div>";
               $str_header_week .= "<div class='enqueue_by_right column_manager column3_out_manager'>".$view_labels['out']."</div>";
               $str_header_week .= "<div class='enqueue_by_right column_manager column4_sub_total_manager'>".$view_labels['sub_total']."</div>";
               $str_header_week .= "<div class='enqueue_by_right column_manager column_delete_manager'></div>";
               //$str_header_week .= "<div class='enqueue_by_right column_manager column5_sub_total_gral_manager'>Sub-Total Hrs.</div>";
               $str_header_week .= "</div>";

               $str_output .= $str_header_week;               
               $str_output .= $row_times_per_week;
               
               $row_week_end = "<div class='clear_both week_end  times_header'>";
               $row_week_end .= "<div class='enqueue_by_right column2_in_manager'></div>";
               $row_week_end .= "<div class='enqueue_by_right column3_out_manager'>".$view_labels['legend_total_hours']."</div>";               
               $row_week_end .= "<div class='enqueue_by_right column4_sub_total_manager'>". number_format($total_hours, 2, '.', '')."</div>";
					$row_week_end .= "<div class='enqueue_by_right column_delete_manager'></div>";
               //$row_week_end .= "<div class='enqueue_by_right column5_sub_total_gral_manager bg_week_header'></div>";
               $row_week_end .= "</div>";
               $str_output .= $row_week_end;
					
					$str_output .= "</div>";//BOX END WEEK
            }
            
            $date_time_begin =  strtotime('+7 days', $date_time_begin );               
         }         
                  
         $str_output .= "</filedset>";//END USER
         $str_output .= "</div>";//END USER
      }
      
      $view_data['str_output'] = $str_output;
      
      
      //$view_data["my_messages"] = $my_messages;
      
      $view_data["array_messages"] = $array_messages;
      
      
      $this->load->view('template/header', $header_data);
      $this->load->view('times/manager_times', $view_data);
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function edit_time_gral()
   {
      $my_messages="";
      
      
      if ( !$this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
      
      $this->load->helper(array('form','url'));            
            
      $user_id = $this->session->userdata('user_id');
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_edit_time_gral_view_labels');
      
      $this->load->library('form_validation');

      if(isset($_GET['id_time']))
      {
         $id_time = $_GET['id_time'];
         $record = $_GET['record'];
            
         $redirect = $_GET['redirect'];
      }
      else
      {
         $id_time = $_POST['id_time'];
         $record = $_POST['record'];
         
         $redirect = $_POST['redirect'];
      }
      
      $current_date_time = $this->model_template->get_system_time();      
      
      if(isset($_POST['save_updates']))
      {  
         $date_time_target = $_POST['date']." ".$_POST['hour'].':'.$_POST['minute'].':'.$_POST['second'];
         
         if( strtotime($date_time_target) <= strtotime($current_date_time) )
         {
            $is_ccross = $this->model_times->is_cannot_save( $id_time, $user_id, $date_time_target, $_POST['record']);
            if( $is_ccross )
            {
               $my_messages .= get_message_information($this->lang->line('coco_msg_times_crossed'));
            }
            else
            {  
               $array_conditions['id_time']= $id_time;
               if(strcasecmp($_POST['record'],"in") == 0)
               {
                  $date_time['time_in']= $date_time_target;
                  $date_time['status_in'] = $_POST['status'];

                  $this->model_times->update_time($date_time, $array_conditions);
               }
               else if(strcasecmp($_POST['record'],"out") == 0)
               {
                  $date_time['time_out']= $date_time_target;
                  $date_time['status_out'] = $_POST['status'];
                  $this->model_times->update_time($date_time, $array_conditions);
               }
               redirect(site_url()."/times/manager_times?".urldecode($redirect));
            }
         }
         else
         {
            $my_messages .= get_message_information($this->lang->line('coco_msg_times_less_than_now'));
         }
      }            
      $time_recovery = $this->model_times->get_time($id_time);

      list($time['date_in'], $time_in) = explode(" ", $time_recovery['time_in']);      
      list($time['hour_in'],$time['minute_in'], $time['second_in']) = explode(":", $time_in);
            
      $time['status_in'] = $time_recovery['status_in'];
      $time['status_out'] = $time_recovery['status_out'];
      
      list($time['date_out'], $time_out) = explode(" ", $time_recovery['time_out']);
      list($time['hour_out'],$time['minute_out'], $time['second_out']) = explode(":", $time_out);
      $time['id_time'] = $time_recovery['id_time'];

      $time['record'] = $record;
      
      $view_data['redirect'] = $redirect;      
      $view_data['time'] = $time;
    
      $view_data['week_interval'] = get_week_interval_arround_date($time_recovery['time_in']);
      $view_data['list_times_status'] = $this->model_times->get_list_times_status();

      $header_data['my_messages'] = $my_messages;
      $this->load->view('template/header', $header_data);

      $this->load->view('times/edit_time_gral', $view_data);
      $this->load->view('template/footer', $view_data);
   }
      
   #############################################################################
   public function delete_time()
   {
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
         
      $user_id = $this->session->userdata('user_id');      
      $current_time = $this->model_template->get_system_time();
      
      $is_time_in = $this->model_times->is_time_in($user_id);
      
      if(isset($_GET['id_time']))
      {
         $this->model_times->delete_time($_GET['id_time']);         
         $this->session->set_flashdata('my_messages',$this->lang->line('coco_msg_times_was_delete_success'));
      }
      
      redirect( site_url(urldecode($_GET['redirect']) ));
   }
   
   #############################################################################
   public function add_time_record()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);
         
      $user_id = $this->session->userdata('user_id');
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      
      $my_messages="".$this->session->flashdata('my_messages');
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_add_time_record_view_labels');
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      $current_date_time = $this->model_template->get_system_time();
      list($current_date, $current_time) = explode(" ", $current_date_time);      
      
      if(isset($_GET['week']))
      {
         $week_number = $_GET['week'];
         $year_of_week = $_GET['year'];         
      }
      else if(isset($_POST['week_number']))
      {
         $week_number = $_POST['week_number'];
         $year_of_week = $_POST['year_of_week'];
      }
      
      
      
      
      $week_interval = get_week_interval_dates($week_number, $year_of_week);      
      $list_times = $this->model_times->get_list_times($week_interval['begin'], $week_interval['end'], $user_id);
      
      
      foreach($list_times as $key => $value)
      {
         $date_hour_in = explode(" ", $value['time_in']);
         $date_hour_out = explode(" ", $value['time_out']);
         $list_times[$key]['time_in_literal'] = call_user_func('get_date_literal_'.$this->config->item('language'), $date_hour_in[0])." - ". $date_hour_in[1];
         $list_times[$key]['time_out_literal'] = call_user_func('get_date_literal_'.$this->config->item('language'), $date_hour_out[0])." - ". $date_hour_out[1];
      }
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      
      $week_interval_literal['begin'] = call_user_func('get_date_literal_'.$this->config->item('language'), $week_interval['begin']);
      $week_interval_literal['end'] = call_user_func('get_date_literal_'.$this->config->item('language'), $week_interval['end']);
      
      
      if(isset($_POST['add_time_record']))
      {  
         $time_record_tentative['user_id'] = $user_id;
         //column in
         $time_record_tentative['time_in'] = $_POST['date_in']." ".$_POST['hour_in'].":".$_POST['minute_in'].":".$_POST['second_in'];
         $time_record_tentative['status_in'] = "Valid";

         //column out
         $time_record_tentative['time_out'] = $_POST['date_out']." ".$_POST['hour_out'].":".$_POST['minute_out'].":".$_POST['second_out'];
         $time_record_tentative['status_out'] = "Valid";
         
         if( ! $this->model_times->is_date_record_cross_range($time_record_tentative, $user_id)
             AND
             strtotime($time_record_tentative['time_in']) < strtotime($time_record_tentative['time_out']) 
           ) 
         {
            
            if( strtotime($time_record_tentative['time_in']) < strtotime($current_date_time) AND 
                strtotime($time_record_tentative['time_out']) <= strtotime($current_date_time))
            {  
               $this->model_times->add_time_record($time_record_tentative);

               $this->session->set_flashdata('my_messages', $view_labels['msg_added_successfully']);
               
               $params = "week=".$week_number."&year=".$year_of_week;
               redirect(site_url()."/times/home?".$params);
            }
            else
            {
               $my_messages .= get_message_information( $this->lang->line('coco_msg_times_less_than_now') );
            }
         }
         else
         {
            $my_messages .= get_message_information( $this->lang->line('coco_msg_times_crossed') );
         }
      }
      
      
      $view_data['list_times'] = $list_times;
      $view_data['week_interval_literal'] = "(".$week_number.") ".$week_interval_literal['begin'].' -- '.$week_interval_literal['end'];
      
      $current_date_time = $this->model_template->get_system_time();
      
      list($current_date, $current_time) = explode(" ", $current_date_time);
      
      if( ! isset($_POST['hour_in']))
      {
         list($view_data['hour_in'], $view_data['minute_in'], $view_data['second_in']) = explode(":", $current_time);
         list($view_data['hour_out'], $view_data['minute_out'], $view_data['second_out']) = explode(":", $current_time);
         
         $view_data['date_in'] = $week_interval['begin'];
         $view_data['date_out'] = $week_interval['end'];
      }
      else
      {
         if(isset($_POST['date_in'])){ $view_data['date_in'] = $_POST['date_in']; }
         if(isset($_POST['date_out'])){ $view_data['date_out'] = $_POST['date_out']; }
         
         if(isset($_POST['hour_in'])){ $view_data['hour_in'] = $_POST['hour_in']; }
         if(isset($_POST['minute_in'])){ $view_data['minute_in'] = $_POST['minute_in']; }
         if(isset($_POST['second_in'])){ $view_data['second_in'] = $_POST['second_in']; }
         
         if(isset($_POST['hour_out'])){ $view_data['hour_out'] = $_POST['hour_out']; }
         if(isset($_POST['minute_out'])){ $view_data['minute_out'] = $_POST['minute_out']; }
         if(isset($_POST['second_out'])){ $view_data['second_out'] = $_POST['second_out']; }         
      }
      
      $view_data['my_messages'] = $my_messages;
      $view_data['week_number'] = $week_number;
      $view_data['year_of_week'] = $year_of_week;
      
      $this->load->view('template/header', $header_data);
      $this->load->view('times/add_time_record',$view_data);
      $this->load->view('template/footer');      
   }
   #############################################################################
   public function add_time()
   {
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
         
      $user_id = $this->session->userdata('user_id');      
      $current_time = $this->model_template->get_system_time();
      $status = "Valid";
      $is_time_in = $this->model_times->is_time_in($user_id);
      if($is_time_in['in'])
      {
         //Add row
         $time_record['user_id'] = $user_id;
         $time_record['time_in'] = $current_time;
         $time_record['status_in'] = $status;
         $this->model_times->add_time($time_record);
      }
      else
      {
         //Edit row
         $condition['id_time'] = $is_time_in['id_time'];
         $condition['user_id'] = $user_id;
         $time_record['time_out'] = $current_time;
         $time_record['status_out'] = $status;
         $this->model_times->update_time($time_record, $condition);
      }      
      redirect(site_url()."times/home");
   }
   #############################################################################
   public function edit_time()
   {
      $my_messages="";
      $array_messages = null;
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }      
      $header_data = $this->tank_auth->get_header_data();      
      $this->tank_auth->has_not_privilege($header_data);         
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
       
      $current_date_time = $this->model_template->get_system_time();      
      list($current_date, $current_time ) = explode(" ", $current_date_time);      
      
      $this->load->helper(array('form','url'));            
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_edit_time_view_labels');
      
      $user_id = $this->session->userdata('user_id');
      
      $this->load->library('form_validation');

      
      if(isset($_GET['id_time']))
      {
         $id_time = $_GET['id_time'];
         $record = $_GET['record'];
         
         $week_interval['begin']= $_GET['begin_week'];
         $week_interval['end']= $_GET['end_week'];
         
         $redirect = $_GET['redirect'];
      }
      else if(isset($_POST['begin_week']))
      {
         $week_interval['begin']= $_POST['begin_week'];
         $week_interval['end']= $_POST['end_week'];
         
         $id_time = $_POST['id_time'];
         $record = $_POST['record'];
         
         $redirect = $_POST['redirect'];         
      }
      #-------------------------------------------------------------------------
      if(isset($_REQUEST['delete_id_time']) AND isset($user_id))
      {
         $this->model_times->delete_time_entry_by($_REQUEST['delete_id_time'], $user_id, $_REQUEST['record']);
         redirect(site_url(urldecode($redirect)));
         
      }
      #-------------------------------------------------------------------------      
      if(isset($_POST['save_updates']))
      {  
         $date_time_target = $_POST['date']." ".$_POST['hour'].':'.$_POST['minute'].':'.$_POST['second'];
         
         
         $dt_begin_interval=$_POST['begin_week'].' 00:00:00';
         $dt_end_interval=$_POST['end_week'].' 23:59:23';
         
         
         if( date_is_cross_interval ($date_time_target, $dt_begin_interval, $dt_end_interval)===TRUE)
         {
            ob_start();
            printf($this->lang->line('coco_msg_times_date_not_is_into_interval'), $date_time_target, $dt_begin_interval, $dt_end_interval);
            $str_message = ob_get_clean();
            $array_messages[] = $str_message;
         }
         else
         {
            ###################################################################
            
            if( strtotime($date_time_target) <= strtotime($current_date_time) )
            {  
               $is_cannot_save = $this->model_times->is_cannot_save( $id_time, $user_id, $date_time_target, $_POST['record']);

               if( $is_cannot_save)
               {
                  //$my_messages .= get_message_information($this->lang->line('coco_msg_times_crossed'));
                  $array_messages[] = $this->lang->line('coco_msg_times_crossed');
               }
               else
               {  
                  $array_conditions['id_time']= $id_time;                  
                  $time['time_'.$_POST['record']]= $date_time_target;
                  
                  $time_before = $this->model_times->get_time($id_time);
                  
                  if( strtotime($time['time_'.$_POST['record']]) != strtotime($time_before['time_'.$_POST['record']]) )
                  {
                     $time['status_'.$_POST['record']]= 'Corrected';
                                          
                     $this->send_changed_time_by_email($user_id, $time_before['time_'.$_POST['record']], $time['time_'.$_POST['record']], $_POST['record']);
                  }
                  $this->model_times->update_time($time, $array_conditions);
                  
                  redirect(site_url(urldecode($redirect)));
               }
            }
            else
            {
               //$my_messages .= get_message_information($this->lang->line('coco_msg_times_less_than_now') );
               $array_messages[] = $this->lang->line('coco_msg_times_less_than_now');
            }
         }
         /*$url = site_url("times/home");
         redirect($url);*/
      }
      /*else
      {*/
      
      
      
      $time_recovery = $this->model_times->get_time($id_time);
      
      
      
      list($time['date_in'], $time_in) = explode(" ", $time_recovery['time_in']);
      list($time['hour_in'],$time['minute_in'], $time['second_in']) = explode(":", $time_in);
      list($time['date_out'], $time_out) = explode(" ", $time_recovery['time_out']);
      list($time['hour_out'],$time['minute_out'], $time['second_out']) = explode(":", $time_out);
      $time['id_time'] = $time_recovery['id_time'];
      $time['record'] = $record;
      
      $previous_time = $this->model_times->get_previous_time($time_recovery['id_time'], $user_id, $record);      
      if(isset($previous_time) AND isset($previous_time['time']))
      {
         $view_data['previous_time_lit'] = call_user_func('get_date_literal_'.$this->config->item('language'), $previous_time['time']);
      }
      
      $view_data['time'] = $time;
      $view_data['week_interval'] = $week_interval;
      $view_data['redirect'] = $redirect;
      $header_data['my_messages']=$my_messages;
      $header_data['array_messages']=$array_messages;
      
      $this->load->view('template/header', $header_data);
      $this->load->view('times/edit_time', $view_data);
      $this->load->view('template/footer', $view_data);
      /*}*/
   }
   #############################################################################
   function send_changed_time_by_email($user_id, $date_time_old, $date_time_new, $in_out=null)
   {
      $view_data = $this->tank_auth->get_header_data();                  
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_send_changed_time_by_email_view_labels');      
      #-------------------------------------------------------------------------      
      
      
      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%   
      $week_number = get_week_number($date_time_new);
      $year = date("Y");

      if(isset($_GET['week']))
      {
         $week_number = $_GET['week'];
         $year = $_GET['year'];
      }

      $interval_current = get_week_interval_dates($week_number, $year); 
      $view_data['date_begin'] = $interval_current['begin'];
      $view_data['date_end'] = $interval_current['end'];

      #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%   
      
      $view_data['company'] = $company = $this->users->get_company_by_id($view_data['company_id']);      
      //$list_ceo_members = $this->users->get_list_users_by_role_type("CEO", $view_data['company_id']);
      $list_ceo_members = $this->users->get_list_users_by_company($view_data['company_id']);
      
				
      $to="";
      $name_to="";
      $str_comment="";
      for($i=0; $i<count($list_ceo_members); $i++)
      {
         if(strcasecmp($to,"")!=0)
         {
            $to .=",";
            $name_to .=", ";
         }
         $to .= $list_ceo_members[$i]['email'];
         $name_to .= $list_ceo_members[$i]['name']." ".$list_ceo_members[$i]['last_name'];
      }
      
      $view_data['name_to'] = $name_to;
      
      $view_data['time_old_literal'] = call_user_func('get_date_literal_'.$this->config->item('language'), $date_time_old);
      $view_data['time_new_literal'] = call_user_func('get_date_literal_'.$this->config->item('language'), $date_time_new);
      
      ob_start();      
      $this->load->view('times/send_changed_time_by_email', $view_data);
      $str_comment = ob_get_clean();     
      
      #-------------------------------------------------------------------------         
      $from="info@onebolivia.com";       
      $part_subject = strtoupper("[".$company['name']."] ".$view_labels['change_time']);      
      $subject = $part_subject; //." ".$view_labels['by'].": ".$member['name']." ".$member['last_name'];            
      $this->template->multi_attach_mail($to, $from, $subject, $str_comment);
   }
   #############################################################################
   /**
    * columns with postfix TEMP arrays are temporary
      defined only in this function
    */
   public function get_list_last_present_users($company_id, $datetime_begin, $datetime_end, $current_ts)
   {
      $TIME_IN_INDEX = 'time_in';
      $TIME_OUT_INDEX = 'time_out';
      $KEY_SUB_TOTAL_TEMP = 'sub_total';
      
      $list_users = $this->model_times->get_list_last_present_users($company_id, $datetime_begin, $datetime_end);
      
      for($j=0; $j<count($list_users); $j++)
      {
         $list_times = $this->model_times->get_list_times($datetime_begin, 
                                                          $datetime_end,
                                                          $list_users[$j]['user_id'],
                                                          $company_id);
         
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

               if( date_is_cross_interval($list_times[$key][$TIME_IN_INDEX], $datetime_begin, $datetime_end))
               {
                  if($this->model_times->is_date_cross_range( $list_times[$key]['id_time'], $list_users[$j]['user_id']) === TRUE)
                  { 
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = 0 ;
                  }
                  else//only over week interval**************************************
                  {
                     $list_times[$i][$KEY_SUB_TOTAL_TEMP] = abs((strtotime($datetime_begin) - strtotime($list_times[$i][$TIME_OUT_INDEX]))/(3600)); 
                  }
               }
               else if( date_is_cross_interval($list_times[$key][$TIME_OUT_INDEX], $datetime_begin, $datetime_end) )
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
      }
      
      return $list_users;
   }
   ############################################################################################
   public function home()
   {  
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); }
      
      $view_data = $header_data = $this->tank_auth->get_header_data();      
      $user_id = $view_data["user_id"];
      
      $this->tank_auth->has_not_privilege($header_data);
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_times_home_view_labels');
      
      $ERROR_CROSS = 'error_cross';
      $ERROR_CROSS_WEEK = 'error_cross_week';
      $STATUS_IN_TEMP = 'status_in_temp';
      $STATUS_OUT_TEMP = 'status_out_temp';
      
      $TIME_IN_INDEX = 'time_in';
      $TIME_OUT_INDEX = 'time_out';
                  
      $SUB_TOTAL_TEMP = 'sub_total_temp';
      
      $my_messages = "".$this->session->flashdata('my_messages');
      
      //Get list of past weeks
      
      $current_ts = $this->model_template->get_system_time();      
      
      list($current_date, $current_time) = explode(" ", $current_ts);
      $quantity_past_weeks = 20;
      //echo "<br>CURRENT DATE = ".$current_date;
      $past_weeks = get_list_past_weeks($current_date, $quantity_past_weeks, 'Y-m-d');
      
      $list_past_weeks = array();

      $week_number = get_week_number($current_date);      
      $year = date("Y", strtotime( $current_date));
      if($week_number==1 AND date("m", strtotime($current_date))!=1)
      {
         $year = $year+1;
      }
      
      
      $current_dt_weeks_before = $current_date;
      
      for($i=0;$i<count($past_weeks); $i++)
      {
         $begin_date = call_user_func('get_date_literal_'.$view_data['language'], $past_weeks[$i]['begin']);
         $end_date = call_user_func('get_date_literal_'.$view_data['language'], $past_weeks[$i]['end']);
         
         
         $list_past_weeks[$i]['begin'] = $past_weeks[$i]['begin'];
         $list_past_weeks[$i]['end'] = $past_weeks[$i]['end'];
                  
         $week_number_i_weeks = get_week_number( $current_dt_weeks_before );
         
         $current_dt_weeks_before = date("Y-m-d H:i:s", strtotime('-7 days',    strtotime($current_dt_weeks_before) ) );
                           
         $list_past_weeks[$i]['interval'] = "(".($week_number_i_weeks).") ".$begin_date ."&nbsp&nbsp-&nbsp&nbsp".$end_date ;
         
         $list_past_weeks[$i]['week_number'] = $past_weeks[$i]['week_number'];
         $list_past_weeks[$i]['year'] = $past_weeks[$i]['year'];
      }

      
      $view_data['list_past_weeks'] = $list_past_weeks;

      
      if(isset($_GET['week']))
      {
         $week_number = $_GET['week'];
         $year = $_GET['year'];
      }

      $interval = get_week_interval_dates($week_number, $year);
      
      $interval['begin'] = $interval['begin'] .' 00:00:00';
      $interval['end'] = $interval['end'] .' 23:59:59';
      $list_times = $this->model_times->get_list_times($interval['begin'], $interval['end'], $user_id);
      
      $view_data['list_last_present_users'] = $this->get_list_last_present_users($view_data['company_id'], 
                                                                                 $interval['begin'], 
                                                                                 $interval['end'], $current_ts);
            
      if(count($list_times)>0)
      {
         $total_hours_last_row = get_date_diff_hours($current_date." ".$current_time,
                                                  $list_times[count($list_times)-1]['time_in']);
      }
      
      $i=0;
      
      $is_current_week = false;
      //echo "<br>BEGIN_WEEK = ".$interval['begin']." (".get_week_number( $interval['begin']).")  DATE_WEEK = ".date("Y-m-d H:i:s")." (".get_week_number( date("Y-m-d H:i:s")).") ";
      if( get_week_number( $interval['begin']) == get_week_number( date("Y-m-d H:i:s")))
      {
         $is_current_week = true;
      }
      
      foreach($list_times as $key => $value)
      {
            list($date_in, $time_in) = explode(" ", $value[$TIME_IN_INDEX]);            
            list($date_out, $time_out) = explode(" ", $value[$TIME_OUT_INDEX]);
                        
            

            if($is_current_week===true)
            {
               $list_times[$key]['time_in_literal'] = call_user_func('get_day_literal_'.$view_data['language'], $date_in)." - ". $time_in;
               $list_times[$key]['time_out_literal'] = call_user_func('get_day_literal_'.$view_data['language'], $date_out)." - ". $time_out;
            }
            else
            {
               $list_times[$key]['time_in_literal'] = call_user_func('get_date_literal_'.$view_data['language'], $date_in)." - ". $time_in;
               $list_times[$key]['time_out_literal'] = call_user_func('get_date_literal_'.$view_data['language'], $date_out)." - ". $time_out;
            }
            
            
            $list_times[$key][$STATUS_IN_TEMP] = $list_times[$key]['status_in'];
            $list_times[$key][$STATUS_OUT_TEMP] = $list_times[$key]['status_out'];
       
            
            if(isset($list_times[$i]['status_in']) AND strcasecmp($list_times[$i]['status_in'],"Observed") != 0 AND
               isset($list_times[$i]['status_out']) AND strcasecmp($list_times[$i]['status_out'],"Observed") != 0 
              )
            {
               $list_times[$i][$SUB_TOTAL_TEMP] = abs((strtotime($list_times[$i][$TIME_IN_INDEX]) - strtotime($list_times[$i][$TIME_OUT_INDEX]))/(60*60));
            }
            else
            {
               $list_times[$i][$SUB_TOTAL_TEMP] = 0;
            }

            if( date_is_cross_interval($list_times[$key][$TIME_IN_INDEX], $interval['begin'], $interval['end']))
            {
               if($this->model_times->is_date_cross_range( $list_times[$key]['id_time'], $user_id) === TRUE)
               { 
                  $list_times[$key][$STATUS_IN_TEMP] = $ERROR_CROSS;                  
                  $list_times[$i][$SUB_TOTAL_TEMP] = 0 ;
               }
               else//only over week interval**************************************
               {
                  $list_times[$i][$SUB_TOTAL_TEMP] = abs((strtotime($interval['begin']) - strtotime($list_times[$i][$TIME_OUT_INDEX]))/(60*60));                  
                  $list_times[$key][$STATUS_IN_TEMP] = $ERROR_CROSS_WEEK;                  
               }
            }
            else if( date_is_cross_interval($list_times[$key][$TIME_OUT_INDEX], $interval['begin'], $interval['end']) )
            {
               if($this->model_times->is_date_cross_range($list_times[$key]['id_time'], $user_id) === TRUE)
               {  
                  $list_times[$key][$STATUS_OUT_TEMP] = $ERROR_CROSS;                  
                  $list_times[$i][$SUB_TOTAL_TEMP] = 0 ;
               }
               else//only over week interval**************************************
               {
                  $list_times[$i][$SUB_TOTAL_TEMP] = abs((strtotime($list_times[$i][$TIME_IN_INDEX]) - strtotime($interval['end']))/(60*60));                  
                  $list_times[$key][$STATUS_OUT_TEMP] = $ERROR_CROSS_WEEK;                  
               }
            }            
            $i++;
      }

      //Insert current time (last row) into times matrix
      $last_index= count($list_times)-1;
      $is_time_in = $this->model_times->is_time_in($user_id, $interval['begin'], $interval['end']);
      if($is_time_in['in'])
      {
         $last_index = count($list_times);
      }
      else
      {
         $list_times[$last_index]['time_out_literal'] = $view_labels['clock_out']." -> ".$current_time;
         $list_times[$last_index][$STATUS_OUT_TEMP] = "pending";         
         $list_times[$last_index][$SUB_TOTAL_TEMP] =  $total_hours_last_row;         
         $list_times[$last_index]['sub_total'] =  $total_hours_last_row;
      }

      

      ##########################################################################
      $list_weeks_with_error = $this->model_times->get_list_weeks_with_error($user_id);
      
      //print_r($list_weeks_with_error);
      ##########################################################################
      
      $view_data['is_current_week'] = $is_current_week;
      $view_data["list_times"] = $list_times;
      $view_data["list_weeks_with_error"] = $list_weeks_with_error;
      //Total sum from the times matrix
      $key_column="sub_total";
      $key_column_temp="sub_total_temp";
      
      $total_hours = get_total_sum($list_times, $key_column);

      $total_hours_other = get_total_sum($list_times, $key_column_temp);
      
      
      $view_data["year"] = $year;
      $view_data["week_number"] = $week_number;
      
      $view_data["total_hours"] = $total_hours;      
      $view_data["total_hours_other"] = $total_hours_other;
      
      $view_data["my_messages"] = $my_messages;
      
      $view_data["is_in"] = $is_time_in['in'];
      $view_data["current_ts"] = $current_ts;
      $view_data["current_date"] = $current_date;
      
      $this->load->view('template/header', $header_data);
      
      $this->load->view('times/home', $view_data);
      $this->load->view('template/footer', $view_data);
   }
}
?>