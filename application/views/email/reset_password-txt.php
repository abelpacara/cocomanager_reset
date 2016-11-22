<?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?>
<?php echo $view_labels['paragraph1']?>
<?php if (strlen($username) > 0) { ?>

<?php echo $view_labels['your_username']?>: <?php echo $username; ?>
<?php } ?>
<?php echo $view_labels['your_email']?>: <?php echo $email; ?>

<?php /* Your new password: <?php echo $new_password; ?>

*/ ?>
<?php echo $view_labels['thank_you']?>
<?php echo $site_name; ?>