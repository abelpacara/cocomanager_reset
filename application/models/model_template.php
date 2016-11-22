<?php
class Model_Template extends CI_Model
{ 
   function __construct()
   {
       parent::__construct();       
       $this->db->query("SET SESSION time_zone='-4:00'");
   }
   #############################################################################
   function add_time_default_to_user($user_id)
   {
      $ts_before_20_years = strtotime("1987-07-07");
      
      $data=array(
         "user_id"=>$user_id,
         "time_in"=>date("Y-m-d H:i:s", $ts_before_20_years),
         "time_out"=>date("Y-m-d H:i:s", $ts_before_20_years+1),
         "status_in"=>"Valid",
         "status_out"=>"Valid",
         );
      
      $this->db->insert("times",$data);            
   }
   #############################################################################
   function get_row_time_last_by_user($user_id)
   {
      $sql_last="SELECT ti.*
                 FROM times ti, users us
                 WHERE us.id='".$user_id."'
                   AND ti.user_id=us.id
                 ORDER BY ti.time_in DESC, 
                          ti.time_out ASC
                 LIMIT 1;";      
      $query = $this->db->query($sql_last); 
      
      return $query->row_array();
   }
   #############################################################################
   function get_list_present_users_by($company_id, $dt_range_begin, $dt_range_end)
   {
      $sql="SELECT up.user_id,
                   up.name, 
                   up.last_name, 
                   us.email,
                   us.username,
                   ti.max_time_in,
                   ti.num_corrections,
                   ro.name AS role,
                   IF( (up.user_id,ti.max_time_in)  IN (SELECT user_id, time_in
                                                        FROM times
                                                        WHERE time_in IS NOT NULL AND status_out IS NULL
                                                          AND CAST(time_in AS DATETIME) BETWEEN CAST('".$dt_range_begin."' AS DATETIME)
                                                                                                 AND
                                                                                                 CAST('".$dt_range_end."' AS DATETIME)
                                                        
                                                        )
                   ,1
                   ,0
                   ) AS is_present
                          
            FROM user_profiles up,
                 users us,
                 user_roles ur,
                 roles ro,
                 (
                 SELECT ti.user_id, max(ti.time_in) AS max_time_in,
                        (sum(IF(lower(ti.status_in)='corrected', 1, 0)) + sum(IF(lower(ti.status_out)='corrected', 1, 0))) AS num_corrections
                 FROM times ti
                 GROUP BY ti.user_id
                 ) AS ti
                 
            WHERE ti.user_id = us.id
              AND us.company_id='".$company_id."'      
              AND us.id = up.user_id
              AND up.user_id=ur.user_id
              AND ur.role_id = ro.id
              AND LOWER(ur.status) = 'active'
            ORDER BY ro.id;";
      
      $query = $this->db->query($sql);
      return $query->result_array();
   }
   /**
    * @param        $user_id Is id_time from TABLE times, to filter user x data only
    *                                     their values ​​should be [INTEGER]
    *
    * @param        $date_begin Is begin Date begin
    *                           their values ​​should be [DATE_TIME or DATE '2011-03-25']
    * @param        $date_end   Is begin Date end
    *                           their values ​​should be [DATE_TIME or DATE  '2011-04-02']
    *
    *                  May contain for weeks, months, years or any other valid range
    *
    *
    * @return        array(    
    *                      'id_time'=>[INTEGER],
    *                      'time_in'=>[DATE_TIME],
    *                      'time_out'=>[DATE_TIME],
    *                      'status_in'=>=>['valid' |  'observed' | 'corrected'],
    *                      'status_out'=>=>['valid' |  'observed' | 'corrected'],
    *                      'sub_total'=>[FLOAT {hours}]
    *                      );
    *
    *                the array will only have ONE OR MORE rows depending on the condition
    */
   function get_list_times($dt_begin, $dt_end, $user_id=null, $company_id=null,  $limit_records = 0)
   {
      $sql_limit = "";
      
      if($limit_records > 0)
      {
         $sql_limit = " LIMIT ".$limit_records;
      }
      
      $sql_user_id="";
            
      
      if(isset($user_id))
      {
         $sql_user_id = " AND ti.user_id='".$user_id."'";
      }
      $sql_FROM_company="";
      $sql_WHERE_company="";
      
      if(isset($company_id))
      {
         $sql_FROM_company = ", users us, companies co";
         $sql_WHERE_company = " AND co.id=us.company_id AND us.id=ti.user_id AND co.id='".$company_id."'";
      }
      
      $sql="
            SELECT ti.id_time,
                   ti.time_in,
                   ti.time_out,
                   ti.status_in,
                   ti.status_out,
                   ti.user_id,
                   WEEKOFYEAR(ti.time_in) AS week_of_year,
                   
                   ABS(TIMESTAMPDIFF(SECOND, ti.time_out, ti.time_in)/ 3600 ) AS sub_total                    
            FROM times ti
                ".$sql_FROM_company."
            WHERE (
                     (
                      ti.time_in BETWEEN CAST('".$dt_begin."' AS DATETIME) AND CAST('".$dt_end."' AS DATETIME)
                     )
                     OR
                     (
                      ti.time_out BETWEEN CAST('".$dt_begin."' AS DATETIME) AND CAST('".$dt_end."' AS DATETIME)
                     )                     
                   )
                   ".$sql_user_id."
                   ".$sql_WHERE_company."
            ORDER BY ti.user_id ASC, ti.time_in ASC, ti.time_out ASC ".$sql_limit.";";
            
      $query = $this->db->query($sql);
         
      return $query->result_array();
   }
   #############################################################################
   function get_list_table_enum_column_values($table, $column)
   {
      $query = $this->db->query("show columns from ".$table." LIKE '".$column."';");
      $row = $query->row_array();
      $array_values= explode(",", str_replace("'", "", substr($row['Type'], 5, (strlen($row['Type'])-6))));      
      
      return $array_values;
   }
   #############################################################################
   function get_system_time()
   {
      $query = $this->db->query("SELECT now() AS date_ts_now;");
      $date_now = $query->row_array();

      return $date_now['date_ts_now'];
   }
   #############################################################################
   function get_id_selectable_by($table_name, $var_name, $value, $sub_group=null)
   {
      /*
      $trace_exception = new Exception();
      $trace = $trace_exception->getTrace();
      $last_call = $trace[ 1 ];
      echo "<br><pre>";
      print_r( $last_call );
      echo "</pre><BR>";
      */
      
      $this->db->select(" 
                 id,
                 value_select,
                 table_name,
                 var_name,
                 order_by");
      
      $this->db->from("selectables");
      $this->db->where("LOWER(table_name)",strtolower($table_name));      
      $this->db->where("LOWER(var_name)", strtolower($var_name));            
      $this->db->where("LOWER(value_select)",strtolower($value));      
      
      if(isset($sub_group) AND strcasecmp( $sub_group,"")!=0)
      {
         $this->db->where("LOWER(sub_group)", strtolower($sub_group));      
      }      
      $this->db->limit(1);      
      $query = $this->db->get();            
      $row = $query->row_array();      
      
      return $row['id'];
   }
   #############################################################################
   function get_selectable_by($id)
   {
      $this->db->select(" 
                 *");
      
      $this->db->from("selectables");
      $this->db->where("id", $id);      
            
      $this->db->limit(1);      
      $query = $this->db->get();            
      $row = $query->row_array();      
      
      return $row;
   }
   #############################################################################
   function get_list_selectable_by($table_name, 
                                   $var_name, 
                                   $value_select=null, 
                                   $sub_group=null, 
                                   $minus_array_values=array())
   {
      $this->db->select(" 
                 id,
                 value_select,
                 table_name,
                 var_name,
                 order_by");
      
      $this->db->from("selectables");
      $this->db->where("LOWER(table_name)", strtolower($table_name));      
      $this->db->where("LOWER(var_name)", strtolower($var_name));      
      
      if(isset($value_select))
      {
         $this->db->where("LOWER(value_select)", strtolower( $value_select) );      
      }      
      
      if(isset($sub_group))
      {
         $this->db->where("LOWER(sub_group)", strtolower($sub_group));      
      }
      
      if(!empty($minus_array_values))
      {
         for($i=0; $i<count($minus_array_values);$i++)
         {
            $this->db->where("LOWER(value_select)!=", "'".strtolower($minus_array_values[$i])."'", FALSE);
         }
      }
      
      $this->db->order_by("order_by");      
      
      $query = $this->db->get();      
      return $query->result_array();
   }

}