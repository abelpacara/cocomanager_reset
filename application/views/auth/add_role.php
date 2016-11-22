<?php

######################################
$name = array(
	'name'	=> 'name',
	'id'	=> 'name',
	'value' => set_value('name'),
	'maxlength'	=>50,
	'size'	=> 30,
);

######################################
$role_type = array(
	'name'=>'role_type',
	'id'	=> 'role_type',
	'value' => set_value('role_type'),
	'maxlength'	=>50,
	'size'	=> 30,
);
?>
<script>   
$(document).ready( function(){  
    $(".delete_role").hover(     
     function () {      
      var id_deleter = $(this).attr('id');      
      $('#row_role_'+id_deleter).addClass("over_row_time");
     },
     function () {
       var id_deleter = $(this).attr('id');
       $('#row_role_'+id_deleter).removeClass("over_row_time");       
     }
    );
});
</script>

<p/>
<div>
   <h1><?php echo $view_labels['title_form']?></h1>
   <?php echo form_open($this->uri->uri_string()); ?>
      <table>         
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['column_name'].':', $name['id']); ?></td>
            <td><?php echo form_input($name); ?></td>
            <td class="error_form_field"><?php echo form_error($name['name']); ?></td>
         </tr>
         <tr>
            <td><?php 
            //####################################################################
            echo form_label($view_labels['column_type'].':', $role_type['id']); ?></td>
            <td><?php echo form_input($role_type); ?></td>
            <td class="error_form_field"><?php echo form_error($role_type['name']); ?></td>
         </tr>
         
      </table>
   <?php echo form_submit('register', $view_labels['btn_register']); ?>
   <?php echo form_close(); ?>
   <div>
      <div class="bar_table_columns">
         <div class="enqueue_by_right" style="width:300px"><?php echo $view_labels['column_name']?></div>
         <div class="enqueue_by_right" style="width:200px"><?php echo $view_labels['column_type']?></div>
         <div class="enqueue_by_right"  style="width:50px"></div>
      </div>   
         <?php
         $redirect_return = urlencode("auth/add_role");


         for($i=0;$i<count($list_roles);$i++)
         {?>
            <div  id="row_role_<?php echo $i?>" class="clear_both row_table" style="height: 20px;">
               <div class="enqueue_by_right height_cell" style="width:300px;"><?php echo $list_roles[$i]['name'];?></div>
               <div class="enqueue_by_right height_cell" style="width:200px;"><?php echo $list_roles[$i]['role_type'];?></div>
               <div class="enqueue_by_right height_cell" style="width:50px;">               
                  <a class="delete_role"
                     id="<?php echo $i?>"
                     href="<?php echo site_url("auth/delete_role/?role_id=".$list_roles[$i]['id']."&redirect=".$redirect_return);?>">
                     <div class="icon_delete_time"></div>
                  </a>               
               </div>
            </div>
         <?php
         }?>
   </div>
</div>
