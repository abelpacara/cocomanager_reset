<?php
echo validation_errors();
?>

<script>
	$(function() {
		$( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

    <h1><?php echo $view_labels['table_title']?></h1>
    
    <div id="content_box">
      
       <div class="bar_table_columns">
         <div class="name_column_table clear_both enqueue_by_right" style="width:40px;"><?php echo $view_labels['column_name']?></div>
         <div class="name_column_table enqueue_by_right" style="width:70px; height: 5px"></div>         
         <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_model']?></div>
         <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_brand']?></div>
         <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_code']?></div>
         <div class="name_column_table enqueue_by_right" style="width:120px;"><?php echo $view_labels['column_location']?></div>
         <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_quantity']?></div>
         <div class="name_column_table enqueue_by_right" style="width:100px;"><?php echo $view_labels['column_purchase_price']?></div>
         
       </div>
       <div class="content_table clear_both">
       <?php
       for($i=0; $i<count($list_inventory);$i++)
       {?>
         <div class="row_table clear_both">
            <div class="enqueue_by_right" style="width:40px; height: 32px">
               <img src="<?php echo base_url($uri_images_inventory).'/'.$list_inventory[$i]['id'].'_thumb_small.jpg';?>"/>
            </div>
            <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['name']?></div>
            <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['model']?></div>
            <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['mark']?></div>
            <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['code']?></div>
            <div class="enqueue_by_right" style="width:120px;"><?php echo $list_inventory[$i]['current_location']?></div>
            <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['quantity']?></div>
            <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['buy_price']?></div>            
         </div>
       <?php
       }?>
       </div>
    </div>