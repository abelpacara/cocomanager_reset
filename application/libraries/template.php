<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Template
{
	private $error = array();   
   public $max_filesize = null;   
   public $max_total_send_filesize = null;
   public $max_total_send_filesize_mb;
   #############################################################################
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->config('tank_auth', TRUE);		
      $this->ci->load->helper(array('form', 'url'));
      $this->ci->load->model('model_template');
      
      $max_upload = (int)(ini_get('upload_max_filesize'));
      $max_post = (int)(ini_get('post_max_size'));
      $memory_limit = (int)(ini_get('memory_limit'));
      $upload_mb = min($max_upload, $max_post, $memory_limit);
      
      $this->max_upload_filesize = $upload_mb;
      
      $this->max_total_send_filesize_mb = $this->max_upload_filesize;
                                                                        // representation in BYTES
      $this->max_total_send_filesize = $this->max_total_send_filesize_mb*1024*1024;
	}
   
   #############################################################################
   function multi_attach_mail($to, $from, $subject, $param_message,  $files=null, $url_source_message="")
   {
      // email fields: to, from, subject, and so on
      $from_sender = "Coco Manager <".$from.">"; 


      $headers = "From: $from_sender";

      // boundary 
      $semi_rand = md5(time()); 
      $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

      // headers for attachment 
      $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

      // multipart boundary 
      $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
      "Content-Transfer-Encoding: 7bit\n\n" . $param_message . "\n\n"; 

      // preparing attachments
      
      $total_filesize=0;
      
      //$max_filesize = 20*1000; //30 KB
      $i = 0;
      if(isset($files))
      {
         for($i=0;$i<count($files);$i++)
         {
           $filesize=0;
           if(is_file($files[$i]))
           {
               $fp =    @fopen($files[$i],"rb");            
               $filesize = filesize($files[$i]);
           }
           $total_filesize += $filesize;
         }
         
         $max_total_send_filesize = (float) ($this->max_total_send_filesize);         
         if( $total_filesize <=  $max_total_send_filesize)
         {
            /*
            echo "<br>send, TOTAL_FILESIZE = ".$total_filesize." MAX_TOTAL_SEND_FILESIZE = ".$max_total_send_filesize;
            exit;*/
            
            for($i=0;$i<count($files);$i++)
            {
               if(is_file($files[$i]))
               {
                  $message .= "--{$mime_boundary}\n";
                  $fp =    @fopen($files[$i],"rb");            
                  $filesize = filesize($files[$i]);

                  $data = @fread($fp, $filesize);
                  @fclose($fp);
                  $data = chunk_split(base64_encode($data));
                  $message .= "Content-Type: application/octet-stream; name=\"".basename($files[$i])."\"\n" . 
                  "Content-Description: ".basename($files[$i])."\n" .
                  "Content-Disposition: attachment;\n" . " filename=\"".basename($files[$i])."\"; size=".filesize($files[$i]).";\n" . 
                  "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
               }
            }
         }
         else
         {
            /*echo "<br>not send, TOTAL_FILESIZE = ".$total_filesize." MAX_TOTAL_SEND_FILESIZE = ".$max_total_send_filesize;
            exit;*/
            
            $message .= "<i>".$this->ci->lang->line('coco_msg_was_not_received_attachment_files').".</i>\n";
            //$message .= $url_source_message." \n";
         }
      }
      
      $message .= "--{$mime_boundary}--";
      $returnpath = "-f" . $from;
      //$ok = @mail($to, $subject, $message, $headers, $returnpath); 
      
      $ok = @mail($to, $subject, $message, $headers, $returnpath); 
      
      if($ok){ return $i; } else { return 0; }      
   }
   #############################################################################
   public function is_have_privilege_by_name(&$list_privileges, $privilege_name)
   {
      for($i=0;$i<count($list_privileges);$i++)
      {
         if(strcasecmp($list_privileges[$i]['name'],$privilege_name)==0)
         {
            return true;
         }
      }
      return false;
   }
   #############################################################################
   public function is_have_privilege_by_uri(&$list_privileges, $privilege_uri)
   {
      for($i=0;$i<count($list_privileges);$i++)
      {
         if(strcasecmp($list_privileges[$i]['uri'],$privilege_uri)==0)
         {
            return true;
         }
      }
      return false;
   }
   #############################################################################
   function get_list_present_membership_by($company_id, $project_id)
   {
      #------------------------------------------------------------------
      $current_ts = $this->ci->model_template->get_system_time();
      list($current_date, $current_time) = explode(" ", $current_ts);
      
      
      $dt_range_current = get_week_interval_arround_date($current_date);
      #------------------------------------------------------------------
      return $this->ci->model_projects->get_list_present_membership_by($company_id, $project_id, $dt_range_current['begin'], $dt_range_current['end']);
   }   
   #############################################################################
   function upload_file($destination_folder, $destination_file_name, $source_file_name, & $array_messages = null)
   {  
      $config['upload_path'] =  $destination_folder;
      $config['file_name'] = $destination_file_name;
      $config['allowed_types'] = '*';
      $config['max_size']	= '0';

      $path_file = $config['upload_path']."/".$config['file_name']; 

      if( file_exists( $path_file ) )
      {
         unlink( $path_file );
      }

      $this->ci->load->library('upload');
      $this->ci->upload->initialize($config);      
      $is_well = true;
      if( ! $this->ci->upload->do_upload($source_file_name) )
      {
         $array_messages[] = $this->ci->upload->display_errors();
         return FALSE;
      }
      return TRUE;
   }
   #############################################################################
   function get_system_time()
   {
      return $this->ci->model_template->get_system_time();
   }
   #############################################################################
   function get_company_by_id($company_id)
   {
      return $this->ci->users->get_company_by_id($company_id);
   }
}