<?php 
echo form_open_multipart($this->uri->uri_string());
?>
<script>
$(function() {
   $( "#date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });   
});
$(function(){
   $('.style_mask').customStyle();
});
</script>
<?php
$styles_mask="class='style_mask' style='width:70px;'";
?>
<h1><?php echo $view_labels['title_form']?></h1>
<table class="table_form_project">
   <tr>
      <?php
      $quantity = isset($time_record['quantity'])?$time_record['quantity']:"";
      ?>
      <td class="text_label_project"><?php echo $view_labels['quantity']?>:</td>
      <td>
         <input name="quantity" value="<?php echo $quantity?>"  size="40" class="pm_text_field" style="width:50px;"/><span class="text_label_project"> &nbsp; Hrs.</span>
      </td>

   </tr>
   <tr>
      <td class="text_label_project"><?php echo $view_labels['description']?>:</td>
      <?php
      $name = isset($time_record['name'])?$time_record['name']:"";
      ?>
      <td colspan="1"><input name="name" value="<?php echo $name?>"  class="pm_text_field" size="70"/></td>      
   </tr>
   <tr>
      <td colspan="1" class="text_label_project">
         <?php echo $view_labels['date']?>:
      </td>
      <?php
      $datetime = isset($time_record['register_date'])?$time_record['register_date']:"";
      $date = "";
      if(strcasecmp($datetime,"")!=0)
      {
         list($date, $time) = explode(" ", $datetime);
      }
      ?> 
      <td colspan="1" size="50" >
         <input type="text" name="date" id="date" style="width:200px;" class="arrow_drowpdown" value="<?php echo $date?>" readonly="yes"/>
      </td>
   </tr>
   <tr>
      <td colspan="1" class="text_label_project">
         <?php echo $view_labels['user']?>:
      </td>      
      <td colspan="1"  class="style_mask">
           <select name="user_id" style="width:200px;">
            <option value="">...</option>
            <?php          
            if(isset($list_members) AND count($list_members)>0)
            {
               $role = $list_members[0]['role'];            
               for($i=0; $i<count($list_members); $i++)
               {               
                  ?>
                  <optgroup label="<?php echo $role;?>">               
                     <?php
                     $j=0;
                     for($j = $i; $j<count($list_members) AND (strcasecmp($role, $list_members[$j]['role'])==0); $j++)
                     {
                        $owner_show_name ="";
                        if(isset($list_members[$j]['name']))
                        {
                           $owner_show_name = $list_members[$j]['name']." ".$list_members[$j]['last_name'];
                        }
                        else
                        {
                           $owner_show_name = $list_members[$j]['email'];
                        }
                        ?>                 
                        <option value="<?php echo $list_members[$j]['user_id']?>"
                        <?php
                        if(isset($time_record['user_id']) AND strcasecmp($list_members[$j]['user_id'], $time_record['user_id'])==0)
                        {
                           echo " SELECTED ";
                        }
                        ?>
                        >
                        <?php echo $owner_show_name?>
                        </option>
                     <?php
                     }
                     if($j<count($list_members))
                     {
                        $role = $list_members[$j]['role'];                                       
                     }
                     $i = $j-1;
                     ?>
                  </optgroup>
               <?php
               }
            }?>
         </select>
         
      </td>
   </tr>
   <tr>
      <td colspan="1" class="text_label_project">
         <?php echo $view_labels['billable_status']?>:
      </td>
      <td colspan="1" class="style_mask">
         <select name="billable_status_select_id" style="width:200px;">
         <?php         
         foreach($list_billable_status AS $item)
         {
            ?>
            <option value="<?php echo $item['id']?>"
              <?php
              if(isset($time_record['billable_status_id'],$item['id'])==0)
              {
                 echo " SELECTED ";
              }
              ?>
            >
               <?php echo $item['value_select']?>
            </option>
            <?php
         }?>
         </select>
      </td>
   </tr>
   <tr>
      <td colspan="4">
         <?php
         if(isset($time_record['id_object']))
         {
         ?>
            <input type="hidden" name="time_record_id" value="<?php echo $time_record['id_object']?>"/>
         <?php
         }
         if( ! isset($time_record) )
         {?>
            <input type="hidden" name="add" value="ok"/>
         <?php
         }
         if(isset($parent_object_id))
         {
         ?>
            <input type="hidden" name="parent_object_id" value="<?php echo $parent_object_id?>"/>
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