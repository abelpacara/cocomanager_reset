<?php 
echo form_open($this->uri->uri_string());
?>
<h1><?php echo $view_labels['form_title']?></h1>
   <table>
      <tr>
         <?php
         $account_name="";
         if(isset($_REQUEST['account_name']) AND !isset($is_saved_account))
         {
            $account_name = $_REQUEST['account_name'];
         }
         ?>
         <td><?php echo $view_labels['name']?>:</td><td><input type="text" name="account_name" value="<?php echo $account_name?>"/></td>
      </tr>
      <tr>
         <?php
         $account_description="";
         if(isset($_REQUEST['account_description']) AND !isset($is_saved_account))
         {
            $account_description = $_REQUEST['account_description'];
         }
         ?>
         <td><?php echo $view_labels['description']?>:</td><td><textarea name="account_description" cols="31"><?php echo $account_description?></textarea></td>
      </tr>
      <tr>
         <td><?php echo $view_labels['parent_account']?>:</td>
         <?php
         $parent_id="";         
         if(isset($_REQUEST['parent_id']) AND !isset($is_saved_account))
         {
            $parent_id = $_REQUEST['parent_id']; 
         }
         ?>
         <td>
            <?php echo get_display_accouts_tree($list_accounts_tree,"parent_id",$parent_id, $view_labels['select_account_parent']."...");?>
         </td>
      </tr>
      <tr>
         <td colspan="2"><input type="submit" name="save_account" value="<?php echo $view_labels['btn_save_account']?>"></td>            
      </tr>
   </table>
</form>
<style>
   .space_char{
      color:#ffffff;
      opacity:0;
   }
</style>


<table class="report_table">   
   <tr>
      <th><?php echo $view_labels['column_name']?></th>
      <th><?php echo $view_labels['column_description']?></th>      
      <th><?php echo $view_labels['column_parent_account']?></th>
   </tr>   
   <?php
   for($i=0;$i<count($list_accounts_tree);$i++)
   {
      $row_odd_style = "report_cell_row_pair";      
      if(($i+1)%2==0)
      {
         $row_odd_style = "report_cell_row_odd";
      }
      ?>
      <tr class="tr_row_table <?php echo $row_odd_style?>">         
         <td><?php
            if(isset($list_accounts_tree[$i]["parent_id"]))
            {
               for($k=0;$k<$list_accounts_tree[$i]["level"];$k++)
               {?>
                  <span class="space_char">--</span>                   
               <?php
               }
               echo "&#8594; ";
            }
         
            if(isset($list_accounts_tree[$i]["count_items"]) AND $list_accounts_tree[$i]["count_items"] > 0) 
            {    
               echo $list_accounts_tree[$i]["name"];
               ?>
               <a href="<?php echo site_url("accounts/home?id_account_category_search=".$list_accounts_tree[$i]["id_account_category"])?>">
               <?php 
               echo  " (".$list_accounts_tree[$i]["count_items"].")";
               
               ?>
               </a>
            <?php
            }
            else
            {
               echo $list_accounts_tree[$i]["name"];
            }
            
            ?>
         </td>
         <td><?php echo $list_accounts_tree[$i]["description"];?></td>                      
         <td>
            <?php 
            if(isset($list_accounts_tree[$i]["parent_id"]))
            {
               for($j=0;$j<count($list_accounts_tree);$j++)
               {
                  if(strcasecmp($list_accounts_tree[$i]["parent_id"], $list_accounts_tree[$j]["id_account_category"]) == 0)
                  {                     
                     echo $list_accounts_tree[$j]["name"];
                     break;
                  }                  
               }
            }              
            ?>
         </td>
         <td>
             <?php
               $url_redirect = urlencode( site_url("accounts/save_account") );
               $uri_delete = "/accounts/delete_account/?id_account_category_delete=".$list_accounts_tree[$i]["id_account_category"]."&url_redirect=".$url_redirect;
               ?>
               <a class="delete_item"
                  id="<?php echo $i?>"
                  onclick=" var is_deleted = confirm ('<?php echo $view_labels['coco_msg_sure_to_delete']?>'); if(!is_deleted){return false;}"
                  href="<?php echo site_url($uri_delete)?>">
                  <?php                  
                  if($list_accounts_tree[$i]["deletable"] == "yes")
                  {?>
                     <div class="icon_delete_time"></div>
                  <?php
                  }                  
                  ?>
               </a>
         </td>
      </tr>
   <?php
   }
   ?>
</table>
   