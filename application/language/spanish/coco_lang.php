<?php
################################################################################
//                    Project Manager
################################################################################
################################################################################
$lang['coco_modules'] = array(
                                 "team"=>"Equipo",
                                 "times"=>"Horas",
                                 "inventory"=>"Inventario",
                                 "projects"=>"Proyecto",
                                 "dashboard"=>"Dashboard",
                                 "accounts"=>"Flujo de Caja");

$lang['coco_privileges'] = array(
                                 "auth/add_user"=>"Agregar Usuario",
                                 "auth/add_role"=>"Agregar Rol",
                                 "auth/assign_privileges"=>"Asignar Privilegios",
                                 "auth/assign_roles"=>"Asignar Roles",
                                 "times/home"=>"Revisar Horas",
                                 "times/edit_time"=>"Editar Hora",
                                 "inventory/add_inventory"=>"Agregar Inventario",
                                 "auth/home"=>"Home",
                                 "times/add_time"=>"Agregar Hora",
                                 "auth/edit_profile"=>"Editar Perfil",
                                 "auth/delete_user"=>"Borrar Usuario",
                                 "auth/manager_users"=>"Administrar Usuarios",
                                 "inventory/add_category_inventory"=>"Agregar Categoria Inventario",
                                 "times/add_time_record"=>"Agregar Registro de Hora",
                                 "times/observe_time"=>"Observar Hora",
                                 "times/correct_time_observed"=>"Corregir Hora Observada",
                                 "times/validate_time_corrected"=>"Validar Hora Corregida",
                                 "times/manager_times"=>"Administrar Horas",
                                 "times/edit_time_gral"=>"Editar Hora Por Administrador",
                                 "auth/edit_user"=>"Editar Usuario",
                                 "auth/edit_company"=>"Editar Compania",
                                 "inventory/manager_inventory"=>"Administrar Inventario",
                                 "inventory/edit_inventory"=>"Editar Inventario",
                                 "times/present_users"=>"Usuarios Presentes",
                                 "times/delete_time"=>"Borrar Hora",
                                 "pm/save_project"=>"Guardar Proyecto",
                                 "pm/home"=>"Proyectos Activos",
                                 "pm/view_project"=>"Ver Proyecto",
                                 "pm/discussions"=>"Discusiones",
                                 "pm/save_comment"=>"Guardar Comentario",
                                 "pm/members"=>"Miembros",
                                 "pm/time_records"=>"Registro de Tiempos",
                                 "pm/trash"=>"Borrador",
                                 "pm/save_time_record"=>"Guardar Registro de Tiempo",
                                 "pm/add_member"=>"Agregar Miembro",
                                 "pm/view_comment"=>"Ver Comentario",
                                 "pm/delete_permanently"=>"Borrar Objeto Permanentemente",
                                 "pm/restore"=>"Restaturar Objectos",
                                 "pm/projects_completed"=>"Proyectos Completados",
                                 "pm/save_task"=>"Guardar Tarea",
                                 "pm/save_task_by_assigned"=>"Guardar Tarea por Asignado",
                                 "pm/save_task/save_points"=>"Guardar Puntos de Tarea",
                                 "pm/save_project/save_points"=>"Guardar Puntos de Proyecto",
   
                                 "auth/manager_clients"=>"Administrar Clientes",                                 
                                 "auth/save_client"=>"Guardar Cliente",
                                 "auth/save_client_company"=>"Guardar Compania de Clientes",
                                 "auth/manager_clients_companies"=>"Administrar Companias de Clientes",
                                 "auth/delete_client_company"=>"Borrar Compania de Clientes",
                                 "auth/delete_client"=>"Borrar Cliente",
                                 "accounts/home"=>"Administrar registros de cuentas",
                                 "accounts/save_account"=>"Guardar Cuenta",
                                 "accounts/budgets"=>"Presupuestos",
                                 "accounts/save_budget_range"=>"Guardar Rangos de Presupuestos",
                                  );

$lang['coco_header_view_labels'] = array("modules"=>$lang['coco_modules'] ,
                                         "privileges"=>$lang['coco_privileges'],
                                         "profile_edit"=>"Editar Perfil",
                                        );

$lang['coco_pm_header_view_labels'] = array("modules"=>$lang['coco_modules'] ,
                                         "privileges"=>$lang['coco_privileges'],
                                         "profile_edit"=>"Editar Perfil",
                                         "msg_filesize_part1"=>"tama&ntildeo de los archivos=",
                                         "msg_filesize_part2"=>" MB intentado, Limite=",
                                         "msg_filesize_part3"=>"MB Superado",
                                        );

$lang['coco_times_status'] = array(
                                   'valid'=>'Valido',
                                   'observed'=>'Observado',
                                   'corrected'=>'Corregido',                                      
                                  );

