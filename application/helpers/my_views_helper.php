<?php
function get_display_accouts_tree($list_accounts_tree, $name="", $item_selected="",$default_option="")
{
   ob_start();
   ?>
   <select name="<?php echo $name?>" class="accounts_dropdown">
      <option value=""><?php echo $default_option ?></option>
      <?php
      for($i=0;$i<count($list_accounts_tree);$i++)
      {?>
         <option value="<?php echo $list_accounts_tree[$i]["id_account_category"];?>"
           <?php
           if(strcasecmp( $list_accounts_tree[$i]['id_account_category'], $item_selected)==0)
           {
              echo " SELECTED ";
           }?>
           >
         <?php echo str_pad("", $list_accounts_tree[$i]['level'],"___")?><?php echo $list_accounts_tree[$i]["name"];?>
         </option>
      <?php                
      }
      ?>
   </select>
   <?php
   $str = ob_get_clean();
   return $str;
}
##################################################################################################
function get_display_selection_membership($prefix, &$list_membership, $uri_images_users, &$user_id)
{  
   ob_start();
   ?>
   <script>
   $(document).ready(function(){
      $(".<?php echo $prefix?>selector").click(function () {

         var id = $(this).attr('id');   

         if($("#"+id).is(':checked')) { 
            $('#<?php echo $prefix?>selected_'+id).removeClass("pm_opacity_user_member");
         }
         else
         {
            $('#<?php echo $prefix?>selected_'+id).addClass("pm_opacity_user_member");   
         }
      });
   });
   </script>
   <table>
      <tr>
         <td>
         <input type="hidden" name="<?php echo $prefix?>quantity_membership" value="<?php echo (count($list_membership)-1)?>"/>
         <?php
         $k=0;
         for($i=0; $i<count($list_membership); $i++)
         {
            $is_checked="";            
            $style_opacity = "";
            
            if(strcasecmp( $list_membership[$i]['user_id'], $user_id) != 0)
            {
               if( (isset($list_membership[$i]['is_member']) AND strcasecmp( $list_membership[$i]['is_member'], "1") == 0))
               {
                  $is_checked = " checked='yes' ";            
               }
               else
               {
                  $style_opacity = "pm_opacity_user_member";
               }
               ?>
               <div id="<?php echo $prefix?>selected_<?php echo $prefix?><?php echo $k?>" class="enqueue_by_right <?php echo $style_opacity?>" style="padding:10px;">
                  <table>
                     <tr>
                        <td>
                          <?php
                          $url_picture = '.'.$uri_images_users.'/'.$list_membership[$i]['user_id'].'_thumb_small.jpg';

                          if( ! file_exists($url_picture))
                          {
                             $url_picture = '.'.$uri_images_users.'/default.jpg';
                          }
                          ?>
                          <a href="<?php echo site_url('/auth/edit_profile');?>">
                           <img src="<?php echo site_url($url_picture);?>"/>
                          </a>
                        </td>                     
                        <td>
                           <table>
                              <tr>
                                 <td  style="font-size: 12px !important; padding: 1px !important; padding-left: 10px !important;">
                                    <?php 
                                    $member_name ="";
                                    if(isset($list_membership[$i]['name']))
                                    {
                                       $member_name = $list_membership[$i]['name']." ".$list_membership[$i]['last_name'];
                                    }
                                    else
                                    {
                                       $member_name = $list_membership[$i]['email'];
                                    }
                                    echo $member_name;
                                    ?> 
                                 </td>
                              </tr>
                              <tr>
                                 <td  class="list_pm_item_sub_text2"  style="font-size: 9px !important;  padding: 1px !important; padding-left: 10px !important;">
                                    <?php echo $list_membership[$i]['role']?>
                                 </td>
                              </tr>
                              <tr>
                                    <td style=" padding-left: 10px !important;">
                                       <input type="hidden" name="<?php echo $prefix?>member_id_<?php echo $k?>" value="<?php echo $list_membership[$i]['user_id'];?>"/>     
                                       <input type="checkbox" 
                                              class="<?php echo $prefix?>selector" 
                                              id="<?php echo $prefix?><?php echo $k?>" style="float:right; margin:0px;" 
                                              name="<?php echo $prefix?>member_id_<?php echo $k?>_is_checked" 
                                              value="<?php echo $list_membership[$i]['user_id'];?> "                                                                
                                              <?php
                                              echo $is_checked;
                                              ?>                                              
                                              />

                                              <?php
                                              if(isset($list_membership[$i]['is_member']))
                                              {?>
                                                   <input type="hidden" 
                                                          name="<?php echo $prefix?>member_id_<?php echo $k?>_member" 
                                                          value="<?php echo $list_membership[$i]['user_id'];?>"                                                
                                                          />     
                                              <?php
                                              }
                                              else
                                              {?>
                                                 <input type="hidden" name="<?php echo $prefix?>member_id_<?php echo $k?>_not_member" 
                                                        value="<?php echo $list_membership[$i]['user_id'];?>"                                                     
                                                        
                                                        />     
                                                 <?php
                                              }
                                              ?>                                        
                                    </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
               </div>
            <?php
            $k++;
            }
         }?>
         </td>
      </tr>
   </table>
   <?php
   $result = ob_get_clean();
   return $result;
}?>
