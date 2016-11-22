<?php
echo validation_errors();
echo $my_messages;
?>

    <h1><?php echo $view_labels['title_form']?></h1>   
    <div id="content_box">
      <form action="<?php echo site_url("inventory/edit_inventory")?>" method="POST" enctype="multipart/form-data">
         
         <input type="hidden" value="<?php echo $inventory['id']?>" name="inventory_id"/>
         <input type="hidden" value="<?php echo $redirect?>" name="redirect"/>
         
         <div class="width_label_add_inventory enqueue_by_right"><?php echo $view_labels['category']?></div><div class="enqueue_by_right">
            <select name="category_inventory_id">
               <?php
               for($i=0;$i<count($list_category_inventory);$i++)
               {?>
                  <option value="<?php echo $list_category_inventory[$i]['id']?>"
                  <?php
                  if(strcasecmp($inventory['category_inventory_id'], $list_category_inventory[$i]['id'])==0)
                  {
                     echo(" SELECTED ");
                  }
                  ?>
                  >
                     <?php echo $list_category_inventory[$i]['name']?>
                  </option>
               <?php
               }
               ?>
            </select>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['name']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="name"
                   value="<?php echo($inventory['name']);?>"/>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['model']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="model"
                   value="<?php echo($inventory['model']);?>"/>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['brand']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="mark"
                   value="<?php echo($inventory['mark']);?>"/>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['code']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="code"
                   value="<?php echo($inventory['code']);?>"/>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['location']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="current_location"
                   value="<?php echo($inventory['current_location']);?>"/>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['quantity']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="quantity"
                   value="<?php echo($inventory['quantity']);?>"/>
         </div>
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['purchase_price']?>:</div>
         <div class="enqueue_by_right">
            <input type="text" class="width_field_add_inventory" name="buy_price"
                   value="<?php echo($inventory['buy_price']);?>"/>
         </div>
         
         <?php
         $uri_picture = ".".$uri_images_inventory.'/'.$inventory['id'].'_thumb_medium.jpg';
         $url_picture = base_url($uri_images_inventory).'/'.$inventory['id'].'_thumb_medium.jpg';
         
         if(file_exists($uri_picture))
         {
            ?>
            <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['current_picture']?>:</div>
            <div class="enqueue_by_right">
               <img src="<?php echo $url_picture; ?>" />
            </div>
            <?php
         }?>
         
         
         <div class="width_label_add_inventory enqueue_by_right clear_both"><?php echo $view_labels['picture']?>:</div>
         <div class="enqueue_by_right">
            <input type="file" class="width_field_add_inventory" name="picture"
                   />
         </div>
         
         <div class="clear_both"><?php echo $view_labels['description']?>:</div>
         <div>
            <textarea name="description" style=" height: 98px; width: 332px;"></textarea>
         </div>
         
         <input type="submit" name="save_changes" value="Guardar"/>
      </form>    
    </div>