<?php
echo validation_errors();
echo $my_messages;
?>

<script>
	$(function() {
		$( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
    <h1><?php echo $view_labels['title_form']?></h1>   
    <div id="content_box">
      <form action="<?php echo site_url("inventory/add_inventory")?>" method="POST" enctype="multipart/form-data">
         
         <div class="width_label_add_inventory enqueue_by_right"><?php echo $view_labels['category']?></div><div class="enqueue_by_right">
            <select name="category_inventory_id">
               <?php
               for($i=0;$i<count($list_category_inventory);$i++)
               {?>
                  <option value="<?php echo $list_category_inventory[$i]['id']?>"><?php echo $list_category_inventory[$i]['name']?></option>
               <?php
               }
               ?>
            </select>
         </div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['name']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="name"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['model']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="model"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['brand']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="mark"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['code']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="code"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['location']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="current_location"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['quantity']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="quantity"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['purchase_price']?>:</div><div class="enqueue_by_right"><input type="text" class="width_field_add_inventory" name="buy_price"/></div>
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['photography']?>:</div><div class="enqueue_by_right"><input type="file" class="width_field_add_inventory" name="picture"/></div>
         
         <div class="clear_both"><?php echo $view_labels['description']?>:</div>
         <div>
            <textarea name="description" style=" height: 98px; width: 332px;"></textarea>
         </div>
         
         <input type="submit" name="add_category_inventory" value="<?php echo $view_labels['btn_save']?>"/>
      </form>    
    </div>