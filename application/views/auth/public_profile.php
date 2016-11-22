<style>
   .picture_size{
      width: 233px;
      height: 233px;
   }
   .color_text_opaque{
      color: #969696 !important;
   }
   .table_profile tr td{
      line-height: 20px;
      padding-left: 15px;
      padding-right: 5px;
      padding-bottom: 5px;
      padding-top: 0px;
   }
</style>
<h1><?php echo $view_labels['form_title']?></h1>
<div>
   <div class="enqueue_by_right">
      <?php
      $uri_picture = '.'.$uri_images_users.'/'.$user['user_id'].'_thumb_medium.jpg';
      $url_picture = site_url($uri_images_users.'/'.$user['user_id'].'_thumb_medium.jpg');

      if( ! file_exists($uri_picture))
      {
         $url_picture = './'.$uri_images_users.'/default.jpg';
      }
      ?>
      <img src="<?php echo $url_picture;?>"/>        
   </div>
   <div class="enqueue_by_right">
      <table class="table_profile">
         <tr>
            <td><?php echo $user['name'].' '.$user['last_name']?></td>
         </tr>
         <tr>
            <td class="color_text_opaque"><?php echo $user['role']?></td>
         </tr>
         <tr>
            <td>Email: <a href="mailto:<?php echo $user['email']?>"><?php echo $user['email']?></td>
         </tr>
      </table>
   </div>
</div>
<div style="height: 10px; clear: both"></div>
