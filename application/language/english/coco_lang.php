<?php
################################################################################
//                    Project Manager
################################################################################
################################################################################
$lang['coco_modules'] = array(
                                 "team"=>"Team",
                                 "times"=>"Times",
                                 "inventory"=>"Inventory",
                                 "projects"=>"Projects",
                                 "dashboard"=>"Dashboard",
                                 "accounts"=>"Cash Flow");

$lang['coco_privileges'] = array(
                                 "auth/add_user"=>"Add User",
                                 "auth/add_role"=>"Add Role",
                                 "auth/assign_privileges"=>"Assign Privileges",
                                 "auth/assign_roles"=>"Assign Roles",
                                 "times/home"=>"Check time",
                                 "times/edit_time"=>"Edit Time",
                                 "inventory/add_inventory"=>"Add Inventory",
                                 "auth/home"=>"Home",
                                 "times/add_time"=>"Add Time",
                                 "auth/edit_profile"=>"Edit Profile",
                                 "auth/delete_user"=>"Delete User",
                                 "auth/manager_users"=>"Manager Users",
                                 "inventory/add_category_inventory"=>"Add Category Inventory",
                                 "times/add_time_record"=>"Add Time Record",
                                 "times/observe_time"=>"Observe the Time",
                                 "times/correct_time_observed"=>"Correct the Time Observed",
                                 "times/validate_time_corrected"=>"Validate the Time Corrected",
                                 "times/manager_times"=>"Manager the Times",
                                 "times/edit_time_gral"=>"Edit Time Gral",
                                 "auth/edit_user"=>"Edit User",
                                 "auth/edit_company"=>"Edit Company",
                                 "inventory/manager_inventory"=>"Manager Inventory",
                                 "inventory/edit_inventory"=>"Edit Inventory",
                                 "times/present_users"=>"Present Users",
                                 "times/delete_time"=>"Delete Time",
                                 "pm/save_project"=>"Save Project",
                                 "pm/home"=>"Active Projects",
                                 "pm/view_project"=>"View project",
                                 "pm/discussions"=>"Discussions",
                                 "pm/save_comment"=>"Save comment",
                                 "pm/members"=>"Members",
                                 "pm/time_records"=>"Time Records",
                                 "pm/trash"=>"Trash",
                                 "pm/save_time_record"=>"Save Time Record",
                                 "pm/add_member"=>"Add Member",
                                 "pm/view_comment"=>"View Comment",
                                 "pm/delete_permanently"=>"Delete Object Permanently",
                                 "pm/restore"=>"Restore Objects",
                                 "pm/projects_completed"=>"Projects Completed",
                                 "pm/save_task"=>"Save Task",
                                 "pm/save_task_by_assigned"=>"Save Task by Assigned",
                                 "pm/save_task/save_points"=>"Save Task Points",
                                 "pm/save_project/save_points"=>"Save Project Points",
   
                                 "auth/manager_clients"=>"Manager Clients",                                 
                                 "auth/save_client"=>"Save Client",                                                                  
                                 "auth/save_client_company"=>"Save Client Company",
                                 "auth/manager_clients_companies"=>"Manager Clients Companies",
                                 "auth/delete_client_company"=>"Delete Client Company",
                                 "auth/delete_client"=>"Delete Client",
                                 "accounts/home"=>"Manager Account Items",
                                 "accounts/save_account"=>"Save Account",
                                 "accounts/budgets"=>"Budgets",
                                 "accounts/save_budget_range"=>"Save Budget Range",
                                  );

$lang['coco_header_view_labels'] = array("modules"=>$lang['coco_modules'] ,
                                         "privileges"=>$lang['coco_privileges'],
                                         "profile_edit"=>"Profile Edit",
                                         "msg_filesize_part1"=>"File sizes=",
                                         "msg_filesize_part2"=>"MB tried, Limit=",
                                         "msg_filesize_part3"=>"MB Exceeded",
                                       );

$lang['coco_times_status'] = array(
                                   'valid'=>'Valid',
                                   'observed'=>'Observed',
                                   'corrected'=>'Corrected',                                      
                                  );

