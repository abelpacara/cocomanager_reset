<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends Model_Template
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles

   #############################################################################
	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
	}
   #############################################################################
   function get_user_status($user_id)
   {
      $this->db->select("status, id AS user_role_id");
      $this->db->from("user_roles");
      $this->db->where("user_id",$user_id);
      $this->db->order_by("id DESC");
      $this->db->limit("1");      
      $query = $this->db->get();
      return $query->row_array();
   }
   #############################################################################
   function update_user_status($user_id, $status)
   {
      $last_user_role = $this->get_user_status($user_id);
      $data["status"] = $status;
      $this->db->update("user_roles", $data, array("id"=>$last_user_role['user_role_id']));      
   }
   #############################################################################
   function update_client($data, $conditions)
   {
      $query=$this->db->update("clients", $data, $conditions);
   }
   #############################################################################
   function delete_client($client_user_id)
   {
      $this->db->cache_on();
      $this->db->where("clients.user_id", $client_user_id);      
      $this->db->delete("clients");
      $this->db->cache_off();
      
      $this->db->cache_on();
      $this->db->where("user_profiles.user_id", $client_user_id);      
      $this->db->delete("user_profiles");      
      $this->db->cache_off();
      
      $this->db->cache_on();
      $this->db->where("users.id", $client_user_id);      
      $this->db->delete("users");      
      $this->db->cache_off();
   }
   #############################################################################
   function delete_client_company($client_company_id)
   {
      $this->db->select("user_id, client_company_id");
      $this->db->from("clients");
      $this->db->where("client_company_id", $client_company_id);
      
      $query = $this->db->get();
      
      $list_clients = $query->result_array();
      
      for($i=0;$i<count($list_clients); $i++)
      {
         $this->db->cache_on();         
         $this->db->delete("clients", array("user_id"=>$list_clients[$i]['user_id']));
         $this->db->cache_off();

         $this->db->cache_on();         
         $this->db->delete("user_profiles", array("user_id"=>$list_clients[$i]['user_id']));
         $this->db->cache_off();

         $this->db->cache_on();
         $this->db->delete("users", array("id"=>$list_clients[$i]['user_id']));
         $this->db->cache_off();
      }
      
      $this->db->cache_on();      
      $this->db->delete("client_companies", array("id_client_company"=>$client_company_id));
      $this->db->cache_off();      
   }
   #############################################################################
   function get_client_company($client_company_id)
   {
      $this->db->select("*");
      $this->db->from("client_companies");      
      $this->db->where("client_companies.id_client_company", $client_company_id);
      
      $query = $this->db->get();      
      return $query->row_array();
   }
   
   #############################################################################
   function update_client_company($data, $conditions)
   {
      $query=$this->db->update("client_companies", $data, $conditions);            
   }
   #############################################################################
   function add_client_company($data)
   {
      $this->db->insert("client_companies", $data);            
      return $this->db->insert_id();
   }
   #############################################################################
   function get_list_clients_companies($company_id)
   {
      $this->db->select("client_companies.name, 
                         client_companies.id_client_company,
                         client_companies.description");
      $this->db->from("client_companies");      
      $this->db->where("client_companies.company_id", $company_id);
      
      $query = $this->db->get();      
      return $query->result_array();
   }
   #############################################################################
   /*
    
    CAST(time_in AS DATETIME) <= CAST('".$date_time_pivot['time_in']."' AS DATETIME)
                 AND
                 CAST('".$date_time_pivot['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME) 
    
    */
   function get_list_clients_by($client_company_id)
   {
      $this->db->select("up.user_id,
                         up.name, 
                         up.last_name");
                   
      $this->db->select("us.email,
                         us.username");
                   
      $this->db->select("ro.name AS role");
      $this->db->select("cl.client_company_id");
                          
      $this->db->from("user_profiles up");
      $this->db->join("users us","us.id = up.user_id");
      $this->db->join("user_roles ur","up.user_id = ur.user_id");
      $this->db->join("roles ro","ur.role_id = ro.id");
      $this->db->join("clients cl","cl.user_id = us.id");
      $this->db->where("cl.client_company_id",$client_company_id);
      $this->db->where("LOWER(ur.status)","active");
      $this->db->where("LOWER(ro.name)","client");
      $this->db->order_by("cl.client_company_id");
      
      $query = $this->db->get();
      return $query->result_array();
   }
   #############################################################################
   function add_client($data)
   {
      $this->db->insert("clients", $data);      
   }
   #############################################################################
   /*
    
    CAST(time_in AS DATETIME) <= CAST('".$date_time_pivot['time_in']."' AS DATETIME)
                 AND
                 CAST('".$date_time_pivot['time_in']."' AS DATETIME) <= CAST(time_out AS DATETIME) 
    
    */
   function get_list_users_by($company_id, $dt_range_begin, $dt_range_end)
   {
      $sql="SELECT up.user_id,
                   up.name, 
                   up.last_name, 
                   us.email,
                   us.username,
                   ti.max_time_in,
                   ti.num_corrections,
                   ro.name AS role,
                   IF( (up.user_id,ti.max_time_in)  
                      IN (SELECT user_id, time_in
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
   
   
   #######################################################
   function get_list_users_activated_by_company($company_id, $user_id_minus=null)
   {
      $sql_user_minus="";
      if(isset($user_id_minus))
      {
         $sql_user_minus = "AND us.user_id=".$user_id_minus;
      }
      
      $sql="SELECT us.*, up.name, up.last_name, up.user_id
            FROM users us,
                 user_profiles up,
                 user_roles
            WHERE us.id=up.user_id
              AND us.company_id='".$company_id."'
              AND us.id NOT IN(SELECT user_id FROM clients)
              
              AND lower(user_roles.user_id)=us.id
              AND lower(user_roles.status)='active'
              
            ORDER BY us.id DESC
              ".$sql_user_minus.";";
      
      $query_result = $this->db->query($sql);
      return $query_result->result_array();
   }
   #######################################################
   function get_list_users_by_company($company_id, $user_id_minus=null)
   {
      $sql_user_minus="";
      if(isset($user_id_minus))
      {
         $sql_user_minus = "AND us.user_id=".$user_id_minus;
      }
      
      $sql="SELECT us.*, up.name, up.last_name, up.user_id
            FROM users us,
                 user_profiles up
            WHERE us.id=up.user_id
              AND us.company_id='".$company_id."'
              AND us.id NOT IN(SELECT user_id FROM clients)
            ORDER BY us.id DESC
              ".$sql_user_minus.";";
      
      $query_result = $this->db->query($sql);
      return $query_result->result_array();
   }
   #############################################################################
   function get_list_users_by_role_type($role_type, $company_id)
   {
      $this->db->select("users.id, users.username,
                     users.email");
      
      $this->db->select("user_profiles.user_id,
                         user_profiles.country,
                         user_profiles.website,
                         user_profiles.name,
                         user_profiles.last_name");
      
      $this->db->from("users");
      $this->db->join("user_profiles", "users.id=user_profiles.user_id");
      $this->db->join("user_roles", "user_roles.user_id=user_profiles.user_id");
      $this->db->join("roles", "roles.id=user_roles.role_id");
      
      $this->db->where("lower(roles.role_type)", strtolower($role_type) );      
      $this->db->where("roles.company_id", $company_id );      
      $this->db->where("user_roles.status", "active");
      
      $query = $this->db->get();      
      return $query->result_array();
   }
   #############################################################################
   function get_last_activated_user_role($user_id, $company_id=null)
   {
      $this->db->select("user_roles.id,
                         user_roles.role_id,
                         user_roles.user_id,
                         roles.role_type,
                         roles.name,
                         roles.company_id
                         ");
      $this->db->from("roles");
      $this->db->join("user_roles", "user_roles.role_id=roles.id");
      $this->db->where("user_roles.status", "active");      
      
      if(isset($company_id))
      {
         $this->db->where("roles.company_id", $company_id);      
      }
      
      $this->db->where("user_roles.user_id", $user_id);      
      $query = $this->db->get();
            
      return $query->row_array();
   }
   
   #############################################################################
   function is_exists_uri_privilege($uri)
   {
      $sql="SELECT COUNT(*) AS count_results FROM privileges WHERE LOWER(uri)=LOWER('".$uri."');";      
      
      $query = $this->db->query($sql);
      $row = $query->row_array();      
      return ($row['count_results']>0);
   }
   #######################################################
   function get_list_user_session_actived()
   {
      $sql = "SELECT *
              FROM ci_sessions;";
      $query = $this->db->query($sql);      
      return $query->result_array();
   }
   #######################################################
   function get_role_by_type($type, $company_id=null)
   {
      $this->db->select('
         id,
         company_id,
         role_type,
         name
         ');
      $this->db->from("roles");
      
      if(isset($company_id))
      {
         $this->db->where("roles.company_id",$company_id);
      }      
      $this->db->where("lower(roles.role_type)",  strtolower($type) );      
      #----------------------------------------------
      
      $query_role = $this->db->get();      
       return $query_role->row_array();
   }
   #############################################################################
   function delete_role($role_id)
	{
		$this->db->where('id', $role_id);
		$this->db->delete('roles');		
		return ($this->db->affected_rows()>0);
	}
   #############################################################################
   function is_available_email($user_id, $email_as_username)
   {
      $sql="SELECT COUNT(*) AS count_users 
            FROM users 
            WHERE id != '".$user_id."'
              AND email='".$email_as_username."'";
      
      $query = $this->db->query($sql);      
      $array = $query->row_array();            
      return (count($array)>0 AND $array['count_users']==0);
   }
   #############################################################################
   function is_available_username($user_id, $username)
   {  
      $sql="SELECT COUNT(*) AS count_users 
            FROM users 
            WHERE id != '".$user_id."'
              AND username='".$username."'";
      $query=$this->db->query($sql);
      
      
      $array = $query->row_array();            
      return (count($array)>0 AND $array['count_users']==0);
   }
   #############################################################################
   function update_company($properties, $conditions)
   { 
      $this->db->update("companies", $properties, $conditions);
      return $this->db->affected_rows();
   }
   #############################################################################
   function get_user_complete_by_id($user_id)
   {
      $this->db->select("users.*");
      
      $this->db->select("user_profiles.user_id,
                     user_profiles.country,
                     user_profiles.website,
                     user_profiles.name,
                     user_profiles.language,
                     user_profiles.last_name");
      
      $this->db->select("roles.name AS role");
      
      $this->db->from("users");
      $this->db->join("user_profiles", "user_profiles.user_id=users.id"); 
      $this->db->join("user_roles", "user_roles.user_id=users.id"); 
      $this->db->join("roles", "roles.id=user_roles.role_id"); 
      
      $this->db->where("users.id", $user_id);
      //$this->db->where("user_roles.status", "active");
      
      $query = $this->db->get();      
      return $query->row_array();
   }
   #############################################################################
   function get_company_by_id($company_id)
   {
      $sql = "SELECT * FROM companies WHERE id='".$company_id."';";
      $query = $this->db->query($sql);      
      
      return $query->row_array();
   }
   #############################################################################
   function get_company_by_name($name)
   {
      $sql = "SELECT * FROM companies WHERE LOWER(name)=LOWER('".$name."')";      
      $query = $this->db->query($sql);      
      return $query->row_array();
   }
   
   #############################################################################
   function get_privilege_by_name($privilege_name)
   {
      $sql="SELECT * FROM privileges WHERE lower(name)='".strtolower($privilege_name)."';";
      
      $query = $this->db->query($sql);
      return $query->row_array();
   }
   #############################################################################
   function update_user_profile($properties, $conditions)
   {
      $this->db->update("user_profiles",$properties, $conditions);
      return $this->db->affected_rows();
   }
   #############################################################################
   function update_user($properties, $conditions)
   {
      $this->db->update("users",$properties, $conditions);
      return $this->db->affected_rows();
   }
   #############################################################################
   function get_list_modules_by_user($user_id)
   {
      $sql="SELECT mo.*
            FROM modules mo,
                 privileges pri,
                 role_privileges rp,
                 user_roles ur
            WHERE pri.module_id = mo.id
              AND rp.privilege_id = pri.id
              AND rp.role_id = ur.role_id
              AND ur.status = 'active'
              AND rp.status = 'active'
              AND ur.user_id = '".$user_id."'
            GROUP BY mo.name
            ORDER BY order_by ASC;";      
      
      $query = $this->db->query($sql);      
      
      
      //echo $this->db->last_query();
      
      return $query->result_array(); 
   }   
   #############################################################################
   function get_list_modules()
   {
      $sql="SELECT *
            FROM modules 
            WHERE id IN (SELECT module_id FROM privileges)
            ORDER BY order_by ASC
            ;";
      $query = $this->db->query($sql);      
      return $query->result_array(); 
   }
   #######################################################
   function get_list_roles_actived_by_user($user_id)
   {
      $sql_roles="SELECT ur.user_id, ur.role_id, ro.* 
                  FROM user_roles ur,
                       roles ro
                  WHERE ur.role_id = ro.id
                    AND ur.user_id ='".$user_id."'
                    AND ur.status='active'";
      
      $query = $this->db->query($sql_roles);      
      return $query->result_array(); 
   }
   #######################################################
   function unassign_role_to_user($role_id, $user_id)
   {      
      $this->db->set('status','inactive');
      
      if(isset($role_id))
      {
         $this->db->where("role_id",$role_id);
      }
      if(isset($user_id))
      {
         $this->db->where("user_id",$user_id);
      }
      
      
      $this->db->update('user_roles');
      
      
      //echo "<br>SQL LAST = ".$this->db->last_query();
      
      return $this->db->affected_rows();
   }
   #######################################################
   function is_assigned_role_to_user($role_id, $user_id)
   {
      $sql_is_assign="SELECT * 
                      FROM user_roles
                      WHERE role_id='".$role_id."' 
                        AND user_id='".$user_id."'
                        AND status='active';";
      
      $query_role_privileges = $this->db->query($sql_is_assign);      
      $array_result =$query_role_privileges->result_array(); 
      
      return (count($array_result)>0);
   }
   
   
   #######################################################
   function get_list_users_roles_activated_by_company($company_id)
   {
      $sql="SELECT ur.*
              FROM user_roles ur,
                   users us,
                   roles ro
            WHERE ur.user_id=us.id
              AND ur.role_id=ro.id
              AND us.company_id='".$company_id."'
              AND ro.company_id='".$company_id."'
              AND ur.status='active';";
      
      //echo "<br>".$sql;
      
      $query_result = $this->db->query($sql);
      return $query_result->result_array();
   }
   #######################################################
   function get_list_users_by_company_with_times_in_range($company_id, $dt_begin, $dt_end)
   {
      
      $sql="SELECT us.*, up.name, up.last_name 
            FROM users us,
                 user_profiles up
            WHERE us.id=up.user_id
              AND us.company_id='".$company_id."'      
              AND us.id IN(SELECT user_id FROM times WHERE (time_in BETWEEN CAST('".$dt_begin."' AS DATETIME) 
                                                                              AND 
                                                                            CAST('".$dt_end."' AS DATETIME))
                                                           OR
                                                           (time_out BETWEEN CAST('".$dt_begin."' AS DATETIME) 
                                                                              AND 
                                                                             CAST('".$dt_end."' AS DATETIME))
                           );";
      //echo $sql;
      
      $query_result = $this->db->query($sql);
      return $query_result->result_array();
   }
   
   #######################################################
   function assign_privileges_to_role_admin($role_id)
   {
      $list_privileges = $this->get_list_privileges();
      
      
      for($i=0; $i<count($list_privileges);$i++)
      {         
         $data=array(
            'role_id'=>$role_id,
            'privilege_id'=>$list_privileges[$i]['id'],
            'status'=>'active'
            );
         
         $this->db->insert('role_privileges',$data);         
      }
   }
   #######################################################
   function assign_privilege_to_role($privilege_id, $role_id)
   {
      $data=array('privilege_id'=>$privilege_id,
                  'role_id'=>$role_id,
                  'status'=>'active');
      $this->db->insert('role_privileges',$data);
      return array('id'=>$this->db->insert_id());
   }
   
   #######################################################
   function unassign_privilege_to_role($privilege_id, $role_id)
   {      
      $this->db->set('status','inactive');
            
      $this->db->where("privilege_id",$privilege_id);
      $this->db->where("role_id",$role_id);
      
      $this->db->update('role_privileges');
      
      return $this->db->affected_rows();
   }
   #######################################################
   function is_assigned_privilege_to_role($role_id, $privilege_id=null, $privilege_name=null)
   {
      $sql="";
      if( strcasecmp( $privilege_id, "")!=0)
      {
         $sql .=" AND privilege_id='".$privilege_id."'";
      }
      
      if( strcasecmp( $privilege_name, "")!=0)
      {
         $sql .=" AND lower(privileges.name)='".strtolower($privilege_name)."'";
      }
      
      $sql_is_assign="SELECT role_privileges.* 
                      FROM role_privileges, privileges
                      WHERE role_id='".$role_id."' 
                        AND role_privileges.privilege_id = privileges.id
                        ".$sql."
                        AND role_privileges.status='active';";
      $query_role_privileges = $this->db->query($sql_is_assign);      
      
      $array_result = $query_role_privileges->result_array();       
      return (count($array_result)>0);
   }
   #######################################################
   function get_list_roles_privileges_actived_by_company($company_id)
   {
      $sql="SELECT rp.*, ro.name AS role 
            FROM roles ro, 
                 role_privileges rp
            WHERE ro.id=rp.role_id
              AND ro.company_id='".$company_id."'
              AND status='active'
            ORDER BY ro.name";
      
      
      $query_role_privileges = $this->db->query($sql);      
      return $query_role_privileges->result_array();
   }
   
   
   #######################################################
   function get_list_privileges2($company_id=null)
   {
      $sql_company = "";
      if(isset($company_id))
      {
         $sql_company = " AND roles.company_id = '".$company_id."' ";
      }
      
      $sql = "SELECT pri.*, role_privileges.role_id,
                     mo.name AS module_name, 
                     mo.uri AS module_uri,                     
                     IF(pri.id IN (
                                    SELECT role_privileges.privilege_id
                                    FROM role_privileges, roles
                                    WHERE role_privileges.role_id = roles.id
                                      AND LOWER(status) = 'active'
                                      ".$sql_company."
                                    ), TRUE, NULL ) AS is_assigned
              FROM privileges pri,
                   modules mo
              WHERE pri.module_id = mo.id
                AND role_privileges.privilege_id = pri.id
              ORDER BY pri.module_id ASC, role_privileges.role_id, pri.order_by ASC;";
      
     
      $query_privilege = $this->db->query($sql);      
      return $query_privilege->result_array();
   }   
   #######################################################
   /*function get_list_privileges_role($company_id)
   {
      $sql = "SELECT rp.*
                FROM roles ro,
                 role_privileges rp,
                 privileges priv
            WHERE ro.id=rp.role_id
                  AND rp.id=priv.id
                 AND ro.company_id='".$company_id."'
                 AND rp.status='active' ;";
      
      $query_privilege = $this->db->query($sql);      
      return $query_privilege->result_array();
   }*/
   #######################################################
   function get_list_privileges()
   {
      $sql = "SELECT pri.*, 
                     mo.name AS module_name, 
                     mo.uri AS module_uri
              FROM privileges pri,
                   modules mo
              WHERE pri.module_id = mo.id
              ORDER BY pri.module_id ASC, pri.order_by ASC ;";
      
      $query_privilege = $this->db->query($sql);      
      return $query_privilege->result_array();
   }
   #######################################################
   function get_list_roles_by_company($company_id)
   {
      $sql = "SELECT *
              FROM roles 
              WHERE company_id='".$company_id."';";
      $query_role = $this->db->query($sql);      
      return $query_role->result_array();
   }
   
   #######################################################
   function get_list_privileges_assigned_by_role($role_id)
   {
      $sql = "SELECT pri.*,
                     sel.value_select AS visibility,
                     
                     mo.name AS module_name, 
                     mo.uri AS module_uri,                     
                     pri.icon_uri
              FROM privileges pri, 
               role_privileges rop,
               modules mo,
               selectables sel

              WHERE rop.role_id='".$role_id."'                  
                AND pri.module_id = mo.id
                AND pri.id=rop.privilege_id
                AND rop.status='active'
                
                AND pri.visibility_select_id = sel.id
                
              ORDER BY mo.order_by ASC, pri.order_by ASC;";
            
      $query_privilege = $this->db->query($sql);      
      $array_query_privilege = $query_privilege->result_array();
      return $array_query_privilege;
   }
   #######################################################
   function get_role_by_name_and_company($name, $company_id)
   {
      $query_role = $this->db->query("SELECT * FROM roles
                                WHERE lower(name)='".$name."' AND company_id='".$company_id."';");      
       return $query_role->row_array();
   }   
   #######################################################
   function create_company($data)
   {
      if ($this->db->insert('companies', $data)) 
      {
			return array('id'=>$this->db->insert_id());
      }
      return NULL;
   }
   #######################################################
   function create_role($data)
   {
      $this->db->insert('roles', $data);
      return array('id' =>$this->db->insert_id());
   }
   
   function assign_role_to_user($data)
   {
      $this->db->insert('user_roles', $data);
      return array('user_roles_id' =>$this->db->insert_id());
   }
   
   #######################################################
   /**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
   function get_user_by_id($user_id, $activated)
	{
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		$this->db->where('LOWER(username)=', strtolower($login));
		$this->db->or_where('LOWER(email)=', strtolower($login));
		$query_user = $this->db->get($this->table_name);
      
		if ($query_user->num_rows() == 1)
      {
         //$user = $query_user->row_array();
         $user = $query_user->row();
         
       
         
         $query_company = $this->db->query('SELECT name AS company FROM companies WHERE id='.$user->company_id);
         
         $company = $query_company->row();
         
         $query_profile = $this->db->query('SELECT name, last_name FROM user_profiles 
                                            WHERE user_id='.$user->id);         
         $user_profile = $query_profile->row();
         
         
         
         $sql_roles="SELECT ro.* 
                           FROM roles ro, user_roles ur
                           WHERE ro.id=ur.role_id
                             AND ur.status='active'
                             AND
                                 ur.user_id=".$user->id;
         
         $query_roles = $this->db->query($sql_roles);
         
         //echo "<br>".$sql_roles;
         
         $i=0;
         foreach($query_roles->result_array() AS $row)
         {
            $roles[$i] = $row;           
            $i++;
         }
         
         
         $data_array = array_merge((array)$user, (array) $user_profile, (array)$company, array('roles'=>$roles) );
         
         $data_object = (object) $data_array;
         return $data_object;
         ####################################################################
      }
		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $activated = TRUE, $profile_data=array())
	{
		$data['created'] = date('Y-m-d H:i:s'); 
      //$this->db->set('created','NOW()', FALSE);
		$data['activated'] = $activated ? 1 : 0;

		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			if ($activated)	
         {
            //$this->create_profile($user_id);
            $profile_data['user_id'] = $user_id;
          
            $this->db->insert($this->profile_table_name, $profile_data);
            //exit;
         }
			return array('user_id' => $user_id);
		}
		return NULL;
	}
   
	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		if ($activate_by_email) {
			$this->db->where('new_email_key', $activation_key);
		} else {
			$this->db->where('new_password_key', $activation_key);
		}
		$this->db->where('activated', 0);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() == 1) {

			$this->db->set('activated', 1);
			$this->db->set('new_email_key', NULL);
			$this->db->where('id', $user_id);
			$this->db->update($this->table_name);

			$this->create_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete($this->table_name);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete($this->table_name);
		if ($this->db->affected_rows() > 0) {
			$this->delete_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
		$this->db->set('new_password_key', $new_pass_key);
		$this->db->set('new_password_requested', 'NOW()', FALSE);  
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
	{
		$this->db->set('password', $new_pass);
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated)
	{
		$this->db->set($activated ? 'new_email' : 'email', $new_email);
		$this->db->set('new_email_key', $new_email_key);
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		$this->db->set('email', 'new_email', FALSE);
		$this->db->set('new_email', NULL);
		$this->db->set('new_email_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_email_key', $new_email_key);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);

		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login','NOW()', FALSE);

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 1,
			'ban_reason'	=> $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 0,
			'ban_reason'	=> NULL,
		));
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert($this->profile_table_name);
	}

	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->profile_table_name);
	}
}
