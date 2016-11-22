<h1 class="enqueue_by_right"><?php echo $view_labels['title_form'];?></h1>
<span class="pm_link_button enqueue_by_left">
   <a href="<?php echo site_url("pm/save_comment?is_discussion=ok&project_id=".$project['id_object'])?>">
      <?php echo $view_labels['link_new_discussion']?>
   </a>
</span>



<table width="100%">   
   <?php   
   foreach($list_discussions AS $discussion)
   {
      $url_discussion = site_url("pm/view_comment/?comment_id=".$discussion['id_object']."&project_id=".$project_id);
      ?>
      <tr class="hr_top tr_row_table"> 
         <td>
            <table width="100%">
               <tr>
                  <td class="font_bold pm_black_text">
                     <a href="<?php echo $url_discussion?>">
                        <?php echo nl2br(decode_chars_special($discussion['name']))?>
                     </a>               
                  </td>
               </tr>
               <tr>
                  <td class="comment_text">
                        <?php echo nl2br(decode_chars_special($discussion['description']))?>
                  </td>
               </tr>
               <tr>
                  <td class="list_pm_item_sub_text2">
                     <?php echo $view_labels['started_by']?>:              
                     <?php                         
                     $owner_show_name ="";
                     if(isset($discussion['owner_name']))
                     {
                        $owner_show_name = $discussion['owner_name']." ".$discussion['owner_last_name'];
                     }
                     else
                     {
                        $owner_show_name = $discussion['owner_email'];
                     }      
                     $url_profile_user = site_url("/auth/public_profile?user_id=".$discussion['user_id']);
                     ?>
                     <a href="<?php echo $url_profile_user?>">
                        <?php echo $owner_show_name;?>
                     </a>
                     |
                     <span class="list_pm_item_sub_text2">                        
                        <?php echo call_user_func('get_date_literal_'.$this->config->item('language'),$discussion['register_date']);?>
                     </span>
                     |
                     <a href="<?php echo base_url()."/pm/save_comment?parent_comment_id=".$discussion['id_object']?>">            
                     <?php echo $view_labels['reply']?>
                     </a>
                  </td>
               </tr>
            </table>             
         </td>
         
         <td style="white-space:nowrap" class="font_bold"><?php echo $discussion['count_replies']?> <?php echo $view_labels['replies']?></td>
         
         <td class="list_pm_item_sub_text2" style="">            
            <?php            
            $last_comment_reply = $discussion['last_comment_reply'];
            
            if(isset($last_comment_reply) AND count($last_comment_reply)>0)
            {
               $owner_show_name_last ="";
               if(isset($last_comment_reply['owner_name']))
               {
                  $owner_show_name_last = $last_comment_reply['owner_name']." ".$last_comment_reply['owner_last_name'];
               }
               else
               {
                  $owner_show_name_last = $last_comment_reply['owner_email'];
               }         
               $url_profile_user = site_url("/auth/public_profile?user_id=".$last_comment_reply['user_id']);
               ?>
                  <?php echo $view_labels['last_comment_published'];?>&nbsp; 
                  <?php echo call_user_func('get_date_literal_'.$this->config->item('language'),$last_comment_reply['register_date']);?>&nbsp;
                  <?php echo $view_labels['by'];?>
                  <a href="<?php echo $url_profile_user?>">
                     <?php echo $owner_show_name_last;?>
                  </a>               
               <?php
            }
            ?>
         </td>
         <td>
            <?php 
            $url_edit_discussion = site_url("pm/save_comment?project_id=".$project['id_object']."&comment_id=".$discussion['id_object']."&is_discussion=ok");
            ?>
            <a class="edit_time" href="<?php echo $url_edit_discussion?>">
               <div class="pm_icon_edit"></div>    
            </a>            
         </td>
      </tr>
      <tr>
         <td colspan="4"></td>
      </tr>
   <?php
   }
   ?>
</table>