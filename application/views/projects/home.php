<script>
$(document).ready( function(){  
    $(".edit_time").hover(      
     function () {      
      var id_deleter = $(this).attr('id');      
      $('#row_project_'+id_deleter).addClass("over_edit_row_time");
      
     },
     function () {
       var id_deleter = $(this).attr('id');
       
       $('#row_project_'+id_deleter).removeClass("over_edit_row_time");       
     }
    );
});
</script>
<h1 class="enqueue_by_right"><?php echo $view_labels['title_form']?></h1>
<?php
if($has_privilege_save_project)
{
   ?>            
   <span class="pm_link_button enqueue_by_left">
      <a href="<?php echo site_url("pm/save_project")?>">
         <?php echo $view_labels['link_new_project']?>
      </a>
   </span>
<?php
}
?>


<table width="100%">   
<?php
$i=0;
if(!empty($list_projects))
{
   foreach($list_projects AS $item)
   {
      $i++;
      
      $style_row_odd = "";
      
      if($i%2==0)
      {
         $style_row_odd = "pm_sumary_row_odd";
      }
      
      $url_project = site_url("/pm/view_project?project_id=".$item['object_id']);
      ?>
      <tr id="row_project_<?php echo $i?>" class="pm_sumary_row hr_top tr_row_table <?php echo $style_row_odd?>">
         <td class="list_pm_item_home list_pm_item_content pm_column_description">
            <h3 class="font_bold enqueue_by_right">
            <a href="<?php echo $url_project?>">
               <h3><?php echo nl2br(decode_chars_special($item['name'])) ?></h3>
            </a>
            </h3>     
            &nbsp;
            <?php       
            $owner_show_name ="";

            if(isset($item['owner_name']))
            {
               $owner_show_name = $item['owner_name']." ".$item['owner_last_name'];
            }
            else
            {
               $owner_show_name = $item['owner_email'];
            }         
            $url_profile_user = site_url("/auth/public_profile?user_id=".$item['user_id']);
            ?>
            <?php echo $view_labels['by']?>
            <a href="<?php echo $url_profile_user?>">
               <?php echo $owner_show_name;?>
            </a>
            <br/>
            <span class="list_pm_item_sub_text2">
            <?php echo call_user_func('get_date_literal_'.$this->config->item('language'), $item['register_date']);?>
            </span>
         </td>      
         <td class="align_top list_pm_item_content pm_column_sumpary">         
            <div class="pm_container_progress_bar enqueue_by_right">
               <?php
               $percent_completed = 0;
               if(isset($item['percent_completed']))
               {
                  $percent_completed = $item['percent_completed'];
               }
               ?>
               <div class="pm_progress_bar" style="width: <?php echo $percent_completed;?>px !important;"></div>
               <div style="white-space: nowrap" class="text_opaque"><span class="font_bold"><?php echo $item['completeds']?>
                     <?php echo $view_labels['completed_tasks']?>
                  <?php echo $item['total']?>
                  <?php echo $view_labels['tasks']?></span>
               </div>
            </div>
            <div class="enqueue_by_right">&nbsp;&nbsp;<?php echo $percent_completed?> %</div>
         </td>
         
         <td>
            <?php            
            if(!empty($item['last_task_by_project']))
            {
               $last_task_by_project = $item['last_task_by_project'];
               $url_last_task = "pm/view_comment?project_id=".$item['object_id']."&comment_id=".$last_task_by_project['parent_id']."&click_view_project_comment=1#item_comment_".$last_task_by_project['id_object'];
               ?>
               <div class="container_link_pm_user_task_button">
                  <a href="<?php echo site_url($url_last_task)?>">
                     <div class="pm_user_task_button">
                        <?php
                        $url_picture = 'public/images/icons/comment.png';
                        ?>
                        <!--<img class="enqueue_by_right img_globe" src="<?php echo site_url($url_picture);?>"></img>-->
                        <div class="button_text">
                           <span class="text_label_comment">
                              <?php echo $view_labels['view_last_comment_project']?>
                           </span>
                        </div>
                     </div>
                  </a>
               </div>
            <?php
            }?>
            <br/>
            <?php
            if(!empty($item['last_task_by_project_user']))
            {
               $last_task_by_project_user = $item['last_task_by_project_user'];
               $url_last_task = "pm/view_comment?project_id=".$item['object_id']."&comment_id=".$last_task_by_project_user['parent_id']."&click_view_my_comment=1#item_comment_".$last_task_by_project_user['id_object'];
               ?>
               <div class="container_link_pm_user_task_button">
                  <a href="<?php echo site_url($url_last_task)?>">
                     <div class="pm_user_task_button">
                        <?php
                        $url_picture = 'public/images/icons/comment.png';
                        ?>
                        <img class="enqueue_by_right img_globe" src="<?php echo site_url($url_picture);?>"></img>
                        <div class="button_text">
                           <span class="text_label_comment">
                              <?php echo $view_labels['view_my_last_comment']?>
                           </span>
                        </div>
                     </div>
                  </a>
               </div>
            <?php
            }?>
            <br/>
            </div>
            <?php
            if(!empty($item['list_user_tasks']))
            {
               $list_user_tasks = $item['list_user_tasks'];               
               
               for($j=0; $j<count($list_user_tasks);$j++)
               {
                  $url_picture = '.'.$uri_images_users.'/'.$list_user_tasks[$j]['user_id'].'_thumb_small.jpg';
                  if( ! file_exists($url_picture))
                  {
                    $url_picture = '.'.$uri_images_users.'/default.jpg';
                  }
                  $url_task = "pm/view_comment?project_id=".$item['object_id']."&comment_id=".$list_user_tasks[$j]['parent_id']."#item_comment_".$list_user_tasks[$j]['object_id'];
                  ?>
                  <div class="container_link_pm_user_task_button">
                  <a href="<?php echo site_url($url_task)?>">
                     <div class="pm_user_task_button">
                        <div class="container_icon">
                           <img class="enqueue_by_right" src="<?php echo site_url($url_picture);?>" width="95%" height="95%"></img>
                        </div>
                        <div class="button_text enqueue_by_right">
                           <span class="text_label"><?php echo $view_labels['task_in_process_by']?>:</span> <span class="text_name"><?php echo $list_user_tasks[$j]['worker_name']." ".$list_user_tasks[$j]['worker_last_name']?></span>
                        </div>
                     </div>
                  </a>
                  </div>
                  <?php
               }
            }
            ?>
            </td>
            <td class="align_top list_pm_item_content">
            <?php
            if($has_privilege_save_project)
            {
               $url_edit = site_url("pm/save_project");
               ?>
               <a href="<?php echo $url_edit."?project_id=".$item['object_id'] ?>" class="edit_time" id="<?php echo $i?>">
                  <div class="pm_icon_edit"></div>            
               </a>         
               <?php
            }
            ?>
            </td>
      </tr>
   <?php
   }
}?>
</table>