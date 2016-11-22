<?php
$menu_clients = $view_labels['menu_clients'];

foreach($menu_clients AS $key=>$value)
{
?>
   <span class="pm_link_button enqueue_by_left">
         <a href="<?php echo site_url($key)?>"><?php echo $value?></a>
   </span>
<?php
}
?>