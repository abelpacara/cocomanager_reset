<h1><?php echo $view_labels['form_title'];?></h1>

<?php echo form_open_multipart($this->uri->uri_string()); ?>
<table>
   <tr>
		<td>
      <?php       
      echo $view_labels['name'].':'; ?>
      </td>
		<td>
         <?php
         $value_name = isset($client_company['name'])?$client_company['name']:"";
         ?>
         <input type="text" name="name" value="<?php echo $value_name?>"/>
      </td>		
	</tr>
   <tr>
      <td>
         <?php echo $view_labels['logo'].':'; ?>
      </td>
		<td>
         <?php
         $value_logo = isset($client_company['value_logo'])?$client_company['value_logo']:"";
         ?>
         <input type="file" name="logo" value="<?php echo $value_logo?>" id="logo"/>
         <?php
         if(isset($_REQUEST['new']) AND strcasecmp( $_REQUEST['new'], "")!=0)
         {?>
            <input type="hidden" name="new" value="ok"/>
         <?php
         }
         if(isset($client_company['id_client_company']) AND strcasecmp( $client_company['id_client_company'], "")!=0)
         {?>
            <input type="hidden" name="client_company_id" value="<?php echo $client_company['id_client_company'];?>"/>
         <?php
         }
         ?>
      </td>
   </tr> 
   <tr>
      <td><?php echo $view_labels['description']?>:</td>
      <td>
         <?php
         $value_description = isset($client_company['description'])?$client_company['description']:"";
         ?>
         <textarea name="description" rows="7" cols="30"><?php echo $value_description?></textarea>
      </td>
   </tr>
</table>
<?php echo form_submit('save', $view_labels['btn_save']); ?>

<?php
if($has_privilege_delete_client_company)
{
   if(isset($client_company['name']))
   {
      $url_delete = base_url("auth/delete_client_company?client_company_id=".$client_company['id_client_company']);
      ?>
      <div class="button_blue">
         <a href="<?php echo $url_delete?>">
            DELETE
         </a>
      </div>
   <?php
   }
}
?>

<?php echo form_close(); ?>