$lang['coco_pm_action_status'] = array(
                                      'created'=>'Created',
                                      'modified'=>'Modified',
                                      'uploaded'=>'Uploaded',
                                      'moved_to_trash'=>'Moved to Trash',
                                      'restored'=>'Restored',
                                      'started'=>'Started',
                                      'completed'=>'Completed',
                                      'canceled'=>'Canceled',                                      
                                      'in_process'=>'In process',
                                      'paused'=>'Paused',                                      
                                     );

$lang['coco_pm_type_object'] = array("project"=>"Project",
                                     "task"=>"Task",
                                     "message"=>"Message",
                                     "discussion"=>"Discussion",
                                     "comment"=>"Comment",
                                     "file"=>"File",
                                     "time_record"=>"Time Record",
                                     "fee"=>"Fee",
   );

$lang['coco_pm_menu_project'] = array("home"=>"Home","discussions"=>"Discussions",
                                          "members"=>"Members",
                                          "time_records"=>"Time Record", 
                                          "task_in_process"=>"Task in Process",
                                           "trash"=>"Trash");





$lang['coco_pm_save_buttons'] = array("save"=>"Save",
                                          "save_go_back_list"=>"Save",
                                          "move_to_trash"=>"Move to trash", 
                                          "cancel"=>"Cancel");
#-------------------------------------------------------------------------------
$lang['coco_msg_was_not_received_attachment_files'] = "Attachment files were not received by having large sizes, for review go to the source of the message";
$lang['coco_msg_sure_to_delete'] = "Sure to delete these?";
#-------------------------------------------------------------------------------

$lang['coco_msg_pm_save_project_successfully'] = "Was successfully saved";
$lang['coco_msg_pm_save_task_successfully'] = "Was successfully saved";

$lang['coco_msg_pm_not_valid_task_status'] = "Changed Status sequence, invalid for Task";
/*$lang['coco_msg_pm_']="";
$lang['coco_msg_pm_']="";
$lang['coco_msg_pm_']="";
$lang['coco_msg_pm_']="";*/
$lang['coco_msg_pm_task_members_not_exists'] = "Members not selected for Task";
$lang['coco_msg_pm_task_not_exists'] = "There is no selected Task ";
$lang['coco_msg_pm_project_not_exists'] = "There is no selected Project ";
$lang['coco_msg_pm_comment_not_exists'] = "There is no selected Comment";
$lang['coco_msg_pm_time_record_not_exists'] = "There is no selected Time Record";
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_accounts_budgets_view_labels'] = array(
                                           "form_title"=>'Budgets',
                                           "select_budget_range"=>'Select Budget Range',
                                           "column_account"=>'Account',
                                           "column_in"=>'Income',
                                           "column_out"=>'Expenses',
                                           "column_result"=>'Result',
                                           "btn_save"=>'Save',
                                           "msg_no_has_ranges"=>"No has Budget Ranges for allocable Budgets",
                                           );
   
$lang['coco_accounts_save_budget_range_view_labels'] = array(
                                           "form_title"=>'Save Budget Range',
                                           "title_label"=>'Title',
                                           "date_from_label"=>'Date From',
                                           "date_to_label"=>'Date To',
                                           "btn_save_budget_range"=>'Save',
                                           "list_title"=>'List Budget Ranges',
                                           "column_title"=>'title',
                                           "column_date_from"=>'Date From',
                                           "column_date_to"=>'Date To',
                                           );
   
$lang['coco_accounts_home_view_labels'] = array(
                                           "form_title"=>'Accounts',
                                           "list_account"=>'Select Account',
                                           "select_account"=>'Show all...',                                           
                                           "date_from"=>'Date From',
                                           "date_to"=>'To',                                          
                                           "column_item"=>'Item',
                                           "column_date"=>'Date',
                                           "column_account_in"=>'Income',
                                           "column_account_out"=>'Expenses',
                                           "column_account"=>'Account',
                                           "msg_result_search"=>'Not found items between',
   
                                           "btn_search" => "Search",
                                           "btn_add" => "Add",
                                           "msg_in_out_required"=>"The Account In or Account Out field is required."
                                           );

$lang['coco_accounts_save_account_item_view_labels'] = array(
                                           "form_title"=>'Save Account Item',
                                           "description"=>'Description',
                                           "date"=>'Date',
                                           "account_in"=>'Incomes',
                                           "account_out"=>'Expenses',
                                           "account"=>'Account',
                                           "select_account"=>'Select Account',
   
                                           "btn_save" => "Save",
                                           "btn_delete" => "Delete",
                                           "msg_in_out_required"=>"The Account In or Account Out field is required"
                                           );

