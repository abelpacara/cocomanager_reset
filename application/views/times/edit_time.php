<script>
	$(function() {
		$( "#date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });
	});
/***************************************************/
$(function(){
   $('.style_mask').customStyle();
});
</script>
<?php
$styles_mask="class='style_mask' style='width:70px;'";
?>
<?php
echo validation_errors();
//echo $my_messages;
display_messages_alert($array_messages);
?>
    <h1><?php echo $view_labels['title_form']?></h1>
    <div id="content_box">
      <form action="<?php echo site_url("times/edit_time")?>" method="POST">

         <input type="hidden" name="begin_week" value="<?php echo $week_interval['begin']?>"/>
         <input type="hidden" name="end_week" value="<?php echo $week_interval['end']?>"/>
         
         <input type="hidden" name="redirect" value="<?php echo $redirect?>"/>
         

         <?php
         if($time['record'] == "in")
         {
             $title = $view_labels['in_edit'];
             $title_previous = $view_labels['previous_mark_out'];
             $date = $time['date_in'];
             $hour = $time['hour_in'];
             $minute = $time['minute_in'];
             $second = $time['second_in'];             
         }
         else
         {
           $title = $view_labels['out_edit'];
           $title_previous = $view_labels['previous_mark_in'];
           $date = $time['date_out'];
           $hour = $time['hour_out'];
           $minute = $time['minute_out'];           
           $second = $time['second_out'];             
         }         
         ?>
         <?php
         if(isset($previous_time_lit) AND strcasecmp($previous_time_lit,"")!=0)
         {?>
         <h2><?php echo($title_previous)?></h2>
         <table class="table_form" id="edit_time_form">
            <tr>               
               <td></td>
               <td><?php echo $previous_time_lit?></td>
            </tr>
         </table>
         <?php
         }?>
         
         <h2><?php echo($title)?></h2>

         <table class="table_form" id="edit_time_form">
         <tbody>
         <tr>
         <td class="labels">
         <?php echo $view_labels['date']?>:
         </td>
          <td>
            <input name = "date" id = "date" type="text" value="<?php echo($date);?>"  class="arrow_drowpdown"/>
          </td>
          <td>
          <?php echo $view_labels['hour']?>:
          </td>
          <td>
            <?php
            $array_hours = array();
            for($i=0; $i<=23; $i++)
            {
               $array_hours[$i]=$i;
            }
            
            $array_minutes = array();
            $array_seconds = array();
            
            for($i=0; $i<=59; $i++)
            {
               $array_minutes[$i] = $i;
               $array_seconds[$i] = $i;
            }
            
            echo form_dropdown('hour', $array_hours, $hour, $styles_mask);
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('minute', $array_minutes, $minute, $styles_mask);
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('second', $array_seconds, $second, $styles_mask);
            ?>            
         </td>
         </tr>
          <tr>
         
         <td colspan="4">
          <input name = "id_time" id = "id_time" type="hidden" value="<?php echo($time['id_time']);?>"/>
          <input name = "record" id = "record" type="hidden" value="<?php echo($time['record']);?>"/>
          <input name = "save_updates" id = "save_updates" type="hidden" value="1"/>
          
          <div class="clear_both enqueue_by_right">             
            <input class="button_submit" type="submit" value="<?php echo $view_labels['btn_save']?>"/>
          </div>         
          <div class="button_blue enqueue_by_right">
            <a href="<?php echo base_url(); ?>/times/home"><?php echo $view_labels['btn_cancel']?></a>
          </div>
          <div class="button_blue enqueue_by_right">
             <?php
             $redirect = urlencode(site_url("times/home"));
             $url_delete=base_url()."/times/edit_time?delete_id_time=".$time['id_time']."&record=".$time['record']."&redirect=".$redirect;
             ?>
             <!--<a href="<?php echo $url_delete?>">Borrar</a>-->
          </div>
          
         </td>
         <td>
            
         </td>
         <td>
        
         </td>
         </tr>
         </tbody>
         </table>

         
         <!-- <div class="button_blue"><a href="<?php echo base_url(); ?>index.php/times/add_time">EDITAR REGISTRO</a></div> -->
      </form>    
    </div>