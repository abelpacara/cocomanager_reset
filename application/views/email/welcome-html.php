<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo $view_labels['send_title']?> <?php echo $site_name; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">
   <?php echo $view_labels['send_title']?> <?php echo $site_name; ?>!
</h2>
<?php echo $view_labels['paragraph1']?> <?php echo $site_name; ?>. <?php echo $view_labels['paragraph2']?><br />

<?php echo $view_labels['paragraph3']?> <?php echo $site_name; ?> <?php echo $view_labels['paragraph4']?>:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;"><?php echo $view_labels['paragraph5']?> <?php echo $site_name; ?> <?php echo $view_labels['paragraph6']?></a></b></big><br />
<br />
<?php echo $view_labels['paragraph7']?>:<br />
<nobr><a href="<?php echo site_url('/auth/login/'); ?>" style="color: #3366cc;"><?php echo site_url('/auth/login/'); ?></a></nobr><br />
<br />
<br />
<?php if (strlen($username) > 0) { ?><b><?php echo $view_labels['paragraph8']?>:</b> <?php echo $username; ?><br /><?php } ?>
<b><?php echo $view_labels['paragraph9']?>:</b> <?php echo $email; ?><br />
<b><?php echo $view_labels['paragraph10']?>:</b> <?php echo $password; ?><br />
<br />
<br />
<?php echo $view_labels['paragraph11']?><br />
<?php echo $site_name; ?>.
</td>
</tr>
</table>
</div>
</body>
</html>