<h1><?php echo $view_labels['title_form']?></h1>

<span class="pm_link_button enqueue_by_left">
      <a href="<?php echo site_url('auth/add_user')?>"><?php echo $view_labels['btn_add_user']?></a>
</span>


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
   $quantity_columns = 7;
   $quantity_columns += 1;
   
   
   
   if(count($list_users)>0)
   {  
      $role = $list_users[0]['role'];   
      ?>
      <h3 class="clear_both title_team_present"><?php echo $role?></h3>
      <?php
      $redirect = urlencode(("auth/manager_users"));
      
      for($i=0; $i<count($list_users); $i++)
      {
         $style_present="user_absent";
         if(strcasecmp($list_users[$i]['is_present'],"1")==0)
         {
            $style_present="user_present";
         }

         $style_enqueue_right="";
         if(( ($i+1) % $quantity_columns ) > 0 )
         {
            $style_enqueue_right=" style='float:left' ";
         }
         else
         {
             $style_enqueue_right =" style='clear:left; float:left' ";
         }

         //$role = $list_users[$i]['role'];

         if(strcasecmp($role, $list_users[$i]['role'])!=0)
         {?>
            <h3 class="clear_both title_team_present"><?php echo $list_users[$i]['role']?></h3>
            <?php
            $role = $list_users[$i]['role'];
         }
         
         $url_jump_login = site_url("auth/edit_user?user_id=".$list_users[$i]['user_id']."&redirect=".$redirect);
         ?>
         
            <div class="presence_person <?php echo $style_present?>" <?php echo $style_enqueue_right?>>
               <?php
               $url_picture = '.'.$uri_images_users.'/'.$list_users[$i]['user_id'].'_thumb_small.jpg';
               
               if( ! file_exists($url_picture))
               {
                  $url_picture = 'public/images/user_present.jpg';
               }
               ?>
               <div class="presence_person_picture">
                  <a href="<?php echo $url_jump_login?>"><img src="<?php echo site_url($url_picture);?>"/></a>
               </div>
               <div class="user_name">
                  <?php echo $list_users[$i]['name']." ".$list_users[$i]['last_name'];?>
               </div>
               <div class="email">
                  <?php echo $list_users[$i]['email'];?>
               </div>
               <div class="last_time_user">
                 <?php echo $list_users[$i]['last_time']; ?>
               </div>
               
               <div class="clock">                  
                  <?php echo $list_users[$i]['total_hours'];?> Hrs.
               </div>
               <!--
               <div class="last_time_user">
                 # <?php echo $view_labels['updates']?>: <?php echo $list_users[$i]['num_corrections']?>
               </div>
               -->
               <?php
               if( ! empty($list_users[$i]['task_actived']) )
               {  
                  $size_excerpt = 20;
                  $project_name =  $list_users[$i]['task_actived']['project'];
                  $task_name = $list_users[$i]['task_actived']['description'];
                  
                  $sub_project_name = substr($project_name, 0, $size_excerpt);
                  $sub_task_name = substr($task_name, 0, $size_excerpt);
                  ?>
                  <div class="project_name">                    
                     <a href="<?php echo site_url("pm/view_project?project_id=".$list_users[$i]['task_actived']['project_id'])?>">
                     <?php                   
                     echo decode_chars_special($sub_project_name);
                     if(strlen($project_name)> strlen( $sub_project_name) )
                     {
                        echo "....";
                     }
                     ?>
                     </a>
                  </div>
                  <?php
                  $url_task = site_url("pm/view_comment?project_id=".$list_users[$i]['task_actived']['project_id']."&comment_id=".$list_users[$i]['task_actived']['parent_id']."#item_comment_".$list_users[$i]['task_actived']['id_object']);
                  ?>
                  <div class="task_name">                    
                     <a href="<?php echo $url_task?>">
                     <?php 
                     echo decode_chars_special($sub_task_name);
                     if(strlen($task_name)> strlen( $sub_task_name) )
                     {
                        echo "....";
                     }
                     ?>
                     </a>
                  </div>
               <?php
               }
               ?>
            </div>
      <?php
      }
   }?>
   </div>