$lang['coco_pm_action_status'] = array(
                                      'created'=>'Creado',
                                      'modified'=>'Modificado',
                                      'uploaded'=>'Subido',
                                      'moved_to_trash'=>'Movido a Basurero',
                                      'restored'=>'Restaurado',
                                      'started'=>'Iniciado',
                                      'completed'=>'Completado',
                                      'canceled'=>'Cancelado',                                      
                                      'in_process'=>'En proceso',
                                      'paused'=>'Pausado',                                    
                                     );



$lang['coco_pm_type_object'] = array("project"=>"Proyecto",
                                     "task"=>"Tarea",
                                     "message"=>"Mensaje",
                                     "discussion"=>"Discusion",
                                     "comment"=>"Comentario",
                                     "file"=>"Archivo",
                                     "time_record"=>"Registro de tiempo",
                                     "fee"=>"Cuota",                                     
                                     );
   
$lang['coco_pm_menu_project'] = array("home"=>"Inicio","discussions"=>"Discusiones",
                                          "members"=>"Miembros",
                                          "time_records"=>"Registros de tiempo", 
                                          "task_in_process"=>"Tarea en Proceso",
                                          "trash"=>"Basurero");

$lang['coco_pm_menu_clients'] = array("auth/add_client_company"=>"Add Client Company",                                       
                                      "auth/manager_client_companies"=>"Manager Client Companies",
                                      "auth/add_client"=>"Add Client",
                                      "auth/save_client_company?new=ok"=>"Add Client Company",
   );

$lang['coco_pm_save_buttons'] = array("save"=>"Guardar",
                                          "save_go_back_list"=>"Guardar",
                                          "move_to_trash"=>"Mover al basurero", 
                                          "cancel"=>"Cancelar");

#-------------------------------------------------------------------------------
$lang['coco_msg_was_not_received_attachment_files'] = "Archivos ajuntos no fueron recibidos por tener tamanos grandes, para revisarlos vaya al origen del mensaje";
$lang['coco_msg_sure_to_delete'] = "Estas seguro de borrar?";
#-------------------------------------------------------------------------------
$lang['coco_msg_pm_save_project_successfully'] = "Se ha guardado exitosamente";
$lang['coco_msg_pm_save_task_successfully'] = "Se ha guardado exitosamente";

$lang['coco_msg_pm_not_valid_task_status'] = "Secuencia de Estado Cambiado, No Valido para Tarea";
/*$lang['coco_msg_pm_']="";
$lang['coco_msg_pm_']="";
$lang['coco_msg_pm_']="";
$lang['coco_msg_pm_']="";*/
$lang['coco_msg_pm_task_members_not_exists'] = "No ha seleccionado Miembros para la Tarea";
$lang['coco_msg_pm_project_not_exists'] = "No existe 'Tarea' seleccionado ";
$lang['coco_msg_pm_project_not_exists'] = "No existe 'Proyecto' seleccionado ";
$lang['coco_msg_pm_comment_not_exists'] = "No existe 'Comentario' seleccionado";
$lang['coco_msg_pm_time_record_not_exists'] = "No existe el 'Registro de tiempo' seleccionado";
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_accounts_budgets_view_labels'] = array(
                                           "form_title"=>'Presupuestos',
                                           "select_budget_range"=>'Seleccionar Rango de Presupuestos',
                                           "column_account"=>'Cuenta',
                                           "column_in"=>'Ingresos',
                                           "column_out"=>'Egresos',
                                           "column_result"=>'Resultado',
                                           "btn_save"=>'Guardar',
                                           "msg_no_has_ranges"=>"No hay rangos de presupuestos",
                                           );

$lang['coco_accounts_save_budget_range_view_labels'] = array(
                                           "form_title"=>'Guardar Rangos Fechas de Presupuestos',
                                           "title_label"=>'Titulo',
                                           "date_from_label"=>'Desde Fecha',
                                           "date_to_label"=>'Hasta Fecha',
                                           "btn_save_budget_range"=>'Guardar',
                                           "list_title"=>'Lista de Rangos de Fechas De Presupuestos',
                                           "column_title"=>'Titulo',
                                           "column_date_from"=>'Desde Fecha',
                                           "column_date_to"=>'Hasta Fecha',
                                           );

$lang['coco_accounts_home_view_labels'] = array(
                                           "form_title"=>'Cuentas',
                                           "list_account"=>'Listar Cuenta',
                                           "select_account"=>'Listar todo...',
                                           "date_from"=>'Desde',
                                           "date_to"=>'Hasta',                                          
                                           "column_item"=>'Item',
                                           "column_date"=>'Fecha',
                                           "column_account_in"=>'Ingresos',
                                           "column_account_out"=>'Egresos',
                                           "column_account"=>'Cuenta',
                                           "msg_result_search"=>'No se encontro items',
   
                                           "btn_search" => "Buscar",
                                           "btn_add" => "Agregar",
                                           "msg_in_out_required"=>"Ingresos o Egresos es obligatorio"
                                           );

