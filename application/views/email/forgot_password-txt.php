<?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?>

<?php echo $view_labels['paragraph1']?>

<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>


<?php echo $view_labels['paragraph3']?>
<?php echo $site_name; ?> 
<?php echo $view_labels['paragraph4']?>


<?php echo $view_labels['paragraph5']?>
<?php echo $site_name; ?>