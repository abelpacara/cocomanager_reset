<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		Tank_auth
 * @author		Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version		1.0.9
 * @based on	DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth
{
	private $error = array();
   
	function __construct()
	{
		$this->ci =& get_instance();
      
		$this->ci->load->config('tank_auth', TRUE);
		$this->ci->load->library('session');      
      $this->ci->load->helper(array('form', 'url'));      
		$this->ci->load->database();
		$this->ci->load->model('tank_auth/users');
      $this->ci->load->model('users');
      $this->ci->load->model('model_projects');
		$this->autologin();
	}
   
   /**
	 * Change user password (by administrator)
	 *
	 * @param	string	 
	 * @return	bool
	 */
	function set_new_password($user_id, $new_pass)
	{
		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if old password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			
				// Hash new password using phpass
				$hashed_password = $hasher->HashPassword($new_pass);
            
            $this->ci->users->change_password($user_id, $hashed_password);
            return TRUE;
		}
		return FALSE;
	}
   
   #############################################################################
   function attempt_login_included_company()
   {
      //do review and get since URI SEGMENT =1 ....
      $array_params = $this->ci->uri->uri_to_assoc($since_uri_segment=1);      
      
      if(isset($array_params['company']))
      {
          $company_found = $this->get_company_by_name($array_params['company']);          
          $this->ci->session->set_userdata('company_logged', $company_found);          
      }
   }
   #############################################################################
   function get_company_by_name($name)
   {
      return $this->ci->users->get_company_by_name($name);
   }
   
   #############################################################################
   function has_not_privilege(& $header_data)
   {
      if(! isset($header_data) OR ! isset($header_data['has_privilege']) OR $header_data['has_privilege']==false)
      {
         redirect("auth/redirect_has_not_privilege?seg_priv=".urlencode($header_data['segment_privilege']));
      }
   }
   ###########################################################################################################
   function get_header_data_whitout_login($language_change_manual=false)
   {
       $data = array();       
      
      $data['have_access_to_privilege'] = false;      
      $data['array_messages']=null;
       
      $data['user_id'] = $user_id;

      $user = $this->ci->users->get_user_complete_by_id($user_id);
      
      if( ! $language_change_manual)
      {
         $this->ci->lang->load('coco',$user['language']);
      }
      
      $data['company_logged'] = $company_logged = $this->ci->users->get_company_by_id($user['company_id']);

      $data['username']	= $user['username'];
      $data['profile_name'] = $user['name'];
      $data['profile_last_name'] = $user['last_name'];
            
      ##########################################################
      $data['language'] = $user['language'];
      
      

      $data['company_id'] = $company_logged['id'];
      $data['company'] = $company_logged['name'];

      $data['list_modules'] = $this->ci->users->get_list_modules_by_user( $user_id );         

      $data['uri_images_users'] = $this->ci->config->item('uri_images_users');//$this->uri_photography;
      $data['uri_images'] = $this->ci->config->item('uri_images');//$this->uri_photography;

      $user_roles = $this->ci->users->get_list_roles_actived_by_user($user_id);         
      $list_privileges = $this->ci->users->get_list_privileges_assigned_by_role($user_roles[0]['role_id']); 

      $data['list_roles'] = $user_roles;
      $data['list_privileges'] = $list_privileges;         

      $data['task_actived'] = $this->ci->model_projects->get_task_active_by_user($user_id);         

      $data['first_segment_uri'] = $this->ci->uri->segment(1);
      $data['email'] = $user['email'];         
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      $data['uri_images_companies'] = $this->ci->config->item('uri_images_companies');
      $data['uri_images_inventory'] = $this->ci->config->item('uri_images_inventory');         
      $data['segment_privilege'] = $this->ci->uri->segment(2);      

      $data['header_view_labels'] =  $this->ci->lang->line('coco_header_view_labels');         
      $data['has_privilege'] = $has_privilege = $this->has_privilege($user_roles, $list_privileges);
      return $data;
   }
   ###########################################################################################################
   function get_header_data($language_change_manual=false, $user_id=null)
   {
      $data = array();       
      if(!isset($user_id))
      {
         $user_id = $this->ci->session->userdata("user_id");
      }
      $data['have_access_to_privilege'] = false;
      
      $data['array_messages']=null;
       
      $data['user_id'] = $user_id;

      $user = $this->ci->users->get_user_complete_by_id($user_id);
      
      if( ! $language_change_manual)
      {
         $this->ci->lang->load('coco',$user['language']);
      }
      
      $data['company_logged'] = $company_logged = $this->ci->users->get_company_by_id($user['company_id']);

      $data['username']	= $user['username'];
      $data['profile_name'] = $user['name'];
      $data['profile_last_name'] = $user['last_name'];
            
      ##########################################################
      $data['language'] = $user['language'];
      
      

      $data['company_id'] = $company_logged['id'];
      $data['company'] = $company_logged['name'];

      $data['list_modules'] = $this->ci->users->get_list_modules_by_user( $user_id );         

      $data['uri_images_users'] = $this->ci->config->item('uri_images_users');//$this->uri_photography;
      $data['uri_images'] = $this->ci->config->item('uri_images');//$this->uri_photography;

      $user_roles = $this->ci->users->get_list_roles_actived_by_user($user_id);         
      $list_privileges = $this->ci->users->get_list_privileges_assigned_by_role($user_roles[0]['role_id']); 

      $data['list_roles'] = $user_roles;
      $data['list_privileges'] = $list_privileges;         

      $data['task_actived'] = $this->ci->model_projects->get_task_active_by_user($user_id);         

      $data['first_segment_uri'] = $this->ci->uri->segment(1);
      $data['email'] = $user['email'];         
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      $data['uri_images_companies'] = $this->ci->config->item('uri_images_companies');
      $data['uri_images_inventory'] = $this->ci->config->item('uri_images_inventory');         
      $data['segment_privilege'] = $this->ci->uri->segment(2);      

      $data['header_view_labels'] =  $this->ci->lang->line('coco_header_view_labels');         
      $data['has_privilege'] = $has_privilege = $this->has_privilege($user_roles, $list_privileges);
      return $data;
   }
   ###########################################################################################################
   function has_privilege($param_user_roles=null, $param_list_privileges=null)
   {
      $user_id = $this->ci->session->userdata('user_id');
      
      if(strcasecmp($user_id,"")!=0)
      {
         #----------------------------------------------------------------------
         $user_roles = null;
         
         if(isset($param_user_roles))
         {
            $user_roles = $param_user_roles;
         }
         else
         {            
            $user_roles = $this->ci->users->get_list_roles_actived_by_user($user_id);          
         }
         
         $list_privileges = null;
         
         if( isset($param_list_privileges))
         {
            $list_privileges = $param_list_privileges;            
         }
         else
         {
            $list_privileges = $this->ci->users->get_list_privileges_assigned_by_role($this->ci->session->userdata('role_id'));             
         }
         #----------------------------------------------------------------------
         $segment_privilege = $this->ci->uri->segment(1)."/".$this->ci->uri->segment(2);      
         

         $have_access=FALSE;

         if(  strcasecmp($segment_privilege, "/")!=0 AND 
              strcasecmp( trim( $segment_privilege, "/"), trim( $this->ci->uri->segment(1), "/")) !=0) 
         {

            for($i=0;$i<count($list_privileges);$i++)
            {
               //echo "<br><br>compare SEGMENT =".$segment_privilege." PRIV = ".$list_privileges[$i]['uri']."<br>";
               if(strcasecmp($segment_privilege,$list_privileges[$i]['uri'])==0)
               {
                  //echo "<br>***************  HAVE TO  ACCESS  ****************<br>";
                  $have_access=TRUE;
                  break;
               }
            }
         }
         else
         {
            // to HOME
            $have_access=TRUE;
         }
      
         return $have_access;
      }
      
      return false;
   }
   
   ###########################################################################################################   
	/**
	 * Login user on the site. Return TRUE if login is successful
	 * (user exists and activated, password is correct), otherwise FALSE.
	 *
	 * @param	string	(username or email or both depending on settings in config file)
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function login($login, $password, $remember, $login_by_username, $login_by_email, $autologin=false)
	{
		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			// Which function to use to login (based on config)
			if ($login_by_username AND $login_by_email) {
				$get_user_func = 'get_user_by_login';
			} else if ($login_by_username) {
				$get_user_func = 'get_user_by_username';
			} else {
				$get_user_func = 'get_user_by_email';
			}
         
         $user = $this->ci->users->$get_user_func($login);
         
         
         
			if (!is_null($user)) {	// login ok
				// Does password match hash in database?
				$hasher = new PasswordHash(
            
            $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
            $this->ci->config->item('phpass_hash_portable', 'tank_auth'));
            
            ##
				if($autologin OR $hasher->CheckPassword($password, $user->password)) {		// password ok

               
					if ($user->banned == 1) 
               {									// fail - banned
						$this->error = array('banned' => $user->ban_reason);
					}
               else 
               {
                                  
                  $company_logged = $this->ci->users->get_company_by_id($user->company_id);
                                    
                  #################################
                  
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
                        'profile_name'=> $user->name,
                        'profile_last_name'=> $user->last_name,
                        'company_id'=> $user->company_id,
                     
                        'company'=> $user->company,
                        'company_id'=> $company_logged['id'],                     
                        'company_logged'=> $company_logged,                   
                        'email'=>$user->email,                   
                        'role_id'=>$user->roles[0]['id'],                     
								'status'	=> ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
						));
                  
                  
                  
						if ($user->activated == 0) {							// fail - not activated
							$this->error = array('not_activated' => '');
						} 
                  else 
                  {												// success
							if ($remember) {
								$this->create_autologin($user->id);
							}

							$this->clear_login_attempts($login);

							$this->ci->users->update_login_info(
									$user->id,
									$this->ci->config->item('login_record_ip', 'tank_auth'),
									$this->ci->config->item('login_record_time', 'tank_auth'));
                     
                     
							return TRUE;
						}
					}
				} else {														// fail - wrong password
					$this->increase_login_attempt($login);
					$this->error = array('password' => 'auth_incorrect_password');
				}
			}
         else {															// fail - wrong login
				$this->increase_login_attempt($login);
				$this->error = array('login' => 'auth_incorrect_login');
			}
		}
      
      
		return FALSE;
	}
  
   #######################################################   
	/**
	 * Logout user from the site
	 *
	 * @return	void
	 */
	function logout()
	{
		$this->delete_autologin();

		// See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
		$this->ci->session->set_userdata(array('user_id' => '', 'username' => '', 'status' => ''));

		$this->ci->session->sess_destroy();
	}

	/**
	 * Check if user logged in. Also test if user is activated or not.
	 *
	 * @param	bool
	 * @return	bool
	 */
	function is_logged_in($activated = TRUE)
	{
		return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
	}

	/**
	 * Get username
	 *
	 * @return	string
	 */
	function get_username()
	{
		return $this->ci->session->userdata('username');
	}

	/**
	 * Create new user on the site and return some data about it:
	 * user_id, username, password, email, new_email_key (if any).
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	array
	 */
   ######################################################################
	function create_user2(
      $username, 
      $email, 
      $password, 
      $email_activation,       
      $profile_data,      
      $company_id,
      $role_id, &$array_messages=null)
	{
		if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username)) {
			$this->error = array('username' => 'auth_username_in_use');

		} elseif (!$this->ci->users->is_email_available($email)) {
			$this->error = array('email' => 'auth_email_in_use');

		} else {
			// Hash password using phpass
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			$hashed_password = $hasher->HashPassword($password);

			$data = array(
				'username'	=> $username,
				'password'	=> $hashed_password,
				'email'		=> $email,
				'last_ip'	=> $this->ci->input->ip_address(),
            #########################################
            'company_id'=>$company_id);
         
         
         $user_role_data=array('role_id'=>$role_id,
                               'status'=>'active');
         
         ######################################################################
			if ($email_activation) {
				$data['new_email_key'] = md5(rand().microtime());
			}
			if (!is_null($res = $this->ci->users->create_user($data, 
                                          !$email_activation,
                                          $profile_data
                                          ))) 
         {  
            
				$user_role_data['user_id'] = $data['user_id'] = $res['user_id'];
            
				$data['password'] = $password;
				unset($data['last_ip']);
            
            ###################################################
            $this->ci->users->assign_role_to_user($user_role_data);            
				return $data;
			}
		}
		return NULL;
	}
   ######################################################################
	function create_user(
      $username, 
      $email, 
      $password, 
      $email_activation,       
      $profile_name,
      $profile_lastname,
      $company_id,
      $role_id)
	{
		if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username)) {
			$this->error = array('username' => 'auth_username_in_use');

		} elseif (!$this->ci->users->is_email_available($email)) {
			$this->error = array('email' => 'auth_email_in_use');

		} else {
			// Hash password using phpass
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			$hashed_password = $hasher->HashPassword($password);

			$data = array(
				'username'	=> $username,
				'password'	=> $hashed_password,
				'email'		=> $email,
				'last_ip'	=> $this->ci->input->ip_address(),
            #########################################
            'company_id'=>$company_id);
                
         
         $profile_data =array('name'=>$profile_name,
                              'last_name'=>$profile_lastname);
         
         $user_role_data=array('role_id'=>$role_id,
                               'status'=>'active');
         
         ######################################################################
			if ($email_activation) {
				$data['new_email_key'] = md5(rand().microtime());
			}
			if (!is_null($res = $this->ci->users->create_user($data, 
                                          !$email_activation,
                                          $profile_data
                                          ))) 
         {
            
				$user_role_data['user_id'] = $data['user_id'] = $res['user_id'];
            
				$data['password'] = $password;
				unset($data['last_ip']);
            
            
            ###################################################
            $this->ci->users->assign_role_to_user($user_role_data);
            
				return $data;
			}
		}
		return NULL;
	}

	/**
	 * Check if username available for registering.
	 * Can be called for instant form validation.
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		return ((strlen($username) > 0) AND $this->ci->users->is_username_available($username));
	}

	/**
	 * Check if email available for registering.
	 * Can be called for instant form validation.
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		return ((strlen($email) > 0) AND $this->ci->users->is_email_available($email));
	}

	/**
	 * Change email for activation and return some data about user:
	 * user_id, username, email, new_email_key.
	 * Can be called for not activated users only.
	 *
	 * @param	string
	 * @return	array
	 */
	function change_email($email)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, FALSE))) {

			$data = array(
				'user_id'	=> $user_id,
				'username'	=> $user->username,
				'email'		=> $email,
			);
			if (strtolower($user->email) == strtolower($email)) {		// leave activation key as is
				$data['new_email_key'] = $user->new_email_key;
				return $data;

			} elseif ($this->ci->users->is_email_available($email)) {
				$data['new_email_key'] = md5(rand().microtime());
				$this->ci->users->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
				return $data;

			} else {
				$this->error = array('email' => 'auth_email_in_use');
			}
		}
		return NULL;
	}

	/**
	 * Activate user using given key
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email = TRUE)
	{
		$this->ci->users->purge_na($this->ci->config->item('email_activation_expire', 'tank_auth'));

		if ((strlen($user_id) > 0) AND (strlen($activation_key) > 0)) {
			return $this->ci->users->activate_user($user_id, $activation_key, $activate_by_email);
		}
		return FALSE;
	}

	/**
	 * Set new password key for user and return some data about user:
	 * user_id, username, email, new_pass_key.
	 * The password key can be used to verify user when resetting his/her password.
	 *
	 * @param	string
	 * @return	array
	 */
	function forgot_password($login)
	{
		if (strlen($login) > 0) {
			if (!is_null($user = $this->ci->users->get_user_by_login($login))) {

				$data = array(
					'user_id'		=> $user->id,
					'username'		=> $user->username,
					'email'			=> $user->email,
					'new_pass_key'	=> md5(rand().microtime()),
				);

				$this->ci->users->set_password_key($user->id, $data['new_pass_key']);
				return $data;

			} else {
				$this->error = array('login' => 'auth_incorrect_email_or_username');
			}
		}
		return NULL;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function can_reset_password($user_id, $new_pass_key)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0)) {
			return $this->ci->users->can_reset_password(
				$user_id,
				$new_pass_key,
				$this->ci->config->item('forgot_password_expire', 'tank_auth'));
		}
		return FALSE;
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user)
	 * and return some data about it: user_id, username, new_password, email.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass_key, $new_password)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0) AND (strlen($new_password) > 0)) {

			if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

				// Hash password using phpass
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
						$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($new_password);

				if ($this->ci->users->reset_password(
						$user_id,
						$hashed_password,
						$new_pass_key,
						$this->ci->config->item('forgot_password_expire', 'tank_auth'))) {	// success

					// Clear all user's autologins
					$this->ci->load->model('tank_auth/user_autologin');
					$this->ci->user_autologin->clear($user->id);

					return array(
						'user_id'		=> $user_id,
						'username'		=> $user->username,
						'email'			=> $user->email,
						'new_password'	=> $new_password,
					);
				}
			}
		}
		return NULL;
	}

	/**
	 * Change user password (only when user is logged in)
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function change_password($old_pass, $new_pass)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if old password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($old_pass, $user->password)) {			// success

				// Hash new password using phpass
				$hashed_password = $hasher->HashPassword($new_pass);

				// Replace old password with new one
				$this->ci->users->change_password($user_id, $hashed_password);
				return TRUE;

			} else {															// fail
				$this->error = array('old_password' => 'auth_incorrect_password');
			}
		}
		return FALSE;
	}

	/**
	 * Change user email (only when user is logged in) and return some data about user:
	 * user_id, username, new_email, new_email_key.
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	function set_new_email($new_email, $password)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)) {			// success

				$data = array(
					'user_id'	=> $user_id,
					'username'	=> $user->username,
					'new_email'	=> $new_email,
				);

				if ($user->email == $new_email) {
					$this->error = array('email' => 'auth_current_email');

				} elseif ($user->new_email == $new_email) {		// leave email key as is
					$data['new_email_key'] = $user->new_email_key;
					return $data;

				} elseif ($this->ci->users->is_email_available($new_email)) {
					$data['new_email_key'] = md5(rand().microtime());
					$this->ci->users->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
					return $data;

				} else {
					$this->error = array('email' => 'auth_email_in_use');
				}
			} else {															// fail
				$this->error = array('password' => 'auth_incorrect_password');
			}
		}
		return NULL;
	}

	/**
	 * Activate new email, if email activation key is valid.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_email_key) > 0)) {
			return $this->ci->users->activate_new_email(
					$user_id,
					$new_email_key);
		}
		return FALSE;
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @param	string
	 * @return	bool
	 */
	function delete_user($password)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)) {			// success

				$this->ci->users->delete_user($user_id);
				$this->logout();
				return TRUE;

			} else {															// fail
				$this->error = array('password' => 'auth_incorrect_password');
			}
		}
		return FALSE;
	}

	/**
	 * Get error message.
	 * Can be invoked after any failed operation such as login or register.
	 *
	 * @return	string
	 */
	function get_error_message()
	{
		return $this->error;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_autologin($user_id)
	{
		$this->ci->load->helper('cookie');
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$this->ci->load->model('tank_auth/user_autologin');
		$this->ci->user_autologin->purge($user_id);

		if ($this->ci->user_autologin->set($user_id, md5($key))) {
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
					'value'		=> serialize(array('user_id' => $user_id, 'key' => $key)),
					'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
			));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Clear user's autologin data
	 *
	 * @return	void
	 */
	private function delete_autologin()
	{
		$this->ci->load->helper('cookie');
		if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

			$data = unserialize($cookie);

			$this->ci->load->model('tank_auth/user_autologin');
			$this->ci->user_autologin->delete($data['user_id'], md5($data['key']));

			delete_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'));
		}
	}

	/**
	 * Login user automatically if he/she provides correct autologin verification
	 *
	 * @return	void
	 */
	private function autologin()
	{
		if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE)) {			// not logged in (as any user)

			$this->ci->load->helper('cookie');
			if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) AND isset($data['user_id'])) {

					$this->ci->load->model('tank_auth/user_autologin');
					if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key'])))) {

						// Login user
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'status'	=> STATUS_ACTIVATED,
						));

						// Renew users cookie to prevent it from expiring
						set_cookie(array(
								'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
								'value'		=> $cookie,
								'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
						));

						$this->ci->users->update_login_info(
								$user->id,
								$this->ci->config->item('login_record_ip', 'tank_auth'),
								$this->ci->config->item('login_record_time', 'tank_auth'));
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * Check if login attempts exceeded max login attempts (specified in config)
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_max_login_attempts_exceeded($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			$this->ci->load->model('tank_auth/login_attempts');
			return $this->ci->login_attempts->get_attempts_num($this->ci->input->ip_address(), $login)
					>= $this->ci->config->item('login_max_attempts', 'tank_auth');
		}
		return FALSE;
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			if (!$this->is_max_login_attempts_exceeded($login)) {
				$this->ci->load->model('tank_auth/login_attempts');
				$this->ci->login_attempts->increase_attempt($this->ci->input->ip_address(), $login);
			}
		}
	}

	/**
	 * Clear all attempt records for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function clear_login_attempts($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			$this->ci->load->model('tank_auth/login_attempts');
			$this->ci->login_attempts->clear_attempts(
					$this->ci->input->ip_address(),
					$login,
					$this->ci->config->item('login_attempt_expire', 'tank_auth'));
		}
	}
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */