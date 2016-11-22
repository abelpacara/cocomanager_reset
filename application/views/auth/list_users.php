<h1><?php echo $view_labels['title_form']?></h1>

<table width="100%">
   <tr class="tr_table_columns">
      <td style="width: 35px;"></td>
      <td><?php echo $view_labels['column_user_name']?></td>
      <td><?php echo $view_labels['column_profile_name']?></td>
      <td><?php echo $view_labels['column_last_name']?></td>
      <td><?php echo $view_labels['column_email']?></td>      
   </tr>
   <?php
   for($i=0; $i<count($list_users); $i++)
   {
   ?>
      <tr id="row_user_<?php echo $i?>" class="tr_row_table">
         <td>
            <img src="<?php echo base_url($uri_images_users).'/'.$list_users[$i]['id'].'_thumb_small.jpg';?>"/>
         </td>
         <td><?php echo $list_users[$i]['username'];?></td>
         <td>
            <?php echo $list_users[$i]['name'];?>
         </td>
         <td><?php echo $list_users[$i]['last_name'];?></td>
         <td><?php echo $list_users[$i]['email'];?></td>         
      </tr>
   <?php
   }?>
</table>