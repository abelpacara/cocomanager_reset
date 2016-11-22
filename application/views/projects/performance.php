<style>
   .performance_total_hrs{
      padding-bottom: 15px;
      vertical-align: top;
      text-align: right;
      padding-right: 4px;
      font-weight:bold; 
      white-space: nowrap;
   }
   .performance_border{
       border: 1px solid #DCDCDC;
       display: inline-block;
       margin: 5px;
       padding: 5px;
   }
   .performance_row{
      border-bottom: 1px solid #DEDEDE;
   }
   .performance_row td{
      padding: 4px;
   }
   .performance_task{
      font-weight:bold;
   }
   .performance_title{
       font-size: 16px;
       padding: 5px;
   }
   .performance_nowrap{
      white-space: nowrap;
   }
   .effective_hrs{
      padding-left: 20px !important;
      padding-right: 20px !important;
   }
   .performance_task_hrs{
      background-color: #F1F1F1;
   }
   .performance_project_hrs{
      background-color: #DEDEDE;
   }
   .performance_project_hrs td{
      padding-top: 5px;
   }
</style>

<fieldset class="performance_border">
   <legend class="performance_title"><?php echo $view_labels['performance']; ?></legend>
<table>   
   <?php
   $last_task = "";
   $project_hrs = 0;
   for($i=0; $i<count($list_tasks_users);$i++)
   {
   ?>
      <tr class="hr_top performance_row tr_row_table">         
         <td class="performance_task">          
         <?php 
         if(  strcasecmp( $last_task, $list_tasks_users[$i]['description']) !=0 )
         {
            echo nl2br(decode_chars_special($list_tasks_users[$i]['description']));
         }
         ?>
         </td>         
         <td class="performance_nowrap"><?php echo $view_labels['by'];?> 
               <a href="<?php echo site_url("auth/public_profile?user_id=".$list_tasks_users[$i]['worker_user_id'])?>">
                  <?php echo $list_tasks_users[$i]['user_name']." ".$list_tasks_users[$i]['user_last_name']; ?>
               </a>
         </td>         
         <td class="performance_nowrap effective_hrs"><?php echo number_format($list_tasks_users[$i]['effective_hours'], 2, '.', '')." h"; ?></td>                  
         
      </tr>      
      <?php
      $project_hrs += $list_tasks_users[$i]['effective_hours'];
      
      if( isset( $list_tasks_users[$i]['total_hours']))
      {?>
         <tr class="performance_task_hrs">            
            <td colspan="2" class="performance_total_hrs"><?php echo $view_labels['total_task_hours'];?>:</td>
            <td class="performance_total_hrs effective_hrs">
               
            <?php 
               echo number_format($list_tasks_users[$i]['total_hours'], 2, '.', '')." h";               
               
            ?>
            </td>
         </tr>
         <?php
      }
      $last_task = $list_tasks_users[$i]['description'];
   }?>
         <tr class="performance_project_hrs">
            <td colspan="2" class="performance_total_hrs"><?php echo $view_labels['total_project_hours'];?>: </td>
            <td class="performance_total_hrs effective_hrs"><?php echo number_format($project_hrs, 2, '.', '')." h"?></td>
         </tr>
</table>
</fieldset>