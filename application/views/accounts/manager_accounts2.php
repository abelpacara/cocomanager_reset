<table>   
   <tr>
      <td>Date</td>
      <td>Description</td>      
      <td>In</td>
      <td>Out</td>
      <td>Account</td>      
   </tr>   
   <?php
   for($i=0;$i<count($list_account_items);$i++)
   {?>
      <tr>         
         <td><?php echo $list_account_items[$i]["register_date"]."<br>";?></td>
         <td><?php echo $list_account_items[$i]["description"]."<br>";?></td>
         <td><?php echo $list_account_items[$i]["account_in"]."<br>";?></td>
         <td><?php echo $list_account_items[$i]["account_out"]."<br>";?></td>      
      </tr>
   <?php
   }
   ?>
</table>

