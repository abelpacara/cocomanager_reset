<?php
class Model_forums extends Model_Template
{
   function __construct()
   {
       parent::__construct();
       $this->load->helper('my_dates_helper');
       $this->db->query("SET SESSION time_zone='-4:00'");
   }
   #############################################################################
   function drop_post($post_id)
   {
      $data=array('status_selectable_id'=> $this->model_forums->get_id_selectable_by("tf_posts", "status", "inactive"));      
      $this->db->update("tf_posts",$data, array("id_tf_post"=>$post_id));      
   }
   #############################################################################
   function drop_topic($topic_id)
   {
      $data_post=array('status_selectable_id'=> $this->model_forums->get_id_selectable_by("tf_posts", "status", "inactive"));      
      $this->db->update("tf_posts", $data_post, array("tf_topic_id"=>$topic_id));
      
      $data=array('status_selectable_id'=> $this->model_forums->get_id_selectable_by("tf_topics", "status", "inactive"));      
      $this->db->update("tf_topics",$data, array("id_tf_topic"=>$topic_id));      
      
   }
  
   #############################################################################
   function is_staff_user($user_id)
   {
      $sql="SELECT COUNT(*) AS count
            FROM
               roles ro,
               user_roles ur
            WHERE ur.user_id ='".$user_id."'
              AND lower(ro.role_type)='ceo'
              AND ur.role_id = ro.id;";
            
      $query_count = $this->db->query($sql);
      $row = $query_count->row_array();      
      return $row['count']>0;
   }
   
   #############################################################################
   function is_topic_by_staff($topic_id)
   {
      $sql="SELECT COUNT(*) AS count
            FROM
               tf_topics top,
               roles ro,
               user_roles ur
            WHERE top.id_tf_topic ='".$topic_id."'
               AND lower(ro.role_type)='ceo'
               AND ur.role_id = ro.id
               AND ur.user_id = top.user_id;";
            
      $query_count = $this->db->query($sql);
      $row = $query_count->row_array();      
      return $row['count']>0;
   }
   #############################################################################
   function is_post_by_staff($post_id)
   {
      $sql="SELECT COUNT(*) AS count
            FROM
               tf_posts pos,
               roles ro,
               user_roles ur
            WHERE pos.id_tf_post ='".$post_id."'
               AND lower(ro.role_type)='ceo'
               AND ur.role_id = ro.id
               AND ur.user_id = pos.user_id;";
      
      
      $query_count = $this->db->query($sql);
      $row = $query_count->row_array();
      
      return $row['count']>0;
   }
   
