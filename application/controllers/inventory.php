<?php
class Inventory extends CI_Controller
{
   
   function __construct()
   {
      parent::__construct();
      
      $this->load->library('session');
      $this->load->library('tank_auth');
      
      $this->load->helper('my_dates_helper');
      $this->load->helper('my_messages_helper');
      
      $this->load->model('model_inventory');      
      $this->load->helper('url');
      
      $this->load->helper('my_toolkits_helper');
      
      $this->load->helper('file');
      
   }
   #############################################################################
   public function edit_inventory()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); } 
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_inventory_edit_inventory_view_labels');
      
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      
      $inventory_id = 0;
      
      if(isset($_GET['inventory_id']))
      {
         $inventory_id = $_GET['inventory_id'];
         
         $redirect = $_GET['redirect'];
      }
      else if($_POST['inventory_id'])
      {
         $inventory_id = $_POST['inventory_id'];
         
         $redirect = $_POST['redirect'];         
      }      
      $my_messages = "";
      
      if(isset($_POST['save_changes']))
      {
         $data=array(
           'category_inventory_id'=>$_POST['category_inventory_id'],
           'name'=>$_POST['name'],
           'model'=>$_POST['model'],
           'mark'=>$_POST['mark'],
           'code'=>$_POST['code'],
           'current_location'=>$_POST['current_location'],
           'registration_date'=>date('Y-m-d H:i:s'),
           'quantity'=>$_POST['quantity'],
           'buy_price'=>$_POST['buy_price'],
           'description'=>$_POST['description']);
         
           $this->model_inventory->update_inventory($data, array("id"=>$inventory_id));           
           $this->upload_image_inventory($inventory_id, 'picture', $my_messages);           
           redirect(site_url(urldecode($redirect)));
      }
      
      $view_data['my_messages'] = $my_messages;
      
      
      $view_data['redirect'] = $redirect;      
      $view_data['uri_images_inventory'] = $this->config->item('uri_images_inventory');      
      $view_data['list_category_inventory'] = $this->model_inventory->get_list_category_inventory();      
      
      $view_data['inventory'] = $this->model_inventory->get_inventory_by_id($inventory_id);      
      
      $this->load->view('template/header',$header_data);
      $this->load->view('inventory/edit_inventory',$view_data);      
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function delete_inventory()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); } 
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      if(isset($_GET['inventory_id']))
      {
         $this->model_inventory->delete_inventory($_GET['inventory_id']);                  
         $preffix_file_name = "." . $this->config->item('uri_images_inventory')."/".$_GET['inventory_id'];
         
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
         
         redirect(site_url(urldecode($_GET['redirect'])));
      }
   }
   
   #############################################################################
   public function manager_inventory()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); } 
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_inventory_manager_inventory_view_labels');
      
      $view_data['list_inventory'] = $this->model_inventory->get_list_inventory($header_data['company_id']);      
      $this->load->view('template/header',$header_data);      
      $this->load->view('inventory/manager_inventory',$view_data);      
      $this->load->view('template/footer', $view_data);
   }
   #############################################################################
   public function upload_image_inventory($inventory_id, $source_file_name, &$my_messages = "")
   {
      $config['upload_path'] = '.'.$this->config->item('uri_images_inventory');
      $config['file_name'] = $inventory_id.'.jpg';                  
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size']	= '100';
      $config['max_width']  = '500';
      $config['max_height']  = '500';
      
      
      $path_file = $config['upload_path']."/".$config['file_name'];      
      
      if(file_exists($path_file))
      {
         unlink( $path_file );
      }
      
      $this->load->library('upload');

      $this->upload->initialize($config);

      if( ! $this->upload->do_upload($source_file_name))
      {
         $my_messages .= get_message_warning($this->upload->display_errors());         
         return;
      }
      else
      {
         //echo "<br><br>FAILED <br>";
      }
      
      $this->load->library('image_lib');
      /****************** CREATE THUMB-NAIL thumb medium **********************/     
      $config_thumb_medium['image_library'] = 'gd2';      
      $config_thumb_medium['source_image'] = $config['upload_path']."/".$inventory_id.'.jpg';        
      $config_thumb_medium['new_image'] = $config['upload_path']."/".$inventory_id.'_thumb_medium.jpg';      
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
      $config_thumb['source_image'] = $config['upload_path']."/".$inventory_id.'.jpg';        
      $config_thumb['new_image'] = $config['upload_path']."/".$inventory_id.'_thumb_small.jpg';      
      $config_thumb['create_thumb'] = false;
      $config_thumb['maintain_ratio'] = TRUE;
      $config_thumb['width'] = 32;
      $config_thumb['height'] = 32;
      
      //$this->load->library('image_lib', $config_thumb); 
      $this->image_lib->initialize($config_thumb); 
      
      if ( ! $this->image_lib->resize() )
      {
         $my_messages .= get_message_warning($this->image_lib->display_errors());
      }
      
      /**********************************************************************/
     
      
      
      /**********************************************************************/
      //$this->image_lib->clear();
   }
      
   #############################################################################
   function add_inventory()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); } 
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_inventory_add_inventory_view_labels');
      $my_messages = "";
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_inventory_add_inventory_view_labels');
      
      if(isset($_POST['add_category_inventory']))
      {
         $data=array(
           'category_inventory_id'=>$_POST['category_inventory_id'],
           'name'=>$_POST['name'],
           'model'=>$_POST['model'],
           'mark'=>$_POST['mark'],
           'code'=>$_POST['code'],
           'current_location'=>$_POST['current_location'],
           'registration_date'=>date('Y-m-d H:i:s'),
           'quantity'=>$_POST['quantity'],
           'buy_price'=>$_POST['buy_price'],
           
           'company_id'=>$header_data['company_id'],
            
           'description'=>$_POST['description']);
         
           $inventory_id = $this->model_inventory->add_inventory($data);
           
           if(isset($_FILES['picture']['name']) AND 
            strcasecmp($_FILES['picture']['name'],"")!=0)
           {
              $this->upload_image_inventory($inventory_id, 'picture', $my_messages);
           }
      }
      
      $view_data['my_messages'] = $my_messages;
      
      $view_data['uri_images_inventory'] = $this->config->item('uri_images_inventory');;
      
      $view_data['list_category_inventory'] = $this->model_inventory->get_list_category_inventory();
      $view_data['list_inventory'] = $this->model_inventory->get_list_inventory($header_data['company_id']);

      $this->load->view('template/header',$header_data);
      $this->load->view('inventory/add_inventory',$view_data);
      $this->load->view('inventory/list_inventory',$view_data);
      
      $this->load->view('template/footer', $view_data);
   }
   
   #############################################################################
   function index()
   {
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); } 
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_inventory_home_view_labels');
            
      $this->load->view('template/header', $header_data);      
      $view_data['uri_images_inventory'] = $this->config->item('uri_images_inventory');;
      $view_data['list_inventory'] = $this->model_inventory->get_list_inventory($header_data['company_id']);
      
      $this->load->view('inventory/list_inventory',$view_data);
      $this->load->view('template/footer', $view_data);
   }
   
   
   #############################################################################
   function add_category_inventory()
   {
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      if (! $this->tank_auth->is_logged_in() ) { redirect('/auth/login/'); } 
      $header_data = $this->tank_auth->get_header_data();
      $this->tank_auth->has_not_privilege($header_data);
      
      /*%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/
      $view_data['view_labels'] = $view_labels = $this->lang->line('coco_inventory_add_category_inventory_view_labels');
      
      if(isset($_POST['add_category_inventory']))
      {
         $data =array('name'=>$_POST['name'],
                      'description'=>$_POST['description']);
         
         $this->model_inventory->add_category_inventory($data);
      }
      
      $view_data['list_category_inventory'] = $this->model_inventory->get_list_category_inventory();

      $this->load->view('template/header',$header_data);
      $this->load->view('inventory/add_category_inventory',$view_data);
      $this->load->view('inventory/list_category_inventory',$view_data);
      
      $this->load->view('template/footer', $view_data);
   }
}
?>
