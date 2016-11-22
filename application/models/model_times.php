<?php
class Model_times extends Model_template
{ 
   public $VALID='Valid';
   public $OBSERVED='Observed';
   public $CORRECTED='Correted';
   
   function __construct()
   {
       parent::__construct();
       $this->load->helper('my_dates_helper');       
   }
   #################################################################################
   /*
   function get_list_user_changes_times($company_id, $dt_range_begin, $dt_range_end)
   {
       $sql="SELECT up.user_id,
                   up.name, 
                   up.last_name, 
                   us.email,
                   us.username,
                   ti.max_time_in,
                   ti.num_corrections,
                   ro.name AS role
            FROM user_profiles up,
                 users us,
                 user_roles ur,
                 roles ro
            WHERE ti.user_id = us.id
              AND us.company_id='".$company_id."'      
              AND us.id = up.user_id
              AND up.user_id=ur.user_id
              AND ur.role_id = ro.id
              AND LOWER(ur.status) = 'active'
              AND (up.user_id,ti.max_time_in)  IN (SELECT user_id, time_in
                                                        FROM times
                                                        WHERE time_in IS NOT NULL AND status_out IS NULL
                                                        AND CAST(time_in AS DATETIME) BETWEEN CAST('".$dt_range_begin."' AS DATETIME)
                                                                                                 AND
                                                                                                 CAST('".$dt_range_end."' AS DATETIME)
                                                  )
            ORDER BY ti.max_time_in ASC;";
      $query = $this->db->query($sql);
      return $query->result_array();
   }*/
   #################################################################################
   function get_list_last_present_users($company_id, $dt_range_begin, $dt_range_end)
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
                   ) AS is_present,
                   DATE_FORMAT(ti.max_time_in,'%p') AS am_pm
                          
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
            ORDER BY is_present DESC, DATE(ti.max_time_in) DESC, TIME(ti.max_time_in) ASC, am_pm DESC;";
      
      $query = $this->db->query($sql);
      return $query->result_array();
   }
   #############################################################################
   function delete_time_entry_by($time_id, $user_id, $is_in_out)
   {
      $this->db->set('time_'.$is_in_out, '0000-00-00 00:00:00');
      $this->db->set('status_'.$is_in_out, 'null', FALSE);
      
      $this->db->where("id_time", $time_id);
      $this->db->where("user_id", $user_id);
      
      $this->db->update('times'); 
   }
   #############################################################################
   function get_previous_time($time_id, $user_id, $is_in_out)
   {
      if(strcasecmp($is_in_out,"in")==0)//posffix pivot time
      {
         $current_pos = "in";
         $previous_pos = "out";
      }
      else
      {
         $current_pos = "out";
         $previous_pos = "in";
      }
      
      
      $sql = "SELECT pre.time_".$previous_pos." AS time
              FROM times pre, times cur
              WHERE pre.user_id='".$user_id."' AND cur.user_id = '".$user_id."'
                 AND cur.id_time = '".$time_id."'
                 AND pre.time_".$previous_pos." < cur.time_".$current_pos."
              ORDER BY pre.time_".$previous_pos." DESC
              LIMIT 1";      
      
      $query = $this->db->query($sql);
      $row = $query->row_array();
      return $row;
   }
   #############################################################################
   /*
    
    CAST(time_in AS DATETIME) <= CAST('".$date_time_pivot['time_in']."' AS DATETIME)
                 AND
                 CAST('".$date_time_pivot['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME) 
    
    */
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

                 (SELECT user_id, max(id) AS id
                  FROM user_roles
                  GROUP BY user_id                
                 ) AS ur,

                 user_roles,

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

              AND ur.id=user_roles.id

              AND user_roles.role_id = ro.id
              
            ORDER BY ro.id;";
      
      $query = $this->db->query($sql);
      return $query->result_array();
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
   function delete_time($time_id)
   {
      $this->db->where('id_time', $time_id);
      $this->db->delete('times'); 
   }
   #############################################################################
   function get_list_times_status()
   {
      $sql = "SELECT * FROM selectables WHERE LOWER(var_name='status') AND LOWER(table_name)='times';";
      $query = $this->db->query($sql);
      return $query->result_array();
   }
   #############################################################################
   function get_list_weeks_with_error($user_id)
   {
      //times_a.id_time, 
      //times_a.time_in,
      //times_a.time_out,
      
      $sql ="(SELECT 
               WEEKOFYEAR(times_a.time_in) AS week_of_year,
               YEAR(times_a.time_in) AS year
               
             FROM times times_a, 
                  times times_b
             WHERE times_a.user_id='".$user_id."' AND times_b.user_id='".$user_id."'
               AND times_a.id_time != times_b.id_time             
               AND times_a.time_in IS NOT NULL AND times_a.time_out IS NOT NULL 
               AND
                  (
                     ( times_a.time_in <= times_b.time_in AND times_b.time_in <= times_a.time_out)
                     OR
                     ( times_a.time_in <= times_b.time_out AND times_b.time_out <= times_a.time_out)
                  )
             GROUP BY 
               times_a.id_time, 
               times_a.time_in,
               times_a.time_out,
               week_of_year,
               year)              
             UNION
             (
               SELECT 
                 WEEKOFYEAR(time_in) AS week_of_year,
                 YEAR(time_in) AS year
               FROM times
               WHERE user_id='".$user_id."'
                AND (LOWER(status_in)=LOWER('Observed')
                    OR 
                    LOWER(status_out)=LOWER('Observed')
                    )
             )
             ";
      
      //echo "<br><br>".$sql."<br>";
      
      $query = $this->db->query($sql);            
      return $query->result_array();
   }
   #############################################################################
   function is_cannot_save($time_id, $user_id, $time_attempt, $type_time)
   {
      $date_time_pivot = $this->get_time($time_id);

      $result = FALSE;
      
      if( ! isset( $time_attempt) )
      {
         return FALSE;
      }
      
      $status_valid="valid";
      
      /*&&&&&&&&&&&&&&&&&&&&&&&&&&&   BELOW  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
      $sql_by_time_before="
         SELECT time_out AS date_time_before
         FROM times
         WHERE time_out IS NOT NULL 
           AND id_time != ".$time_id."
           AND user_id = ".$user_id."
           AND lower(status_out)=lower('".$status_valid."')
           AND  CAST(time_out AS DATETIME) < CAST('".$date_time_pivot['time_in']."' AS DATETIME)
         ORDER BY time_out DESC LIMIT 1;";
      
      
       $query = $this->db->query($sql_by_time_before);
       $list_before = $query->result_array();
       
      if( isset($list_before) AND count($list_before)>0 )
      {
         $date_time_before = $list_before[0]['date_time_before'];
      }
      else
      {
         $sql_by_time_before="
         SELECT time_in AS date_time_before
         FROM times
         WHERE time_in IS NOT NULL 
            AND id_time != ".$time_id."
            AND user_id = ".$user_id."
            AND lower(status_in)=lower('".$status_valid."')
            AND  CAST(time_in AS DATETIME) < CAST('".$date_time_pivot["time_".$type_time]."' AS DATETIME)
         ORDER BY time_in DESC LIMIT 1;";

         $query = $this->db->query($sql_by_time_before);            
         $list_before = $query->result_array();
         
         if(isset($list_before) AND count($list_before)>0)
         {
            $date_time_before = $list_before[0]['date_time_before'];             
         }
      }
      
      /*&&&&&&&&&&&&&&&&&&&&&&&&&&&   VERIFY IF NOT LAST &&&&&&&&&&&&&&&&&&&&&*/
      $sql_is_last ="SELECT * 
                     FROM times 
                     WHERE user_id=".$user_id."
                       AND id_time=".$time_id." 
                       AND time_in=(SELECT max(time_in) AS time_in FROM times WHERE user_id =".$user_id.");";
      
      $query_is_last = $this->db->query($sql_is_last);
      
      $time_last = $query_is_last->result_array();
      
      if(count($time_last)<=0)
      {
         /*&&&&&&&&&&&&&&&&&&&&&&&&&&&&   OVER  &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&*/
         $sql_by_time_after="
            SELECT time_in AS date_time_after
            FROM times
            WHERE time_in IS NOT NULL 
               AND id_time != ".$time_id."
               AND user_id = ".$user_id."
               AND lower(status_in)=lower('".$status_valid."') 
               AND CAST(time_in AS DATETIME) > CAST('".$date_time_pivot["time_".$type_time]."' AS DATETIME)
            ORDER BY time_in ASC LIMIT 1;";

         $query = $this->db->query($sql_by_time_after);
         $list_after = $query->result_array();

         if( isset($list_after) AND count($list_after)>0 )
         {
            $date_time_after = $list_after[0]['date_time_after'];
            
         }
         else
         {
            $sql_by_time_after = "
            SELECT time_out AS date_time_after
            FROM times
            WHERE time_out IS NOT NULL 
               AND id_time != ".$time_id."
               AND user_id = ".$user_id."
               AND lower(status_out)=lower('".$status_valid."')
               AND  CAST(time_out AS DATETIME) > CAST('".$date_time_pivot['time_in']."' AS DATETIME)
            ORDER BY time_out ASC LIMIT 1;";

            
            $query = $this->db->query($sql_by_time_after);            
            $list_after = $query->result_array();


            if(isset($list_after) AND count($list_after)>0)
            {
               $date_time_after = $list_over[0]['date_time_after'];

            }
         }         
      }
      
      
      if( isset($date_time_before) AND strtotime($date_time_before) >= strtotime($time_attempt) OR 
        ( isset($date_time_after) AND strtotime($date_time_after) <= strtotime($time_attempt) ) OR 
                   
          ( strcasecmp( $type_time,"in")==0 AND  
            isset($date_time_pivot['status_out']) AND
            strtotime($time_attempt) >= strtotime($date_time_pivot['time_out'] ) 
          )  OR
          
          ( strcasecmp( $type_time,"out")==0 AND  
            isset($date_time_pivot['status_in']) AND
            strtotime($time_attempt) <= strtotime($date_time_pivot['time_in'] ) 
          )
        )
      {
         $result = TRUE;
      } 
      
      return $result;
   }
   
   ##################################################################################################
   function is_date_cross_range($id_time, $user_id, $in_out=null, $target_time=null)
   {
      $date_time_pivot = $this->get_time($id_time);
      
      if(isset($in_out) AND strcasecmp($in_out,"in") == 0 AND isset($target_time))
      {
         $date_time_pivot['time_in'] = $target_time;
      }
      else if(isset($in_out) AND isset($target_time))
      {
         $date_time_pivot['time_out'] = $target_time;
      }
      //case 1) IS CONTAINED  for IN and OUT  :) :) :) :) :) :) :) :) :) :) 
      //case 2) CONTAINS     for  IN and  OUT       :) :) :) :) :) :) :) :)
      $sql_by_time_before = "
         SELECT time_in, time_out
         FROM times
         WHERE time_out IS NOT NULL
           AND user_id = ".$user_id."
           AND id_time != ".$date_time_pivot['id_time']."
           AND
           (
              (
                 CAST(time_in AS DATETIME) <= CAST('".$date_time_pivot['time_in']."' AS DATETIME)
                 AND
                 CAST('".$date_time_pivot['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME)
              )
              OR
              (
                CAST(time_in AS DATETIME) <= CAST('".$date_time_pivot['time_out']."' AS DATETIME)
                AND
                CAST('".$date_time_pivot['time_out']."' AS DATETIME) <= CAST(time_out AS DATETIME)
              )
              OR
              (
                CAST('".$date_time_pivot['time_in']."' AS DATETIME) <= CAST(time_in AS DATETIME)
                AND
                CAST(time_in AS DATETIME) <= CAST('".$date_time_pivot['time_out']."' AS DATETIME)
              )
              OR
              (
                CAST('".$date_time_pivot['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME)
                AND
                CAST(time_out AS DATETIME) <= CAST('".$date_time_pivot['time_out']."' AS DATETIME)
              )
           )
         ORDER BY time_out;";
     
      
      $query = $this->db->query($sql_by_time_before);
      
      $list_times_intermediate = $query->result_array();
            
      
      if(count($list_times_intermediate)>0)
      {
         return TRUE;
      }
      return FALSE;
   }
   ##################################################################################################
   function is_date_record_cross_range($time_record_tentative, $user_id)
   {
      //case 1) IS CONTAINED  for IN and OUT  :) :) :) :) :) :) :) :) :) :) 
      //case 2) CONTAINS     for  IN and  OUT       :) :) :) :) :) :) :) :)
      
      $sql_by_time_before = "
         SELECT time_in, time_out
         FROM times
         WHERE time_out IS NOT NULL
           AND user_id = ".$user_id."           
           AND
           (
              (
                 CAST(time_in AS DATETIME) <= CAST('".$time_record_tentative['time_in']."' AS DATETIME)
                 AND
                 CAST('".$time_record_tentative['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME)
              )
              OR
              (
                CAST(time_in AS DATETIME) <= CAST('".$time_record_tentative['time_out']."' AS DATETIME)
                AND
                CAST('".$time_record_tentative['time_out']."' AS DATETIME) <= CAST(time_out AS DATETIME)
              )
              OR
              (
                CAST('".$time_record_tentative['time_in']."' AS DATETIME) <= CAST(time_in AS DATETIME)
                AND
                CAST(time_in AS DATETIME) <= CAST('".$time_record_tentative['time_out']."' AS DATETIME)
              )
              OR
              (
                CAST('".$time_record_tentative['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME)
                AND
                CAST(time_out AS DATETIME) <= CAST('".$time_record_tentative['time_out']."' AS DATETIME)
              )
           )
           ORDER BY time_out;";
     
      /*
      echo "<br>*************".$sql_by_time_before."********<br>";
      exit;
      */
      
      $query = $this->db->query($sql_by_time_before);
      
      $list_times_intermediate = $query->result_array();
            
      
      if(count($list_times_intermediate)>0)
      {
         /*
         echo "<br>**************  LIST CROSS TIME ******  ";
         print_r($list_times_intermediate);
         */
         return TRUE;
      }
      return FALSE;
   }
   
   /**
    * @param
    * @return
    */
   function add_time_record($properties)
   {
      $this->db->insert("times",$properties);      
   }
   
    /**
    * @param        $user_id Is id_time from TABLE times, to filter user x data only
    *                        their values ​​should be [INTEGER]
    * @param        $time_in  Is begin Date Time to filter
    *                         their values ​​should be [DATE_TIME or DATE '2011-03-25 13:00:00' OR '2011-03-25']
    * @param        $time_out Is begin Date Time to filter
    *                          their values ​​should be [DATE_TIME or DATE  '2011-04-02 13:00:00' OR '2011-04-02']
    *
    * @return        array(
    *                      'id_time'=>[INTEGER],
    *                      'time_in'=>[DATE_TIME],
    *                      'time_out'=>[DATE_TIME],
    *                      'status_in'=>['valid' | 'corrected' | 'observed'],
    *                      'status_out'=>['valid' | 'corrected' | 'observed']
    *                      );
    *
    *                the array will only have ONE OR MORE rows depending on the condition
    */
    function is_time_in($user_id, $begin_date = 0, $end_date = 0)
    {
         
         $between = "";
         if ($begin_date != 0 or $end_date != 0 )
         {
            $between = " AND ((
                    time_in BETWEEN CAST('".$begin_date." 00:00:00' AS DATETIME) AND CAST('".$end_date." 23:59:59' AS DATETIME)
                   )
                   OR
                   (
                     time_out BETWEEN CAST('".$end_date." 00:00:00' AS DATETIME) AND CAST('".$end_date." 23:59:59' AS DATETIME)
                   )) ";
          }
          $sql_last_added="SELECT id_time,
                                 time_in,
                                 time_out,
                                 status_in,
                                 status_out
                          FROM times
                          WHERE user_id=".$user_id.$between.                       
                          " ORDER BY time_in DESC, time_out DESC LIMIT 1;";
         
            
         $query = $this->db->query($sql_last_added);
         $time_last = $query->row_array();  
           
         if($query->num_rows() == 0 OR strcasecmp($time_last['status_out'],"") != 0)
         {
           $data['in'] = TRUE;
           if(isset($time_last['id_time']))
           {
             $data['id_time'] = $time_last['id_time'];
           }
           return $data;
         }
         else
         {
           $data['in'] = FALSE;
           $data['id_time'] = $time_last['id_time'];           
           return $data;
         }    
    }
   
   
   /**
    * @param        $user_id              user_id from USER for anyone trying to record the time
    *                                     their values ​​should be [INTEGER]
    * @return       [boolean] TRUE if added FALSE if failed
    */

   function update_time($properties, $conditions)
   { 
      $this->db->update("times", $properties, $conditions);
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
   /**
    * @param        $user_id              user_id from USER for anyone trying to record the time
    *                                     their values ​​should be [INTEGER]
    * @return       [boolean] TRUE if added FALSE if failed
    */
   function add_time($properties)
   {
      $this->db->insert("times",$properties);
   }
   /**    
    * @param        $id_time Is id_time from TABLE times to filter time x data only
    * 
    * @return        array(
    *                      'id_time'=>[INTEGER AUTOINCREMENT],
    *                      'user_id'=>[INTEGER],
    *                      'time_in'=>[TIMESTAMP],
    *                      'time_out'=>[TIMESTAMP],
    *                     'status_in'=>=>['valid' |  'observed' | 'corrected'],
    *                      'status_out'=>=>['valid' |  'observed' | 'corrected'],
    *                      );
    *
    *                the array will only have ONE row by the condition
    */
   function get_time($id_time)
   {
      $sql_time="SELECT * FROM times WHERE id_time=".$id_time;
      $query = $this->db->query($sql_time);
      $time = $query->row_array();
      return $time;
   }
}
?>
