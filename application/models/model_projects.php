<?php
class Model_projects extends Model_template
{ 
   function __construct()
   {
       parent::__construct();       
       $this->db->query("SET SESSION time_zone='-4:00'");
   }
   #############################################################################
   function get_list_projects_by($company_id, $status=null, $user_id = null, $limit = null)
   {
      $member_status_select_id = $this->get_id_selectable_by("members", "status", "active");
      
      if(isset($status))
      {
         $status_select_id = $this->get_id_selectable_by("user_objects", "action_status", $status, "PR");
      }
      
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      $type_select_id = $this->get_id_selectable_by("objects", "type","project");      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description,
                
                objects.status_date,
                
                projects.object_id,
                projects.start_date,
                projects.end_date,
                projects.priority_select_id,
                projects.percent_completed,
                
                pri.value_select AS priority,
                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
      $this->db->select('');
   
      $this->db->from('objects');
      $this->db->join('projects',"objects.id_object=projects.object_id");
      
      $this->db->join('selectables pri',"projects.priority_select_id=pri.id");
      $this->db->join('users', "objects.user_id= users.id");      
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");      
      $this->db->join('user_objects', "objects.id_object = user_objects.object_id AND user_objects.user_id = users.id");
                  
      $this->db->where('objects.company_id',$company_id);      
      
      if(isset($status))
      {
         $this->db->where('user_objects.action_status_select_id',$status_select_id);      
         $this->db->where('user_objects.sorter',null,false);      
      }
      
      $this->db->where('objects.type_select_id',$type_select_id);      
      $this->db->where('user_objects.action_status_select_id !=', $action_status_select_id, FALSE);
      
      $this->db->where('user_objects.sorter',null, FALSE);
      
      if(isset($user_id))
      {
         $this->db->where("(objects.id_object,".$user_id.") IN (SELECT object_id, user_id FROM members WHERE status_select_id=".$member_status_select_id.") ", null, FALSE);
      }
      
      $this->db->group_by(array("user_objects.object_id"));      
      $this->db->order_by('user_objects.register_date DESC');
      
      if(isset($limit))
      {         
         $this->db->limit($limit);
      }
      
      $query = $this->db->get(); 
      
   
      
      return $query->result_array();
   }
    #############################################################################
   function get_list_last_objects_from_project($project_id, $limit=100)
   {
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.status_date,
                objects.description,
                selectables.value_select AS type,
                action_st.value_select AS action_status,
                
                CAST(user_objects.register_date AS DATE) AS activity_date,
                CAST(user_objects.register_date AS DATETIME) AS activity_date_time,
                
                user_profiles.user_id AS owner_user_id,
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
            
      $this->db->from('objects');      
      $this->db->join('selectables',"objects.type_select_id=selectables.id");
      $this->db->join('user_objects',"user_objects.object_id=objects.id_object");
      $this->db->join('selectables action_st',"user_objects.action_status_select_id=action_st.id");
      
      $this->db->join('users', "users.id = user_objects.user_id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      $this->db->where('objects.project_id',$project_id);
      //$this->db->where('objects.id_object !=',$project_id);
      $this->db->where('selectables.table_name','objects');
      $this->db->where('selectables.var_name','type');      
      
      $this->db->where('action_st.table_name','user_objects');
      $this->db->where('action_st.var_name','action_status');      
      
      //$this->db->where('selectables.value_select!=',"'project'", FALSE);
            
      $this->db->group_by(array("user_objects.action_status_select_id","user_objects.object_id")); 
      $this->db->group_by("user_objects.sorter",NULL, FALSE);
      $this->db->order_by('activity_date_time DESC, user_objects.id_user_object DESC');
      
      $this->db->limit($limit);      
      $query = $this->db->get();  
      
      return $query->result_array();
   }
   #############################################################################
   function get_statistics($var_name)
   {
      $this->db->select("quantity_uses");
      $this->db->from("statistics");
      $this->db->where("var_name", $var_name);
      
      $query = $this->db->get();
      return $query->row_array();
   }
   #############################################################################
   function update_statistics($data, $conditions)
   {
      $this->db->update("statistics",$data, $conditions);
   }
   #############################################################################
   function get_effective_hours_by_task_action($user_id,  $date_time_in, $date_time_out)
   {
      $this->db->cache_on();

      $this->db->select("times.id_time");  
      $this->db->select("times.time_in, times.time_out");        

      $this->db->select(" ABS(TIMESTAMPDIFF(SECOND, CAST('".$date_time_in."' AS DATETIME), 
                                                    CAST('".$date_time_out."' AS DATETIME)
                                              )/ 3600)
                            AS effective_hours",false);        

      $this->db->from("times");
      $this->db->where("CAST('".$date_time_in."' AS DATETIME) BETWEEN CAST(times.time_in AS DATETIME) AND CAST(IF(times.status_out IS NULL, now(), times.time_out) AS DATETIME)
                          AND 
                          CAST('".$date_time_out."' AS DATETIME) BETWEEN CAST(times.time_in AS DATETIME) AND CAST(IF(times.status_out IS NULL, now(), times.time_out) AS DATETIME)
                           ","",false
                        );
      $this->db->where("times.user_id",$user_id);
            
      $query = $this->db->get();
      
      $row_both = $query->row_array();
      $this->db->cache_off();      
       
      if( ! ( ! empty($row_both) AND $row_both['effective_hours']>0) )
      {
         $this->db->cache_on();         
         $this->db->select("times.id_time");  
         $this->db->select("times.time_in");        
         $this->db->select("ABS(TIMESTAMPDIFF(SECOND, CAST('".$date_time_in."' AS DATETIME), IF(times.status_out IS NULL, now(), times.time_out))/ 3600) AS effective_hours",false);
         $this->db->from("times");
         $this->db->where("CAST('".$date_time_in."' AS DATETIME) BETWEEN CAST(times.time_in AS DATETIME) AND CAST(IF(times.status_out IS NULL, now(), times.time_out) AS DATETIME)
                              ","",false
                           );
         $this->db->where("times.user_id",$user_id);
         $query = $this->db->get();       
         
     
         
         $row_in = $query->row_array();         
         $this->db->cache_off();      
         #---------------------------------------------------------------------------------
         $this->db->cache_on();         
         $this->db->select("times.id_time");  
         $this->db->select("times.time_out");        
         $this->db->select("ABS(TIMESTAMPDIFF(SECOND, CAST('".$date_time_out."' AS DATETIME), times.time_in)/ 3600) AS effective_hours",false);
         $this->db->from("times");
         $this->db->where("CAST('".$date_time_out."' AS DATETIME) BETWEEN CAST(times.time_in AS DATETIME) AND CAST(IF(times.status_out IS NULL, now(), times.time_out) AS DATETIME)
                              ","",false
                         );
         $this->db->where("times.user_id",$user_id);
         $query = $this->db->get();       
         
       
         
         $row_out = $query->row_array();         
         $this->db->cache_off();  
         
         $row_both['effective_hours'] = 0;
         
         if(isset($row_in['effective_hours']))
         {
            $row_both['effective_hours'] += $row_in['effective_hours'];         
         }
         if(isset($row_out['effective_hours']))
         {
            //$row_both['effective_hours'] += $row_out['effective_hours'];
         }
         
         
         
         if( isset($row_in['id_time']) AND isset($row_out['id_time']) AND strcasecmp( $row_in['id_time'], $row_out['id_time'] ) ==0)
         {
            $row_both['id_time'] = $row_in['id_time'];
         }
         else
         {
            $row_both['id_time'] = isset($row_in['id_time']) ? $row_in['id_time'] : "";
            $row_both['id_time'] .= ",";
            $row_both['id_time'] .= isset($row_out['id_time']) ? $row_out['id_time'] : "";
         }
         
         $row_both['time_in'] = isset($row_in['time_in']) ? $row_in['time_in']:"";
         $row_both['time_out'] = isset($row_out['time_out']) ? $row_out['time_out'] : "";
      }
      
       
      //$row['sql'] = $this->db->last_query();
      return $row_both;
   }  
  
