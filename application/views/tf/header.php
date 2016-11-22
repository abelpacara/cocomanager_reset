<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Foro Coco Manager</title>
  <link href="<?php echo base_url(); ?>public/css/forums.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="general_wrapper">
		<div id="header_menu_wrapper">			
			<div id="header_menu_content">
				<span class="logo_text">COCO Manager - Foro</span>
			</div>
		</div>	
		<div id="header_breadcrumb_wrapper">			
			<div id="header_breadcrumb_content">
				<?php
            if(isset($forum_id))
            {
               if(isset($topic_id))
               {
                  echo "<a href='".base_url()."forums'>Home</a> > <a href='".base_url()."/forums/topics/?topic_id=".$topic_id."&forum_id=".$forum_id."'>".$forum_title."</a> > ".$topic_title;
               }
               else
               {
                  echo "<a href='".base_url()."forums'>Home</a> > ".$forum_title;
               }
            }
            else
            {
               echo "Home";
            }
            ?>
			</div>
		</div>
		
		<div id="content_wrapper">			
			<div id="content_content">