$lang['coco_accounts_save_account_item_view_labels'] = array(
                                           "form_title"=>'Guardar Item de Cuenta',
                                           "description"=>'Descripcion',
                                           "date"=>'Fecha',
                                           "account_in"=>'Ingresos',
                                           "account_out"=>'Egresos',
                                           "account"=>'Cuenta',
                                           "select_account"=>'Seleccionar Cuenta',
   
                                           "btn_save" => "Guardar",
                                           "btn_delete" => "Borrar",
                                           "msg_in_out_required"=>"Ingresos o Egresos es obligatorio"
                                           );

$lang['coco_accounts_save_account_view_labels'] = array(
                                           "form_title"=>'Guardar Cuenta',                                           
                                           "name"=>'Nombre cuenta',
                                           "description"=>'Descripcion',
                                           "parent_account"=>'Bajo la categoria',
                                           "select_account_parent"=>'Seleccionar categoria de cuenta',
                                           "column_name"=>'Nombre',
                                           "column_description"=>'Descripcion',
                                           "column_parent_account"=>'Bajo la categoria',
   
                                           "btn_save_account" => "Guardar cuenta",
                                           );
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_auth_manager_clients_view_labels'] = array(
                                           "form_title"=>'Administrar Clientes',                                           
                                           "btn_add_client"=>'Agregar Cliente',   
                                           "company_name"=>"Nombre de compania",
                                           "company_description"=>"Descripcion",
                                           "list_title"=>"Lista de Clientes",
   
                                           "column_edit_value"=>"Editar",
                                           );

$lang['coco_auth_save_client_view_labels'] = array(
                                           "title_form"=>'Guardar Cliente',                                           
                                           "client_company"=>'Compania',   
                                           "client_company_description"=>'Descripcion',   
   
                                           "user_name"=>'Usuario',   
                                           "email"=>"Email",
                                           "password"=>"Password",
                                           "picture"=>"Foto",
                                           "names"=>"Nombres",
                                           "last_name"=>"Apellidos",
                                           "btn_save"=>"Guardar cliente",
                                           "btn_delete"=>"Borrar",
                                           "language"=>"Lenguaje",
   
                                           "existing_company"=>"Empresa ya existente...",                                           
                                           "msg_cannot_register"=>"No se puede guardar",
                                           "link_manager_clients"=>"Administrar Clientes",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_manager_clients_companies_view_labels'] = array(
                                           "title_form"=>'Administrar companias de clientes',                                           
                                           "edit"=>'Editar',   
                                           "btn_add_client_company"=>'Agregar Compania de Clientes',   
                                           "msg_delete_these_insurance"=>"Estas seguro de borrarlo?",   
                                           "column_user_name"=>"Nombre de nuevo usuario",
                                           "column_profile_name"=>"Nombres",
                                           "column_last_name"=>"Apellidos",
                                           "column_e_mail"=>"E-Mail",
                                           "column_edit"=>"Editar",
                                           "link_manager_clients"=>"Administrar Clientes",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_save_client_company_view_labels'] = array(
                                           "form_title"=>'Guardar Compania de Clientes',                                           
                                           "name"=>"Compania",                                           
                                           "logo"=>"Logo",                                                                                      
                                           "description"=>"Descripcion",
                                           "btn_save"=>"Guardar",                                                                                         
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_redirect_has_not_privilege_view_labels'] = array(
                                           "msg1"=>"No tienen acceso a esta funcionalidad de URI: "
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_login_view_labels'] = array(
                                            "form_title"=>'Entrar',                                           
                                           "username"=>'Usuario',   
                                           "email"=>"Email",
                                           "password"=>"Contrase&ntildea",                                           
   
                                          "captcha_another"=>"obtener otro CAPTCHA",
                                          "captcha_audio"=>"obtener audio CAPTCHA",
                                          "captcha_image"=>"obtener imagen CAPTCHA",

                                          "captcha_above"=>"Introduzca las palabras anteriores",
                                          "captcha_numbers"=>"Introduzca los números que oyes",

                                          "captcha_confirmacion"=>"Codigo de Confirmacion",
   
                                          "remember_password"=>"Recordar mi Contrase&ntildea",
                                          "forgot_password"=>"Olvido su Contrase&ntildea?",

                                           "btn_create_company"=>"Crear Compania",
                                           "btn_login"=>"Entrar",     
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_register_view_labels'] = array(
                                            "form_title"=>'Registrar Compania',                                           
                                           "username"=>'Usuario',   
                                           "email"=>"Email",
                                           "password"=>"Contrase&ntildea",                                           
                                           "re_password"=>"Confirmar Contrase&ntildea",                                           
                                           "company_name"=>"Nombre de Compania",                                           
                                          "company_logo"=>"Logo de la Compania",                                           
                                          "name"=>"Nombre del Administrador",                                           
                                          "last_name"=>"Apellidos del Administrador",                                           
                                          "language"=>"Lenguaje",

                                          "captcha_another"=>"obtener otro CAPTCHA",
                                          "captcha_audio"=>"obtener audio CAPTCHA",
                                          "captcha_image"=>"obtener imagen CAPTCHA",

                                          "captcha_above"=>"Introduzca las palabras anteriores",
                                          "captcha_numbers"=>"Introduzca los números que oyes",

                                          "captcha_confirmacion"=>"Codigo de Confirmacion",
   
                                          "captcha_exactly"=>"Ingrese el codigo exactamente como aparece",

                                          "btn_register"=>"Registrar",  
                                           
                                          "auth_back_to_login_page"=>"Regresar al login",   
   
                                          "send_title"=>"Bienvenido a ",
                                          "paragraph1"=>"Gracias por unirte a ",
                                          "paragraph2"=>"A continuacion los detalles para el ingreso a nuestro sistema. (Asegurate de mantenerlos en lugar seguro).",
                                          "paragraph3"=>"Para visitar tu",
                                          "paragraph4"=>" homepage, por favor usa el siguiente enlace",
                                          "paragraph5"=>"Para ir",
                                          "paragraph6"=>"ahora!",
                                          "paragraph7"=>"¿El enlace no funciona? copia el siguiente enlance a la barra de direccion en tu navegador",
                                          "paragraph8"=>"Tu nombre de usuario",

                                          "paragraph9"=>"Tu correo electronico",   
                                          "paragraph10"=>"Tu contrase&ntilde;a",   
                                          "paragraph11"=>"Disfrutalo!",   
   
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_reset_password_view_labels'] = array(
             "form_title"=>'Resetear Contrase&ntilde;a',                                                    
             "new_password"=>"Nueva Contrase&ntilde;a",
             "confirm_new_password"=>"Confirmar Nueva Contrase&ntilde;a",
             "btn_save_password"=>"Guardar",
             "mail_title"=>"Su nueva contrasena en ",
             "sub_title"=>"Su nueva contrasena en",
             "paragraph1"=>"Ha cambiado su contrasena.<br />
                            Por favor, mantenga en sus registros para que no se le olvide.<br />",
             "your_username"=>"Su nombre de usuario",
             "your_email"=>"Su direccion de correo electronico",
             "thank_you"=>"Gracias,",
            );
