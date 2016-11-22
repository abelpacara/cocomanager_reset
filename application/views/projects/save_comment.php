<script>
$(document).ready(function(){
         $(".delete_file").click(function () {
            
            var id_deleter = $(this).attr('id');      
            
            $('#row_id_'+id_deleter).toggleClass("over_row_time");          
         });
      });

</script>
<?php 
echo form_open_multipart($this->uri->uri_string());
?>
<h1>
<?php
if(isset($parent_comment))
{
   echo $view_labels['title_form']["comment"];
}
else
{
   echo $view_labels['title_form']["discussion"];   
}
?>
</h1>

<?php
$quantity_files = 3;
?>   
<input type="hidden" id="quantity_files" name="quantity_files" value="<?php echo $quantity_files?>"/>

<script>
$(document).ready(function(){

   $('#add_file').click(function(){
         
         var quantity_files= $('#quantity_files').attr('value');
         quantity_files++;
         $('#quantity_files').attr('value', quantity_files);
         
         var str_append = "";
         str_append += "<tr>";      
         str_append += "<td class='text_label_project'><?php echo $view_labels['new_attachment_file'] ?>"+quantity_files+":</td>";
         str_append += "<td  style='white-space: nowrap'>";
         str_append += "<input type='file' name='file_"+quantity_files+"' class='pm_text_field file_input' size='50'/>";
         str_append += "&nbsp;<?php echo $max_upload_filesize?> MB max per file";
         str_append += "</td>";
         str_append += "</tr>";

         $('#entries').append(str_append);   
         
         show_filesize_restrict(".file_input", <?php echo $max_total_send_filesize_mb?>,'#current_filesizes');
   });
   
   
   //binds to onchange event of your input field
   show_filesize_restrict(".file_input", <?php echo $max_total_send_filesize_mb?>,'#current_filesizes');
   
   
});


</script>

<table id="entries">      
   <?php   
   if(isset($parent_comment))
   {
   ?>
   <tr>
      <td>
         <?php echo $view_labels['base_comment'] ?>:
      </td>
      <td>
         <?php echo $parent_comment['name']?>
      </td>
   </tr>
   <?php
   }?>
   <?php
   
   if(isset($is_discussion) OR !isset($parent_comment))
   {
      $title = isset($comment['name'])?$comment['name']:"";
      ?>
      <tr>
         <td class="text_label_project">
            <?php echo $view_labels['title']?>
            <span class="pm_bullet_required">*</span>
         </td>
         <td><input type ="text" name="title" class="pm_text_field pm_width_text_field" value="<?php echo $title?>"/></td>
      </tr>
      <?php
      
   }
   ?>
   <tr>      
      <td colspan="1"  class="text_label_project">
         <?php echo $view_labels['content'] ?>:
      </td>
      <?php
      $content = isset($comment['description'])?$comment['description']:"";
      ?>
      <td colspan="2">
         <textarea name="content" rows="5" class="pm_text_field pm_width_text_field"><?php echo $content?></textarea>
      </td>
   </tr>   
   <?php
   if( !(isset($is_discussion) OR !isset($parent_comment)) )
   {?>
      <tr>
         <td></td>
         <td>
            <table style="width:100%">
               <tr>                  
                  <td class="text_label_project">
                     <?php echo $view_labels['is_private'] ?>?:
                     <input type="checkbox" name="is_private" id="_is_private"  class="_displayer_members" value="ok"
                            <?php
                            if(isset($comment['is_private']))
                            {
                               echo "checked='yes'";
                            }
                            ?>
                            />
                  </td>
               </tr>
            </table>
         </td>
      </tr>
      <?php
   }
   ?>
   
   
   <?php
   //if( (isset($is_discussion) OR !isset($parent_comment)) OR isset($comment['is_private']))
   //{
      ?>
      <tr>
         <td>
            <span id="label_list_members_displayer_members" class="text_label_project">
               <?php echo $view_labels['members']?>:
            </span>
         </td>
         <td id="list_members_displayer_members" colspan="2">
            <?php
            echo get_display_selection_membership("", $list_membership, $uri_images_users, $user_id);
            ?>
         </td>
      </tr>
   <?php
   //}
   ?>
   
   <?php
   /*
   if( empty($comment) )
   {
      
      ?>
      <tr>
         <td class="text_label_project"><?php echo $view_labels['set_a_task_also']?></td>
         <td><input type="checkbox" name="set_as_task_also"/></td>
      </tr>
      <?php
   }
   */
   
   for($i=1;$i<=$quantity_files;$i++)
   {
      ?>      
      <tr>
         <td class="text_label_project" style="white-space: nowrap"><?php echo $view_labels['new_attachment_file'] ?> <?php echo $i?>:</td>
         <td   style='white-space: nowrap'>
            <input type="file" name="file_<?php echo $i?>" class="pm_text_field file_input" size="50"/>
            &nbsp;<?php echo $max_upload_filesize?> MB max per file
         </td>
      </tr>
      <?php
   }   
   ?>
   
