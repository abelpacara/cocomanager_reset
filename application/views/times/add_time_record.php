
  <script>
	$(function() {
		$( "#date_in" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
	});
   
   $(function() {
		$( "#date_out").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
	});
   /*******************************************************/
   $(document).ready( function(){  
       $(".delete_time").hover(     
        function () {      
         var id_deleter = $(this).attr('id');      
         $('#row_id_'+id_deleter).addClass("over_row_time");
        },
        function () {
          var id_deleter = $(this).attr('id');
          $('#row_id_'+id_deleter).removeClass("over_row_time");       
        }
       );
   }); 
   
/***************************************************/
$(function(){
   $('.style_mask').customStyle();
});
<?php
$styles_mask="class='style_mask' style='width:70px;'";
?>
   </script>
<div id="content_box">
       <form action="<?php echo site_url("times/add_time_record")?>" method="POST">       
       <h1><?php echo $view_labels['title_form']?></h1>
       <?php
       echo validation_errors();
       echo $my_messages;
       ?>
       
       <table id="hor-minimalist-b" summary="Employee Pay Sheet">
         <thead>
            <tr>
               <td colspan="3"><h2><?php echo $week_interval_literal;?></h2></td>
               <input type="hidden" name="week_number" value="<?php echo $week_number?>"/>
               <input type="hidden" name="year_of_week" value="<?php echo $year_of_week?>"/>
            </tr>
         </thead>
         <thead>
         <tr>
         <th scope="col"><?php echo $view_labels['column_in']?></th>         
         <th scope="col"><?php echo $view_labels['column_out']?></th> 
         <th scope="col"></th>         
         </tr>
         </thead>
         <tbody>
         <?php
         $url_redirect=urlencode('times/add_time_record/?week='.$week_number.'&year='.$year_of_week);

         for($i=0; $i<count($list_times);$i++)
         {?>
          <tr id="row_id_<?php echo $i?>">
            <td class="style_no_wrap"><?php echo $list_times[$i]['time_in_literal'];?></td>
            
            <td class="style_no_wrap"><?php echo $list_times[$i]['time_out_literal'];?></td>
            <td>
               
               <a class="delete_time"
                  onclick=" var is_deleted = confirm ('<?php echo $view_labels['msg_delete_these_insurance']?>'); if(!is_deleted){return false;}"
                  id="<?php echo $i?>"
                  href="<?php echo site_url('times/delete_time/?id_time='.$list_times[$i]['id_time']."&redirect=".$url_redirect);?>">
                  <div class="icon_delete_time"></div>
               </a>
               
            </td>
          </tr>
         <?php
         }?>
       </table>
       
         <h1><?php echo $view_labels['add_record_in_and_out']?></h1>
        
         <?php echo $view_labels['column_in']?>
         <p>
         <?php echo $view_labels['date']?> : <input name = "date_in" id = "date_in" type="text" value="<?php echo $date_in?>" class="arrow_drowpdown"/>
         <?php echo $view_labels['hour']?> : 
         
          <?php
            $array_hours=array();
            for($i=0; $i<=23; $i++)
            {
               $array_hours[$i]=$i;
            }
            
            $array_minutes=array();
            $array_seconds=array();
            
            for($i=0; $i<=59; $i++)
            {
               $array_minutes[$i] = $i;
               $array_seconds[$i] = $i;
            }
            
            
            echo form_dropdown('hour_in', $array_hours, $hour_in, $styles_mask);
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('minute_in', $array_minutes, $minute_in, $styles_mask);       
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('second_in', $array_seconds, $second_in, $styles_mask);       
            ?>
         </p>
         <br>
         <?php echo $view_labels['column_out']?>
         <p>
         <?php echo $view_labels['date']?> : <input name = "date_out" id = "date_out" type="text" value="<?php echo $date_out?>" class="arrow_drowpdown"/>
         <?php echo $view_labels['hour']?> : 
         
            <?php            
            echo form_dropdown('hour_out', $array_hours, $hour_out, $styles_mask);
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('minute_out', $array_minutes, $minute_out, $styles_mask);       
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('second_out', $array_seconds, $second_out, $styles_mask);       
            ?>
                
         </p>
         
         <div class="container_button_blue">
            <div class="enqueue_by_right">
               <input type="submit" name="add_time_record" value="<?php echo $view_labels['btn_save']?>"/>
            </div>

            <div class="button_blue enqueue_by_right">
               <a href="<?php echo base_url(); ?>/times/"><?php echo $view_labels['btn_cancel']?></a>
            </div>
         </div>
         
      </form>
    </div>