$lang['coco_accounts_save_account_view_labels'] = array(
                                           "form_title"=>'Save Account',
                                           "name"=>'Name',
                                           "description"=>'Description',
                                           "parent_account"=>'Parent Account',
                                           "select_account_parent"=>'Select Account Parent',
                                           "column_name"=>'Name',
                                           "column_description"=>'Description',
                                           "column_parent_account"=>'Parent Account',
                                           "btn_save_account" => "Save Account",
                                           );
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_auth_manager_clients_view_labels'] = array(
                                           "form_title"=>'Manager Clients',                                           
                                           "btn_add_client"=>'Add Client',   
                                           "company_name"=>"Company Name",
                                           "company_description"=>"Description",
                                           "list_title"=>"List Clients",
   
                                           "column_edit_value"=>"Edit",
                                           );

$lang['coco_auth_save_client_view_labels'] = array(
                                           "title_form"=>'Save Client',                                           
                                           "client_company"=>'Company',  
                                           "client_company_description"=>'Company Description',   
                                           "user_name"=>'User',   
                                           "email"=>"Email",
                                           "password"=>"Password",
                                           "picture"=>"Picture",
                                           "names"=>"Names",
                                           "last_name"=>"Last Name",
                                           "btn_save"=>"Save Client",
                                           "btn_delete"=>"Delete",
                                           "language"=>"Language",
                                           
                                           "existing_company"=>"Existing Company...",                                           
                                           "msg_cannot_register"=>"Can not save",
                                           "link_manager_clients"=>"Manager Clients",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_manager_clients_companies_view_labels'] = array(
                                           "title_form"=>'Manager Clients Companies',                                           
                                           "edit"=>'Edit',   
                                           "btn_add_client_company"=>'Add Client Company',   
                                           "msg_delete_these_insurance"=>"Delete These Insurance?",   
                                           "column_user_name"=>"User Name",
                                           "column_profile_name"=>"Profile Name",
                                           "column_last_name"=>"Last Name",
                                           "column_e_mail"=>"E-Mail",
                                           "column_edit"=>"Edit",
                                           "link_manager_clients"=>"Manager Clients",
                                           );

#-------------------------------------------------------------------------------
$lang['coco_auth_save_client_company_view_labels'] = array(
                                           "form_title"=>'Save Client Company',                                                                                      
                                           "name"=>"Company",
                                           "logo"=>"Logo",                                           
                                           "description"=>"Description",                                                                                     
                                           "btn_save"=>"Save"
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_redirect_has_not_privilege_view_labels'] = array(
                                           "msg1"=>"Not have access to this functionality of URI: "
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_login_view_labels'] = array(
                                           "form_title"=>'Login',                                           

                                           "email"=>"Email",
                                           "password"=>"Password",                                           
                                           
                                           

                                          "captcha_another"=>"Get another CAPTCHA",
                                          "captcha_audio"=>"Get an audio CAPTCHA",
                                          "captcha_image"=>"Get an image CAPTCHA",

                                          "captcha_above"=>"Enter the words above",
                                          "captcha_numbers"=>"Enter the numbers you hear",

                                          "captcha_confirmacion"=>"Confirmation Code",
                                          
                                          "remember_password"=>"Remember My Password",
                                          "forgot_password"=>"Forgot your password?",
   
                                          "btn_create_company"=>"Create Company",   
                                          "btn_login"=>"Login",
   
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_register_view_labels'] = array(
                                           "form_title"=>'Register Company',                                           
                                           "username"=>'User',   
                                           "email"=>"Email",
                                           "password"=>"Password",                                           
                                           "re_password"=>"Confirm Passowrd",                                           
                                           "company_name"=>"Company Name",                                           
                                          "company_logo"=>"Company Logo",                                           
                                          "name"=>"Administrator Name",                                           
                                          "last_name"=>"Administrador Last Name",
                                          "language"=>"Language",

                                          "captcha_another"=>"Get another CAPTCHA",
                                          "captcha_audio"=>"Get an audio CAPTCHA",
                                          "captcha_image"=>"Get an image CAPTCHA",

                                          "captcha_above"=>"Enter the words above",
                                          "captcha_numbers"=>"Enter the numbers you hear",

                                          "captcha_confirmacion"=>"Confirmation Code",

                                          "captcha_exactly"=>"Enter the code exactly as it appears",

                                           "btn_register"=>"Register",   
                                           
                                           "auth_back_to_login_page"=>"return to login",   
                                           
   
                                          "send_title"=>"Welcome to",
                                          "paragraph1"=>"Thanks for joining ",
                                          "paragraph2"=>"We listed your sign in details below, make sure you keep them safe.",
                                          "paragraph3"=>"To open your",
                                          "paragraph4"=>" homepage, please follow this link",
                                          "paragraph5"=>"Go to",
                                          "paragraph6"=>"now!",
                                          "paragraph7"=>"Link doesn't work? Copy the following link to your browser address bar",
                                          "paragraph8"=>"Your username",

                                          "paragraph9"=>"Your email address",   
                                          "paragraph10"=>"Your password",   
                                          "paragraph11"=>"Have fun!",  
                                          
   
                                           );

