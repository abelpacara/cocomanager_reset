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

<span class="pm_link_button enqueue_by_left">
   <a href="<?php echo site_url("pm/save_project")?>">
      <?php echo $view_labels['link_new_project']?>
   </a>
</span>


<table width="100%">   
<?php
$i=0;
foreach($list_projects AS $item)
{
   $i++;
   $url_project = site_url("/pm/view_project?project_id=".$item['object_id']);
   ?>
   <tr id="row_project_<?php echo $i?>" class="hr_top tr_row_table">
      <td class="list_pm_item_home list_pm_item_content">
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
         $url_profile_user = site_url("/auth/owner_profile?owner_id=".$item['user_id']);
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
      
      <td class="align_top list_pm_item_content">         
         <div class="pm_container_progress_bar enqueue_by_right"><div class="pm_progress_bar" style="width: <?php echo $item['percent_completed']?>px !important;"></div></div>
         <div class="enqueue_by_right">&nbsp;&nbsp;<?php echo $item['percent_completed']?> %</div>
      </td>
      
      <td class="align_top list_pm_item_content">
         <?php
         $url_edit = site_url("pm/save_project");
         ?>
         <a href="<?php echo $url_edit."?project_id=".$item['object_id'] ?>" class="edit_time" id="<?php echo $i?>">
            <div class="pm_icon_edit"></div>            
         </a>         
      </td>
   </tr>
<?php
}?>
</table>