   #############################################################################
   function get_last_post_by($forum_id, $topic_id=null)
   {  
      $sql_topic="";
      
      $status_active_post_selectable_id = $this->model_forums->get_id_selectable_by("tf_posts", "status", "active");
      $status_active_topic_selectable_id = $this->model_forums->get_id_selectable_by("tf_topics", "status", "active");
      
      if(isset($topic_id))
      {
         $sql_topic = " AND pos.tf_topic_id = '".$topic_id."'";
      }
      
      
      $sql="SELECT
               pos.id_tf_post,
               pos.tf_topic_id,
               pos.user_id,
               CAST(pos.register_date AS date) AS register_date,
               top.title AS topic_title
             FROM tf_posts pos, 
                  tf_topics top
             WHERE top.id_tf_topic = pos.tf_topic_id
               AND top.tf_forum_id = '".$forum_id."'
               AND top.status_selectable_id=".$status_active_topic_selectable_id."
               AND pos.status_selectable_id=".$status_active_post_selectable_id."
               ".$sql_topic."
             ORDER BY pos.register_date DESC LIMIT 1;";
      
      
      $query_post = $this->db->query($sql);
      $post = $query_post->row_array();
      
      if(count($post)>0)
      {
         $topic = $this->get_topic($topic_id);
         if(count($topic)>0)
         {
            $post['topic_title'] = $topic['title'];      
            //$post['topic_description'] = $topic['description'];
         }

         if(isset($post['user_id']) AND $post['user_id']>0)
         {
            $sql = "SELECT COUNT(*) count_ceo
                    FROM user_roles ur, roles ro
                    WHERE  ur.role_id = ro.id
                       AND ur.user_id = ".$post['user_id']."
                       AND lower(ro.role_type) = lower('CEO');";

            $query_role = $this->db->query($sql);      
            $role = $query_role->row_array();

            if($role['count_ceo']>0)
            {
               $post['is_staff'] = true;
            }
            else
            {
               $post['is_staff'] = false;
            }


            $sql = "SELECT us.*,
                        up.country,
                        up.website,
                        up.name,
                        up.last_name
                 FROM users us, 
                      user_profiles up 
                 WHERE us.id='".$post['user_id']."'
                   AND us.id=up.user_id;";


            $query_user = $this->db->query($sql);
            $user = $query_user->row_array();     

            $post['user_username'] = $user['username'];
            $post['user_name'] = $user['name'];
            $post['user_last_name'] = $user['last_name'];
            $post['user_email'] = $user['email'];
         }

         return $post;
      }
      return null;
   }
   #############################################################################
   function get_topic($topic_id)
   {  
      $sql="SELECT 
                  top.id_tf_topic,
                  top.tf_forum_id,
                  top.title,
                  top.description,
                  top.register_date,
                  CONCAT(up.name,' ', up.last_name) AS user_name

                 FROM tf_topics top, user_profiles up
                 WHERE id_tf_topic ='".$topic_id."'
                   AND up.user_id = top.user_id
                 ORDER BY register_date ASC;";
      
      
      $query = $this->db->query($sql);
      return $query->row_array();
   }
   #############################################################################
   function get_forum($forum_id)
   {
      $sql_last="SELECT 
                  id_tf_forum,
                  tf_category_id,
                  title,
                  description,
                  order_by
                 FROM tf_forums
                 WHERE id_tf_forum ='".$forum_id."';";
      $query = $this->db->query($sql_last);      
      return $query->row_array();
   }
   #############################################################################
   function add_post($data)
   {
      $this->db->insert("tf_posts",$data);      
      return $this->db->insert_id();
   }
   #############################################################################
   function add_topic($data)
   {
      $this->db->insert("tf_topics",$data);      
      return $this->db->insert_id();
   }
   #############################################################################
   function add_category($data)
   {
      $sql_max = "SELECT max(order_by) AS order_by FROM tf_categories;";      
      $query = $this->db->query($sql_max);
      $row = $query->row_array();
      
      $this->db->set('order_by', $row['order_by']+1);      
      $this->db->insert("tf_categories",$data);      
      return $this->db->insert_id();
   }
   #############################################################################
   function add_forum($data)
   {
      $sql_max = "SELECT max(order_by) AS order_by FROM tf_forums;";      
      $query = $this->db->query($sql_max);
      $row = $query->row_array();
      
      $this->db->set('order_by', $row['order_by']+1);      
      $this->db->insert("tf_forums",$data);      
      return $this->db->insert_id();
   }
   #############################################################################
   function get_list_posts($topic_id)
   {  
      $status_active_selectable_id = $this->model_forums->get_id_selectable_by("tf_posts", "status", "active");
      
      $sql_last="SELECT 
                  pos.id_tf_post,
                  pos.tf_topic_id,                  
                  pos.description,
                  pos.register_date,
                  
                  us.email AS user_email,
                  CONCAT(up.name,' ',up.last_name) AS user_name,
                  up.last_name AS user_last_name

                 FROM tf_posts pos,
                      user_profiles up,
                      users us                      
                 WHERE pos.tf_topic_id ='".$topic_id."'
                  AND us.id = pos.user_id
                  AND us.id = up.user_id
                  AND pos.status_selectable_id = ".$status_active_selectable_id."
                 ORDER BY register_date ASC;";
      
      $query = $this->db->query($sql_last);      
      return $query->result_array();
   }
   #############################################################################
   function get_count_posts_by_topic($topic_id)
   {
      $status_active_selectable_id = $this->model_forums->get_id_selectable_by("tf_posts", "status", "active");
      
      $sql="SELECT COUNT(*) AS count_posts
                 FROM tf_posts
                 WHERE tf_topic_id ='".$topic_id."'
                    AND status_selectable_id=".$status_active_selectable_id.";";
      
      $query = $this->db->query($sql);      
      $row = $query->row_array();
      
      return $row['count_posts'];
   }
   #############################################################################
   function get_count_posts_by_forum($forum_id)
   {
      $status_active_selectable_id = $this->model_forums->get_id_selectable_by("tf_posts", "status", "active");
      
      $sql="SELECT COUNT(*) AS count_posts
                 FROM tf_topics top, tf_posts pos
                 WHERE top.id_tf_topic = pos.tf_topic_id
                   AND top.tf_forum_id ='".$forum_id."'
                   AND pos.status_selectable_id=".$status_active_selectable_id.";";
      
      $query = $this->db->query($sql);      
      $row = $query->row_array();
      
      return $row['count_posts'];
   }
   
   #############################################################################
   function get_count_topics_by_forum($forum_id)
   {
      $status_active_selectable_id = $this->model_forums->get_id_selectable_by("tf_topics", "status", "active");
      
      $sql="SELECT COUNT(*) AS count_topics
                 FROM tf_topics
                 WHERE tf_forum_id ='".$forum_id."'
                   AND status_selectable_id =".$status_active_selectable_id.";";
      
      $query = $this->db->query($sql);
      $row = $query->row_array();
      
      return $row['count_topics'];
   }
   
   #############################################################################
   function get_list_topics($forum_id)
   {  
      $status_active_selectable_id = $this->model_forums->get_id_selectable_by("tf_topics", "status", "active");
      $sql_last="SELECT 
                  top.id_tf_topic,
                  top.tf_forum_id,
                  top.title,
                  top.description,
                  top.register_date,

                  us.email AS user_email,
                  up.name AS user_name,
                  up.last_name AS user_last_name

                 FROM tf_topics top,
                   user_profiles up,
                        users us 

                 WHERE tf_forum_id ='".$forum_id."'
                   AND us.id = top.user_id
                   AND us.id = up.user_id
                   AND top.status_selectable_id = ".$status_active_selectable_id."
                   ;";
      
      $query = $this->db->query($sql_last);      
      return $query->result_array();
   }
   #############################################################################
   function get_list_forums($category_id)
   {
      $sql_last="SELECT 
                  id_tf_forum,
                  tf_category_id,
                  title,
                  description,
                  order_by
                 FROM tf_forums
                 WHERE tf_category_id ='".$category_id."';";
      $query = $this->db->query($sql_last);      
      return $query->result_array();
   }
   #############################################################################
   function get_list_categories()
   {
      $sql_last="SELECT 
                  id_tf_category,
                  title,
                  description,
                  order_by
                 FROM tf_categories;";
      $query = $this->db->query($sql_last);      
      return $query->result_array();
   }
}
?>
