<?php
echo "<br>".$my_messages;

######################################
$form_name = array(
	'name'	=> 'company_name',
	'id'	=> 'company_name',
	'value' => set_value('company_name', $company['name']),
	'maxlength'	=>50,
	'size'	=> 30,
);

######################################
$form_description = array(
	'name'=>'description',
	'id'	=> 'description',
	'value' => set_value('description', $company['description']),
	'rows'=>5,
	'cols'=>29,
);
?>
<?php
echo validation_errors();

?>
<p/>
<div>
   <h1><?php echo $view_labels['title_form']?></h1>
   
   <?php //echo form_open($this->uri->uri_string()); ?>
   <?php echo form_open_multipart($this->uri->uri_string()); ?>
   <table>
         <tr>
            <td><?php echo form_label($view_labels['name'].':', $form_name['id']); ?></td>
            <td><?php echo form_input($form_name); ?></td>
            <td style="color: red;"><?php echo form_error($form_name['name']); ?>
            <?php echo isset($errors[$form_name['name']])?$errors[$form_name['name']]:''; ?></td>
         </tr>
         <tr>
            <td><?php echo form_label($view_labels['description'].':', $form_description['id']); ?></td>
            <td><?php echo form_textarea($form_description); ?></td>
            <td style="color: red;"><?php echo form_error($form_description['name']); ?>
            <?php echo isset($errors[$form_description['name']])?$errors[$form_description['name']]:''; ?></td>
         </tr>
         
         <?php
         $uri_picture = ".".$uri_images_companies.'/'.$company['id'].'_main-logo.jpg';
         $url_picture = base_url($uri_images_companies).'/'.$company['id'].'_main-logo.jpg';
         
         if(file_exists($uri_picture))
         {
            ?>
            <tr>
               <td>
                  <?php echo $view_labels['current_logo']?>:
               </td>
               <td>
                  <img src="<?php echo $url_picture;?>"/>
               </td>
            </tr>
            <?php
         }?>
         
         
         <tr>
            <td>
               <?php echo $view_labels['logo']?> [70x70 (100KB)]:
            </td>
            <td>
               <input type="file" name="main-logo" size="20"/>
            </td>
         </tr>
   </table>
   <?php echo form_submit('save', 'Save Changes'); ?>
   <?php echo form_close(); ?>
</div>