#-------------------------------------------------------------------------------
$lang['coco_auth_forgot_password_view_labels'] = array(
                                                 "form_title"=>'Olvido su Contrase&ntildea?',                                                 
                                                 "get_new_password"=>"Obtener Nueva Contrase&ntildea",
                                                 
                                                 "auth_back_to_login_page"=>"Regresar al login",   
                                                 
                                                 "mail_form_title"=>'Cree una contraseña nueva',                                                 
                                                 "mail_sub_title1"=>"Crear una nueva contraseña",                                                    
                                                 "paragraph1" =>"Olvidaste tu contraseña, ¿eh? No es gran cosa.</br>
                                                                 Para crear una nueva contraseña, sólo tienes que seguir este enlace:</br>
                                                                 <br/>",
                                                 "link_create_password"=>"Crear una nueva contraseña",
                                                 "paragraph2" =>"Link no funciona? Copie el siguiente link en tu barra de direcciones del navegador:<br />",
                                                 "paragraph3" =>"Has recibido este correo electrónico, ya que fue solicitada por un ",
                                                 "paragraph4" =>"Usuario. Esto es parte del procedimiento para crear una nueva contraseña en el sistema.
                                                                 Si no has solicitado una nueva contraseña, por favor, hacer caso omiso de
                                                                 este correo electrónico y su contraseña se mantendrá igual.<br />",
                                                 "paragraph5" => "Gracias,<br />",                                                 
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_edit_inventory_view_labels'] = array(
                                           "title_form"=>'Editar Item Inventario',    
   
                                           "category"=>"Categoria",
                                           "name"=>"Nombre",
                                           "model"=>"Modelo",
                                           "brand"=>"Marca",
                                           "code"=>"Codigo",
                                           "location"=>"Ubicacion",
                                           "quantity"=>"Cantidad",
                                           "purchase_price"=>"Precio de Compra",
                                           "current_picture"=>"Fotografia Actual",
                                           "picture"=>"Fotografia",
                                           
                                           "description"=>"Descripcion",
                                           "btn_save"=>"Guardar",
   
                                           "table_title"=>'Inventario',                                           
                                           "column_name"=>'Nombre',   
                                           "column_model"=>'Modelo',   
                                           "column_brand"=>'Marca',    
                                           "column_code"=>'Codigo',   
                                           "column_location"=>'Ubicacion',   
                                           "column_quantity"=>'Cantidad',   
                                           "column_purchase_price"=>'Precio de Compra',                                              
                                           );

