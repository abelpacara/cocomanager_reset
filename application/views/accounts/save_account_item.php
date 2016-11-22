<script>
   
$(document).ready(function(){
       
   $(function() {
      $( "#date_in" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
   });
   
   //$('.mount_price').keypress(function() {
   $('.mount_price').keypress(function(evt) {
      var account_in = $('#account_in');
      var account_out = $('#account_out');
      var charCode;
      
      if($(account_in).is(":focus"))
      {
         //$(account_out).attr('value',''); 
         
         if(evt.which)
         {charCode = evt.which;}
         else
         { charCode = event.keyCode;}         
         if(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
         {
            console.log("dentro if");
            return false;
         }
         else
         { return true; }
      }
      else
      {
         //$(account_in).attr('value','');
         
         if(evt.which)
         { charCode = evt.which; }
         else
         { charCode = event.keyCode; }         
         if(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
         {
            console.log("dentro if");
            return false;
         }
         else
         { return true; }
      }
   });
   
   $('.mount_price').change(function() {
         var value = $(this).attr('value');
         $('#account_mount').attr("value",value);
      });
      
      var account_in_value = $('#account_in').attr('value');
      var account_out_value = $('#account_out').attr('value');
      if(account_in_value != '' &&  parseFloat(account_in_value)>0)
      {
         $('#account_mount').attr("value",account_in_value);
      }
      else
      {
         $('#account_mount').attr("value",account_out_value);
      }
});
</script>

<?php 
echo form_open($this->uri->uri_string());
?>
<h1><?php echo $view_labels['form_title']?></h1>
   <table>
      <tr>
         <?php
         $description="";
         if(isset($_REQUEST['description']))
         {
            $description = $_REQUEST['description'];
         }
         else if(isset($account_item['description']))
         {
            $description = $account_item['description'];
         }
         ?>
         <td><?php echo $view_labels['description']?>:</td><td><input type="text" name="description" value="<?php echo $description?>"/></td>
      </tr>
      <tr> 
         <?php
         $register_date="";
         if(isset($_REQUEST['register_date']))
         {
            $register_date = $_REQUEST['register_date'];
         }
         else if(isset($account_item['register_date']))
         {
            $register_date = $account_item['register_date'];
         }
         ?>
         <td><?php echo $view_labels['date']?></td> 
         <td>            
            <input value="<?php echo date("Y-m-d", strtotime($register_date))?>" 
                    readonly="yes" type="text" class="arrow_drowpdown" name = "register_date" id = "date_in" />
         </td>     
      </tr>
      <tr> 
         <?php 
          $account_in="";
         if(isset($_REQUEST['account_in']))
         {
            $account_in = $_REQUEST['account_in'];
         }
         else if(isset($account_item['account_in']))
         {
            $account_in = $account_item['account_in'];
         }
         ?>
         <td><?php echo $view_labels['account_in']?>:</td><td><input type="text" name="account_in" value="<?php echo $account_in?>" id="account_in" class="mount_price"  /></td>
      </tr>
      <tr>
         <?php 
          $account_out="";
         if(isset($_REQUEST['account_out']))
         {
            $account_out = $_REQUEST['account_out'];
         }
         else if(isset($account_item['account_out']))
         {
            $account_out = $account_item['account_out'];
         }
         ?>
         <td><?php echo $view_labels['account_out']?>:</td><td><input type="text" name="account_out" value="<?php echo $account_out?>" id="account_out" class="mount_price" /></td>
      </tr>
      
      <tr>
         <td><?php echo $view_labels['account']?>:</td>
         <?php 
          $account_category_id="";
         if(isset($_REQUEST['id_account_category']))
         {
            $account_category_id = $_REQUEST['id_account_category'];
         }
         else if(isset($account_item['account_category_id']))
         {
            $account_category_id = $account_item['account_category_id'];
         }
         ?>
         <td>
              <?php echo get_display_accouts_tree($list_accounts_tree,"id_account_category",$account_category_id,$view_labels['select_account']."...");?>
         </td>
      </tr>
      <tr>                    
         <td colspan="2">
            <div style="float: left;">
              <input type="submit" name="save_account_item" value="<?php echo $view_labels['btn_save']?>">
            </div>            
             <?php $url_redirect = urlencode( site_url("accounts/home") );
               $uri_delete = "/accounts/delete_account_item/?id_account_item_delete=".$account_item['id_account_item']."&url_redirect=".$url_redirect;
             ?>                      
            <div class="button_blue enqueue_by_right ">
               <a
                  style="display: inline-block; vertical-align: middle; width:80px;background-color: #4A8BF5; "                        
                  onclick=" var is_deleted = confirm ('<?php echo $view_labels['coco_msg_sure_to_delete']?>'); if(!is_deleted){return false;}"
                  href="<?php echo site_url($uri_delete)?>" >
                     <?php echo $view_labels['btn_delete']?>
               </a>    
            </div>            
         </td>
      </tr>
      
      <input type="hidden" value="<?php echo $id_account_item_update?>" name="id_account_item_update">
      <input type="hidden" name="account_mount" id="account_mount"/>
   </table>
</form>

   