#-------------------------------------------------------------------------------
$lang['coco_auth_reset_password_view_labels'] = array(
                                                 "form_title"=>'Reset password',                                                    
                                                 "new_password"=>"New Password",
                                                 "confirm_new_password"=>"Confirm New Password",
                                                 "btn_save_password"=>"Save",            
   
                                                 "mail_title"=>"Your new password on ",
                                                 "sub_title"=>"Your new password on",
   
                                                 "paragraph1"=>"You have changed your password.<br />
                                                                Please, keep it in your records so you don't forget it.<br />",
                                                 "your_username"=>"Your username",
                                                 "your_email"=>"Your email address",
                                                 "thank_you"=>"Thank you,",
                                              );
#-------------------------------------------------------------------------------
$lang['coco_auth_forgot_password_view_labels'] = array(
                                                 "form_title"=>'Forgot your password?',                                                    
                                                 "get_new_password"=>"Get New Password",
   
                                                 "auth_back_to_login_page"=>"return to login",   
   
                                                 "mail_form_title"=>'Create a new password on',                                                 
                                                 "mail_sub_title1"=>"Create a new password",                                                    
                                                 "paragraph1" =>"Forgot your password, huh? No big deal.<br />
                                                                 To create a new password, just follow this link:<br />
                                                                 <br/>",
                                                 "link_create_password"=>"Create a new password",
                                                 "paragraph2" =>"Link doesn't work? Copy the following link to your browser address bar:<br />",
                                                 "paragraph3" =>"You received this email, because it was requested by a ",
                                                 "paragraph4" =>"user. This is part of the procedure to create a new password on the system. 
                                                                 If you DID NOT request a new password then please ignore 
                                                                 this email and your password will remain the same.<br />",
                                                 "paragraph5" => "Thank you,<br />",                                                 
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_edit_inventory_view_labels'] = array(
                                                 "title_form"=>'Inventory Item Edit',   
                                                 
                                                 "category"=>"Category",
                                                 "name"=>"Name",
                                                 "model"=>"Model",
                                                 "brand"=>"Brand",
                                                 "code"=>"Code",
                                                 "location"=>"Location",
                                                 "quantity"=>"Quantity",
                                                 "purchase_price"=>"purchase_price",
                                                 "current_picture"=>"Current Picture",
                                                 "picture"=>"Picture",
                                                 "description"=>"Description",
                                                 "btn_save"=>"Save",
   
                                                 "table_title"=>'Inventory',    
   
                                                 "column_name"=>'Name',   
                                                 "column_model"=>'Model',   
                                                 "column_brand"=>'Brand',    
                                                 "column_code"=>'Code',   
                                                 "column_location"=>'Location',   
                                                 "column_quantity"=>'Quantity',   
                                                 "column_purchase_price"=>'Purchase Price',                                              
                                                 );