#-------------------------------------------------------------------------------
$lang['coco_inventory_manager_inventory_view_labels'] = array(
                                                 "title_form"=>'Administrar Inventario',   
                                                 "edit"=>"Editar",
                                                 "column_name"=>"Nombre",   
                                                 "column_model"=>"Modelo",   
                                                 "column_brand"=>"Marca",   
                                                 "column_code"=>"Codigo",   
                                                 "column_location"=>"Ubicacion",   
                                                 "column_quantity"=>"Cantidad",   
                                                 "column_purchase_price"=>"Precio de Compra",   
                                                 "column_edit"=>"Editar?",   
                                                 "column_delete"=>"Borrar?",  
                                                 "msg_delete_these_insurance"=>"Esta Seguro de Borrarlo?",
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_add_category_inventory_view_labels'] = array(
                                                 "title_form"=>'Agregar Categoria de Inventario',       
                                                 "name"=>"Nombre",   
                                                 "description"=>"Descripcion",
                                                 "btn_save"=>"Guardar",
   
                                                 "table_title"=>"Lista de Categorias de Inventario",
                                                 "column_name"=>"Nombre",
                                                 "column_description"=>"Descripcion",
                                                 );
#-------------------------------------------------------------------------------
$lang['coco_inventory_add_inventory_view_labels'] = array(
                                           "title_form"=>'Agregar Item Inventario',    
   
                                           "category"=>"Categoria",
                                           "name"=>"Nombre",
                                           "model"=>"Modelo",
                                           "brand"=>"Marca",
                                           "code"=>"Codigo",
                                           "location"=>"Ubicacion",
                                           "quantity"=>"Cantidad",
                                           "purchase_price"=>"Precio de Compra",
                                           "photography"=>"Fotografia",
                                           "description"=>"Descripcion",
                                           "btn_save"=>"Guardar",
   
                                           "table_title"=>'Inventario',                                           
                                           "column_name"=>'Nombre',   
                                           "column_model"=>'Modelo',   
                                           "column_brand"=>'Marca',    
                                           "column_code"=>'Codigo',   
                                           "column_location"=>'Ubicacion',   
                                           "column_quantity"=>'Cantidad',   
                                           "column_purchase_price"=>'Precio de Compra',                                              
                                           );
#-------------------------------------------------------------------------------
$lang['coco_inventory_home_view_labels'] = array(
                                           "table_title"=>'Inventario',                                           
                                           "column_name"=>'Nombre',   
                                           "column_model"=>'Modelo',   
                                           "column_brand"=>'Marca',    
                                           "column_code"=>'Codigo',   
                                           "column_location"=>'Ubicacion',   
                                           "column_quantity"=>'Cantidad',   
                                           "column_purchase_price"=>'Precio de Compra',                                              
                                           );
#-------------------------------------------------------------------------------

#-------------------------------------------------------------------------------
$lang['coco_auth_public_profile_view_labels'] = array(
                                           "form_title"=>'Perfil Publico',                                           
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_edit_company_view_labels'] = array(
                                           "title_form"=>'Editar Compania',                                           
                                           "name"=>'Nombre',   
                                           "description"=>"Descripcion",
                                           "current_logo"=>"Logo Actual",                                           
                                           "logo"=>"Logo",                                           
                                           "btn_save_changes"=>"Guardar Cambios",   
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_edit_user_view_labels'] = array(
                                           "title_form"=>'Editar Usuario',                                           
                                           "user_name"=>'Usuario',   
                                           "email"=>"Email",
                                           "new_password"=>"Contrase&ntildea",                                           
                                           "names"=>"Nombres",
                                           "last_name"=>"Apellidos",
                                           "current_picture"=>"Fotografia Actual",
                                           "picture"=>"Nueva Fotografia",
                                           "btn_save_changes"=>"Guardar Cambios",   
                                           "language"=>"Lenguaje",
                                           "status"=>"Estado",
                                           "status_active"=>"Activo",
                                           "status_inactive"=>"Inactivo",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_add_user_view_labels'] = array(
                                           "title_form"=>'Agregar Usuario',                                           
                                           "user_name"=>'Usuario',   
                                           "email"=>"Email",
                                           "password"=>"Contrase&ntilde;a",
                                           "picture"=>"Fotografia",
                                           "names"=>"Nombres",
                                           "last_name"=>"Apellidos",
                                           "btn_add"=>"Agregar Usuario",   
                                           "language"=>"Lenguaje", 
                                           "msg_cannot_register"=>"No se pudo registrar"
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_manager_users_view_labels'] = array(
                                           "title_form"=>'Administrar Usuarios',                                           
                                           "edit"=>'Editar',  
                                           "btn_add_user"=>'Agregar Usuario',   
                                           "msg_delete_these_insurance"=>"Estas seguro de borrarlo?",
                                           
                                           "column_user_name"=>"Usuario",
                                           "column_profile_name"=>"Nombres",
                                           "column_last_name"=>"Apellidos",
                                           "column_e_mail"=>"E-Mail",
                                           "column_edit"=>"Editar",
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_assign_roles_view_labels'] = array(
                                           "title_form"=>'Asignar Roles a Usuarios',                                           
                                           "btn_save_changes"=>'Guardar Cambios',                                           
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_assign_privileges_view_labels'] = array(
                                           "title_form"=>'Asignar Privilegios A Roles',                                           
                                           "btn_changes"=>'Guardar Cambios',
                                           "privileges"=>$lang['coco_privileges'],
                                           "modules"=>$lang['coco_modules'],
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_add_role_view_labels'] = array(
                                           "title_form"=>'Agregar Rol',
                                           "column_name"=>'Nombre',
                                           "column_type"=>'Tipo',                                           
                                           "btn_register"=>'Agregar Rol',                                           
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_list_users_view_labels'] = array(
                                           "title_form"=>'Lista de Usuarios',
                                           "column_user_name"=>'Nombre de Usuario',
                                           "column_profile_name"=>'Nombre de Perfil',
                                           "column_last_name"=>'Apellidos',
                                           "column_email"=>'E-mail',
                                           );
