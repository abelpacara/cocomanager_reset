<h1 class="enqueue_by_right"><?php echo $project['name']?></h1>
   <?php
   if($has_privilege_edit_project)
   {
      $url_edit = site_url("pm/save_project?project_id=".$project['project_id']);   
      ?>
      <div class="pm_link_button enqueue_by_left">
      <a href="<?php echo $url_edit?>">
         <?php echo $view_labels['edit']?>
      </a> 
      </div>
   <?php
   }
   ?>
<table class="clear_both">
   <tr>
      <td class="list_pm_item_content">
         <?php
         echo nl2br(decode_chars_special($project['description']));
         ?>         
      </td>      
   </tr>
   
   <tr>
      <td class="list_pm_item_sub_text">         
         <?php echo $view_labels['action_status'][$project['status']]; ?>
         |
         <?php       
         $owner_show_name ="";

         if(isset($owner['name']))
         {
            $owner_show_name = $owner['name']." ".$owner['last_name'];
         }
         else
         {
            $owner_show_name = $owner['email'];
         }         
         $url_profile_user = site_url("/auth/public_profile?user_id=".$owner['user_id']);
         
         echo $view_labels['by'].' ';
         ?>         
            <a href="<?php echo $url_profile_user?>">
               <?php echo $owner_show_name;?>
            </a>
         |
         <?php
         echo number_format($project['percent_completed'], 0, '.', '')." % ".$view_labels['completed'];
         ?>
         |
         <?php
         echo $project['points']." ".$view_labels['points'];         
         ?>
      </td>      
   </tr>   
</table>
<?php

$this->load->view("projects/performance" );

?>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<h2><?php echo $view_labels['activities']?></h2>

<table>
   <?php
   if(isset($list_activities) AND count($list_activities)>0)
   {
      $date_pivot = $list_activities[0]['activity_date'];
      for($i=0; $i<count($list_activities);$i++)
      {?>
      <tr>
         <td>
            <fieldset class="user_box clear_both">
               <legend class="user_data_manager">
                  <h3 class="font_bold">
                  <?php                
                  echo call_user_func('get_date_literal_'.$this->config->item('language'), $date_pivot);
                  ?>
                  </h3>               
               </legend>
         <table>
        
         <?php
         for($j = $i; $j<count($list_activities) AND (strcasecmp($date_pivot, $list_activities[$j]['activity_date'])==0); $j++)
         {?>
            <tr class="tr_row_table">
               <td class="list_pm_item_content" style="padding-top: 3px!important; padding-bottom: 3px !important;">
                  <div class="enqueue_by_right align_top pm_bg_object_<?php echo $list_activities[$j]['type']?>" style="width: 120px;">
                     <?php echo $view_labels['type_object'][$list_activities[$j]['type']]?></b>
                  </div>
                  <div class="enqueue_by_right pm_description_object font_bold pm_black_text" style="width:500px">                                       
                     <?php
                     echo $my_class->get_object_link_url($list_activities[$j]);
                     ?>
                  </div>
                  
                  <div class="list_pm_item_sub_text2 text_align_left  enqueue_by_right" style="width: 220px;padding-top: 0px!important; padding-bottom: 0px !important;">
                     <?php
                     echo $view_labels['action_status'][$list_activities[$j]['action_status']];
                     //echo $list_activities[$j]['action_status'];
                     ?> 
                     <?php echo $view_labels['by'] ?>: 
                     <?php                     
                     $owner_show_name ="";
                     if(isset($list_activities[$j]['owner_name']))
                     {
                        $owner_show_name = $list_activities[$j]['owner_name']." ".$list_activities[$j]['owner_last_name'];
                     }
                     else
                     {
                        $owner_show_name = $list_activities[$j]['owner_email'];
                     }         
                     $url_profile_user = site_url("/auth/public_profile?user_id=".$list_activities[$j]['owner_user_id']);
                     ?>
                     <a href="<?php echo $url_profile_user?>">
                        <?php echo $owner_show_name;?>
                     </a>
                  </div>
                  
                  <div class="enqueue_by_right text_opaque align_top"  style="width:50px">
                     <?php
                     list($date_activity, $time_activity) = explode(" ",$list_activities[$j]['activity_date_time']);
                     echo $time_activity;
                     ?>
                  </div>
                  
               </td>
            </tr>
         <?php
         }
         if($j<count($list_activities))
         {
            $date_pivot = $list_activities[$j]['activity_date'];          
         }
         $i = $j-1;
         ?>
         </table>
            </fieldset>
         </td>
      </tr>
      <?php
      }
   }
   ?>
</table>
<?php

?>