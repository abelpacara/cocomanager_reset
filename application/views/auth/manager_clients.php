<?php
if($has_privilege_save_client)
{
   ?>
   <span class="pm_link_button enqueue_by_left">
      <a href="<?php echo site_url("auth/save_client?new=ok&client_company_id=".$client_company['id_client_company'])?>"><?php echo $view_labels['btn_add_client']?></a>
   </span>
   <?php
}
?>
<h1><?php echo $view_labels['form_title']?></h1>

   <div class="clear_both"></div>
   <script>   
   $(document).ready( function(){  
       $(".delete_user").hover(     
        function () {      
         var id_deleter = $(this).attr('id');      
         $('#row_user_'+id_deleter).addClass("over_row_time");
        },
        function () {
          var id_deleter = $(this).attr('id');
          $('#row_user_'+id_deleter).removeClass("over_row_time");       
        }
       );
   });
   </script>   
   <div class="content_box" style="overflow: auto;">
      <table>
         <tr>
            <td colspan="2">
             <?php 
               $uri_image = ".".$array_uri_logo['uri']."/".$client_company['id_client_company']."_login-logo.jpg";               
               $url_image ="";
               $style = "";
               
               $url = site_url("auth/save_client_company?client_company_id=".$client_company['id_client_company']);
               
               if(file_exists($uri_image))
               {
                  $url_image = site_url($uri_image);
               }
               else
               {
                  $url_image = site_url(".".$array_uri_logo['uri']."/default_login-logo.jpg");
                  $style = "style='opacity:0.1'";
               }
               ?>
               <a href="<?php echo $url?>">
                  <img src="<?php echo $url_image?>" <?php echo $style?>/>
               </a>            
            </td>
         </tr>
         <tr>
            <td><?php echo $view_labels['company_name']?>:</td><td><?php echo $client_company['name']?></td>
         </tr>
         <tr>
            <td><?php echo $view_labels['company_description']?>:</td><td><?php echo $client_company['description']?></td>
         </tr>
      </table>
      
      <h2><?php echo $view_labels['list_title']?></h2>
   <?php
   for($i=0;$i<count($list_clients);$i++)
   {?>
      <div class="auth_box_client enqueue_by_right">
         <div class="container_picture">            
            <?php 
            $uri_image = ".".$this->config->item('uri_images_users')."/".$list_clients[$i]['user_id']."_thumb_small.jpg";               
            $url_image ="";
            $style = "";

            $url = site_url("auth/save_client?user_id_edit=".$list_clients[$i]['user_id']."&client_company_id=".$client_company['id_client_company']);

            if(file_exists($uri_image))
            {
               $url_image = site_url($uri_image);
            }
            else
            {
               $url_image = site_url(".".$this->config->item('uri_images_users')."/default.jpg");
               $style = "style='opacity:0.1'";
            }
            ?>
            <a href="<?php echo $url?>" style="display: inline-block">
               <img src="<?php echo $url_image?>" <?php echo $style?>/>
            </a>
         </div>
         <div><?php echo $list_clients[$i]['name']." ".$list_clients[$i]['last_name']?></div>         
         <div>
            <?php
            if($has_privilege_save_client)
            {
               $url_edit = site_url("auth/save_client?user_id_edit=".$list_clients[$i]['user_id']."&client_company_id=".$client_company['id_client_company']);
               ?>
               <a href="<?php echo $url_edit?>"><?php echo $view_labels['column_edit_value']?></a>
            <?php
            }
            ?>
         </div>
      </div>
   <?php   
   }
   ?>
   </div>