#-------------------------------------------------------------------------------
$lang['coco_auth_edit_profile_view_labels'] = array(
                                           "title_form"=>'Editar Perfil',
                                           "user_name"=>"Usuario",
                                           "password"=>"Contrase&ntildea Actual",
                                           "new_password"=>"Nueva Contrase&ntildea",
                                           "email_address"=>"Correo Electronico",
                                           "name"=>"Nombres",
                                           "last_name"=>"Apellidos",
                                           "current_picture"=>"Fotografia Actual",
                                           "picture"=>"Fotografia",
                                           "save_changes"=>"Guardar Cambios",
                                           "language"=>"Lenguage",
                                        );
#-------------------------------------------------------------------------------
#-------------------------------------------------------------------------------
$lang['coco_times_send_changed_time_by_email_view_labels'] = array(                                        
                                        "company"=>'Compania',
                                        "sent_to"=>'Este Cambio de Hora, fue enviado a',
                                        "paragraph1"=>"Cambio Hora, de ",                                        
                                        "paragraph2"=>"a ",
                                        "view_change"=>"Ver el cambio ",
                                        "change_time"=>"Cambio de Hora ",
                                        );
#-------------------------------------------------------------------------------
$lang['coco_times_present_users_view_labels'] = array(
                                        "title_form"=>'Equipo',
                                        "last"=>'Ultimo',
                                        "updates"=>'Actualizaciones',
                                        );
#-------------------------------------------------------------------------------
$lang['coco_times_edit_time_gral_view_labels'] = array(
                                        "title_form"=>'Editar tiempo',
                                        "in"=>"Entrada",
                                        "out"=>"Salida",
                                        "date"=>"Fecha",
                                        "hour"=>"Hora",
                                        "status"=>"Estado",
                                        "btn_save"=>"Guardar",
                                        "btn_cancel"=>"Cancelar",
                                        "times_status"=>$lang['coco_times_status'],
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_manager_times_view_labels'] = array(
                                        "title_form"=>'Administrar Horas',
                                        "select_range"=>"Seleccione Rango",
                                        "week"=>"Semana",
                                        "search"=>"Buscar",
                                        "since"=>"Desde",
                                        "until"=>"Hasta",
                                        "in"=>"Entrada",
                                        "out"=>"Salida",
                                        "sub_total"=>"Sub-total Hrs.",
                                        "msg_delete_these_insurance"=>"Estas seguro de borrarlo?",
                                        "legend_total_hours"=>"Total Horas Trabajadas en la Semana",
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_edit_time_view_labels'] = array(
                                        "title_form"=>'Horarios',
                                        "previous_mark_in"=>"Entrada (Marca Anterior)",
                                        "previous_mark_out"=>"Salida (Marca Anterior)",
                                        
                                        "in_edit"=>"Entrada (Editar)",
                                        "out_edit"=>"Salida (Editar)",
                                        "date"=>"Fecha",
                                        "hour"=>"Hora",
                                        "btn_save"=>"Guardar",
                                        "btn_cancel"=>"Cancelar",
                                       );
#-------------------------------------------------------------------------------
$lang['coco_times_add_time_record_view_labels'] = array(
                                        "title_form"=>'Horas para la Semena Seleccionada',
                                        "msg_added_successfully"=>"Se ha Agregado Satisfactoriamente",
                                        "column_in"=>"Entrada",
                                        "column_out"=>"Salida",
                                        "add_record_in_and_out"=>"Agregar Registro de Entrada  y Salida",
                                        "date"=>"Fecha",
                                        "hour"=>"Hora",
                                        "btn_save"=>"Guardar",
                                        "btn_cancel"=>"Cancelear",
                                        "msg_delete_these_insurance"=>"Estas seguro de borrarlo?",
                                        );