</table>
<table>
   <tr>
      <td class="text_label_project"><div id="current_filesizes" style="padding-left: 10px; padding-right: 10px">0MB</div></td>
      <td colspan="3" class="text_label_project"><?php echo "Max. Total File Sizes = ".$max_total_send_filesize_mb ?> MB</td>
   </tr>
</table>

<table style="width: 100%">   
   <tr>
      <td>
         <input type="button" name="" id="add_file" value="+" style="float: right; cursor:pointer;"/>
      </td>
      <td style="width: 350px"></td>
         
   </tr>
</table>




<table style="width: 100%">   
   
   <tr>
      <td colspan="3">
         <table>
            <?php
            if(isset($comment['id_object']))
            {
               for($i=0;$i<count($list_attachment_files);$i++)
               {
                  $url_file = site_url($this->config->item("uri_comment_files")."/".$comment['id_object']."/".get_filename_uploaded($list_attachment_files[$i]['name']));
                  ?>
                  <tr id="row_id_<?php echo $i?>">
                     <td class="text_label_project"><?php echo $view_labels['new_attachment_file'] ?> <?php echo ($i+1)?>:</td>
                     <td class="pm_container_file_uploaded pm_width_text_field">
                        <a href="<?php echo $url_file?>"><?php echo $list_attachment_files[$i]['name']?>
                        </a>                  
                        &nbsp;&nbsp;
                        <div class="enqueue_by_left">
                        <input type="checkbox" class="delete_file" id="<?php echo $i?>" name="ids_delete_object[]" value="<?php echo $list_attachment_files[$i]['id_object']?>"/>
                        <?php echo $view_labels['move_to_trash'] ?>
                        </div>
                        <input type="hidden" name="names_delete_object[]" value="<?php echo $list_attachment_files[$i]['name']?>"/>
                     </td>
                  </tr>
                  <?php
               }
            }
            ?>
         </table>
      </td>
   </tr>   
   
</table>


<table style="width: 100%">
   <tr>
      <td colspan="3">
         <?php
         if(isset($comment['id_object']))
         {
         ?>
            <input type="hidden" name="comment_id" value="<?php echo $comment['id_object']?>"/>
         <?php
         }
         
         if( ! isset($comment) )
         {?>
            <input type="hidden" name="add" value="ok"/>
         <?php
         }
         if(isset($parent_comment_id))
         {
         ?>
            <input type="hidden" name="parent_comment_id" value="<?php echo $parent_comment_id?>"/>
         <?php
         }
         if(isset($is_discussion))
         {?>
            <input type="hidden" name="is_discussion" value="<?php echo $is_discussion?>"/>
            <?php
         }
         ?>
         <input type="hidden" name="project_id" value="<?php echo $project_id?>"/>
         <input type="submit" name="save_go_to_list" value="<?php echo $view_labels['save_buttons']['save_go_back_list'] ?>"/>
         <input type="submit" name="delete" value="<?php echo $view_labels['save_buttons']['move_to_trash'] ?>"/>
         <input type="reset" name="cancel" value="<?php echo $view_labels['save_buttons']['cancel'] ?>"/>
      </td>
   </tr>
</table>


</form>