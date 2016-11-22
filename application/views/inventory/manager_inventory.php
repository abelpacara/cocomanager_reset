<script>   
$(document).ready( function(){  
    $(".delete_inventory").hover(     
     function () {      
      var id_deleter = $(this).attr('id');      
      $('#row_inventory_'+id_deleter).addClass("over_row_time");
     },
     function () {
       var id_deleter = $(this).attr('id');
       $('#row_inventory_'+id_deleter).removeClass("over_row_time");       
     }
    );
});
</script>

<?php
echo validation_errors();
?>


<h1><?php echo $view_labels['title_form']?></h1>

<div id="content_box">

 <div class="bar_table_columns">
   <div class="name_column_table enqueue_by_right" style="width:35px; min-height: 20px"></div>         
   <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_name']?></div>   
   <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_model']?></div>
   <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_brand']?></div>
   <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_code']?></div>
   <div class="name_column_table enqueue_by_right" style="width:120px;"><?php echo $view_labels['column_location']?></div>
   <div class="name_column_table enqueue_by_right" style="width:70px;"><?php echo $view_labels['column_quantity']?></div>
   <div class="name_column_table enqueue_by_right" style="width:100px;"><?php echo $view_labels['column_purchase_price']?></div>
   <div class="name_column_table enqueue_by_right" style="width:100px;"><?php echo $view_labels['column_edit']?></div>
   <div class="name_column_table enqueue_by_right" style="width:100px;"><?php echo $view_labels['column_delete']?></div>

 </div>
 <div class="content_table clear_both">
 <?php
 for($i=0; $i<count($list_inventory);$i++)
 {?>
   <div  id="row_inventory_<?php echo $i?>" class="row_table clear_both">
      <div class="enqueue_by_right" style="width:35px; min-height:10px;">
         <img src="<?php echo base_url($uri_images_inventory).'/'.$list_inventory[$i]['id'].'_thumb_small.jpg';?>"
              title="<?php echo $list_inventory[$i]['description']?>"
              />
      </div>
      <div class="enqueue_by_right" 
           style="width:70px;"
           title="<?php echo $list_inventory[$i]['description']?>"
           >
         <?php echo $list_inventory[$i]['name']?>
      </div>
      
      <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['model']?></div>
      <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['mark']?></div>
      <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['code']?></div>
      <div class="enqueue_by_right" style="width:120px;"><?php echo $list_inventory[$i]['current_location']?></div>
      <div class="enqueue_by_right" style="width:70px;"><?php echo $list_inventory[$i]['quantity']?></div>
      <div class="enqueue_by_right" style="width:100px;"><?php echo $list_inventory[$i]['buy_price']?></div>            
      
      <div class="enqueue_by_right" style="width:100px;">
         <?php      
         $url_edit_redirect ="&redirect=".urlencode('inventory/manager_inventory');      
         ?>
         <a href="<?php echo site_url('inventory/edit_inventory?inventory_id='.$list_inventory[$i]['id'].$url_edit_redirect);?>">
            <?php echo $view_labels['edit']?>
         </a>   
      </div>
      
      
      
      
      
      <div class="enqueue_by_right" style="width:100px;">
         <?php      
         $url_edit_redirect ="&redirect=".urlencode('inventory/manager_inventory');
         ?>
         
         <a class="delete_inventory"
            onclick=" var is_deleted = confirm ('<?php echo $view_labels['msg_delete_these_insurance']?>'); if(!is_deleted){return false;}"
            id="<?php echo $i?>"
            href="<?php echo site_url('inventory/delete_inventory?inventory_id='.$list_inventory[$i]['id'].$url_edit_redirect);?>">
            <div class="icon_delete_time"></div>
         </a>
         
      </div>
   </div>
 <?php
 }?>
 </div>
</div>