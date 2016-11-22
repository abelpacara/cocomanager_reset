<script>   
$(document).ready( function(){  
    $(".link_icon_delete_item").hover(     
     function () {      
      var id_deleter = $(this).attr('id');      
      $('#row_id_'+id_deleter).addClass("over_row_time");
     },
     function () {
       var id_deleter = $(this).attr('id');
       $('#row_id_'+id_deleter).removeClass("over_row_time");       
     }
    );
       
    $(".link_icon_edit_item").hover(      
     function () {      
      var id_deleter = $(this).attr('id');      
      $('#row_id_'+id_deleter).addClass("over_edit_row_time");
      
     },
     function () {
       var id_deleter = $(this).attr('id');
       
       $('#row_id_'+id_deleter).removeClass("over_edit_row_time");       
     }
    );
});

/***************************************************/
$(function(){
$('select.style_mask').customStyle();
});

$(function() {
   $( "#date_from" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
});
$(function() {
   $( "#date_to" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
});
$(function() {
   $( "#date_in" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
});

$(function() {
   $( "#date_out").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
});
   /*******************************************************/
</script>
<style>
   #list_category_items{
      float: left;
      padding-right: 30px;
  }
   #date_from_category_item{
      float: left;
      padding-right: 20px;
   }
   #date_to_category_item{
      float: left;
      padding-right: 30px;
   }
   #search_for_category_items{
      padding: 20px 5px;
      overflow: hidden;
   }
  
   #button_search_item{
      float: right;
   }
   .row_item {
    border-bottom: 1px solid #DEDEDE;
    height: 40px;
    line-height: 40px;
}
.customStyleSelectBoxInner {
    background-color: #F1F1F1;
    border-color: #DEDEDE;
    font-size: 13px!important;
    height: -14px;
    margin: 0;
    padding: 9px 9px 9px 3px;
}
</style>
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
   <script>   
   $(document).ready(function(){
      $('.mount_price').keypress(function(evt) {         
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
      
      $('.mount_price').change(function() {
         var value = $(this).attr('value');
         $('#account_mount').attr("value",value);
      });
   });
   </script>
   
<div id="content_box" class="clear_both">
   <h1><?php echo $view_labels['form_title']?></h1>  
   <?php                
   //echo form_open($this->uri->uri_string());
   $array_attributes = array("method"=>"get");
   echo form_open($this->uri->uri_string(),$array_attributes);
   ?>
   <div id="search_for_category_items">
      <div id="list_category_items"><span style="display: inline-block; vertical-align: middle"><?php echo $view_labels['list_account']?>:</span>
         <?php
         $id_account_category_search="";
         if(isset($_REQUEST['id_account_category_search']))
         {
            $id_account_category_search = $_REQUEST['id_account_category_search'];
         }
         ?>
         <?php echo get_display_accouts_tree($list_accounts_tree,"id_account_category_search",$id_account_category_search,$view_labels['select_account']);?>
      </div>
      <div id="date_from_category_item"><?php echo $view_labels['date_from']?>:
         <input name = "date_from" 
                id = "date_from"  
                readonly="yes" 
                type="text" 
                value="<?php if(isset($date_from)){ echo $date_from; } else { echo date("Y-m-d", strtotime($system_time)); }?>" 
                class="arrow_drowpdown"/>
      </div>
      <div id="date_to_category_item"><?php echo $view_labels['date_to']?>:
         <input name = "date_to" 
                id = "date_to"  
                readonly="yes" 
                type="text" 
                value="<?php if(isset($date_to)){ echo $date_to; } else { echo date("Y-m-d", strtotime($system_time)); }?>" 
                class="arrow_drowpdown"/>
         <p><input type="hidden" name="validate" value="1" /></p>
      </div>
      <div id="button_search_item"><input type="submit" name="search" value="<?php echo $view_labels['btn_search']?>"></div>
   </div>
   </form>
  
   
   <table id="account_item" class="report_table">
      <thead>
         <tr>
            <th class="item_account"><?php echo $view_labels['column_item']?></th>
            <th><?php echo $view_labels['column_date']?></th>
            <th class="mount_price"><?php echo $view_labels['column_account_in']?></th>
            <th class="mount_price"><?php echo $view_labels['column_account_out']?></th>
            <th><?php echo $view_labels['column_account']?></th>
            <th></th>
         </tr>
         <tr>
               <?php                
               $array_attributes = array("method"=>"get");
               echo form_open($this->uri->uri_string(),$array_attributes);
               ?>
            <!--from method="get"-->
               <th>
                  <?php 
                    $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : "";
                  ?>
                  <input  class="item_account" type="text" name="description" value="<?php echo $description ?>" >
               </th>
               <th>                  
                  <input value="<?php echo date("Y-m-d", $system_time)?>" readonly="yes" type="text" class="arrow_drowpdown" 
                          name = "register_date" id = "date_in" />
               </th>
               <th>
                  <?php
                    $account_in = isset($_REQUEST['account_in']) ? $_REQUEST['account_in'] : "";
                  ?>
                  <input class="mount_price" type="text" name="account_in" id="account_in" value="<?php echo $account_in?>" >
               </th>
               <th>
                  <?php
                  $account_out = isset($_REQUEST['account_out']) ? $_REQUEST['account_out'] : "";
                  ?>
                  <input class="mount_price" type="text" name="account_out" id="account_out" value="<?php echo $account_out?>" >
                  <input type="hidden" name="account_mount" id="account_mount"/>
               </th>
               <th>
                  <?php                 
                  
                  $id_account_category_add="";
                  if(isset($_REQUEST['id_account_category_search']))
                  {
                     $id_account_category_add=$_REQUEST['id_account_category_search'];
                  }
                  else if(isset($_REQUEST['id_account_category_add']) AND !$is_saved)
                  {
                     $id_account_category_add = $_REQUEST['id_account_category_add'];
                  }
                  ?>
                  <?php echo get_display_accouts_tree($list_accounts_tree, "id_account_category_add", $id_account_category_add,$view_labels['select_account']); ?>
               </th>
               <th><input style="padding: 10px 20px !important;" type="submit" value="<?php echo $view_labels['btn_add']?>" name="save_account_item"></th>         
            </form>
         </tr>
      </thead>
      <tbody>
      <?php
      $in_summation = 0;
      $out_summation = 0;
      
      if(count($list_account_items) == 0)
      {?>
         <tr>
            <td colspan="6" style="font-weight:bold;text-align: center;padding-top: 20px;padding-bottom: 10px;">
               <?php echo $view_labels['msg_result_search']?> [
               <?php echo call_user_func('get_date_literal_'.$language, $date_from);?> - 
               <?php echo call_user_func('get_date_literal_'.$language, $date_to);?>
               ]
            </td>
         </tr>
      <?php   
      }
    
      
      for($i=0;$i<count($list_account_items);$i++)
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
                     <?php echo $list_account_items[$i]["description"];?>
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                     <?php echo $list_account_items[$i]["register_date"];?>                    
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                  <?php
                  $in_summation += $list_account_items[$i]["account_in"];
                  echo $list_account_items[$i]["account_in"];
                  ?>
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                  <?php               
                  $out_summation += $list_account_items[$i]["account_out"];
                  echo $list_account_items[$i]["account_out"];
                  ?>
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div class="<?php echo $row_odd_style?>">
                     <a href="<?php echo site_url("accounts/home?id_account_category_search=".$list_account_items[$i]["account_category_id"])?>">
                        <?php echo $list_account_items[$i]["account"];?>
                     </a>
                  </div>
               </td>
               <td class="accounts_cell_row">
                  <div style="padding-left:30px">
                     <?php
                     $url_redirect = urlencode( site_url("accounts/home") );
                     $uri_delete = "/accounts/delete_account_item/?id_account_item_delete=".$list_account_items[$i]["id_account_item"]."&url_redirect=".$url_redirect;
                     //$id_account_item_update = 
                     $uri_update = "/accounts/save_account_item/?id_account_item_update=".$list_account_items[$i]["id_account_item"]."&url_redirect=".$url_redirect;
                     ?>
                     <a href="<?php echo site_url($uri_update)?>"
                        class="link_icon_edit_item"
                        id="<?php echo $i?>"
                        style="display: inline-block; vertical-align: middle"
                     >
                        <div class="pm_icon_edit"></div>            
                     </a>                                          
                  </div>
               </td> 
               
            </tr>
            <?php
      }
      ?>
         <tr>
            <td colspan="2"></td>
            <td>
               <table>      
                   <tr class="row_item">                     
                     <td class="font_bold"><div>Balance:&nbsp;&nbsp;</div></td>                     
                     <td class="font_bold"><div><?php echo ($in_summation - $out_summation);?></div></td>
                   </tr>                  
               </table>
            </td>
         </tr>
   </tbody>
   </table>
         
   
 </div>