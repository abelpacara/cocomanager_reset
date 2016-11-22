<div id="header_title_wrapper">			
   <div id="header_title_content">
      <h1> Indice de foros </h1>
   </div>		
</div>
<?php
foreach($list_categories AS $category)
{
   if(! empty($list_forums[$category['id_tf_category']]))
   {   
      ?>
      <h2>
      <?php
      echo $category['title'];
      ?>
      </h2>
      <table class="data_table">      
      <?php
      $i=0;
      foreach($list_forums[$category['id_tf_category']] AS $forum)
      {
         $last_post = $forum['last_post'];
         
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
         
         $url_topics = base_url()."forums/topics/?forum_id=".$forum['id_tf_forum'];
         
         ?> 
         <tr class="<?php echo $style_row?>">
            
            <td class="forum_icon align_top">
               <a href="<?php echo $url_topics?>">
                  <img src="<?php echo base_url()?>/public/images/forums/forum_icon.gif"/>
               </a>
            </td>
            <td class="forum_title">
               <a href="<?php echo $url_topics?>"><?php echo $forum['title']?></a>
               <br/>
               <span class="forum_description"><?php echo nl2br(decode_chars_special($forum['description']))?></span>
            </td>
            <td class="forum_stats"><?php echo $forum['count_topics']?> Temas / <?php echo $forum['count_posts']?> mensajes</td>
            <td class="forum_last_post">
            <?php
            if( ! empty($last_post))
            {
               ?>
               Ultimo mensaje en:
               <a href="<?php echo base_url()."forums/posts/?topic_id=".$last_post['tf_topic_id']."&forum_id=".$forum['id_tf_forum']?>"><?php echo $last_post['topic_title'];?></a>,
               publicado el <?php echo get_date_literal_spanish($last_post['register_date']) ?> por 
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
               
               if($last_post['is_staff']==true)
               {
                  $publisher = $publisher." ".$staff;
               }
               if(isset($last_post['user_email']))
               {
                  //echo '<a href="mailto:'.$last_post['user_email'].'">'.$publisher.'</a>';
                  echo $publisher;
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
}
?>
