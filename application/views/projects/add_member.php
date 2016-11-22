<script>
$(document).ready(function(){
   
   $(".selector").click(function () {
      
      var id = $(this).attr('id');   
      
      if($("#"+id).is(':checked')) { 
         $('#selected_'+id).removeClass("pm_opacity_user_member");
      }
      else
      {
         $('#selected_'+id).addClass("pm_opacity_user_member");   
      }
   });
});
</script>
<h1><?php echo $view_labels['title_form']?></h1>

<?php 
if(!empty($list_membership))
{
   $quantity_columns = 6;
   $quantity_columns += 1;
   $role = $list_membership[0]['role'];  
   echo form_open($this->uri->uri_string());
?>
<div class="content_box" style="overflow: auto; width:100%;">  
   <input type="hidden" name="quantity_membership" value="<?php echo count($list_membership)?>"/>
      <?php  
      $k=0;
      for($i=0; $i<count($list_membership); $i++)
      {
         ?>
        <h3 class="clear_both title_team_present"><?php echo $role ?></h3>      
         
               <?php
               $j=0;
               for($j = $i; $j<count($list_membership) AND (strcasecmp($role, $list_membership[$j]['role'])==0); $j++)
               {
                  $owner_show_name ="";
                  if(isset($list_membership[$j]['name']))
                  {    
                  
                     $owner_show_name = $list_membership[$j]['name']."<br/> ".$list_membership[$j]['last_name'];               
                  }
                  else
                  {
                     $owner_show_name = $list_membership[$j]['email'];
                  }
                  ?> 
                 
                   <?php $style_enqueue_right="";

                  if(( ($j+1) % $quantity_columns ) > 0 )
                  {
                     $style_enqueue_right=" style='float:left' ";
                  }
                  else
                  {
                      $style_enqueue_right =" style='clear:left; float:left' ";
                  }
                  if(strcasecmp($role, $list_membership[$i]['role'])!=0)
                  {?>
                     <h3 class="clear_both title_team_present"><?php echo $list_membership[$i]['role']?></h3>
                     <?php
                     $role = $list_membership[$i]['role'];
                  }
                  
                  $assigned = "";
                  $style_assigned = " pm_opacity_user_member ";
                                    
                  if(isset($list_membership[$j]['is_member']) AND strcasecmp($list_membership[$j]['is_member'],"")!=0)
                  {
                     $assigned = " checked='yes' ";
                     $style_assigned = "  ";                     
                  }
                  
                  $style_present = " user_present ";
                  /*
                  $style_present = " user_absent ";
                  if(strcasecmp($list_membership[$j]['is_present'],"1")==0)
                  {
                     $style_present = " user_present ";                  
                  }*/
                  ?>       
                     <div id="selected_<?php echo $k?>" 
                          class="selectable presence_person 
                                 <?php echo $style_assigned?> 
                                 <?php echo $style_present?>
                                " 
                                 <?php echo $style_enqueue_right?>>
                        <input type="hidden" name="user_id_<?php echo $k?>" value="<?php echo $list_membership[$j]['user_id'];?>"/>     
                        
                        <?php
                        if( strcasecmp( $list_membership[$j]['user_id'], $project['user_id'])!=0 AND strcasecmp( $list_membership[$j]['role_type'],"CEO")!=0)
                        {
                           if(isset($list_membership[$j]['is_member']) AND strcasecmp($list_membership[$j]['is_member'],"1")==0)
                           {
                           ?>
                              <input type="hidden" name="user_id_<?php echo $k?>_member" value="<?php echo $list_membership[$j]['user_id'];?>"/>     
                           <?php
                           }
                           else
                           {?>
                              <input type="hidden" name="user_id_<?php echo $k?>_not_member" value="<?php echo $list_membership[$j]['user_id'];?>"/>     
                              <?php
                           }
                           ?>
                           <input type="hidden" name="role_type_<?php echo $k?>" value="<?php echo $list_membership[$j]['role_type'];?>"/>     
                           <input type="checkbox" class="selector" id="<?php echo $k?>" style="float:right; margin:0px;" name="user_id_<?php echo $k?>_is_checked" value="<?php echo $list_membership[$j]['user_id'];?> "                               
                           <?php
                              echo $assigned;
                           ?>
                           />
                        <?php 
                        }
                    $url_picture = '.'.$uri_images_users.'/'.$list_membership[$j]['user_id'].'_thumb_small.jpg';

                  if( ! file_exists($url_picture))
                  {
                     $url_picture = 'public/images/user_present.jpg';
                  }
                  ?>
                        <div class="presence_person_picture">
                           <a href="#"><img src="<?php echo site_url($url_picture);?>"/></a>
                        </div>                          
                        <div class="user_name" style="text-align:center">
                           <?php echo $owner_show_name; ?>
                        </div>
                        <div class="last_time_user" style="text-align:center">                 
                            <?php echo $list_membership[$j]['email'];?>                 
                        </div>
                        
                     </div>                  
                  <?php
                  $k++;
               }
               if($j<count($list_membership))
               {
                  $role = $list_membership[$j]['role'];                                       
               }
               $i = $j-1;
            }?>            
         <div style="clear:both">
            <input type="hidden" value="<?php echo $project_id?>" name="project_id"/>            
            <input type="submit" value="<?php echo $view_labels['save']?>" name="save"/>            
         </div>   
  </div>
  </form>
  <?php
}?>