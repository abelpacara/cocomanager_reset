<script>   
$(document).ready( function(){  
   $(function() {
      $("#date_in" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
   });

   $(function() {
      $("#date_out").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
   });
   
   
    $(".delete_time").hover(     
     function () {      
      var id_deleter = $(this).attr('id');
      //$('#row_id_'+id_deleter).removeClass("row_week");       
      
      $('#row_id_'+id_deleter).addClass("over_row_time");
      
     },
     function () {
       var id_deleter = $(this).attr('id');
       $('#row_id_'+id_deleter).removeClass("over_row_time");              
     }
    );
});
</script> 
      
<div id="content_box">    
     <?php
     if(count($array_messages)>0)
     {
        ?>
        <div class="alert_box">
        <?php
        foreach($array_messages AS $key=>$value)
        {
           echo $value;
        }
        ?>
        </div>
     <?php
     }?>
     
     <h1><?php echo $view_labels['title_form']?></h1>
     <span class="title_h2 enqueue_by_right"><?php echo $view_labels['select_range']?></span>
      <div> 
         <form action="<?php echo site_url("times/manager_times");?>" method="POST">
         <?php echo $view_labels['since']?>:<input name = "date_begin" id = "date_in" type="text" value="<?php echo $date_begin?>"  class="arrow_drowpdown"/>     
         <?php echo $view_labels['until']?>:<input name = "date_end" id = "date_out" type="text" value="<?php echo $date_end?>"  class="arrow_drowpdown"/>
         <input type="submit" name="search" value="<?php echo $view_labels['search']?>"/>
         </form>
      </div>
    <?php
    echo $str_output;
    ?>
 </div>
 