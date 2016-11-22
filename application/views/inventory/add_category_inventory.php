<?php
echo validation_errors();
?>

<script>
	$(function() {
		$( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

    <h1><?php echo $view_labels['title_form']?></h1>
    
    <div id="content_box">
      <form action="<?php echo site_url("inventory/add_category_inventory")?>" method="POST">
         
         <div class="enqueue_by_right width_label_add_category_inventory"><?php echo $view_labels['name']?>:</div>
         <div class="enqueue_by_right"><input type="" name="name"/></div>
         <div class="clear_both width_label_add_category_inventory"><?php echo $view_labels['description']?>:</div>
         <div>
            <textarea name="description" style="width: 241px; height: 91px;"></textarea>
         </div>
         <input type="submit" name="add_category_inventory" value="<?php echo $view_labels['btn_save']?>"/>
      </form>    
    </div>