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
if($has_privilege_save_time_record)
{
   ?>
   <span class="pm_link_button enqueue_by_left">
      <a href="<?php echo site_url("pm/save_time_record?project_id=".$project_id)?>">  
         <?php echo $view_labels['link_new_time_record'];?>
      </a>
   </span>
   <?php
}
?>

<br/>   
<table width="100%"> 
  
   <?php
   //print_r($list_activities);   
   foreach($list_time_records AS $time_record)
   {
      $url_time_record = site_url("pm/view_discussion/?discussion_id=".$time_record['id_object']);
      ?>
      <tr class="hr_top tr_row_table">
         <td>
            <h3 class="font_bold enqueue_by_right">
               <a name="item_time_record_<?php echo $time_record['id_object']?>"></a>
               <?php echo $time_record['name']?>
            </h3> 
            <br/>       

             <?php                         
            $worker_show_name ="";

            if(isset($time_record['worker_name']))
            {
               $worker_show_name = $time_record['worker_name']." ".$time_record['worker_last_name'];
            }
            else
            {
               $worker_show_name = $time_record['worker_email'];
            }      
            $url_profile_user = site_url("/auth/public_profile?user_id=".$time_record['worker_user_id']);
            ?>
            <span class="list_pm_item_sub_text2"><?php echo $view_labels['assigned_to']?>:
            <a href="<?php echo $url_profile_user?>">
               <?php echo $worker_show_name;?>
            </a> 
            <b class="list_pm_item_sub_text2">               
               <?php echo call_user_func('get_date_literal_'.$this->config->item('language'), $time_record['register_date']) ?>                
            </b>
            </span>
          </td>
         
         <td>
            <h3 class="font_bold enqueue_by_right"><?php echo $time_record['quantity']?> Hrs.</h3>
         </td>
         
         <td>
            <h3 class="font_bold enqueue_by_right"><?php echo $time_record['billable_status']?></h3>
         </td>
         <td class=" list_pm_item_content">
            <?php 
            $url_edit_time_record = site_url("pm/save_time_record?time_record_id=".$time_record['id_object']);
            ?>
            <a href="<?php echo $url_edit_time_record?>"><div class="pm_icon_edit"></div> </a>
         </td>    
      </tr>
   <?php
   }
   ?>
</table>