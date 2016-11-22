<div id="content_wrapper">

   <div id="content_content">
      <div id="header_title_wrapper">			
         <div id="header_title_content">
            <h1> <?php echo $forum['title']?> </h1><!-- <div class="pages_menu">1</div> <div class="pages_menu">2</div> <div class="pages_menu">3</div> -->
         </div>
      </div>		
      
      <?php
      display_messages($array_messages);
      ?>
   </div>
      <?php
      if(count($list_topics)>0)
      {
      ?>
         <table class="data_table">
            <tr class="table_header">
               <td class="forum_icon"></td>
               <td class="forum_icon"></td>
               <td class="forum_title">Temas</td>
               <td class="forum_stats">Mensajes</td>
               <td class="forum_last_post">Ultimo mensaje</td>			
            </tr>
            <?php
            $i=0;
            foreach($list_topics AS $topic)
            {
               $last_post = $topic['last_post'];


               $style_row="";   
               if($i%2 == 0)
               {
                  $style_row = "odd";
               }
               else
               {
                  $style_row = "even";
               }
               $i++;

               $publisher_topic = "";
               $staff = "<span class='staff'>staff</span>";
               if(isset($topic['user_username']))
               {
                  $publisher_topic = $topic['user_username'];
               }
               else if(isset($topic['user_name']))
               {
                  $publisher_topic = $topic['user_name']." ".$topic['user_last_name'];
               }
               else
               {
                  $publisher_topic = "Guest";
               }
               $publisher_topic = "<span class='publisher'>".$publisher_topic."</span>";

               if(isset($topic['is_staff']) AND $topic['is_staff']==true)
               {
                  $publisher_topic = $publisher_topic." ".$staff;
               }            
               if(isset($topic['user_email']))
               {
                  //$publisher_topic =  mailto($topic['user_email'], $publisher_topic);
               }


               ?>
               <tr class="<?php echo $style_row?>">
                  <td class="forum_icon align_top">
                     <?php
                     if($is_logged_in==1 AND $is_staff_user==true)
                     {
                     ?>
                     <a href="<?php echo base_url()."forums/topics/?drop_topic_id=".$topic['id_tf_topic']."&forum_id=".$forum_id?>">
                        <div class="icon_delete"></div>
                     </a>
                     <?php
                     }?>
                  </td>
                  <?php
                  $url_posts = base_url()."forums/posts/?topic_id=".$topic['id_tf_topic']."&forum_id=".$forum_id;
                  ?>
                  <td class="forum_icon align_top"><a href="<?php echo $url_posts?>">
                        <img src="<?php echo base_url()?>/public/images/forums/forum_icon.gif"/></a></td>
                  <td class="topic_title">
                     <a href="<?php echo $url_posts?>">
                        <?php echo $topic['title']; ?>
                     </a>
                        <br /> por <?php echo $publisher_topic?>                     
                  </td>
                  <td class="topic_stats"><?php echo $topic['count_posts']; ?> mensajes</td>
                  <td class="topic_last_post">
                     <?php
                     if(isset($last_post['register_date']))
                     {
                        ?>
                        Ultimo mensaje el <?php echo get_date_literal_spanish($last_post['register_date']) ?> por 
                        <?php
                        $publisher = "";
                        $staff = "<span class='staff'>staff</span>";

                        if(isset($last_post['user_name']))
                        {
                           $publisher = $last_post['user_name']." ".$last_post['user_last_name'];
                        }
                        else if(isset($last_post['user_username']))
                        {
                           $publisher = $last_post['user_username'];
                        }
                        else
                        {
                           $publisher = "Guest";
                        }

                        $publisher = "<span class='publisher'>".$publisher."</span>";

                        if(isset($last_post['is_staff']) AND $last_post['is_staff']==true)
                        {
                           $publisher = $publisher." ".$staff;
                        }
                        if(isset($last_post['user_email']))
                        {
                           //echo  mailto($last_post['user_email'], $publisher);
                           echo  $publisher;
                        }
                        else
                        {
                           echo $publisher;
                        }
                     }
                     ?>
                  </td>
               </tr>
               <?php
            }
            ?>
         </table>
      <?php
      }
      
      if(isset($is_logged_in) AND $is_logged_in==1)
      {?>
      <br/>
      <h1>Agregar nuevo tema</h1>
      <br/>
      <br/>
      
         <table>
            <?php echo form_open($this->uri->uri_string()); ?>
               <tr>
                  <th class="label_field align_top">Titulo:</th>
                  <td>
                     <input type="text" name="title" class="width_field_forum"/>
                     <input type="hidden" name="forum_id" value="<?php echo $forum_id?>"/>
                  </td>
               </tr>
               <tr>
                  <th class="label_field align_top">Descripcion:</th>
                  <td>                  
                     <textarea name="description"  class="width_field_forum" rows="5"></textarea>
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
