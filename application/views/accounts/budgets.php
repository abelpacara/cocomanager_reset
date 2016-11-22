<div><h1><?php echo $view_labels['form_title']?></h1></div>
<?php
echo form_open($this->uri->uri_string(),array("method"=>"post"));
?>
<table>
   <tr>
      <td><?php echo $view_labels['select_budget_range']?>:</td>
      <td>
         <select onchange="location = '<?php echo current_url(); ?>?'+ this.options[this.selectedIndex].value;">

            <?php
            for($i=0;$i<count($list_budget_ranges);$i++)
            {?>
               <option value="id_budget_range=<?php echo $list_budget_ranges[$i]['id_budget_range']?>"
                       <?php
                       if(strcasecmp( $list_budget_ranges[$i]['id_budget_range'], $current_budget_range_id)==0)
                       {
                          echo " SELECTED ";
                       }
                       ?>
                       >
                  <?php echo " (".$list_budget_ranges[$i]['title'].") "?>
                  <?php echo $list_budget_ranges[$i]['date_from']?> __ 
                  <?php echo $list_budget_ranges[$i]['date_to']?>

               </option>
            <?php
            }
            ?>
         </select>
      </td>
   </tr>
</table>
   <script>   
   $(document).ready(function(){
      $('.mount_budget').keypress(function(evt) {
         
         if($(this).is(":focus"))
         {
            if(evt.which)
            { charCode = evt.which; }
            else
            { charCode = event.keyCode; }         
            if(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
            {
               return false;
            }
            else
            { return true; }
         }
         
      });
      
   });
   </script>
<input type="hidden" name="id_budget_range" value="<?php echo $current_budget_range_id?>"/>
<table class ="report_table">
   <tr>
      <th><?php echo $view_labels['column_account']?></th>
      <th><?php echo $view_labels['column_in']?></th>
      <th><?php echo $view_labels['column_out']?></th>
      <th><?php echo $view_labels['column_result']?></th>
   </tr>
   <?php
   $in_summation=0;
   $out_summation=0;
   $result_summation=0;
   
   for($i=0;$i<count($list_accounts_budget_items);$i++)
   {?>
      <tr class="tr_row_table">
         <td><?php echo $list_accounts_budget_items[$i]['name']?>
            <input type="hidden" name="id_account_category" value="<?php $list_accounts_budget_items[$i]['id_account_category']?>"/>
         </td>         
         <?php
         $budget_in="";
         if(isset($list_accounts_budget_items[$i]['budget_in']) AND $list_accounts_budget_items[$i]['budget_in']>0)
         {            
            $budget_in = $list_accounts_budget_items[$i]['budget_in'];
            $in_summation += $budget_in;
         }
         $budget_out="";
         if(isset($list_accounts_budget_items[$i]['budget_out']) AND $list_accounts_budget_items[$i]['budget_out']>0)
         {
            $budget_out =$list_accounts_budget_items[$i]['budget_out'];
            $out_summation += $budget_out;
         }
         ?>
         <td><input name="budget_in_of_id_account_category_<?php echo $list_accounts_budget_items[$i]['id_account_category']?>" 
                    type="text"
                    class="mount_budget"
                    value="<?php echo $budget_in?>"
                    />
         </td>
         <td><input name="budget_out_of_id_account_category_<?php echo $list_accounts_budget_items[$i]['id_account_category']?>" 
                    type="text"
                    class="mount_budget"
                    value="<?php echo $budget_out?>"
                    />
         </td>
         <td class="font_bold">
            <?php 
            if(strcasecmp($budget_in,"")!=0 OR strcasecmp($budget_out,"")!=0)
            {
               $result = $budget_in-$budget_out;
               echo $result;
               $result_summation += $result;
            }
            ?>
         </td>
      </tr>
   <?php
   }?>
      <tr class="font_bold">
      <td></td>
      <td class="td_separator-column-table"><?php echo $in_summation?></td>
      <td class="td_separator-column-table"><?php echo $out_summation?></td>
      <td class="td_separator-column-table"><?php echo $result_summation?></td>
   </tr>
</table>

<table>
   <tr>
      <td>
      
         <input type="submit" 
                value="<?php echo $view_labels['btn_save']?>" 
                <?php
               if(count($list_budget_ranges)<=0)
               {
                  echo " disabled='yes' ";
               }
               ?>
                name="save_budget"/>
         
      </td>
   </tr>
</table>
</form>