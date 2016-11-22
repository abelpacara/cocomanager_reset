<div bgcolor="#ffffff" link="#0099cc" alink="#0099cc" vlink="#0099cc" style="text-align:left">
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tbody>
      
      <tr>
         <td style="text-align:left;font-size:14px;border-bottom:1px solid #dddddd;font-family:Helvetica,Arial,sans-serif;padding:10px 20px 5px 20px" colspan="2">
             <table cellpadding="0" cellspacing="0" border="0">
               <tbody>                  
                  <tr>
                     <td style="padding:0 0 5px 0;font-weight:normal;color:#999999;text-align:left;font-size:14px;font-family:Helvetica,Arial,sans-serif" width="75" valign="top">
                        <?php echo $view_labels['company']?>:
                     </td>
                     <td style="text-align:left;font-size:14px;font-family:Helvetica,Arial,sans-serif;padding:0 0 5px 10px" valign="top">
                        <?php echo $company['name']?>
                     </td>
                  </tr>
               </tbody>
             </table>
         </td>
      </tr>
      <tr>
         <td style="padding-top:0;padding-bottom:20px;text-align:left">
            <table cellpadding="0" cellspacing="0" border="0" align=left">
                   <tbody><tr>
                     <td style="text-align:left;font-size:14px;font-family:Helvetica,Arial,sans-serif;padding:0 20px 10px 20px">
                         
                         <div style="padding:10px 0 20px 0">
                          <table cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td width="57" valign="top">
                                      <?php
                                      $url_picture = '.'.$this->config->item('uri_images_users').'/'.$user_id.'_thumb_small.jpg';

                                      if( ! file_exists($url_picture))
                                      {
                                         $url_picture = '.'.$this->config->item('uri_images_users').'/default.jpg';
                                      }
                                      ?>
                                      <a href="<?php echo site_url('/auth/edit_profile');?>">
                                       <img src="<?php echo site_url($url_picture);?>" height="55"  style="border:1px solid #cccccc;padding:1px" width="55"/>
                                      </a>
                                    </td>
                                    <td style="padding-left:15px;font-size:14px;font-family:Helvetica,Arial,sans-serif" valign="top">
                                       <div class="im">
                                          <h1 style="line-height:1.3em;font-size:14px;margin:0 0 15px 0;font-family:Helvetica,Arial,sans-serif;font-weight:normal">
                                             <?php                         
                                             $changer_show_name ="";

                                             if(isset($profile_name))
                                             {
                                                $changer_show_name = $profile_name." ".$profile_last_name;                     
                                             }
                                             else
                                             {
                                                $changer_show_name = $email;
                                             }
                                             $url_profile_user = site_url("/auth/edit_profile?changer_id=".$user_id);
                                             ?>
                                             
                                                <?php echo $changer_show_name;?>                                             
                                                <?php echo ", "?> <?php echo $view_labels['paragraph1']?>: 
                                                <span style="font-weight: bold;"><?php echo $time_old_literal?></span> 
                                                <?php echo $view_labels['paragraph2']; ?>
                                                <span style="font-weight: bold;"><?php echo $time_new_literal?></span>
                                                <br/>
                                                <span style="font-weight:bold">
                                                   <?php
                                                   $url_view_change = "";

                                                   $reply = "";

                                                   $url_view_change = site_url("times/manager_times/?date_begin=".$date_begin."&date_end=".$date_end."#user_id_".$user_id);
                                                   //$url_discussion = site_url("pm/view_comment/?comment_id=".$discussion['id_object']);
                                                   ?>
                                                   <a href="<?php echo $url_view_change?>" target="_blank">
                                                      <?php echo $view_labels['view_change']?>
                                                   </a>
                                                </span>
                                          </h1>
                                       </div>
                                    </td>
                              </tr>
                           </tbody></table>
                     </div>

                  </td>
               </tr>
            </tbody>
            </table>

      </td>
   </tr>
   <tr>
      <td style="text-align:left;font-size:12px;font-family:Helvetica,Arial,sans-serif;border-top:1px solid #dddddd;padding:10px 20px 10px 20px">
          <p style="margin:0;color:#444444">
           <?php echo $view_labels['sent_to']?>: <?php echo $name_to;?>
          </p>
      </td>
   </tr>
   
   
</tbody></table>

</div>
