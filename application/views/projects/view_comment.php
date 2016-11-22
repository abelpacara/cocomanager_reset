<h1 class="enqueue_by_right">   
   <?php echo $comment['name']?>
</h1>
<?php
$url_timthumb = base_url()."public/timthumb.php";
?>
<script type="text/javascript" src="<?php echo base_url()?>public/js/lightbox2/prototype.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/lightbox2/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/lightbox2/lightbox.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>public/css/lightbox.css" type="text/css" media="screen" />

<script>
LightboxOptions = Object.extend({
    fileLoadingImage:        '<?php echo base_url()?>public/images/loading.gif',     
    fileBottomNavCloseImage: '<?php echo base_url()?>public/images/closelabel.gif',    
    overlayOpacity: 0.8,   // controls transparency of shadow overlay

    animate: true,         // toggles resizing animations
    resizeSpeed: 7,        // controls the speed of the image resizing animations (1=slowest and 10=fastest)
    borderSize: 10,         //if you adjust the padding in the CSS, you will need to update this variable
	// When grouping images this is used to write: Image # of #.
	// Change it for non-english localization
	labelImage: "Image",
	labelOf: "of"
});
</script>

<style>
.files_attachments{
   background-color:#dcdcdc;
}
.file_attachment{
   float: left;
   padding: 15px;
   padding-left: 0px;
   padding-top: 5px;
   text-align: center;
}
.file_attachment_container_image{
   
   text-align: center;
}

.file_attachment_image{
   border-radius: 5px 5px 5px 5px; 
   /*border: 1px solid #dcdcdc;*/
   box-shadow: 4px 4px 4px #888888;
}
.file_attachment_size{
   color:#9A9A9A;
   /*color:#000000;*/
   font-size: 9px;
   font-style: italic;
   padding: 3px;   
}

.file_attachment_image:hover{
   box-shadow:none;
}


.file_attachment_name{
   padding-top: 5px;
   text-align: center;
}

.file_attachment_name a, .file_attachment_name{
   font-size: 11px;
   color:#2383B9;
}
</style>
                              