#-------------------------------------------------------------------------------
$lang['coco_inventory_manager_inventory_view_labels'] = array(
                                                 "title_form"=>'Manager Inventory',   
                                                 "edit"=>"Edit",
                                                 "column_name"=>"Name",   
                                                 "column_model"=>"Model",   
                                                 "column_brand"=>"Brand",   
                                                 "column_code"=>"Code",   
                                                 "column_location"=>"Location",   
                                                 "column_quantity"=>"Quantity",   
                                                 "column_purchase_price"=>"Purchase Price",   
                                                 "column_edit"=>"Edit?",   
                                                 "column_delete"=>"Delete?",   
                                                 "msg_delete_these_insurance"=>"Delete These Insurance?",
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_add_category_inventory_view_labels'] = array(
                                                 "title_form"=>'Add Category Inventory',   
                                                 "name"=>"Name",   
                                                 "description"=>"Description",
                                                 "btn_save"=>"Save",
   
                                                 "table_title"=>"Inventory Category List",
                                                 "column_name"=>"Nombre",
                                                 "column_description"=>"Descripcion",
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_add_inventory_category_view_labels'] = array(
                                                 "title_form"=>'Add Inventory Category',       
                                                 "name"=>"Name",   
                                                 "description"=>"Description",
                                                 "btn_save"=>"Save",
   
                                                 "table_title",
                                                 "column_name",
                                                 "column_description",
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_add_inventory_view_labels'] = array(
                                                 "title_form"=>'Add Inventory Item',    
   
                                                 "category"=>"Category",
                                                 "name"=>"Name",
                                                 "model"=>"Model",
                                                 "brand"=>"Brand",
                                                 "code"=>"Code",
                                                 "location"=>"Location",
                                                 "quantity"=>"Quantity",
                                                 "purchase_price"=>"purchase_price",
                                                 "photography"=>"Photography",
                                                 "description"=>"Description",
                                                 "btn_save"=>"Save",
   
                                                 "table_title"=>'Inventory',    
   
                                                 "column_name"=>'Name',   
                                                 "column_model"=>'Model',   
                                                 "column_brand"=>'Brand',    
                                                 "column_code"=>'Code',   
                                                 "column_location"=>'Location',   
                                                 "column_quantity"=>'Quantity',   
                                                 "column_purchase_price"=>'Purchase Price',                                              
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_home_view_labels'] = array(
                                           "table_title"=>'Invetory',                                           
                                           "column_name"=>'Name',   
                                           "column_model"=>'Model',   
                                           "column_brand"=>'Brand',    
                                           "column_code"=>'Code',   
                                           "column_location"=>'Location',   
                                           "column_quantity"=>'Quantity',   
                                           "column_purchase_price"=>'Purchase Price',                                              
                                           );
#-------------------------------------------------------------------------------