#-------------------------------------------------------------------------------
$lang['coco_times_home_view_labels'] = array(
                                        "title_form"=>'Horarios',
                                        "message_error"=>"Usted tiene problemas en uno de los registros anteriores. Click en la semana # ",
                                        "column_in"=>"Ingreso",
                                        "column_out"=>"Salida",
                                        "hours_by_week"=>"Horas por Semana",
                                        "clock_out"=>"Registrar Salida",
                                        "total_hrs_week"=>"Total Horas Trabajadas en la Semana",
                                        "record_in"=>"REGISTRAR ENTRADA",
                                        "record_out"=>"REGISTRAR SALIDA",
                                        "add_hrs"=>"AGREGAR HRS",
                                        "msg_delete_these_insurance"=>"Estas seguro de borrarlo?",
                                        "first_come"=>"Orden de llegada",
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_send_task_change_status_by_email_view_labels'] = array(
                                        "view_comment"=>'Ver Comentario',
                                        "by"=>'Por',
                                        "project"=>'Proyecto',
                                        "company"=>'Compania',
                                        "task_change_status" =>"Modifico Tarea a Estado de",
                                        "sent_to" =>"Esta Modificacion de Estado de Tarea fue enviado para",
                                        "action_status"=>$lang['coco_pm_action_status'],   );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_task_view_labels'] = array('title_form'=>"Guardar Tarea",                                                                                
                                        "title"=>'Titulo',
                                        "assigned_to"=>'Asignado a',
                                        "status"=>'Estado',
                                        "priority"=>'Prioridad',
                                        "start_date"=>'Fecha de inicio',
                                        "end_date"=>'Fecha de entrega',
                                        "hour"=>'Hora',
                                        
                                        "points"=>'Puntos',
                                        "is_private"=>'Es privado',
                                        
                                        'move_to_trash'=>'Mover al basurero',
                                        'new_attachment_file'=>'Nuevo archivo adjunto',
                                        "percent_completed"=>'Porcentaje completado',
                                        "object_status"=>$lang['coco_pm_action_status'],
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_send_task_by_email_view_labels'] = array(
                                        "view_comment"=>'Ver Comentario',
                                        "by"=>'Por',
                                        "project"=>'Proyecto',
                                        "company"=>'Compania',
                                        "task_in" =>"Puso Tarea en",
                                        "sent_to" =>"Esta Tarea fue enviado para",
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_send_comment_by_email_view_labels'] = array(
                                        "view_comment"=>'Ver Comentario',
                                        "by"=>'Por',
                                        "project"=>'Proyecto',
                                        "company"=>'Compania',
                                        "commented_on" =>"Comento en",
                                        "sent_to" =>"Este comentario fue enviado para",
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_trash_view_labels'] = array('title_form'=>"Basurero",                                        
                                        'created_by'=>'Creado por',
                                        'in'=>'En',
                                        'restore'=>'Restaurar',
                                        'delete_permanently'=>'Eliminar permanentemente',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "type_object"=>$lang['coco_pm_type_object'],   
                                        );

#-------------------------------------------------------------------------------
$lang['coco_pm_time_records_view_labels'] = array('title_form'=>"Registros de tiempo",
                                        'link_new_time_record'=>'Nuevo registro de tiempo',
                                        'link_edit'=>'Editar',
                                        'assigned_to'=>'Asignado a',                                        
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_time_record_view_labels'] = array('title_form'=>"Guardar Registro de Tiempo",
                                        'quantity'=>'Cantidad Horas',
                                        'description'=>'Descripcion',
                                        'date'=>'Fecha',
                                        'user'=>'Asignado a',
                                        'billable_status'=>'Estado Facturable',
                                          
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_members_view_labels'] = array('title_form'=>"Miembros",
                                        'link_add_member'=>'Agregar/Quitar miembros',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_view_comment_view_labels'] = array('title_form'=>array("discussion"=>'Discusion',
                                                                      "comment"=>'Comentarios',),
                                        'link_new_comment'=>'Nuevo Comentario', 
                                        'said'=>'comento',                                       
                                        'suscribers'=>'Suscriptores',
                                        'replies_comments'=>'Respuestas',
                                        'replies'=>'Respuestas',
                                        'link_edit'=>'Editar',                                        
                                        'created_by'=>'Creado por',
                                        'reply'=>'Responder',
                                        'attachments'=>'Archivos adjuntos',
   
                                        'put_a_task'=>'Puso tarea',
                                        
                                        'completed'=>'COMPLETADOS',
                                        'pending'=>'PENDIENTES',
                                        'tasks' => 'Tareas',
                                        'add_task' => 'Agregar Tarea',
                                           
                                        'is_task'=>'Es Tarea?',
                                        'is_private'=>'Es Privado?',
   
                                        'members_assignable'=>'Miembros Asignables',
   
                                        'title_add_comment'=>'Agregar comentario',                                        
                                        'content'=>'Contenido',                                        
                                        'new_attachment_file'=>'Nuevo archivo adjunto',
                                        "object_status"=>$lang['coco_pm_action_status'],                                        
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_comment_view_labels'] = array('title_form'=>array("discussion"=>'Guardar Discusion',
                                                                      "comment"=>'Guardar Comentario',),
                                        'set_a_task_also'=>'Establecer como 1ra una tarea, tambien',
                                        'members'=>'Miembros',
                                        'content'=>'Contenido',
                                        'title'=>'Titulo',
   
                                        'is_private'=>'Es privado',
   
                                        'new_attachment_file'=>'Nuevo archivo adjunto',
                                        'base_comment'=>'Comentario base',
                                        'move_to_trash'=>'Mover al basurero',
   
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        "save_buttons"=>$lang['coco_pm_save_buttons'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_discussions_view_labels'] = array('title_form'=>'Discusiones',
                                        'link_new_discussion'=>'Nueva Discusion',
                                        'by'=>'Por',
                                        'started_by'=>'Iniciado por',
                                        'reply'=>'Comentar',
                                        'replies'=>'Respuestas',
                                        'last_comment_published'=>'Ultimo mensaje publicado el',
                                        'link_edit'=>'Editar',
                                        "menu_project"=>$lang['coco_pm_menu_project'],
                                        );