   #############################################################################
   function get_list_user_objects_by_task($task_id)
   {
      
      $action_status_paused_id = $this->get_id_selectable_by("user_objects", "action_status", "paused", "TA");      
      $action_status_in_process_id = $this->get_id_selectable_by("user_objects", "action_status", "in_process", "TA");      
      $action_status_completed_id = $this->get_id_selectable_by("user_objects", "action_status", "completed", "TA");      
      
      $this->db->select("user_objects.id_user_object,
                         user_objects.object_id,
                         user_objects.register_date,                         
                         user_objects.user_id");
      
      $this->db->select("user_profiles.name AS user_name,
                         user_profiles.last_name AS user_last_name");
      
      $this->db->select("objects.description");
      $this->db->select("selectables.value_select AS action");
      
      $this->db->from("tasks");
      $this->db->join("objects", "objects.id_object=tasks.object_id");
      $this->db->join("user_objects", "user_objects.object_id=objects.id_object");
      $this->db->join("user_profiles", "user_profiles.user_id=user_objects.user_id");
      $this->db->join("selectables", "user_objects.action_status_select_id=selectables.id");
   
      
      $this->db->where("tasks.object_id", $task_id);
      $this->db->where(" (user_objects.action_status_select_id=".$action_status_paused_id." OR 
                          user_objects.action_status_select_id=".$action_status_in_process_id." OR 
                          user_objects.action_status_select_id=".$action_status_completed_id.") ","", false);
      
      $this->db->order_by("user_objects.register_date ASC");      
      $query    = $this->db->get();
      
      return $query->result_array();
   }
   #############################################################################
   function get_list_user_objects_for_tasks_by_project($project_id)
   {  
      $action_status_paused_id = $this->get_id_selectable_by("user_objects", "action_status", "paused", "TA");      
      $action_status_in_process_id = $this->get_id_selectable_by("user_objects", "action_status", "in_process", "TA");      
      $action_status_completed_id = $this->get_id_selectable_by("user_objects", "action_status", "completed", "TA");      
      
      $this->db->select("user_objects.id_user_object,
                         user_objects.object_id,
                         user_objects.register_date,                         
                         user_objects.user_id");
      
      $this->db->select("objects.description");
      $this->db->select("selectables.value_select");
      
      $this->db->from("tasks");
      $this->db->join("objects", "objects.id_object=tasks.object_id");
      $this->db->join("user_objects", "user_objects.object_id=objects.id_object");
      $this->db->join("selectables", "user_objects.action_status_select_id=selectables.id");
      
      $this->db->where("objects.project_id", $project_id);
      $this->db->where(" (user_objects.action_status_select_id=".$action_status_paused_id." OR 
                          user_objects.action_status_select_id=".$action_status_in_process_id." OR 
                          user_objects.action_status_select_id=".$action_status_completed_id.") ","", false);
      
      $this->db->order_by("user_objects.register_date ASC");      
      $query = $this->db->get();
   
      return $query->result_array();
   }
   #############################################################################
   function get_user_object($id_user_object)
   {
      $this->db->where("id_user_object", $id_user_object);
      $query = $this->db->get("user_objects");
      return $query->row_array();
   }
   
   #############################################################################
   function get_list_user_tasks_actived_by_project($project_id)
   {
      $action_status_in_process_id = $this->get_id_selectable_by("user_objects", "action_status", "in_process", "TA");
      
      $this->db->select("project_objects.name AS project");
      
      $this->db->select("
                        objects.id_object,                         
                        objects.parent_id,                         
                        objects.project_id,                         
                        objects.name,             
                        objects.description");
      
      $this->db->select("user_objects.object_id, 
                         user_objects.user_id, 
                         user_objects.user_role_id");
      
      $this->db->select("user_profiles.name AS worker_name,
                         user_profiles.last_name AS worker_last_name,
                        ");
      
      $this->db->select("user_objects.id_user_object,
                        user_objects.register_date,
                        user_objects.action_status_select_id,
                        selectables.value_select");
      
      $this->db->from("objects");
      $this->db->join("user_objects", "objects.id_object = user_objects.object_id ");
      $this->db->join("selectables", "selectables.id = user_objects.action_status_select_id ");
      $this->db->join("user_profiles", "user_profiles.user_id = user_objects.user_id ");
      
      $this->db->join("objects project_objects", "project_objects.id_object=objects.project_id");
      
      
      $this->db->where("objects.project_id",$project_id);
      $this->db->where("user_objects.sorter",null);
      $this->db->where("user_objects.action_status_select_id", $action_status_in_process_id);
      
      $query = $this->db->get();      
      return $query->result_array();
      

      
   }
   #############################################################################
   function get_last_task_by_project($project_id)
   {
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $type_comment_id = $this->get_id_selectable_by("objects", "type", "comment");
      $type_task_id = $this->get_id_selectable_by("objects", "type", "task");
      
      $this->db->select("project_objects.name AS project");
      
      $this->db->select("
                        objects.id_object,                         
                        objects.parent_id,                         
                        objects.project_id,                         
                        objects.name,             
                        objects.description");
      
      $this->db->select("user_objects.object_id, user_objects.user_id, user_objects.user_role_id");
      
      $this->db->select("user_objects.id_user_object,
                        user_objects.register_date,
                        user_objects.action_status_select_id,
                        selectables.value_select");
      
      $this->db->from("objects");
      $this->db->join("user_objects", "objects.id_object = user_objects.object_id ");
      $this->db->join("selectables", "selectables.id = user_objects.action_status_select_id ");
      
      $this->db->join("objects project_objects", "project_objects.id_object=objects.project_id");
      
      $this->db->where("objects.project_id",$project_id);      
      $this->db->where("user_objects.sorter",null);
      $this->db->where("user_objects.action_status_select_id!='".$action_status_moved_to_trash_id."'",null,false);      
      $this->db->where("(objects.type_select_id='".$type_comment_id."' OR objects.type_select_id='".$type_task_id."')",null,false);      
      $this->db->order_by("user_objects.register_date DESC");      
      $this->db->limit(1);      
      $query = $this->db->get();      
            
      return $query->row_array();          
   }
   #############################################################################
   function get_last_task_by_project_user($project_id, $user_id)
   {
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $type_comment_id = $this->get_id_selectable_by("objects", "type", "comment");
      $type_task_id = $this->get_id_selectable_by("objects", "type", "task");
      
      $this->db->select("project_objects.name AS project");
      
      $this->db->select("
                        objects.id_object,                         
                        objects.parent_id,                         
                        objects.project_id,                         
                        objects.name,             
                        objects.description");
      
      $this->db->select("user_objects.object_id, user_objects.user_id, user_objects.user_role_id");
      
      $this->db->select("user_objects.id_user_object,
                        user_objects.register_date,
                        user_objects.action_status_select_id,
                        selectables.value_select");
      
      $this->db->from("objects");
      $this->db->join("user_objects", "objects.id_object = user_objects.object_id ");
      $this->db->join("selectables", "selectables.id = user_objects.action_status_select_id ");
      
      $this->db->join("objects project_objects", "project_objects.id_object=objects.project_id");
            
      $this->db->where("user_objects.user_id",$user_id);
      $this->db->where("objects.project_id",$project_id);
      
      $this->db->where("user_objects.sorter",null);
      $this->db->where("user_objects.action_status_select_id!='".$action_status_moved_to_trash_id."'",null,false);
      
      $this->db->where("(objects.type_select_id='".$type_comment_id."' OR objects.type_select_id='".$type_task_id."')",null,false);
      
      $this->db->order_by("user_objects.register_date DESC");
      
      $this->db->limit(1);
      
      $query = $this->db->get();      
      
      return $query->row_array();          
   }
   #############################################################################
   function get_task_active_by_user($user_id)
   {
      $action_status_in_process_id = $this->get_id_selectable_by("user_objects", "action_status", "in_process", "TA");
      
      $this->db->select("project_objects.name AS project");
      
      $this->db->select("
                        objects.id_object,                         
                        objects.parent_id,                         
                        objects.project_id,                         
                        objects.name,             
                        objects.description");
      
      $this->db->select("user_objects.object_id, user_objects.user_id, user_objects.user_role_id");
      
      $this->db->select("user_objects.id_user_object,
                        user_objects.register_date,
                        user_objects.action_status_select_id,
                        selectables.value_select");
      
      $this->db->from("objects");
      $this->db->join("user_objects", "objects.id_object = user_objects.object_id ");
      $this->db->join("selectables", "selectables.id = user_objects.action_status_select_id ");
      
      $this->db->join("objects project_objects", "project_objects.id_object=objects.project_id");
      
      
      $this->db->where("user_objects.user_id",$user_id);
      $this->db->where("user_objects.sorter",null);
      $this->db->where("user_objects.action_status_select_id", $action_status_in_process_id);
      
      $query = $this->db->get();      
      $result_array = $query->result_array();
      

      return $query->row_array();          
   }
   #############################################################################
   function get_task_active_by_user3($user_id)
   {
      $action_status_in_process_id = $this->get_id_selectable_by("user_objects", "action_status", "in_process", "TA");
      
      $this->db->select("
                        objects.id_object,                         
                        objects.parent_id,                         
                        objects.project_id,                         
                        objects.name,             
                        objects.description");
      
      $this->db->select("user_objects.object_id, user_objects.user_id, user_objects.user_role_id");
      
      $this->db->select("user_objects.id_user_object,
                        user_objects.register_date,
                        user_objects.action_status_select_id,
                        selectables.value_select");
      
      $this->db->from("objects");
      $this->db->join("user_objects", "objects.id_object = user_objects.object_id ");
      $this->db->join("selectables", "selectables.id = user_objects.action_status_select_id ");
      
      $this->db->where("user_objects.user_id",$user_id);
      $this->db->where("user_objects.sorter",null);
      $this->db->where("user_objects.action_status_select_id", $action_status_in_process_id);
      
      $query = $this->db->get();      
      $result_array = $query->result_array();
      

      return $query->row_array();          
   }
  
   #############################################################################
   function update_project_percent_completed($project_id)
   {
      $this->db->cache_on();      
      $id_type_select = $this->get_id_selectable_by("objects", "type", "task");
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $action_status_completed_id = $this->get_id_selectable_by("user_objects", "action_status", "completed","TA");
    
      $this->db->select("SUM(
         IF(user_objects.action_status_select_id='".$action_status_completed_id."',100,percent_completed)
         )/COUNT(*) AS percent_completed", false);
      
      //$this->db->select('SUM(tasks.percent_completed)/COUNT(*) AS percent_completed');
            
      $this->db->from('objects');      
      $this->db->join('tasks', "tasks.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = tasks.priority_select_id"); 
      
      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      $this->db->join('selectables sel_status', "sel_status.id = user_objects.action_status_select_id");      
      
      
      $this->db->where('user_objects.sorter',NULL, FALSE);
      
      $this->db->where('objects.project_id',$project_id);
      $this->db->where('objects.type_select_id',$id_type_select);      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_moved_to_trash_id, FALSE);
      
      $query = $this->db->get();               
      
      $row = $query->row_array();      
      $this->db->cache_off();      
      
      $conditions = array("object_id"=>$project_id);      
      $data['percent_completed'] = $row['percent_completed'];      
      $this->db->update('projects', $data, $conditions);    
      
   }
   #############################################################################
   function update_project_points($project_id)
   {
      $this->db->cache_on();
      $id_type_select = $this->get_id_selectable_by("objects", "type", "task");
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
    
      $this->db->select('SUM(tasks.points) AS total_points');
      
            
      $this->db->from('objects');      
      $this->db->join('tasks', "tasks.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = tasks.priority_select_id"); 
      
      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      $this->db->join('selectables sel_status', "sel_status.id = user_objects.action_status_select_id");      
      
      
      $this->db->where('user_objects.sorter',NULL, FALSE);
      
      $this->db->where('objects.project_id',$project_id);
      $this->db->where('objects.type_select_id',$id_type_select);      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_moved_to_trash_id, FALSE);
      
      $query = $this->db->get();      
         
      $row = $query->row_array();
      
      $this->db->cache_off();      
      
      $conditions = array("object_id"=>$project_id);      
      $data['points'] = $row['total_points'];      
      $this->db->update('projects', $data, $conditions);    
      
   }
   #############################################################################
   function is_member($user_id, $object_id)
   {
      $id_status_select = $this->get_id_selectable_by("members", "status", "active");
      
      $this->db->select("COUNT(*) AS count_member ", FALSE);
      $this->db->from("members");
      $this->db->where("user_id", $user_id);
      $this->db->where("object_id", $object_id);
       
      $this->db->where("status_select_id",$id_status_select);
      
      $query = $this->db->get();
     
      $row = $query->row_array();      
      if(isset($row['count_member']))
      {
         return $row['count_member']>0;
      }
      return false;
   }
   #############################################################################
   function get_list_membership($parent_id, $object_id=null, $sub_group=null)
   {
      $sql_condition="";
      if(isset($object_id))
      {
         $sql_condition = " AND members.object_id='".$object_id."'";
      }
      
      $id_status_select = $this->get_id_selectable_by("members", "status", "active");
      $this->db->select("
                        user_roles.id AS user_role_id,
                        user_roles.user_id,
                        user_roles.role_id,
                        user_roles.status");
      
      if(isset($object_id))
      {
         $this->db->select("IF(members.user_id IN (SELECT members.user_id 
                                                 FROM objects, members 
                                                 WHERE objects.id_object = members.object_id
                                                   AND members.status_select_id=".$id_status_select." 
                                                   ".$sql_condition."                                                   
                                                   AND objects.parent_id='".$parent_id."'), 1, NULL ) AS is_member", FALSE);
      }
      
      $this->db->select("roles.name AS role");
      
      
      
      $this->db->select("user_profiles.name,
                         user_profiles.last_name,
                         users.email");
      
      $this->db->from("members");
      $this->db->join("users","users.id = members.user_id");
      $this->db->join("user_profiles","user_profiles.user_id = users.id");
      $this->db->join("user_roles","user_roles.user_id = user_profiles.user_id");
      $this->db->join("roles","roles.id = user_roles.role_id");      
      
      $this->db->where("user_roles.status","active");
      
      $this->db->where("members.object_id",$parent_id);
      $this->db->where("members.status_select_id",$id_status_select);
      
      $query = $this->db->get();
      
     
      return $query->result_array();
   }
   #############################################################################   
   function inactive_member_recursively($user_id, $object_id, $sub_group=null)
   {  
      $id_status_select = $this->get_id_selectable_by("members", "status", "active");
      $this->db->select("
                        members.object_id,
                        members.user_id");      
      $this->db->from("members");
      $this->db->join("objects", "objects.id_object = members.object_id ");
      $this->db->where("objects.parent_id",$object_id);
      $this->db->where("members.status_select_id",$id_status_select);      
      $query = $this->db->get();   
      
      
      $child_members = $query->result_array();      
            
      for($i=0; $i<count($child_members);$i++)
      {
         //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
         $this->inactive_member_recursively($child_members[$i]['user_id'], $child_members[$i]['object_id']);
      }
      $this->inactive_member($user_id, $object_id);
   }
   #############################################################################
   function inactive_member($user_id, $object_id, $sub_group=null)
   {  
      $conditions = array("user_id"=>$user_id, "object_id"=>$object_id);
      $data['status_select_id'] = $id_status_select = $this->get_id_selectable_by("members", "status", "inactive", $sub_group);
      $this->db->update('members', $data, $conditions);                  
   }
   #############################################################################
   function inactive_all_member_by_object($object_id, $sub_group=null)
   {  
      $conditions = array("object_id"=>$object_id);
      $data['status_select_id'] = $id_status_select = $this->get_id_selectable_by("members", "status", "inactive", $sub_group);
      $this->db->update('members', $data, $conditions);
   }
   #######################################################
   function get_list_membership_last_role_by($company_id, $object_id, $sub_group=null)
   {
      $id_status_select = $this->get_id_selectable_by("members", "status", "active",$sub_group);
      $this->db->select("
                        user_roles.id AS user_role_id,
                        user_roles.user_id,
                        user_roles.role_id,
                        user_roles.status");
      
      $this->db->select("IF(users.id IN (SELECT user_id FROM members 
                                         WHERE status_select_id=".$id_status_select." 
                                           AND project_id=".$object_id."), 1, NULL ) AS is_member", FALSE);
      
      
      $this->db->select("roles.name AS role");
      
      $this->db->select("user_profiles.name,
                         user_profiles.last_name,
                         users.email");
      
      $this->db->from("users");
      $this->db->join("user_profiles","user_profiles.user_id=users.id");
      $this->db->join("user_roles","user_roles.user_id=user_profiles.user_id");
      $this->db->join("roles","roles.id=user_roles.role_id");      
      
      $this->db->where("user_roles.status","active");
      $this->db->where("users.company_id",$company_id);            
      
      $this->db->order_by("roles.name");                  
      $query = $this->db->get();            
      return $query->result_array();
   }
   #############################################################################
   function get_member($user_id)
   {  
      $this->db->select("
                        user_roles.id AS user_role_id,
                        user_roles.user_id,
                        user_roles.role_id,
                        user_roles.status");
      $this->db->select("roles.name AS role");
      $this->db->select("
                         user_profiles.name,
                         user_profiles.last_name,
                         users.email");
      
      $this->db->from('members');      
      $this->db->join('users', "users.id=members.user_id");      
      $this->db->join("user_profiles","user_profiles.user_id=users.id");
      $this->db->join("user_roles","user_roles.user_id=user_profiles.user_id");
      $this->db->join("roles","roles.id=user_roles.role_id");      
      
      $this->db->where("user_roles.status","active");      
      $this->db->where("members.user_id",$user_id);      
      
      $query_result = $this->db->get();
            
      return $query_result->row_array(); 
   }
   
   #############################################################################
   function add_member($user_id, $object_id, $sub_group=null)
   {  
      $id_status_select = $this->get_id_selectable_by("members", "status", "active",$sub_group);
      
      $this->db->cache_on();
      $this->db->select('COUNT(*) AS is_member');
      $this->db->from('members');            
      $this->db->where('members.user_id',$user_id);      
      $this->db->where('members.object_id',$object_id);
      $this->db->where('members.status_select_id', $id_status_select);
      
      $query = $this->db->get();      
      $row = $query->row_array();
      $this->db->cache_off();      
      
      
      #----------------------------------------------------------------------      
      if($row['is_member']<=0)
      {
         $this->db->cache_on();      
         
         $this->db->select('*');
         $this->db->from('objects');            
         $this->db->where('id_object',$object_id);      

         $query = $this->db->get();      
         $object = $query->row_array();
         $this->db->cache_off();      
         #----------------------------------------------------------------------      
         $data['user_id'] = $user_id;
         $data['object_id'] = $object_id;         
         $data['status_select_id'] = $id_status_select;         
         $this->db->cache_on();
         $this->db->set("register_date","NOW()", FALSE);      
         $this->db->insert("members",$data);      
         $this->db->cache_off();
         
         if(isset($object['parent_id']) AND strcasecmp(trim($object['parent_id']) ,"")!=0)
         {
            //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
            $this->add_member($user_id, $object['parent_id']);
         }
      }
   }
   #############################################################################
   function get_list_present_membership_by($company_id, $object_id, $dt_range_begin=null, $dt_range_end=null)
   {
      $id_status_select = $this->get_id_selectable_by("members", "status", "active");
      
      $this->db->select("
                        user_roles.id AS user_role_id,
                        user_roles.user_id,
                        user_roles.role_id,
                        user_roles.status");
      
      $this->db->select("roles.name AS role, roles.role_type");
      
      
      $this->db->select("user_profiles.user_id,
                         user_profiles.name, 
                         user_profiles.last_name, 
                         users.email,
                         users.username,
                         roles.name AS role");
      /*
      $this->db->select("ti.max_time_in,
                         ti.num_corrections");
      */
      $this->db->select("IF(users.id IN (SELECT user_id FROM members 
                                               WHERE status_select_id=".$id_status_select." 
                                                 AND object_id=".$object_id."), 1, NULL ) AS is_member",FALSE);

      /*
      if(isset($dt_range_begin) AND isset($dt_range_end))
      {
         $this->db->select("IF( (user_profiles.user_id,ti.max_time_in)  IN (SELECT user_id, time_in
                                                                 FROM times
                                                                 WHERE time_in IS NOT NULL AND status_out IS NULL
                                                                AND CAST(time_in AS DATETIME) BETWEEN CAST('".$dt_range_begin."' AS DATETIME)
                                                                                                       AND
                                                                                                       CAST('".$dt_range_end."' AS DATETIME)
                                                             )
                                     ,1
                                     ,0
                                     ) AS is_present
                                    ",FALSE);
      }
      */
      
      $this->db->from("users");
      $this->db->join("user_profiles", "users.id = user_profiles.user_id");      
      $this->db->join("user_roles", "user_profiles.user_id=user_roles.user_id");
      $this->db->join("roles", "user_roles.role_id = roles.id");
      /*
      $this->db->join("(
                       SELECT ti.user_id, max(ti.time_in) AS max_time_in,
                              (sum(IF(lower(ti.status_in)='corrected', 1, 0)) + sum(IF(lower(ti.status_out)='corrected', 1, 0))) AS num_corrections
                       FROM times ti
                       GROUP BY ti.user_id
                       ) AS ti", "ti.user_id = users.id");
      */
      $this->db->where("users.company_id", $company_id);
      $this->db->where("LOWER(user_roles.status) = 'active'");
      $this->db->order_by("roles.id ASC");
      
      $query = $this->db->get();          
      return $query->result_array();
   }
   #############################################################################
   function get_list_members($project_id, $dt_range_begin=null, $dt_range_end=null)
   {  
      $id_status_select = $this->get_id_selectable_by("members", "status", "active");
      
      $this->db->select("
                        user_roles.id AS user_role_id,
                        user_roles.user_id,
                        user_roles.role_id,
                        user_roles.status");
      
      $this->db->select("roles.name AS role");
      
      $this->db->select("user_profiles.user_id,
                         user_profiles.name, 
                         user_profiles.last_name, 
                         users.email,
                         users.username,                         
                         roles.name AS role");
    
      
      $this->db->from("users");
      $this->db->join("user_profiles", "users.id = user_profiles.user_id");      
      $this->db->join("user_roles", "user_profiles.user_id=user_roles.user_id");
      $this->db->join("roles", "user_roles.role_id = roles.id");
      $this->db->join("members", "members.user_id = users.id");
      $this->db->join("objects", "objects.id_object = members.object_id");
      
      
      $this->db->where("objects.id_object", $project_id);
      
      $this->db->where("members.status_select_id", $id_status_select);
      
      $this->db->where("LOWER(user_roles.status) = 'active'");
      $this->db->order_by("roles.id ASC");
      
      $query = $this->db->get();
          
      return $query->result_array();     
   }  
   #############################################################################
   function is_member_task($user_id, $task_id)
   {
      $this->db->select("COUNT(*) AS count_member ", FALSE);
      $this->db->from("member_tasks");
      $this->db->where("member_id", $user_id);
      $this->db->where("task_id", $task_id);
      $this->db->where("status",'active');
      
      $query = $this->db->get();

     
      $row = $query->row_array();      
      if(isset($row['count_member']))
      {
         return $row['count_member']>0;
      }
      return false;
   }
   #############################################################################
   function get_tasks_pendings_total_by($project_id)
   {
      $type_select_id_task = $this->get_id_selectable_by("objects", "type", "task");      
      $action_status_select_id_trash = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");      
      $action_status_select_id_completed = $this->get_id_selectable_by("user_objects", "action_status", "completed","TA");
      
      $this->db->cache_on();
      $this->db->select("COUNT(*) AS pendings ", FALSE);
      $this->db->from("objects");
      $this->db->join("user_objects","user_objects.object_id=objects.id_object");
      
      $this->db->where("type_select_id", $type_select_id_task);
      $this->db->where("project_id", $project_id);
      
      $this->db->where("user_objects.sorter", NULL, FALSE);
      
      $this->db->where("user_objects.action_status_select_id!=",$action_status_select_id_trash, FALSE);
      $this->db->where("user_objects.action_status_select_id!=",$action_status_select_id_completed, FALSE);
            
      $query = $this->db->get();

     
      $row = $query->row_array();      
      if(!isset($row['pendings']))
      {
         $row['pendings']=0;
      }      
      
      $row_return['pendings'] = $row['pendings'];
      
      $this->db->cache_off();
      
      #---------------------------------------------------------------------------------------------------
      $this->db->cache_on();
      
      $this->db->select("COUNT(*) AS total ", FALSE);
      $this->db->from("objects");
      $this->db->join("user_objects","user_objects.object_id=objects.id_object");
      $this->db->where("type_select_id", $type_select_id_task);
      $this->db->where("project_id", $project_id);
      $this->db->where("user_objects.action_status_select_id!=",$action_status_select_id_trash, FALSE);
      $this->db->where("user_objects.sorter", NULL, FALSE);      
      
      $query = $this->db->get();
      $row = $query->row_array();
      
      if(!isset($row['total']))
      {
         $row['total']=0;
      }
      $row_return['total']= $row['total'];
      $this->db->cache_off();
      
      
      #---------------------------------------------------------------------------------------------------
      $this->db->cache_on();
      $this->db->select("COUNT(*) AS completeds ", FALSE);
      $this->db->from("objects");
      $this->db->join("user_objects","user_objects.object_id=objects.id_object");
      
      $this->db->where("type_select_id", $type_select_id_task);
      $this->db->where("project_id", $project_id);
      
      $this->db->where("user_objects.sorter", NULL, FALSE);
      
      $this->db->where("user_objects.action_status_select_id!=",$action_status_select_id_trash, FALSE);
      $this->db->where("user_objects.action_status_select_id",$action_status_select_id_completed, FALSE);
            
      $query = $this->db->get();

     
      $row = $query->row_array();      
      if(!isset($row['completeds']))
      {
         $row['completeds']=0;
      }      
      
      $row_return['completeds'] = $row['completeds'];
      
      $this->db->cache_off();
      
      return $row_return;
   }
   #############################################################################
   function update_task($data, $conditions)
   {
      $this->db->update("tasks",$data, $conditions);     
   }
   #############################################################################
   function add_member_task($data)
   {
      $this->db->insert("member_tasks",$data);
   }
   #############################################################################
   function inactive_member_task($member_id, $task_id)
   { 
      $data=array("status"=>"inactive");
      $conditions = array("member_id"=>$member_id, "task_id"=>$task_id);      
      $this->db->update('member_tasks', $data, $conditions);      
   }
   #############################################################################
   function get_list_member_tasks($task_id)
   {
      $this->db->select('member_tasks.member_id');                      
      
      $this->db->select('user_profiles.user_id');                      
      
      $this->db->select('                
                user_profiles.name AS name,
                user_profiles.last_name AS last_name,
                users.email AS email');
      
            
      $this->db->from('member_tasks');            
      $this->db->join('users', "member_tasks.member_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      
      $this->db->where('member_tasks.task_id',$task_id);
      
      $this->db->where('member_tasks.status','active');
     
      
      $query = $this->db->get();         
      return $query->result_array();
   }
   #############################################################################
   function get_task($object_id)
   {
      $id_type_select = $this->get_id_selectable_by("objects", "type", "task");
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,                
                objects.is_private,                
                objects.description');
      
      $this->db->select('user_objects.action_status_select_id,
                        user_objects.user_id AS action_user_id,
                        user_objects.user_role_id');
      
      $this->db->select('                
                tasks.object_id,                
                tasks.start_date,
                tasks.end_date,                
                tasks.percent_completed,
                tasks.points,
                
                tasks.priority_select_id,
                selectables.value_select AS priority                
                ');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
      $this->db->select('                
                up.name AS changer_name,
                up.last_name AS changer_last_name,
                us.email AS changer_email');
      
      $this->db->from('objects');      
      $this->db->join('tasks', "tasks.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = tasks.priority_select_id");      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");
      
      $this->db->join('user_profiles up', "user_objects.user_id=up.user_id");
      $this->db->join('users us', "up.id=up.user_id");
      
      $this->db->where('user_objects.sorter',NULL, FALSE);
      
      $this->db->where('objects.type_select_id',$id_type_select);      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_moved_to_trash_id, FALSE);
      
      $this->db->where('objects.id_object', $object_id);      
      $query = $this->db->get();      
    
     
      return $query->row_array();
   }
   #############################################################################
   function add_task($data)
   {
      $this->db->cache_on();
      $this->db->insert("tasks",$data);      
      $this->db->cache_off();
   }
   
   #############################################################################
   function get_list_tasks_by($parent_id)
   {
      $id_type_select = $this->get_id_selectable_by("objects", "type", "task");
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      
      $this->db->select('                
                tasks.object_id,                
                tasks.start_date,
                tasks.end_date,                
                tasks.percent_completed,
                selectables.value_select AS priority,
                sel_status.value_select AS status,
                ');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
            
      $this->db->from('objects');      
      $this->db->join('tasks', "tasks.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = tasks.priority_select_id"); 
      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      $this->db->join('selectables sel_status', "sel_status.id = user_objects.action_status_select_id");      
      
      
      $this->db->where('user_objects.sorter',NULL, FALSE);
      
      $this->db->where('objects.parent_id',$parent_id);
      $this->db->where('objects.type_select_id',$id_type_select);      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_moved_to_trash_id, FALSE);
      
      $this->db->order_by('user_objects.register_date DESC');      
      $query = $this->db->get();      
      
     
         
      return $query->result_array();
   }
   
   #############################################################################
   function get_list_tasks_by_project($project_id)
   {
      $id_type_select = $this->get_id_selectable_by("objects", "type", "task");
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      
      $this->db->select('                
                tasks.object_id,                
                tasks.start_date,
                tasks.end_date,                
                tasks.percent_completed,
                selectables.value_select AS priority,
                sel_status.value_select AS status,
                ');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
            
      $this->db->from('objects');      
      $this->db->join('tasks', "tasks.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = tasks.priority_select_id"); 
      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      $this->db->join('selectables sel_status', "sel_status.id = user_objects.action_status_select_id");      
      
      $this->db->where('user_objects.sorter',NULL, FALSE);
      
      $this->db->where('objects.project_id',$project_id);
      $this->db->where('objects.type_select_id',$id_type_select);      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_moved_to_trash_id, FALSE);
      
      $this->db->order_by('objects.register_date ASC');      
      $query = $this->db->get();      
      
      return $query->result_array();
   }
   #############################################################################
   function get_list_values_table_field_enum($table, $field)
   {
      $sql = "SHOW COLUMNS FROM $table LIKE '$field'";
      $query = $this->db->query($sql);     
      $row = $query->row_array();     
      $type = $row['Type'];      
      list($str_enum,$str_values) = explode("enum(",$type);            
      $array_values = explode("','", $str_values);      
      if( ! empty($array_values))
      {
         $array_values[0] = ltrim($array_values[0],"'");
         $array_values[ count($array_values)-1 ] = rtrim($array_values[count($array_values)-1],"')");         
      }
      return $array_values;      
   }
   
   
   #############################################################################
   function get_time_record($object_id)   
   {
      $id_type_select = $this->get_id_selectable_by("objects", "type", "time_record");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      
      $this->db->select('                
                time_records.user_id,
                time_records.billable_status_select_id,
                selectables.value_select AS billable_status,
                time_records.quantity');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
            
      $this->db->from('objects');      
      $this->db->join('time_records', "time_records.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = time_records.billable_status_select_id");      
      $this->db->join('users', "objects.user_id = users.id");
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");      
      $this->db->where('user_objects.sorter',NULL, FALSE);      
      $this->db->where('objects.id_object',$object_id);
      $this->db->where('objects.type_select_id',$id_type_select);            
      $query = $this->db->get();
      
      return $query->row_array();
   }
   
   #############################################################################
   function add_time_record($data)
   {
      $this->db->cache_on();
      $this->db->insert("time_records",$data);      
      $this->db->cache_off();
   }
   
   #############################################################################
   function update_time_record($data, $conditions)
   {
      $this->db->cache_on();
      $this->db->update("time_records",$data, $conditions);
      $this->db->cache_off();
   }
   
   #############################################################################
   function get_list_time_records($parent_id, $limit=100)
   {
      $action_status_moved_to_trash_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");      
      
      
      $id_type_select = $this->get_id_selectable_by("objects", "type", "time_record");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      
      $this->db->select('                
                time_records.billable_status_select_id,
                selectables.value_select AS billable_status,
                time_records.quantity');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
      $this->db->select('                
                up_worker.user_id AS worker_user_id,
                up_worker.name AS worker_name,
                up_worker.last_name AS worker_last_name,
                us_worker.email AS worker_email');
            
      $this->db->from('objects');      
      $this->db->join('time_records', "time_records.object_id = objects.id_object");      
      $this->db->join('selectables', "selectables.id = time_records.billable_status_select_id");      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      
      $this->db->join('users us_worker', "time_records.user_id= us_worker.id");
      $this->db->join('user_profiles up_worker', "up_worker.user_id=us_worker.id");
      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      
      
      $this->db->where('user_objects.sorter',NULL, FALSE);
      
      $this->db->where('objects.parent_id',$parent_id);
      $this->db->where('objects.type_select_id',$id_type_select);      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_moved_to_trash_id, FALSE);
      
      $this->db->order_by('user_objects.register_date DESC');
      
      $this->db->limit($limit);      
      $query = $this->db->get();
      
      return $query->result_array();
   }
   #############################################################################
   function restore_object_recursively($user_id, $user_role_id, $object_id=null)
   {
      
      $this->db->select('id_object');      
      $this->db->from('objects');
      $this->db->where('objects.parent_id', $object_id);            
      $query = $this->db->get();
      
      $child_list = $query->result_array();
      
      for($i=0; $i<count($child_list); $i++)
      {
         //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
         $this->restore_object_recursively($user_id, $user_role_id, $child_list[$i]['id_object']);
      }
      $this->restore_object($user_id, $user_role_id, $object_id);
   }
   #############################################################################
   function restore_object($user_id, $user_role_id, $object_id=null)
   {
      #---------------------------------------------------------------      
      $this->db->cache_on();
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $this->db->select("*");      
      $this->db->from("user_objects");
      $this->db->where("action_status_select_id!='".$action_status_select_id."'", null, false);
      $this->db->where("object_id", $object_id);
      
      $this->db->order_by("register_date DESC");
      //$this->db->limit(1);        
      $query = $this->db->get();
      #####################################################$result = $query->result_array();
      
      $data = $query->row_array();                  
      $this->db->cache_off();      
      #---------------------------------------------------------------
      $this->db->cache_on();      
      $user_object = $this->get_last_user_object($object_id);      
      $user_object['id_user_object'] = null;
      $user_object['user_id'] = $user_id;
      $user_object['user_role_id'] = $user_role_id;      
      $this->add_user_object($user_object, $action_status = "restored");            
      $this->db->cache_off();
      #---------------------------------------------------------------      
      $this->db->cache_on();
      
      $this->db->select("project_id");
      $this->db->from("objects"); 
      $this->db->where("id_object", $object_id);
      
      $query = $this->db->get();      
      $row_project = $query->row_array();      
      $this->db->cache_off();      
      $this->update_project_percent_completed($row_project['project_id']);      
      #---------------------------------------------------------------
      $data['id_user_object'] = null;
      $data['register_date'] = null;      
      $action_status = $this->get_selectable_by($data['action_status_select_id']);      
      $this->add_user_object($data, $action_status['value_select'], $action_status['sub_group'] );      
   }
   #############################################################################
   /*last row will have   order=null*/
   function add_user_object($data, $action_status = "created", $action_status_sub_group = null)
   {      
      #-------------------------------------------------------------------------
      $this->db->cache_on();
      $object_id = $data['object_id'];
      $user_role_id = $data['user_role_id'];
      
      $sql="SELECT id_user_object 
            FROM user_objects 
            WHERE object_id=".$object_id."
              AND `sorter` IS NULL";
      $last_query=$this->db->query($sql);      
      $last_user_object = $last_query->row_array();                  
      $this->db->cache_off();
      if( ! empty($last_user_object) AND isset($last_user_object['id_user_object']))
      {  
         $id_last_user_object = $last_user_object['id_user_object'];      
         
         $this->db->cache_on();
         $sql="SELECT COUNT(*) AS quantity
               FROM user_objects 
               WHERE object_id=".$object_id;
         $count_query = $this->db->query($sql);      
         $row_count_user_object = $count_query->row_array(); 
         $this->db->cache_off();
         if(! empty($row_count_user_object) AND isset($row_count_user_object['quantity']))
         { 
            $count_user_object = $row_count_user_object['quantity'];
            $this->db->cache_on();
            $data_update = array("sorter"=>$count_user_object);
            $conditions = array("id_user_object"=>$id_last_user_object);
            $this->db->update("user_objects", $data_update, $conditions);
            $this->db->cache_off();
         }
      }
      #-------------------------------------------------------------------------
      
      
      $action_status_select_id = $this->get_id_selectable_by("user_objects","action_status", $action_status, $action_status_sub_group);
      $this->db->cache_on();
      $data["sorter"] = null;      
      $data["action_status_select_id"] = $action_status_select_id;
      $data["register_date"] = $this->get_system_time();      
      $this->db->insert("user_objects", $data);  
   
      $this->db->cache_off();
   }   
   #############################################################################
   function get_object($object_id)
   {
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description,                
                objects.status_date,      
                
                sel_type.value_select AS type');
            
      $this->db->from('objects');
      $this->db->join('selectables sel_type',"objects.type_select_id=sel_type.id");            
      $this->db->where('objects.id_object',$object_id); 
      $query = $this->db->get(); 
            
      return $query->row_array();
   }
   #############################################################################
   function get_list_trash_objects($project_id=null, $company_id=null)
   {
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                selectables.value_select AS type,
                objects.register_date,
                obj_owner.name project_owner,
                objects.description');
      
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
      
      $this->db->from('objects');      
      $this->db->join('selectables',"objects.type_select_id=selectables.id");      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_objects',"user_objects.object_id=objects.id_object");      
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");      
      $this->db->join('objects obj_owner', "obj_owner.id_object = objects.project_id");
      $this->db->where('user_objects.action_status_select_id', $action_status_select_id);
      
      if(isset($project_id))
      {
         $this->db->where('objects.project_id',$project_id);
      }
      if(isset($company_id))
      {
         $this->db->where('objects.company_id',$company_id);
      }
      
      $this->db->where('user_objects.sorter',null, false);
      
      $this->db->where('selectables.table_name','objects');
      $this->db->where('selectables.var_name','type'); 
      
      $this->db->order_by('user_objects.register_date DESC');      
      $query = $this->db->get();

      
      return $query->result_array();
   }
   #############################################################################
   function get_list_attachment_files($parent_id)
   {  
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      $type_select_id = $this->get_id_selectable_by("objects", "type", "file");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      
      $this->db->from('objects');      
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");
      $this->db->where('objects.parent_id',$parent_id);
      
      $this->db->where('objects.type_select_id',$type_select_id);      
      
      $this->db->where('user_objects.sorter',NULL,FALSE);      
      $this->db->where('user_objects.action_status_select_id!=',$action_status_select_id,FALSE);       
      $this->db->order_by('objects.register_date DESC');
      $query = $this->db->get();   
      
      
      return $query->result_array();            
   }
   
   #############################################################################
   function delete_object_permanently_recursively($object_id)
   {
      $this->db->select('id_object');      
      $this->db->from('objects');
      $this->db->where('objects.parent_id', $object_id);            
      $query = $this->db->get();
     
      $child_list = $query->result_array();
      
      for($i=0; $i<count($child_list); $i++)
      {
         //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
         $this->delete_object_permanently_recursively($child_list[$i]['id_object']);
      }
      $this->delete_object_permanently($object_id);
   }
   #############################################################################
   function delete_object_permanently($object_id)
   {
      $this->db->cache_on();
      $this->db->delete("objects", array("id_object"=>$object_id));
      $this->db->cache_off();
      
      $this->db->delete("user_objects", array("object_id"=>$object_id));
      $this->db->delete("members", array("object_id"=>$object_id));
   }
   #############################################################################
   function delete_object_recursively($object_id, $user_id, $user_role_id)
   {
      $this->db->select('id_object');      
      $this->db->from('objects');
      $this->db->where('objects.parent_id', $object_id);            
      $query = $this->db->get();
      
      $child_list = $query->result_array();
      
      for($i=0; $i<count($child_list); $i++)
      {
         //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
         $this->delete_object_recursively($child_list[$i]['id_object'], $user_id, $user_role_id);
      }
      $this->delete_object($object_id, $user_id, $user_role_id);
   }
   #############################################################################
   function delete_object($object_id, $user_id, $user_role_id)
   {
      $user_object = $this->get_last_user_object($object_id);      
      $user_object['id_user_object'] = null;
      $user_object['user_id'] = $user_id;
      $user_object['user_role_id'] = $user_role_id;
      $this->add_user_object($user_object, $action_status = "moved_to_trash");      
   }
   #############################################################################
   function get_last_user_object($object_id)
   {
      $this->db->select('*');      
      $this->db->from('user_objects');            
      $this->db->where('user_objects.object_id', $object_id);
      $this->db->limit(1);  
      $this->db->order_by('user_objects.register_date DESC');      
      
      $query = $this->db->get();
      
      return $query->row_array();
   }
   
   #############################################################################
   function delete_task_permanently($project_id)
   {  
      $this->db->delete("tasks", array("object_id"=>$project_id));
   }
   #############################################################################
   function delete_project_permanently($project_id)
   {  
      $this->db->delete("projects", array("object_id"=>$project_id));      
   }   
   #############################################################################
   function update_project($data, $conditions)
   {      
      $this->db->update("projects",$data, $conditions);
   }
   #############################################################################
   function update_object($data, $conditions)
   {  
      $this->db->set("status_date","NOW()",FALSE);
      $this->db->update("objects",$data, $conditions);      
   }
   
   #############################################################################
   function add_project($data)
   {  
      $this->db->cache_on();
      $this->db->insert("projects",$data);      
      $this->db->cache_off();
      
      $data_obj['project_id'] = $data['object_id'];
      $this->db->update("objects",$data_obj,array("id_object"=>$data['object_id']));      
   }   
   #############################################################################
   function add_object($data)
   {  
      $this->db->set("register_date","NOW()",FALSE);
      $this->db->set("status_date","NOW()",FALSE);
      $this->db->insert("objects",$data);
            
      return $this->db->insert_id();
   }
   
   
   #############################################################################
   function get_list_object_peoples($object_id)
   {      
      $this->db->select(' 
                roles.name AS role,
                user_profiles.user_id,
                user_profiles.name,
                user_profiles.last_name,
                users.email');
      
      $this->db->from('users');      
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      $this->db->join('user_objects', "user_objects.user_id=user_profiles.user_id");      
      $this->db->join('user_roles', "user_roles.id=user_objects.user_role_id");
      $this->db->join('roles', "roles.id=user_roles.role_id");
      
      $this->db->where('user_objects.object_id', $object_id);
      $this->db->group_by("user_roles.id");
      $this->db->order_by('user_objects.register_date DESC');
      $query = $this->db->get();
      return $query->result_array();            
   }
   
   #############################################################################
   function get_comment($object_id){
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "created");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.is_private,
                objects.description');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
      $this->db->from('objects');      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");      
      
      $this->db->where('objects.id_object', $object_id);
      $this->db->where('user_objects.action_status_select_id', $action_status_select_id);
      
      $this->db->order_by('objects.register_date DESC');
      
      $query = $this->db->get();       
      $row = $query->row_array();
     
      return $row;
   }   
   #############################################################################
   function get_list_comments_reply($parent_id, $member_user_id =null)
   {
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $type_select_id_comment = $this->get_id_selectable_by("objects", "type","comment");      
      $type_select_id_task = $this->get_id_selectable_by("objects", "type","task");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.type_select_id,
                objects.register_date,
                objects.modified_date,
                objects.is_private,
                objects.description'); 
      
      $this->db->select('user_objects.register_date AS register_date_action_status');
      
      $this->db->select("IF( (objects.id_object, ".$member_user_id.") IN (SELECT object_id, user_id FROM members WHERE user_id=".$member_user_id.") , 1 ,null) AS have_access ", false);
      
      $this->db->select('selectables.value_select AS action_status');
      
      $this->db->select('user_objects.action_status_select_id,
                         user_objects.user_id AS action_status_user_id');
      
      $this->db->select('   
                         user_profiles.name AS owner_name,
                         user_profiles.last_name AS owner_last_name,
                         users.email AS owner_email');      
      $this->db->from('objects');
      $this->db->join('user_objects', "objects.id_object= user_objects.object_id");
      
      $this->db->join('selectables', "selectables.id = user_objects.action_status_select_id");
      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");      
      
      $this->db->where('(objects.type_select_id=', $type_select_id_comment, FALSE);
      $this->db->or_where('objects.type_select_id=', $type_select_id_task.")", FALSE);
      
      $this->db->where('user_objects.action_status_select_id !=', $action_status_select_id, FALSE);
      $this->db->where('user_objects.sorter',null, FALSE);      
      $this->db->where('objects.parent_id', $parent_id);            
      
      /*
      if(isset($member_user_id) AND strcasecmp( $member_user_id, "")!=0)
      {
         $this->db->where("
            ( 
               AND (objects.id_object, ".$member_user_id.") IN (SELECT object_id, user_id FROM members) 
            ) 
            ", null, false);
      }
      */
      
      $this->db->group_by(array("user_objects.action_status_select_id","user_objects.object_id"));            
      $this->db->order_by('objects.register_date ASC');      
      $query = $this->db->get(); 
      
      return $query->result_array();
   }
   #############################################################################
   function get_last_comment_reply($parent_id)
   {
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");      
      $type_select_id_comment = $this->get_id_selectable_by("objects", "type","comment");
      $type_select_id_task = $this->get_id_selectable_by("objects", "type","task");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
      
      $this->db->from('objects');
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      
      $this->db->where('objects.parent_id', $parent_id);
      
      $this->db->where('(objects.type_select_id='.$type_select_id_comment.' OR objects.type_select_id='.$type_select_id_task.')', NULL, FALSE);
      
            
      $this->db->where('user_objects.action_status_select_id !=', $action_status_select_id, FALSE);      
      $this->db->order_by('objects.register_date DESC');
      $this->db->limit("1");      
      $query = $this->db->get(); 
     
      
      $row = $query->row_array();      
      return $row;
   }
   #############################################################################
   function get_counts_reply_comments($comment_id)
   {
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      
      $type_select_id = $this->get_id_selectable_by("objects", "type","comment");
      
      $this->db->select('COUNT(*) AS count');      
      $this->db->from('objects');            
      $this->db->join('user_objects', "objects.id_object=user_objects.object_id");
      $this->db->where('objects.parent_id',$comment_id);
      
      $this->db->where('objects.type_select_id =', $type_select_id);
      
      $this->db->where('user_objects.sorter',null,false);
      
      $this->db->where('user_objects.action_status_select_id !=', $action_status_select_id);
      
      $query = $this->db->get();       
      $row = $query->row_array();
      
      return $row['count'];
   }
   #############################################################################
   function get_list_discussions($project_id, $limit=15)
   {
      $action_status_select_id = $this->get_id_selectable_by("user_objects", "action_status", "moved_to_trash");
      $id_type_select = $this->get_id_selectable_by("objects", "type", "discussion");
      
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description');
      $this->db->select('                
                user_profiles.name AS owner_name,
                user_profiles.last_name AS owner_last_name,
                users.email AS owner_email');
            
      $this->db->from('objects');      
      
      $this->db->join('users', "objects.user_id= users.id");
      $this->db->join('user_objects', "user_objects.object_id=objects.id_object");
      $this->db->join('user_profiles', "user_profiles.user_id=users.id");
      
      $this->db->where('objects.project_id',$project_id);
      $this->db->where('objects.type_select_id',$id_type_select);
      
      $this->db->where('user_objects.action_status_select_id!=', $action_status_select_id,FALSE);
      $this->db->where('user_objects.sorter', NULL, FALSE);
      
      $this->db->order_by('objects.register_date DESC');
      
      $this->db->limit($limit);      
      $query = $this->db->get();
      
      return $query->result_array();
   }
  
   #############################################################################
   function get_project($project_id)
   {
      $this->db->select('objects.id_object,
                objects.project_id,
                objects.parent_id,
                objects.user_id,
                objects.company_id,
                objects.name,
                objects.register_date,
                objects.description,
                
                objects.status_date,
                selectables.value_select AS status,                
                user_objects.action_status_select_id');
      $this->db->select('          
                projects.object_id,
                projects.start_date,
                projects.end_date,                
                projects.priority_select_id,                
                projects.percent_completed,
                projects.points,
                pri.value_select AS priority');
            
      $this->db->from('objects');
      $this->db->join('projects',"objects.id_object=projects.object_id");                        
      $this->db->join('selectables pri',"projects.priority_select_id=pri.id");      
      $this->db->join('user_objects',"user_objects.object_id=objects.id_object");      
      $this->db->join('selectables',"user_objects.action_status_select_id=selectables.id");
      
      $this->db->where('user_objects.sorter',null, false); 
            
      $this->db->where('objects.id_object',$project_id); 
      $query = $this->db->get(); 
            
      return $query->row_array();
   }
   
   #############################################################################
   function get_list_priorities()
   {
      $sql = "SELECT * FROM selectables WHERE LOWER(var_name='priority') AND LOWER(table_name)='projects';";
      $query = $this->db->query($sql);
      return $query->result_array();
   }
   #############################################################################
   function get_list_projects_status()
   {
      $sql = "SELECT * FROM selectables 
              WHERE LOWER(var_name='status') AND LOWER(table_name)='projects'
              ORDER BY order_by ASC;";
      $query = $this->db->query($sql);
      return $query->result_array();
   } 
}   