#-------------------------------------------------------------------------------
$lang['coco_auth_public_profile_view_labels'] = array(
                                           "form_title"=>'Public Profile',                                           
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_edit_company_view_labels'] = array(
                                           "title_form"=>'Company Edit',                                           
                                           "name"=>'Name',   
                                           "description"=>"Description",
                                           "current_logo"=>"Current Logo",                                           
                                           "logo"=>"Logo",                                           
                                           "btn_save_changes"=>"Save Changes",   
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_edit_user_view_labels'] = array(
                                           "title_form"=>'Edit User',                                           
                                           "user_name"=>'User',   
                                           "email"=>"Email",
                                           "new_password"=>"Password",                                           
                                           "names"=>"Names",
                                           "last_name"=>"Last Name",
                                           "current_picture"=>"Current Picture",
                                           "picture"=>"New Picture",
                                           "btn_save_changes"=>"Save Changes",
                                           "language"=>"Language",
                                           "status"=>"Status",
                                           "status_active"=>"Active",
                                           "status_inactive"=>"Inactive",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_add_user_view_labels'] = array(
                                           "title_form"=>'Add User',                                           
                                           "user_name"=>'User',   
                                           "email"=>"Email",
                                           "password"=>"Password",
                                           "picture"=>"Picture",
                                           "names"=>"Names",
                                           "last_name"=>"Last Name",
                                           "btn_add"=>"Add User",
                                           "language"=>"Language",
                                           "msg_cannot_register"=>"Cannot register",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_manager_users_view_labels'] = array(
                                           "title_form"=>'Manager Users',                                           
                                           "edit"=>'Edit',   
                                           "btn_add_user"=>'Add User',   
                                           "msg_delete_these_insurance"=>"Delete These Insurance?",
   
                                           "column_user_name"=>"User Name",
                                           "column_profile_name"=>"Profile Name",
                                           "column_last_name"=>"Last Name",
                                           "column_e_mail"=>"E-Mail",
                                           "column_edit"=>"Edit",
   
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_assign_roles_view_labels'] = array(
                                           "title_form"=>'Assign Roles to Users',                                           
                                           "btn_save_changes"=>'Save Changes',                                           
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_assign_privileges_view_labels'] = array(
                                           "title_form"=>'Assign Privileges to Roles',                                           
                                           "btn_changes"=>'Save Changes',
                                           "privileges"=>$lang['coco_privileges'],
                                           "modules"=>$lang['coco_modules'],
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_add_role_view_labels'] = array(
                                           "title_form"=>'Add Role',
                                           "column_name"=>'Name',
                                           "column_type"=>'Type',                                           
                                           "btn_register"=>'Add Role',                                           
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_list_users_view_labels'] = array(
                                           "title_form"=>'User list',
                                           "column_user_name"=>'User Name',
                                           "column_profile_name"=>'Profile Name',
                                           "column_last_name"=>'Last Name',
                                           "column_email"=>'E-Mail',
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_edit_profile_view_labels'] = array(
                                        "title_form"=>'Edit Profile',
                                        "user_name"=>"User Name",
                                        "password"=>"Current Password",
                                        "new_password"=>"New Password",
                                        "email_address"=>"Email Address",
                                        "name"=>"Name",
                                        "last_name"=>"Last Name",
                                        "current_picture"=>"Current Picture",
                                        "picture"=>"Picture",
                                        "save_changes"=>"Save Changes",                                        
                                        "language"=>"Language",                                        
                                        );
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_times_send_changed_time_by_email_view_labels'] = array(                                        
                                        "company"=>'Company',                                        
                                        "sent_to" =>"This change of Time was sent to",
                                        "paragraph1"=>"Time change from ",                                        
                                        "paragraph2"=>"to ",
                                        "view_change"=>"See the Change ",
                                        "change_time"=>"Change Time ",
                                        );
#-------------------------------------------------------------------------------
$lang['coco_times_present_users_view_labels'] = array(
                                        "title_form"=>'Team',
                                        "last"=>'Last',
                                        "updates"=>'Updates',
                                        );
