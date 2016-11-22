<?php
echo validation_errors();
//echo $my_messages;
?>

<script>
	$(function() {
		$( "#date" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
	});
$(function(){
   $('.style_mask').customStyle();
});
</script>
<?php
$styles_mask="class='style_mask' style='width:70px;'";
$styles_mask_status="class='style_mask' style='width:150px;'";

echo $my_messages;
?>

    <h1><?php echo $view_labels['title_form']?></h1>
    <div id="content_box">
      <form action="<?php echo site_url("times/edit_time_gral")?>" method="POST">
         
         <input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
         
         <?php
         if($time['record'] == "in")
         {
             $title = $view_labels['in'];
             $date = $time['date_in'];
             $hour = $time['hour_in'];
             $minute = $time['minute_in'];
             $second = $time['second_in'];             
         }
         else
         {
           $title = $view_labels['out'];
           $date = $time['date_out'];
           $hour = $time['hour_out'];
           $minute = $time['minute_out'];           
           $second = $time['second_out'];             
         }
         ?>

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
            
            echo form_dropdown('hour', $array_hours, $hour, $styles_mask);
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('minute', $array_minutes, $minute, $styles_mask);
            echo " <span class='colon_time'>:</span> ";
            echo form_dropdown('second', $array_seconds, $second, $styles_mask);
            
            //print_r($time);
            ?>
          </td>
          <td>
             <?php echo $view_labels['status']?>:
          </td>
          <td>             
             <select name="status" <?php echo $styles_mask_status?>>
                <?php
                
                for($i=0;$i<count($list_times_status);$i++)
                {?>
                  <option value="<?php echo $list_times_status[$i]['value_select'];?>"
                          <?php
                          if($time['record'] == "in")
                          {
                             if(strcasecmp($time['status_in'], $list_times_status[$i]['value_select'])==0)
                             {
                                echo " SELECTED ";
                             }                             
                          }
                          else
                          {
                             if(strcasecmp($time['status_out'], $list_times_status[$i]['value_select'])==0)
                             {
                                echo " SELECTED ";
                             }                             
                          }
                          ?>
                          >
                        <?php echo $view_labels['times_status'][ $list_times_status[$i]['value_select'] ];?>
                     </option>
                <?php
                }
                ?>
             </select>
          </td>
          </tr>
          <tr>
         
         <td colspan="4">
             <input name = "id_time" id = "id_time" type="hidden" value="<?php echo($time['id_time']);?>"/>
             <input name = "record" id = "record" type="hidden" value="<?php echo($time['record']);?>"/>
             <input name = "save_updates" id = "save_updates" type="hidden" value="1"/>

             <div class="clear_both enqueue_by_right">             
               <input class="button_submit"type="submit" value="<?php echo $view_labels['btn_save']?>"/>
             </div>         
             <div class="button_blue enqueue_by_right">
               <a href="<?php echo base_url(); ?>/times/manager_times"><?php echo $view_labels['btn_cancel']?></a>
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