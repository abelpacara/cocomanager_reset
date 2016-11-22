<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo $view_labels['mail_form_title']?> <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">
   <?php echo $view_labels['mail_sub_title1']?>
</h2>
<?php echo $view_labels['paragraph1']?>
<big style="font: 16px/18px Arial, Helvetica, sans-serif;">
   <b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">         
         <?php echo $view_labels['link_create_password']?>
      </a>
   </b>
</big><br />
<br />
<?php echo $view_labels['paragraph2']?>
<nobr>
   <a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;">
   <?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>
   </a>
</nobr><br />
<br />
<br />
<?php echo $view_labels['paragraph3']?>
   <a href="<?php echo site_url(''); ?>" style="color: #3366cc;">
   <?php echo $site_name; ?>
   </a> 
<?php echo $view_labels['paragraph4']?>
<br />
<br />
<?php echo $view_labels['paragraph5']?>
<?php echo $site_name; ?>
</td>
</tr>
</table>
</div>
</body>
</html>