<span class="pm_link_button enqueue_by_left">
   <?php
   if($has_privilege_save_client_company)
   {?>
      <a href="<?php echo site_url("auth/save_client_company?new=ok")?>">
         <?php echo $view_labels['btn_add_client_company']; ?>
      </a>                                      
   <?php
   }
   ?>
</span>
                                      
<h1><?php echo $view_labels['title_form']?></h1>

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
      <?php
      for($i=0; $i<count($list_clients_companies);$i++)
      {?>
         <div class="enqueue_by_right auth_box_client_company">
            <div class="container_logo">
               <?php 
               $uri_image = ".".$array_uri_logo['uri']."/".$list_clients_companies[$i]['id_client_company']."_login-logo.jpg";               
               $url_image =site_url("/".$array_uri_logo['uri']."/".$list_clients_companies[$i]['id_client_company']."_login-logo.jpg");
               $style = "";
               
               $url = site_url("auth/save_client_company?client_company_id=".$list_clients_companies[$i]['id_client_company']);
               
               if(file_exists($uri_image))
               {
                  $url_image = site_url($uri_image);
               }
               else
               {
                  $url_image = site_url($array_uri_logo['uri']."/default_login-logo.jpg");
                  $style = "style='opacity:0.1'";
               }
               ?>
               <a href="<?php echo $url?>">
                  <img src="<?php echo $url_image?>" <?php echo $style?>/>
               </a>
            </div>
            <div class="name">               
               <a href="<?php echo $url?>">
                  <?php echo $list_clients_companies[$i]['name']?>
               </a>
            </div>
            <div><?php echo $list_clients_companies[$i]['description']?></div>
            <div>
               <?php
               if($has_privilege_manager_clients)
               {?>
                  <a href="<?php echo site_url("auth/manager_clients?client_company_id=".$list_clients_companies[$i]['id_client_company'])?>"><?php echo $view_labels['link_manager_clients']?>
                  </a>
               <?php
               }?>
            </div>
         </div>
      <?php
      }
      ?>
      
   </div>