#-------------------------------------------------------------------------------
$lang['coco_times_edit_time_gral_view_labels'] = array(
                                        "title_form"=>'Edit Time',
                                        "in"=>"In",
                                        "out"=>"Out",
                                        "date"=>"Date",
                                        "hour"=>"Hour",
                                        "status"=>"Status",
                                        "btn_save"=>"Save",
                                        "btn_cancel"=>"Cancel",
                                        "times_status"=>$lang['coco_times_status'],
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_manager_times_view_labels'] = array(
                                        "title_form"=>'Manage users hours',
                                        "select_range"=>"Select Range",
                                        "week"=>"Week",
                                        "search"=>"Search",
                                        "since"=>"Since",
                                        "until"=>"Until",
                                        "in"=>"In",
                                        "out"=>"Out",
                                        "sub_total"=>"Sub-total Hrs.",
                                        "msg_delete_these_insurance"=>"Delete These Insurance?",
                                        "legend_total_hours"=>"Total hours worked in this Week",
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_edit_time_view_labels'] = array(
                                        "title_form"=>'Schedules',
                                        "previous_mark_in"=>"In (Previous Mark)",
                                        "previous_mark_out"=>"Out (Previous Mark)",
                                        
                                        "in_edit"=>"In (Edit)",
                                        "out_edit"=>"Out (Edit)",
                                        "date"=>"Date",
                                        "hour"=>"Hour",
                                        "btn_save"=>"Save",
                                        "btn_cancel"=>"Cancel",
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_add_time_record_view_labels'] = array(
                                        "title_form"=>'Hours for the selected week',
                                        "msg_added_successfully"=>"Has been added Successfully",
                                        "column_in"=>"In",
                                        "column_out"=>"Out",
                                        "add_record_in_and_out"=>"Add Record In and Out",
                                        "date"=>"Date",
                                        "hour"=>"Hour",
                                        "btn_save"=>"Save",
                                        "btn_cancel"=>"Cancel",
                                        "msg_delete_these_insurance"=>"Delete These Insurance?",
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_home_view_labels'] = array(
                                        "title_form"=>'Schedules',
                                        "message_error"=>"You have problems in one of the previous records. Click on the week # ",
                                        "column_in"=>"In",
                                        "column_out"=>"Out",
                                        "hours_by_week"=>"Hours by Week",
                                        "clock_out"=>"Clock Out",
                                        "total_hrs_week"=>"Total hours worked in the week",
                                        "record_in"=>"RECORD IN",
                                        "record_out"=>"RECORD OUT",
                                        "add_hrs"=>"ADD HRS",
                                        "msg_delete_these_insurance"=>"Delete These Insurance?",
                                        "first_come"=>"First Come",
                                       );
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_pm_send_task_change_status_by_email_view_labels'] = array(
                                        "view_comment"=>'View Comment',
                                        "by"=>'By',
                                        "project"=>'Proyect',
                                        "company"=>'Company',
                                        "task_change_status" =>"Task Modified to Status",
                                        "sent_to" =>"This change of Task Status was sent to",
                                        "action_status"=>$lang['coco_pm_action_status'],   );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_task_view_labels'] = array('title_form'=>"Save Task",                                                                          
                                        "title"=>'Title',
                                        "assigned_to"=>'Assigned to',
                                        "status"=>'Status',
                                        "priority"=>'Priority',
                                        "start_date"=>'Start Date',
                                        "end_date"=>'End Date',
                                        "hour"=>'Hour',
   
                                        "points"=>'Points',
                                        "is_private"=>'Is private',
   
                                        'move_to_trash'=>'Move to Trash',
                                        'new_attachment_file'=>'New Attachment File',
                                        "percent_completed"=>'Percent completed',
                                        "object_status"=>$lang['coco_pm_action_status'],
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_send_task_by_email_view_labels'] = array(
                                        'view_comment'=>'View Comment',
                                        'by'=>'By',
                                        "project"=>'Proyect',
                                        "company"=>'Company',
                                        "task_in" =>"Put a Task in",
                                        "sent_to" =>"This Task was sent to",
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_send_comment_by_email_view_labels'] = array(
                                        'view_comment'=>'View Comment',
                                        'by'=>'By',
                                        "project"=>'Proyect',
                                        "company"=>'Company',
                                        "commented_on" =>"Commented on",
                                        "sent_to" =>"This comment was sent to",
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_trash_view_labels'] = array('title_form'=>"Trash",                                        
                                        'created_by'=>'Created By',
                                        'in'=>'In',
                                        'restore'=>'Restore',
                                        'delete_permanently'=>'Delete permanently',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "type_object"=>$lang['coco_pm_type_object'],   
                                        );

#-------------------------------------------------------------------------------
$lang['coco_pm_time_records_view_labels'] = array('title_form'=>"Time Records",
                                        'link_new_time_record'=>'New Time Record',
                                        'link_edit'=>'Edit',                                        
                                        'assigned_to'=>'Assigned to',                                        
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_time_record_view_labels'] = array('title_form'=>"Save Time Record",
                                        'quantity'=>'Quantity Hrs.',
                                        'description'=>'Description',
                                        'date'=>'Date',
                                        'user'=>'Assigned to',
                                        'billable_status'=>'Billable Status',
                                                                                
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
                                        );

#-------------------------------------------------------------------------------
$lang['coco_pm_members_view_labels'] = array('title_form'=>"Members",
                                        'link_add_member'=>'Add/Remove Members',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );

#-------------------------------------------------------------------------------
$lang['coco_pm_view_comment_view_labels'] = array('title_form'=>array("discussion"=>'Discussion',
                                        "comment"=>'Comments',),
                                        'link_new_comment'=>'New Comment',  
                                        'said'=>'Said',                                        
                                        'suscribers'=>'Suscribers',
                                        'replies_comments'=>'Replies Comments',
                                        'replies'=>'Replies',
                                        'link_edit'=>'Edit',                                        
                                        'created_by'=>'Created By',
                                        'reply'=>'Reply',
                                        'attachments'=>'Attachment Files',
                                        
                                        'put_a_task'=>'Put a Task',
   
                                        'completed'=>'COMPLETED',
                                        'pending'=>'PENDING',
                                        'tasks' => 'Tasks',
                                        'add_task' => 'Agregar Tarea',
                                        
                                        'is_task'=>'Is Task?',
                                        'is_private'=>'Is Private?',
                                        
                                        'members_assignable'=>'Members Assignable',
                                        
                                        'title_add_comment'=>'Add Comment',                                        
                                        'content'=>'Content',                                        
                                        'new_attachment_file'=>'New Attachment File',
                                        "object_status"=>$lang['coco_pm_action_status'],
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_comment_view_labels'] = array('title_form'=>array("discussion"=>'Save Discussion',
                                                                      "comment"=>'Save Comment',),
                                        'set_a_task_also'=>'Set a 1 st Task, also',
                                        'members'=>'Members',
                                        'content'=>'Content',
                                        'title'=>'Title',
                                        
                                        "is_private"=>"Is private",
                                        
                                        'new_attachment_file'=>'New Attachment File',
                                        'base_comment'=>'Base comment',
                                        'move_to_trash'=>'Move to Trash',   
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
                                        );

