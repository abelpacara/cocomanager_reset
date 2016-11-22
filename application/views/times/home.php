<script>   
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
$('select.style_mask').customStyle();
});

 </script> 
    <div id="content_box" class="clear_both">
       <h1><?php echo $view_labels['title_form']?></h1>     
       <?php
       if(isset($_GET['week']))
       {
         $current_week= $_GET['week'];
         $current_year= $_GET['year'];
       }
       else
       {
          $current_week = $list_past_weeks[0]['week_number'];
          $current_year = $list_past_weeks[0]['year'];
       }
       
       
       $last_data['$list_last_present_users'] = $list_last_present_users;
       $last_data['current_week'] = $current_week;
       $this->load->view('times/home_last_present_users', $last_data);
       ?>
       
       <?php
       $array_messages[] = $my_messages;
       
       if(count($list_weeks_with_error)>0)
       {?>
           <?php
           ob_start();
           
           echo $view_labels['message_error'];
           ?>           
           <?php           
           for($i=0; $i< count($list_weeks_with_error);$i++)
           {?>
           <a href="<?php echo site_url("times/home?week=".$list_weeks_with_error[$i]['week_of_year']."&
                                                     year=".$list_weeks_with_error[$i]['year']); ?>">
               <?php echo "(".$list_weeks_with_error[$i]['week_of_year'].")&nbsp;-&nbsp;".$list_weeks_with_error[$i]['year']?>
           </a>&nbsp;&nbsp;&nbsp;&nbsp;
           <?php
           }
           $error_msg_weeks = ob_get_clean();
           $array_messages[] = $error_msg_weeks;
       }
       
       display_messages_alert($array_messages);
       ?>
          
       <div class="selectable_week enqueue_by_right">
        <select name="" 
                class="select_special style_mask"
                style="width: 780px;"
                ONCHANGE="location = '<?php echo current_url(); ?>?'+ this.options[this.selectedIndex].value;">
       <?php
       
       $begin_interval_selected="";
       $end_interval_selected="";
       
       
       
       foreach($list_past_weeks as $key => $value)
       { 
         $selected = ""; 
         $week_year_url = "week=".$value['week_number']."&year=".$value['year'];
         
         if($_GET['week'] == $value['week_number'] and $_GET['year'] == $value['year'])
         {
           $selected = "SELECTED";
           $begin_interval_selected = $value['begin'];
           $end_interval_selected = $value['end'];
           
         };

         echo("<option $selected VALUE='".$week_year_url."'>".$value['interval']."</option>");
       }
       if(strcasecmp($begin_interval_selected,"")==0)
       {
          $begin_interval_selected = $list_past_weeks[0]['begin'];
          $end_interval_selected = $list_past_weeks[0]['end'];
       }
       ?>
        </select>
    </div>
           
           
     <?php      
     if(in_array_column("times/add_time_record", $list_privileges, $name_column="uri"))
     {?>
         <div class="button_blue enqueue_by_right button_blue_not_important">
            <a href="<?php echo site_url("times/add_time_record?week=".$week_number."&year=".$year);?>"><?php echo $view_labels['add_hrs']?></a>
         </div>
      <?php
     }
     ?>
           
    <div class="enqueue_by_right">
      <?php
      //echo get_legend_times();
      ?>
    </div>
<p class="clear_both"/>

<table id="hor-minimalist-b" summary="Employee Pay Sheet">
<thead>
<tr>
<th scope="col"><?php echo $view_labels['column_in']?></th>
<th scope="col"><?php echo $view_labels['column_out']?></th>
<th scope="col"  style="white-space: nowrap; text-align: left"><?php echo $view_labels['hours_by_week']?></th>
<th scope="col"></th>
</tr>
</thead>
<tbody>
<style>
   .row_time{
      border: none !important;
   }
   .cell_time{
       padding-top: 2px !important; 
       padding-bottom: 2px !important; 
       padding-left: 0px !important; 
       padding-right: 0px !important; 
       border: none !important;
   }
   .cell_content{
      line-height: 40px;
      padding-left: 7px;
   }
   .cell_sumary{
      border: none !important;
      text-align: left; 
      padding-left: 50px !important;
   }
