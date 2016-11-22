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
      
       <div>
          <div class="bar_table_columns">
            <div class="name_column_table clear_both enqueue_by_right" style="width:170px;"><?php echo $view_labels['column_name']?></div>                  
            <div class="name_column_table enqueue_by_right" style="width:270px;"><?php echo $view_labels['column_description']?></div>
          </div>
          <div class="content_table clear_both">
          <?php
          for($i=0; $i<count($list_category_inventory);$i++)
          {?>
             <div class="clear_both"></div> 
            <div class="row_table">
               <div class="enqueue_by_right" style="width:170px;"><?php echo $list_category_inventory[$i]['name']?></div>            
               <div class="enqueue_by_right" style="width:270px;"><?php echo $list_category_inventory[$i]['description']?></div>            
            </div>
          <?php
          }?>
          </div>
          
       </div>
       
       
    </div>