#-------------------------------------------------------------------------------
$lang['coco_pm_discussions_view_labels'] = array('title_form'=>'Discussions',
                                        'link_new_discussion'=>'New Discussion',
                                        'by'=>'By',
                                        'started_by'=>'Started By',
                                        'reply'=>'Reply',
                                        'replies'=>'Replies',
                                        'last_comment_published'=>'Last Comment Published',
                                        'link_edit'=>'Edit',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_project_view_labels'] = array('title_form'=>'Save Project',
                                                  'title'=>'Title',
                                                  'points'=>'Points',
                                                  'start_date'=>'Start date',
                                                  'end_date'=>'End date',
                                                  'priority'=>'Priority',
                                                  'status'=>'Status',
                                                  'content'=>'Content',
                                                  "action_status"=>$lang['coco_pm_action_status'],
                                                  "save_buttons"=>$lang['coco_pm_save_buttons'],
                                                  "menu_project"=>$lang['coco_pm_menu_project'],
                                                  );
#-------------------------------------------------------------------------------
$lang['coco_pm_view_project_view_labels'] = array('by'=>'By',
                                                   'activities'=>'Activities Historical',
                                                   'edit'=>'Edit',
                                                   'completed'=>'Completed',
                                                   'points'=>'Points',
                                                   "menu_project"=>$lang['coco_pm_menu_project'],
                                                   "action_status"=>$lang['coco_pm_action_status'],
                                                   "type_object"=>$lang['coco_pm_type_object'],
   
                                                   'performance'=>'Performance',                                                   
                                                   'total_task_hours'=>'Total Task Hours',
                                                   'total_project_hours'=>'Total Project Hours',
                                                   );


#-------------------------------------------------------------------------------
$lang['coco_pm_home_view_labels'] = array('title_form'=>'Projects',
                                        'link_new_project'=>'New Project',
                                        'by'=>'By',
                                        'link_edit_project'=>'Edit',
                                        'pending_tasks'=>'Pending Tasks',
                                        'completed_tasks'=>'Completed of ',
                                        'tasks'=>' Tasks',
                                        'view_my_last_comment'=>"View my last Comment/Task",
                                        'view_last_comment_project'=>"View last Comment/Task of Project",
   
                                        'task_in_process_by'=>"Task In Process by",
   
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_add_member_view_labels'] = array('title_form'=>'Add/Remove Members',
                                        'save'=>'Save',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                       );
################################################################################
$lang['coco_privilege_edit_profile'] = 'Edit Profile';
$lang['coco_msg_username_not_available'] = 'The username you wrote, not available';
$lang['coco_msg_email_not_available'] = 'User already exists, the email that says';
$lang['coco_msg_was_success_password_changed'] = 'The password was successfully changed';
$lang['coco_msg_gral_was_success_saved'] = 'Successfully';
$lang['coco_msg_gral_image_was_success_uploaded'] = 'The image was uploaded successfully';
$lang['coco_msg_gral_could_not_change_password']= 'You can not change the password';
$lang['coco_msg_times_less_than_now']= 'The time that income must be less than at present, please try again';
$lang['coco_msg_times_crossed']='The time that income is crossed, please try again';
$lang['coco_msg_times_was_delete_success']='It has been successfully deleted';
$lang['coco_msg_times_date_not_is_into_interval']='The date %1$s is not in the range of the week [%2$s, %3$s]';
$lang['auth_message_new_password_sent']='';