</style>
 <?php
    if(count($list_times)>0)
    {
       $i=0;
      
       
       
       
       list($last_date, ) = explode(" ", $list_times[0]['time_in']);

       $array_styles = array("style='background:#F8F8F8;'",
                             "style='background:#D0D0D0;'",
                             "style='background:#E8E8E8;'"
          );
       $last_style_date = $array_styles[0];

       $styles_index = 0;

       foreach($list_times as $key => $value)
       {
          if(strcasecmp($value['status_in'],"Observed")==0 OR
             strcasecmp($value['status_in_temp'],"error_cross")==0 OR
             strcasecmp($value['status_in_temp'],"error_cross_week")==0)
          {
             if(strcasecmp($value['status_in_temp'],"error_cross_week")==0)
             {
               $class_in = "function_cross_week";
             }
             else
             {
               $class_in = "time_observed_link";
             }
          }
          else if(strcasecmp($value['status_in'],"Corrected")==0)
          {
             $class_in = "time_corrected_link";
          }
          else
          {
             $class_in = "function_link";
          }

          if(strcasecmp($value['status_out'],"Observed")==0 OR
             strcasecmp($value['status_out_temp'],"error_cross")==0 OR
             strcasecmp($value['status_out_temp'],"error_cross_week")==0)
          {
             if(strcasecmp($value['status_out_temp'],"error_cross_week")==0)
             {
               $class_out = "function_cross_week";
             }
             else
             {
               $class_out = "time_observed_link";
             }
          }
          else if(strcasecmp($value['status_out'],"Corrected")==0)
          {
             $class_out = "time_corrected_link";
          }
          else
          {
             $class_out = "function_link";
          }


         if($value['status_in_temp'] == "pending")
         {
            $edit_link = "#";
         }
         else
         {
            $edit_link = "edit_time/?id_time=".$value['id_time']."&begin_week=".$begin_interval_selected."&end_week=".$end_interval_selected."&redirect=".urlencode("times/home/?week=".$current_week."&year=".$current_year);
         }
         ?>
         <tr id="row_id_<?php echo $i?>" class="row_time">
            <?php
            list($last_date_i, ) = explode(" ", $list_times[$i]['time_in']);         

            if( isset($last_date) AND isset($last_date_i) )
            {

               if(strcasecmp( $last_date, $last_date_i)!=0)
               {
                  $styles_index = ($styles_index + 1) % count($array_styles);          
               }
            }
            $last_date  = $last_date_i;
            ?>
            <td class="style_no_wrap cell_time">
               <div class="cell_content" <?php echo $array_styles[$styles_index]?>>
               <?php
               if(strcasecmp($value['status_in_temp'],'error_cross_week')!=0)
               {?>
                  <a href="<?php echo site_url('times/'.$edit_link.'&record=in');?>" 
                     class="<?php echo($class_in);?>"><?php echo($value['time_in_literal']);?>
                  </a>
               <?php
               }
               else if($i==0)
               {?>
                  <span class="<?php echo($class_in);?>"
                        title="<?php echo($value['time_in_literal']);?>"
                        >
                        <?php 
                        echo "<< ".call_user_func('get_date_literal_'.$language, $begin_interval_selected).' 00:00:00';                        
                        ?>
                  </span>
               <?php
               }
               else//**********************************???????????????????
               {?>
                  <a href="<?php echo site_url('times/'.$edit_link.'&record=in');?>" 
                     class="<?php echo($class_in);?>"><?php echo($value['time_in_literal']);?>
                  </a>
               <?php
               }
               ?>
               </div>
            </td>
            <?php

            list($last_date_i, ) = explode(" ", $list_times[$i]['time_out']);                  
            if( isset($last_date) AND isset($last_date_i) ) 
            {
               if(strcasecmp( $last_date, $last_date_i)!=0)
               {
                  $styles_index = ($styles_index+1) % count($array_styles);               
               }
            }
            $last_date  = $last_date_i;
            ?>         
            <td class="style_no_wrap cell_time">
               <div class="cell_content"  <?php echo $array_styles[$styles_index]?>>
               <?php
               if(strcasecmp($value['status_out_temp'],'error_cross_week')!=0)
               {?>
                  <a href="<?php echo site_url('times/'.$edit_link.'&record=out');?>" 
                     class="<?php echo($class_out);?>"><?php echo($value['time_out_literal']);?>
                  </a>
               <?php
               }
               else if($i == (count($list_times)-1))
               {?>
                  <span class="<?php echo($class_out);?>"
                        title="<?php echo($value['time_out_literal']);?>">
                        <?php 
                        echo call_user_func('get_date_literal_'.$language, $end_interval_selected).' 23:59:59 >>';
                        ?>
                  </span>
               <?php
               }
               else//**********************************???????????????????
               {?>
                  <a href="<?php echo site_url('times/'.$edit_link.'&record=out');?>" 
                     class="<?php echo($class_in);?>"><?php echo($value['time_in_literal']);?>
                  </a>               
               <?php
               }
               ?>
               </div>
            </td>
            <td class="cell_sumary"><?php echo(number_format($value['sub_total_temp'], 2, '.', ''));?></td>
            <td class="cell_sumary">
               <a class="delete_time"
                  id="<?php echo $i?>"
                  onclick=" var is_deleted = confirm ('<?php echo $view_labels['msg_delete_these_insurance']?>'); if(!is_deleted){return false;}"
                  href="<?php echo site_url("times/delete_time/?id_time=".$value['id_time']."&redirect=".urlencode("times/home/?week=".$current_week."&year=".$current_year));?>">
                  <div class="icon_delete_time"></div>
               </a>
            </td>         
         </tr>
         <?php
         $i++;
      }
   }
   ?>


<tfoot>
<tr>
<td><div>&nbsp;</div></td>
<td><div><?php echo $view_labels['total_hrs_week']?></div></td>
<td class="cell_sumary"><div><?php echo(number_format($total_hours_other, 2, '.', ''));?></div></td>
<td><div>&nbsp;</div></td>

</tr>
</tfoot>
</tbody>
</table>
   <div class="container_button_blue">
     <?php
     if($is_current_week)
     {
        $style_button = "";
       if($is_in)
       {
         $style_button = " button_red_input ";         
         $button_message = $view_labels['record_in']."  >>";
       }
       else
       {
         $style_button = " button_blue ";          
         $button_message = "<<  ".$view_labels['record_out'];
       }
       ?>
         <div class="<?php echo $style_button?>"><a href="<?php echo site_url('times/add_time')?>"><?php echo($button_message);?></a></div>
     <?php
     }
     /*else
     {*/
        
     /*}*/
     ?>
   </div>

 </div>
 