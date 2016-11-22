<script>
$(function(){
   $('select.style_mask').customStyle();
});

$(function() {
   $( "#date_from" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
});
$(function() {
   $( "#date_to" ).datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true, firstDay: 1 });      
});
</script>
<div><h1><?php echo $view_labels['form_title']?></h1></div>
<?php
echo form_open($this->uri->uri_string(),array("method"=>"post"));
?>
<table>
   <tr>
      <td><?php echo $view_labels['title_label']?>:</td>
      <td><input type="text" name="title"/></td>
   </tr>
   <tr>
      <td><?php echo $view_labels['date_from_label']?>:</td>
      <td><input type="text" name="date_from" id="date_from" readonly="yes" class="arrow_drowpdown"/></td>
   </tr>
   <tr>
      <td><?php echo $view_labels['date_to_label']?>:</td>
      <td><input type="text" name="date_to" id="date_to" readonly="yes" class="arrow_drowpdown"/></td>
   </tr>
   <tr>
      <td colspan="2">
         <input type="submit" name="save_budget_range" value="<?php echo $view_labels['btn_save_budget_range']?>"/>
      </td>
   </tr>
</table>

</form>

<br/>
<h1><?php echo $view_labels['list_title']?></h1>
<table class ="report_table">
   <tr>
      <th><?php echo $view_labels['column_title']?></th>
      <th><?php echo $view_labels['column_date_from']?></th>
      <th><?php echo $view_labels['column_date_to']?></th>
   </tr>
   <?php
   for($i=0;$i<count($list_budget_ranges);$i++)
   {?>
      <tr class="tr_row_table">
         <td><?php echo $list_budget_ranges[$i]['title']?></td>
         <td><?php echo $list_budget_ranges[$i]['date_from']?></td>
         <td><?php echo $list_budget_ranges[$i]['date_to']?></td>
         <td>
            <?php
            $url_redirect = urlencode( site_url("accounts/save_budget_range") );
            $uri_delete = "/accounts/delete_budget_range/?id_budget_range_delete=".$list_budget_ranges[$i]["id_budget_range"]."&url_redirect=".$url_redirect;
            ?>
            <a class="delete_item"
                  id="<?php echo $i?>"
                  onclick=" var is_deleted = confirm ('Delete These Insurance?'); if(!is_deleted){return false;}"
                  href="<?php echo site_url($uri_delete)?>">
               <div class="icon_delete_time"></div>
            </a>
         </td>
      </tr>
   <?php
   }?>
</table>