#-------------------------------------------------------------------------------
$lang['coco_pm_save_project_view_labels'] = array('title_form'=>'Guardar Proyecto',
                                          'title'=>'Titulo',
                                          'points'=>'Puntos',
                                          'start_date'=>'Fecha de inicio',
                                          'end_date'=>'Fecha de finalizacion',
                                          'priority'=>'Prioridad',
                                          'status'=>'Estado',
                                          'content'=>'Contenido',
                                          "action_status"=>$lang['coco_pm_action_status'],
                                          "save_buttons"=>$lang['coco_pm_save_buttons'],
                                          "menu_project"=>$lang['coco_pm_menu_project'],   );
#-------------------------------------------------------------------------------

$lang['coco_pm_view_project_view_labels'] = array('by'=>'Por',
                                        'activities'=>'Historial de Actividades',
                                        'edit'=>'Editar',
                                        'completed'=>'Completado',
                                        'points'=>'Puntos',
                                        "type_object"=>$lang['coco_pm_type_object'],
                                        "action_status"=>$lang['coco_pm_action_status'],
                                        "menu_project"=>$lang['coco_pm_menu_project'],
   
                                        'performance'=>'Rendimiento',                                                   
                                        'total_task_hours'=>'Total Horas de Tarea',
                                        'total_project_hours'=>'Total Horas de Proyecto',
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_home_view_labels'] = array('title_form'=>'Proyectos',
                                        'link_new_project'=>'Nuevo Proyecto',
                                        'by'=>'Por',
                                        'link_edit_project'=>'Editar',
                                        'pending_tasks'=>'Tareas Pendientes',
                                        'completed_tasks'=>'Completadas de ',
                                        'tasks'=>' Tareas',
                                        'view_my_last_comment'=>"Ver mi ultimo Comentario/Tarea",
                                        'view_last_comment_project'=>"Ver el ultimo Comentario/Tarea de Proyecto",
   
                                        'task_in_process_by'=>"Tarea en Proceso por",
   
                                        "menu_project"=>$lang['coco_pm_menu_project'],
   );
#-------------------------------------------------------------------------------
$lang['coco_pm_add_member_view_labels'] = array('title_form'=>'Agregar/Quitar Miembros',
                                        'save'=>'Guardar',
                                       "menu_project"=>$lang['coco_pm_menu_project'],
   );
################################################################################
$lang['coco_privilege_edit_profile'] = 'Editar Perfil';
$lang['coco_msg_username_not_available'] = 'El Nombre de Usuario que escribio, no esta disponible';
$lang['coco_msg_email_not_available'] = 'ya existe usuario, con el Email que Escribio';
$lang['coco_msg_was_success_password_changed'] = 'La contraseña fue cambiado exitosamente';

$lang['coco_msg_gral_was_success_saved'] = 'Se ha guardado exitosamente';
$lang['coco_msg_gral_image_was_success_uploaded'] = 'La imagen fue subido exitosamente';
$lang['coco_msg_gral_could_not_change_password']= 'No se puede cambiar la contrase&ntildea';

$lang['coco_msg_times_less_than_now']= 'La Hora que ingreso debe ser menor que la actual, Intente de nuevo por favor';
$lang['coco_msg_times_crossed']='La Hora que ingreso esta cruzado, Intente de nuevo por favor';
$lang['coco_msg_times_was_delete_success']='Se ha borrado exitosamente';
$lang['coco_msg_times_date_not_is_into_interval']='La fecha %1$s No esta en el intervalo de la semana [%2$s, %3$s]';

$lang['auth_message_new_password_sent']='';



