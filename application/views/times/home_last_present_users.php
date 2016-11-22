<style>
   .member_company{
      min-width: 130px;
      height: 40px;
      padding:10px; 
      padding-right:7px; 
      padding-top:0px; 
      padding-left:0px !important; 
   }
   .content_menber_company{
      padding:10px; 
   }
   .menber_company_name{
      font-size: 12px !important; padding: 1px !important; padding-left: 5px !important;
   }
   
   .item_sub_role{
      font-size: 9px !important;  padding: 1px !important; padding-left: 5px !important;
      
   }
   .item_times{
      padding: 0px;
      padding-top: 3px;
   }
   .item_sub_time_in{
      font-size: 10px !important;  
      padding: 0px !important; 
      
      padding-left: 5px !important;      
      color: inherit;
      font-style: normal;
      color:#9A9A9A;      
      vertical-align: bottom;
      display: inline-block;
   }
   .item_sub_time_total{
      font-size: 9px !important;  
      padding:0px !important; 
      padding-left: 2px !important;
      color: inherit;
      color:#9A9A9A;
      font-style: italic;
      vertical-align: middle;
      display: inline-block;
   }
   .content_box_menber_company{
      padding-top: 3px;
   }
</style> 
<h2><?php echo $view_labels['first_come']?></h2>
<table>
      <tr>
         <td style="padding-bottom: 10px;padding-top: 0px;">
         
         <?php
         $k=0;
         for($i=0; $i<count($list_last_present_users); $i++)
         {
               ?>
               <div id="selected_<?php echo $k?>" class="enqueue_by_right member_company">
                  <table class="content_menber_company"
                         <?php
                         if(strcasecmp(trim($list_last_present_users[$i]['is_present']),"0")==0)
                         {
                            echo " style ='opacity:0.3' ";
                         }
                         else
                         {
                            echo " value='".$list_last_present_users[$i]['is_present']."' ";
                         }
                         ?>
                         >
                     <tr>
                        <td class="content_box_menber_company">
                          <?php
                          $url_picture = '.'.$uri_images_users.'/'.$list_last_present_users[$i]['user_id'].'_thumb_small.jpg';

                          if( ! file_exists($url_picture))
                          {
                             $url_picture = '.'.$uri_images_users.'/default.jpg';
                          }
                          ?>
                          <a href="<?php echo site_url('/auth/public_profile?user_id='.$list_last_present_users[$i]['user_id']);?>">
                           <img src="<?php echo site_url($url_picture);?>"/>
                          </a>
                        </td>                     
                        <td class="content_box_menber_company">
                           <table>
                              <tr>
                                 <td class="menber_company_name">
                                    <?php 
                                    $member_name ="";
                                    if(isset($list_last_present_users[$i]['name']))
                                    {
                                       $member_name = $list_last_present_users[$i]['name']." ".$list_last_present_users[$i]['last_name'];
                                    }
                                    else
                                    {
                                       $member_name = $list_last_present_users[$i]['email'];
                                    }
                                    echo $member_name;
                                    ?> 
                                 </td>
                              </tr>
                              <tr>
                                 <td  class="list_pm_item_sub_text2 item_sub_role">
                                    <?php echo $list_last_present_users[$i]['role']?>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="item_times">                                    
                                    <span class="item_sub_time_in">
                                    <?php 
                                    list($max_date, $max_time) = explode(" ", $list_last_present_users[$i]['max_time_in']);                                    
                                    
                                    if(strtotime($current_date)==strtotime($max_date))
                                    {
                                       echo $max_time;
                                    }
                                    else if($current_week == get_week_number($max_date))
                                    {
                                       echo call_user_func('get_day_literal_'.$language, $max_date)." ".$max_time." ";
                                    }
                                    else 
                                    {
                                       echo call_user_func('get_date_literal_'.$language, $max_date)." ".$max_time." ";
                                    }
                                    ?>
                                    </span>
                                    <span class="item_sub_time_total">
                                       <?php
                                       echo "/ ".$list_last_present_users[$i]['total_hours']." Hrs.";
                                       ?>
                                    </span>
                                 </td>                              
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
               </div>
            <?php
            $k++;
            
         }?>
         </td>
      </tr>
   </table>