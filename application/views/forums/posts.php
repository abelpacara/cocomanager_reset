<div id="content_wrapper">			
   
   <div id="content_content">
      
      <div id="header_title_wrapper">			
         <div id="header_title_content">
            <h1> <?php echo $topic['title']?> </h1><!-- <div class="pages_menu">1</div> <div class="pages_menu">2</div> <div class="pages_menu">3</div> -->
         </div>
      </div>		
      

      <?php
      display_messages($array_messages);      
      ?>
      <div class="post_user_first">
         <table class="post_table">
            <tr>
               <td class="post_table_name">                     
                  <span class="publisher">
                     <?php echo $topic['user_name']?>
                     <?php
                     if($topic['is_staff']==1)
                     {?>
                        <span class="staff">staff</span>
                        <?php
                     }
                     ?>
                  </span>                  
               </td>
               <td class="post_table_date">
                  <?php 
                  echo get_date_literal_spanish($topic['register_date']);
                  ?>                  
               </td>
               <td class="post_table_number">#1</td>		
            </tr>
         </table>
      </div>
      <div class="post_description_first">         
         <?php echo nl2br($topic['description']);          
         if($is_logged_in==1 AND $is_staff_user==true)
         {
         ?>
            <div class="align_right" style="clear: both">
               <a href="<?php echo base_url()."forums/topics/?drop_topic_id=".$topic['id_tf_topic']."&topic_id=".$topic_id."&forum_id=".$forum_id?>">
                  <div class="icon_delete"></div>
               </a>
            </div>
            <br/>            
         <?php
         }?>
      </div>      
   </div>
   
   <?php
   $i=1;
   foreach($list_posts AS $post)
   {
      $i++;
      ?>
         <div class="post_user">
         <table class="post_table">
            <tr>
               <td class="post_table_name">                     
                  <span class="publisher">
                     <?php echo $post['user_name']?>
                  </span>
                  <?php
                  if($post['is_staff']==1)
                  {?>
                     <span class="staff">staff</span>
                     <?php
                  }
                  ?>
               </td>
               <td class="post_table_date">
                  <?php 
                  echo get_date_literal_spanish($post['register_date']);
                  ?>
               </td>
               <td class="post_table_number">#<?php echo $i?></td>		
            </tr>
         </table>
         </div>
         <div class="post_description">
            <?php echo nl2br(decode_chars_special($post['description'])); 
            if($is_logged_in==1 AND $is_staff_user==true)
            {
            ?>
               <div class="align_right">
                  <a href="<?php echo base_url()."forums/posts/?drop_post_id=".$post['id_tf_post']."&topic_id=".$topic_id."&forum_id=".$forum_id?>">
                     <div class="icon_delete"></div>
                  </a>
               </div>
            <?php
            }?>
         </div>
         <!-- END POSTS -->
         <!-- POSTS -->
      <?php
   }


   if(isset($is_logged_in) AND $is_logged_in==1)
   {?>
      <br/>
      <h1>Agregar nuevo mensaje</h1>
      <br/>
      <br/>
      <table>
         <?php echo form_open($this->uri->uri_string()); ?>            
            <tr>
               <th class="label_field align_top">Descripcion:</th>
               <td>                  
                  <textarea name="description" class="width_field_forum" rows="5"></textarea>
                  <input type="hidden" name="topic_id" value="<?php echo $topic_id?>"/>
                  <input type="hidden" name="forum_id" value="<?php echo $forum_id?>"/>
               </td>
            </tr>
            <tr>
               <td></td>
               <td>
                  <input type="submit" value="Agregar" name="add"/>
               </td>
            </tr>
         </form>
      </table>
   <?php
   }
   ?>
   </div><!-- DIV CONTENT --> 
</div>
