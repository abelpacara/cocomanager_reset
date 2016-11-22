<script>   
$(document).ready( function(){  
    $(".delete_user").hover(     
     function () {      
      var id_deleter = $(this).attr('id');      
      $('#row_user_'+id_deleter).addClass("over_row_time");
     },
     function () {
       var id_deleter = $(this).attr('id');
       $('#row_user_'+id_deleter).removeClass("over_row_time");       
     }
    );
});
</script>
<h1 class="enqueue_by_right"><?php echo $view_labels['title_form'];?></h1>
<?php
if($has_privilege_add_member)
{
?>
   <span class="pm_link_button enqueue_by_left">
      <a href="<?php echo site_url("pm/add_member?project_id=".$project_id)?>">
         <?php echo $view_labels['link_add_member'];?>
      </a>
   </span>
<?php
}?>

<div class="content_box" style="overflow: auto; width:100%;">  
   <?php
   if(isset($list_project_peoples) AND count($list_project_peoples)>0)
   {
      $quantity_columns = 6;
      $quantity_columns += 1;
      $role = $list_project_peoples[0]['role'];           
   ?>   
   <h3 class="clear_both title_team_present"><?php echo $role ?></h3>
   <?php 
   
      for ( $i=0; $i<count($list_project_peoples); $i++)
      {
           $style_enqueue_right="";

            if(( ($i+1) % $quantity_columns ) > 0 )
            {
               $style_enqueue_right=" style='float:left' ";
            }
            else
            {
                $style_enqueue_right =" style='clear:left; float:left' ";
            }
            
            if(strcasecmp($role, $list_project_peoples[$i]['role'])!=0)
            {?>
               <h3 class="clear_both title_team_present"><?php echo $list_project_peoples[$i]['role']?></h3>
               <?php
               $role = $list_project_peoples[$i]['role'];
            }

            $style_present = " user_present ";                  
            /*
            $style_present = " user_absent ";
            if(strcasecmp($list_project_peoples[$i]['is_present'],"1")==0)
            { 
            }*/



            ?>
           <div class="presence_person <?php echo $style_present?>" <?php echo $style_enqueue_right?> >
              <?php
                  $url_picture = '.'.$uri_images_users.'/'.$list_project_peoples[$i]['user_id'].'_thumb_small.jpg';

                  if( ! file_exists($url_picture))
                  {
                     $url_picture = 'public/images/user_present.jpg';
                  }
                  ?>
                  <div class="presence_person_picture">
                     <a href="<?php echo site_url("auth/public_profile?user_id=".$list_project_peoples[$i]['user_id'])?>">
                        <img src="<?php echo site_url($url_picture);?>"/>
                     </a>
                  </div>
                  <?php
                    $owner_show_name ="";

                    if(isset($list_project_peoples[$i]['name']))
                    {
                       $owner_show_name = $list_project_peoples[$i]['name']."<br/> ".$list_project_peoples[$i]['last_name'];
                    }
                    else
                    {
                       $owner_show_name = $list_project_peoples[$i]['email'];
                    }         
                    $url_profile_user = site_url("/auth/owner_profile?owner_id=".$list_project_peoples[$i]['user_id']);

                   ?>
                  <div class="user_name">                   
                         <?php echo $owner_show_name;?>                
                  </div>
                  <div class="last_time_user" style="text-align:center">                 
                       <?php echo $list_project_peoples[$i]['email'];?>                 
                  </div>
            </div>   
            <?php
         }
   }
   ?>
</div>