<table class="clear_both" style="width: 100%">
<tr>
   <td style="padding-right: 15px; vertical-align: top">
      
      <div class="list_pm_item_sub_text2" style="clear:both;">         
         <?php echo $view_labels['created_by']?>:              
         <?php                         
         $owner_show_name ="";
         if(isset($comment['owner_name']))
         {
            $owner_show_name = $comment['owner_name']." ".$comment['owner_last_name'];
         }
         else
         {
            $owner_show_name = $comment['owner_email'];
         }      
         $url_profile_user = site_url("/auth/public_profile?user_id=".$comment['user_id']);
         ?>
         <a href="<?php echo $url_profile_user?>">
            <?php echo $owner_show_name;?>
         </a>
         |
        <?php echo call_user_func('get_date_literal_'.$this->config->item('language'), $comment['register_date']) ?>
      </div> 

      <span class="comment_text">
        <?php echo nl2br(decode_chars_special($comment['description']))?>
      </span>
      <table>
         <tr>
            <td><?php echo $view_labels['suscribers']?>:&nbsp;</td>
            <td class="list_pm_item_sub_text">                  
               <?php       
               if(isset($list_discussion_peoples) AND count($list_discussion_peoples)>0)
               {
                  $i=0;
                  foreach($list_discussion_peoples AS $people)
                  {
                     $owner_show_name ="";

                     if(isset($people['name']))
                     {
                        $owner_show_name = $people['name']." ".$people['last_name'];
                     }
                     else
                     {
                        $owner_show_name = $people['email'];
                     }         
                     $url_profile_user = site_url("/auth/public_profile?user_id=".$people['user_id']);

                     $comma = "";
                     if($i>0)
                     {
                        $comma=", ";
                     }
                     echo $comma.$people['role'];
                     ?>
                     <a href="<?php echo $url_profile_user?>">
                        <?php echo $owner_show_name;?>
                     </a>
                  <?php
                  $i++;
                  }
               }
               ?>
            </td>
         </tr>
      </table>
      <?php
      
      
      if(isset($list_attachment_files) AND count($list_attachment_files)>0)
      {?>
         <h2 style="padding-top:15px;"><?php echo $view_labels['attachments']?></h2>
         <div class="files_attachments">
            <?php
            for($i=0;$i<count($list_attachment_files);$i++)
            {
               $uri_file = "./".$this->config->item("uri_comment_files")."/".$comment['id_object']."/".get_filename_uploaded($list_attachment_files[$i]['name']);
               $url_file = site_url($uri_file);
               $file_size = "";
               if(file_exists($uri_file))
               {
                  $file_size = filesize($uri_file);
                  ?>
                  <div class="file_attachment">
                     <div class="file_attachment_container_image">
                        <?php
                        if(getimagesize($uri_file))
                        {?>
                           <a href="<?php echo $url_file?>" rel="lightbox[roadtrip]">                        
                           <img class="file_attachment_image"
                                 title=""
                                 alt=""
                                 src="<?php echo $url_timthumb."?src=".$url_file."&w=100&h=100"; ?>">
                           </a>
                        <?php
                        }?>
                     </div>
                     <div class="file_attachment_name">
                        <a href="<?php echo $url_file?>"  target="_blank">
                           <?php echo $list_attachment_files[$i]['name']?>
                        </a>     
                     </div>
                     <div class="file_attachment_size">
                        <?php echo human_filesize($file_size);?>
                     </div>
                  </div>
                  <?php
               }
            }
            ?>
         </table>
      <?php
      }
      ?>

      <?php
      if(isset($list_comments_reply) AND count($list_comments_reply)>0)
      {
      ?>
         <h1 style="margin-top:20px; padding-top:25px;"><?php echo $view_labels['title_form']['comment']?></h1>
      <?php
      }
      ?>

      <table width="100%">


         <?php
         if(isset($list_comments_reply) AND count($list_comments_reply)>0)
         {
            $k = 0;
            foreach($list_comments_reply AS $reply_comment)
            {
               $k++;

               $url_comment = site_url("pm/view_comment?comment_id=".$reply_comment['id_object']."&parent_comment_id=".$comment['id_object']);
               ?> 

               <tr class="hr_top <?php if(isset($reply_comment['is_private'])){ echo "pm_private_object";}?>">
               <td>
                  <a name="item_comment_<?php echo $reply_comment['id_object']?>"></a>
               <table width="100%">
               <tr> 
                     <td style="padding-top:15px; vertical-align: top">
                        <div class="enqueue_by_right" style="padding-right:5px">
                           <div class="profile_picture">
                                <?php
                                $url_picture = '.'.$uri_images_users.'/'.$reply_comment['user_id'].'_thumb_small.jpg';

                                if( ! file_exists($url_picture))
                                {
                                   $url_picture = '.'.$uri_images_users.'/default.jpg';
                                }
                                ?>
                                <a href="<?php echo site_url('/auth/public_profile?user_id='.$reply_comment['user_id']);?>">
                                 <img src="<?php echo site_url($url_picture);?>"/>
                                </a>
                             </div>
                        </div>




                        <div class="enqueue_by_right">

                           <?php                         
                           $owner_show_name ="";

                           if(isset($reply_comment['owner_name']))
                           {
                              $owner_show_name = $reply_comment['owner_name']." ".$reply_comment['owner_last_name'];                     
                           }
                           else
                           {
                              $owner_show_name = $reply_comment['owner_email'];
                           }         

                           $url_profile_user = site_url("/auth/public_profile?user_id=".$reply_comment['user_id']);
                           ?>
                           <a href="<?php echo $url_profile_user?>">
                              <?php echo $owner_show_name;?>
                           </a>
                           <span style="color: #969696 !important">
                           <?php 
                           if(strcasecmp($selectable_type_object_task_id, $reply_comment['type_select_id'])==0)
                           {
                              echo $view_labels['put_a_task'];                        
                           }
                           else
                           {
                              echo $view_labels['said'];
                           }
                           ?> 
                           </span>

                        </div>
                        <br/>
                        <div class="list_pm_item_sub_text2">                  
                           <?php echo call_user_func('get_date_literal_'.$this->config->item('language'),$reply_comment['modified_date']);?>
                        </div>
                     </td>


                     <td style="vertical-align: top; padding-top:10px" class="enqueue_by_left">               
                     <?php 
                     if(strcasecmp($selectable_type_object_task_id, $reply_comment['type_select_id'])==0)
                     {
                        $url_status = base_url()."pm/view_comment/?project_id=".$project_id."&comment_id=".$comment_id."&reply_object_id=".$reply_comment['id_object'];
                        ?>
                        <div>
                           <?php
                           /*echo "<br> ".$reply_comment['action_status']." = "."in_process";
                           echo "<br> ".$user_id." ".$reply_comment['action_status_user_id'];*/
                           ?>
                           
                           <select name="" style="font-size: 12px;"  id="status_<?php echo $k?>"  onchange=" 
                              var action_status_id = $('#status_<?php echo $k?> option:selected').val();

                              window.open('<?php echo $url_status?>&action_status_id='+action_status_id+'#item_comment_'+'<?php echo $reply_comment['id_object']?>', '_self')"
                              <?php
                              if(strcasecmp($reply_comment['action_status'],"in_process")==0 AND 
                                 strcasecmp($user_id, $reply_comment['action_status_user_id'])!=0)
                              {
                                 echo "disabled='yes'";
                              }
                              ?>
                           >
                              <?php
                              for($j=0; $j<count($list_task_status);$j++)
                              {?>
                                 <option value="<?php echo $list_task_status[$j]['id']?>"
                                         <?php
                                         if(strcasecmp($list_task_status[$j]['id'], $reply_comment['action_status_select_id'])==0)
                                         {
                                            echo " SELECTED ";
                                         }
                                         ?>
                                         >
                                    <?php echo "---".$view_labels['object_status'][$list_task_status[$j]['value_select']]?>
                                 </option>
                              <?php
                              }
                              ?>
                           </select>                     
                        </div>
                        <div style="white-space: nowrap" class="list_pm_item_sub_text2">
                           <?php echo call_user_func('get_date_literal_'.$this->config->item('language'),$reply_comment['register_date_action_status']);?>
                        </div>
                        <div>

                        </div>
                     <?php
                     }
                     ?>
                     </td>

                     <td style="width: 300px; padding: 5px; " class="enqueue_by_left">
                     <?php
                     /*
                     if(strcasecmp($selectable_type_object_task_id, $reply_comment['type_select_id'])==0)
                     {*/
                        if( ! empty($reply_comment['list_member_tasks']) )
                        {
                           $list_member_tasks = $reply_comment['list_member_tasks'];

                           for($i=0; $i<count($list_member_tasks); $i++)
                           {
                              if(strcasecmp($list_member_tasks[$i]['user_id'],$user_id)!=0)
                              {
                                 $member_name ="";
                                 if(isset($list_member_tasks[$i]['name']))
                                 {
                                    $member_name = $list_member_tasks[$i]['name']." ".$list_member_tasks[$i]['last_name'];
                                 }
                                 else
                                 {
                                    $member_name = $list_member_tasks[$i]['email'];
                                 }                     
                                 ?>
                                 <div class="enqueue_by_left" style="padding:5px; width:35px; height: 35px" title="<?php echo $member_name;?>">                        
                                             <?php
                                            $url_picture = '.'.$uri_images_users.'/'.$list_member_tasks[$i]['user_id'].'_thumb_small.jpg';

                                            if( ! file_exists($url_picture))
                                            {
                                               $url_picture = '.'.$uri_images_users.'/default.jpg';
                                            }
                                            ?>
                                            <a href="<?php echo site_url('/auth/public_profile?user_id='.$list_member_tasks[$i]['user_id']);?>">
                                             <img src="<?php echo site_url($url_picture);?>"/>
                                            </a>

                                 </div>
                              <?php
                              }
                           }
                        }
                     /*
                     }*/
                     ?>
                     </td>
                     <td style="padding-bottom:15px; padding-left:10px; width:75px; vertical-align: top; padding-top:15px">
                          <div class="picture_comment"></div>
                           <?php
                           $url_comment = site_url("pm/view_comment?comment_id=".$comment_id."&project_id=".$project_id."#item_comment_".$reply_comment['id_object']);
                           ?>
                           <a href="<?php echo $url_comment?>">
                              <div class="comment_number">
                              <?php
                              echo "# ".$reply_comment['index'];
                              ?>
                              </div>
                           </a>


                           <?php
                           $url_comment = site_url("pm/save_task_comment?project_id=".$project_id."&comment_id=".$reply_comment['id_object']."&parent_comment_id=".$comment['id_object']);

                           $url_redirect = "";

                           if(strcasecmp($selectable_type_object_task_id, $reply_comment['type_select_id'])==0)
                           {
                              $url_redirect = "&redirect=".urlencode( site_url("pm/save_task/?project_id=".$project_id."&task_id=".$reply_comment['id_object']."&parent_id=".$comment['id_object']) );
                           }
                           else
                           {
                              $url_redirect = "&redirect=".urlencode( site_url("pm/save_comment/?project_id=".$project_id."&comment_id=".$reply_comment['id_object']."&parent_comment_id=".$comment['id_object']) );
                           }
                           ?> 
                           <div class="align_link_edit">              
                              <a href="<?php echo $url_comment.$url_redirect ?>">                  
                                 <div class="pm_icon_edit"></div>  
                              </a>
                           </div>
                     </td>

                  </tr>
                  <tr>
                     <td colspan="3">

                     <span class="comment_text">                  
                        <?php 
                        if(isset($reply_comment['description']))
                        {
                           echo nl2br(decode_chars_special($reply_comment['description'])) ;
                        }
                        else
                        {
                           echo nl2br(decode_chars_special($reply_comment['name'])) ;
                        }
                        ?>                  
                     </span>
                  </td>                         
                  </tr>

                  <?php
                  $list_files = $reply_comment['list_attachment_files'];

                  if(isset($list_files) AND count($list_files)>0)
                  {                 
                  ?>
                     <tr>
                        <td colspan="4" style="padding-bottom:20px; padding-left: 15px;">                  
                              <div class="list_pm_item_sub_text"><?php echo $view_labels['attachments']?></div>
                              
                              <div class="files_attachments">
                                 <?php
                                 for($i=0;$i<count($list_files);$i++)
                                 {
                                    $uri_file = "./".$this->config->item("uri_comment_files")."/".$reply_comment['id_object']."/".get_filename_uploaded($list_files[$i]['name']);                                    
                                    $url_file = site_url($uri_file);
                                    $file_size = "";
                                    if(file_exists($uri_file))
                                    {
                                       $file_size = filesize($uri_file);
                                       ?>
                                       <div class="file_attachment">
                                          <div class="file_attachment_container_image">
                                             <?php
                                             if(getimagesize($uri_file))
                                             {?>
                                                <a href="<?php echo $url_file?>" rel="lightbox[roadtrip<?php echo $k?>]">
                                                   <img class="file_attachment_image"
                                                      title=""
                                                      alt=""
                                                      src="<?php echo $url_timthumb."?src=".$url_file."&w=100&h=100"; ?>">
                                                </a>
                                             <?php
                                             }?>
                                          </div>
                                          <div class="file_attachment_name">
                                             <a href="<?php echo $url_file?>"  target="_blank">
                                                <?php echo $list_files[$i]['name']?>
                                             </a>                                          
                                          </div>
                                          <div class="file_attachment_size">
                                             <?php echo human_filesize($file_size);?>
                                          </div>
                                       </div>
                                    <?php
                                    }
                                 }
                                 ?>
                              </div>            
                        </td>
                     </tr>
                  <?php
                }
                ?>
               </table>
               </td>
               </tr>
               <?php
            }
         }
         ?>
      </table>
      <script>
      $(document).ready(function(){
               $(".delete_file").click(function () {

                  var id_deleter = $(this).attr('id');      

                  $('#row_id_'+id_deleter).toggleClass("over_row_time");          
               });
            });

      </script>
      <div style="margin-top:20px; padding-top:20px;">

      <?php 
      echo form_open_multipart( site_url($this->uri->uri_string()."?project_id=".$project_id."&comment_id=".$comment_id));
      ?>
      <h1>
      <?php
      echo $view_labels["title_add_comment"];
      ?>
      </h1>

      <?php
      $quantity_files = 3;
      ?>   
      <input type="hidden" id="quantity_files" name="quantity_files" value="<?php echo $quantity_files?>"/>

      <script>
      $(document).ready(function(){

         $('#add_file').click(function(){

               var quantity_files= $('#quantity_files').attr('value');
               quantity_files++;
               $('#quantity_files').attr('value', quantity_files);

               var str_append = "";
               str_append += "<tr>";      
               str_append += "<td class='text_label_project'><?php echo $view_labels['new_attachment_file'] ?> "+quantity_files+":</td>";
               str_append += "<td  style='white-space: nowrap'>";
               str_append += "<input type='file' name='file_"+quantity_files+"' class='pm_text_field file_input' size='40'/>";
               str_append += "&nbsp;<?php echo $max_upload_filesize?> MB max per file";
               str_append += "</td>";
               str_append += "</tr>";

               $('#entries').append(str_append);   
               
               
               show_filesize_restrict(".file_input", <?php echo $max_total_send_filesize_mb?>,'#current_filesizes');
         });
         show_filesize_restrict(".file_input", <?php echo $max_total_send_filesize_mb?>,'#current_filesizes');
      });
      
      
      </script>
      <table id="entries" style="width: 100%">      
         <tr>      
            <?php
            if(isset($comment['id_object']))
            {
            ?>
               <input type="hidden" name="comment_id" value="<?php echo $comment['id_object']?>"/>
               <input type="hidden" name="project_id" value="<?php echo $project_id?>"/>
            <?php
            }
            ?>
            <td colspan="1"  class="text_label_project">
               <?php echo $view_labels['content'] ?>:
            </td>      

            <td colspan="3">
               <textarea name="content" rows="5" class="pm_text_field pm_width_text_field"></textarea>
            </td>
         </tr>
         <tr>
            <td></td>
            <td>
               <table style="width:100%">
                  <tr>
                     <td class="text_label_project">                        
                        <?php echo $view_labels['is_task'] ?>:
                        <input type="checkbox" name="is_task" id="_is_task" class="_displayer_members" value="ok"/>
                     </td>
                     <td class="text_label_project">
                        <?php echo $view_labels['is_private'] ?>:
                        <input type="checkbox" name="is_private" id="_is_private"  class="_displayer_members" value="ok"/>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         



      <script>
      $(document).ready(function(){            
         check_click('_displayer_members');         
      });
      function check_click(postfix)
      {
         $("."+postfix).click(function () {
   
            if($("."+postfix).is(':checked')) { 
               $("#list_members"+postfix).css('display','block');
               $("#label_list_members"+postfix).css('display','block');
            }
            else
            {
               $("#list_members"+postfix).css('display','none');
               $("#label_list_members"+postfix).css('display','none');
            }
         });
      }
      </script>   
      
     
         <tr>
            <td>
               <span id="label_list_members_displayer_members" style="display: none" class="text_label_project">
                  <?php echo $view_labels['members_assignable'] ?>:
               </span>
            </td>
         
            <td id="list_members_displayer_members" style="display: none" colspan="2">
               <?php            
               echo get_display_selection_membership("", $list_membership, $uri_images_users, $user_id);
               ?>
            </td>
         </tr>
         
       
         <?php
         for($i=1;$i<=$quantity_files;$i++)
         {?>      
            <tr>
               <td class="text_label_project"  style="white-space: nowrap"><?php echo $view_labels['new_attachment_file'] ?> <?php echo $i?>:&nbsp;</td>
               <td style="white-space: nowrap">
                  <input type="file" name="file_<?php echo $i?>" class="pm_text_field file_input" size="40"/>
                  &nbsp;<?php echo $max_upload_filesize?> MB max per file
               </td>
            </tr>
         <?php
         }   
         ?>
      </table>
      <table>
         <tr>
            <td class="text_label_project"><div id="current_filesizes" style="padding-left: 10px; padding-right: 10px">0MB</div></td>
            <td colspan="2" class="text_label_project"><?php echo "Max. Total File Sizes = ".$max_total_send_filesize_mb ?> MB</td>
         </tr>
      </table>
      <table style="width: 100%">   
         <tr>
            <td>
               <input type="button" name="" id="add_file" value="+" style="float: right; cursor:pointer;"/>
            </td>
            <td style="width: 140px"></td>

         </tr>
      </table>

      <table>
         <tr>
            <td colspan="3">         
               <input type="submit" name="save_go_to_list" value="<?php echo $view_labels['save_buttons']['save_go_back_list'] ?>"/>         
            </td>
         </tr>
      </table>

      </form>
      </div>
</td>

</tr>
</table>