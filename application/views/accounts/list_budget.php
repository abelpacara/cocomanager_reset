<style>
      .item_account{
         width: 270px;
      }
      .mount_price{
         width: 50px;
      }
      optgroup{
         font-size: 14px;
      }
      option{
         font-size: 13px;
      }
      
      .accounts_dropdown option{
         line-height: 20px;
      }
      .accounts_cell_row{
         padding-top: 1px !important;
         padding-bottom: 1px !important;
         padding-left: 0px !important;
         padding-right: 0px !important;
      }
      .accounts_cell_row div{
         padding-left: 10px;
      }
   </style>
   <div>

<div><h2 style="font-weight:bold;">BUDGETS REPORT</h2></div>

<div style="float:left;">Rango presupuesto:</div>
<div style="float:left;padding-bottom: 20px;">
   <select class="accounts_dropdown" style="width:220px;">
      <option value="">Selection range budget </option>      
         <?php 
            for($i=0;$i<count($list_budget_range);$i++)
            {
               echo "budget_range: ".$list_budget_range[$i]['budget_date_from']." ".$list_budget_range[$i]['budget_date_to'];
               ?>
               <option value="<?php $list_budget_range[$i]['budget_date_from']." -> ".$list_budget_range[$i]['budget_date_to'] ?>">
                  <?php echo date("Y-m-d",strtotime($list_budget_range[$i]['budget_date_from']))." -> ".date("Y-m-d",strtotime($list_budget_range[$i]['budget_date_to'])); ?>
               </option>
            <?php
            }
         ?>
      
   </select>
</div>

<table class ="report_table">
   <!--tr>
      <td>
         <div><h2 style="font-weight:bold;">BUDGETS REPORT</h2></div>
      </td>
   </tr-->
   <!--tr>
      <td>
         <div>Rango presupuesto:</div>
         <div>
            <select >
               <option>Uno </option>
               <option>Dos </option>
            </select>
         </div>
      </td>
   </tr-->
   <tr>
      <th>Category</th>
      <th>Budget In</th>
      <th>Budget Out</th>
      <th>Result</th>
   </tr>
   
   <?php   
   $sum_budget_in = 0;
   $sum_budget_out = 0;
   //for($i=0;$i<count($list_budgets);$i++)
   for($i=0;$i<count($list_budgets_account_total);$i++)
   {
         $row_odd_style = "report_cell_row_pair";      
         if(($i+1)%2==0)
         {
            $row_odd_style = "report_cell_row_odd";
         }
         ?>
            <tr class="row_item tr_row_table" id="row_id_<?php echo $i?>">
              <td class="accounts_cell_row">               
                  <div class="<?php echo $row_odd_style?>">
                     <?php echo $list_budgets_account_total[$i]["account_category"];?>
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                     <?php 
                        $sum_budget_in += $list_budgets_account_total[$i]["budget_in"];
                        echo $list_budgets_account_total[$i]["budget_in"];
                     ?>                    
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                     <?php 
                        $sum_budget_out += $list_budgets_account_total[$i]["budget_out"];
                        echo $list_budgets_account_total[$i]["budget_out"];
                     ?>                    
                  </div>
               </td>               
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                     <?php 
                        $budget_mount = "";
                        if(isset($list_budgets_account_total[$i]["budget_in"]) AND $list_budgets_account_total[$i]["budget_in"] != "")
                        {
                           $budget_mount = $list_budgets_account_total[$i]["budget_in"];
                        }                       
                        if(isset($list_budgets_account_total[$i]["budget_out"]) AND $list_budgets_account_total[$i]["budget_out"] != "")
                        {                           
                           if($budget_mount != "")
                           {
                              $budget_mount = $budget_mount - $list_budgets_account_total[$i]["budget_out"];
                           }
                           else
                           {
                              $budget_mount = $list_budgets_account_total[$i]["budget_out"];
                           }
                        }
                        echo $budget_mount.' / '.($list_budgets_account_total[$i]["result_in_out"]);
                     ?>                    
                  </div>
               </td>
            </tr>
           
            <?php
      }
      ?> 
            
      <tr>
         <td colspan="1"></td>
         <td>
            <table>
               <tr class="row_item">
                  <!--td class="font_bold"><div>Total Budget In:&nbsp;&nbsp;</div></td-->                     
                  <td class="font_bold"><div><?php echo $sum_budget_in;?></div></td>
               </tr>
            </table>
         </td>
         
         <td>
            <table>
               <tr class="row_item">
                  <!--td class="font_bold"><div>Total Budget Out:&nbsp;&nbsp;</div></td-->                     
                  <td class="font_bold"><div><?php echo $sum_budget_out;?></div></td>
               </tr>
            </table>
         </td>
      </tr>
      
      <tr>
         <td colspan="2"></td>
         <td>
            <table>
               <tr class="row_item" style="margin-right:100px;">
                  <td class="font_bold"><div>Total Budget:&nbsp;&nbsp;</div></td>                     
                  <td class="font_bold"><div><?php echo ($sum_budget_in - $sum_budget_out);?></div></td>
               </tr>
            </table>
         </td>
      </tr>
      
</table>
    
   </div>