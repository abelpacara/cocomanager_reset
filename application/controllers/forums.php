<?php
class Forums extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
      
      $this->load->library('session');
      $this->load->library('tank_auth');
      
      $this->load->helper('my_dates_helper');
      $this->load->helper('my_messages_helper');
      
      $this->load->helper('my_toolkits_helper');
      
      $this->load->model('model_template');      
      $this->load->model('model_forums');      
      $this->load->model('tank_auth/users');
            
      $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
   }
   #############################################################################
   function index()
   {  
      $this->forums();
   }
   #############################################################################
   public function posts()
   {
      $view_data = array();
      $view_data['is_logged_in'] = $is_logged_in = $this->tank_auth->is_logged_in();      
      $is_staff_user = null;
      if($is_logged_in == 1)
      {
         $is_staff_user = $this->model_forums->is_staff_user($this->session->userdata('user_id'));
         $view_data['is_staff_user'] = $is_staff_user;
      }
      
      $array_messages = null;
            
      if(isset($_REQUEST['topic_id']))
      {
         $header_data['forum_id'] = $view_data['forum_id'] = $forum_id = $_REQUEST['forum_id'];
         
         $topic_id = $_REQUEST['topic_id'];   
         
         $this->form_validation->set_rules('description', 'Descripcion', 'trim|required');
         
         if($this->form_validation->run() AND isset($_POST['add']) AND  $is_logged_in==1)
         {
            $data = array(         
                 'tf_topic_id'=>$topic_id,
                 'user_id'=>$this->session->userdata('user_id'),
                 'description'=>$_POST['description'],  
               'status_selectable_id'=> $this->model_template->get_id_selectable_by("tf_posts", "status", "active"),
            );

            $this->model_forums->add_post($data);
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
         #----------------------------------------------------------------------         
         if(isset($_REQUEST['drop_post_id']) AND $is_logged_in==1 AND $is_staff_user == true)
         {
            $this->model_forums->drop_post($_REQUEST['drop_post_id']);
         }
         #----------------------------------------------------------------------         
         $list_posts = $this->model_forums->get_list_posts($topic_id);
         for($i=0; $i<count($list_posts); $i++)
         {
            $list_posts[$i]['is_staff'] = $this->model_forums->is_post_by_staff($list_posts[$i]['id_tf_post']);
         }
         $view_data['list_posts'] = $list_posts;
         $topic = $this->model_forums->get_topic($topic_id);            
         $topic['is_staff'] = $this->model_forums->is_topic_by_staff($topic_id);            
         $view_data['topic'] = $topic;
                  
         #----------------------------------------------------------------------
         
         $header_data['topic_id'] = $view_data['topic_id']=$topic_id;  
         $forum = $this->model_forums->get_forum($forum_id);
         $header_data['topic_title'] = $topic['title'];
         $header_data['forum_title'] = $forum['title'];
         #----------------------------------------------------------------------
      }
      else
      {
         $array_messages[] = "No ha seleccionado algun Topico";
      }
      
      
      $this->load->view('tf/header', $header_data);
      
      $view_data['array_messages']=$array_messages;
      $this->load->view('forums/posts', $view_data);
      $this->load->view('tf/footer');
   }
   #############################################################################
   public function topics()
   {
      $view_data = array();      
      $header_data = array();      
      
      $view_data['is_logged_in'] = $is_logged_in = $this->tank_auth->is_logged_in();
      $is_staff_user = null;
      if($is_logged_in == 1)
      {
         $is_staff_user = $this->model_forums->is_staff_user($this->session->userdata('user_id'));
         $view_data['is_staff_user'] = $is_staff_user;
      }
      
      
      
      $array_messages = null;
      
      if(isset($_REQUEST['forum_id']))
      {
         $forum_id = $_REQUEST['forum_id'];
         
         $header_data['forum_id'] = $view_data['forum_id'] = $forum_id;      
                  
         $this->form_validation->set_rules('title', 'Titulo', 'trim|required');
         $this->form_validation->set_rules('description', 'Descripcion', 'trim|required');

         if($this->form_validation->run() AND isset($_POST['add']))
         {
            $data = array(
                 'tf_forum_id'=>$forum_id,
                 'user_id'=>$this->session->userdata('user_id'),
                 'title'=>$_POST['title'],            
                 'description'=>$_POST['description'],
                 'status_selectable_id'=> $this->model_template->get_id_selectable_by("tf_topics", "status", "active"),
            );
            $this->model_forums->add_topic($data);
         }
         else
         {
            $str_validation = validation_errors();
            if(strcasecmp(trim($str_validation),"")!=0)
            {
               $array_messages[] = $str_validation;
            }
         }
         #----------------------------------------------------------------------
         
         #----------------------------------------------------------------------         
         if(isset($_REQUEST['drop_topic_id']) AND $is_logged_in==1)
         {
            $this->model_forums->drop_topic($_REQUEST['drop_topic_id']);
         }
         #----------------------------------------------------------------------
         $list_topics = $this->model_forums->get_list_topics($forum_id);
         
         
         for($i=0;$i<count($list_topics);$i++)
         {
            $list_topics[$i]['count_posts'] = $this->model_forums->get_count_posts_by_topic($list_topics[$i]['id_tf_topic']);
            $list_topics[$i]['last_post'] = $this->model_forums->get_last_post_by($forum_id, $list_topics[$i]['id_tf_topic']);

            $list_topics[$i]['is_staff'] = $this->model_forums->is_topic_by_staff($list_topics[$i]['id_tf_topic']);
         }
         if(count($list_topics)<=0)
         {
            $array_messages[] = "En este momento, no hay temas en este foro";            
         }
         
         $view_data['list_topics'] = $list_topics;
         
         $view_data['forum'] = $forum = $this->model_forums->get_forum($forum_id);
         #----------------------------------------------------------------------
         $header_data['forum_title'] = $forum['title'];
         #----------------------------------------------------------------------
      }
      else
      {
         $array_messages[] = "No ha seleccionado algun Foro";
      }
      
      $this->load->view('tf/header', $header_data);
      
      $view_data['array_messages'] = $array_messages;
      $this->load->view('forums/topics', $view_data);
      $this->load->view('tf/footer');
   }
   #############################################################################
   public function forums()
   {
      $view_data['list_categories'] = $list_categories = $this->model_forums->get_list_categories();
      $list_forums=array();
      for($i=0;$i<count($list_categories);$i++)
      {
         $list_forums_by_category = $this->model_forums->get_list_forums($list_categories[$i]['id_tf_category']);
         
         if(!empty($list_forums_by_category) AND count($list_forums_by_category)>0)
         {
            for($j=0; $j<count($list_forums_by_category); $j++)
            {
               $list_forums_by_category[$j]['count_topics'] = $this->model_forums->get_count_topics_by_forum($list_forums_by_category[$j]['id_tf_forum']);
               $list_forums_by_category[$j]['count_posts'] = $this->model_forums->get_count_posts_by_forum($list_forums_by_category[$j]['id_tf_forum']);
               
               $list_forums_by_category[$j]['last_post'] = $this->model_forums->get_last_post_by($list_forums_by_category[$j]['id_tf_forum']);
            }
            $list_forums[$list_categories[$i]['id_tf_category']] = $list_forums_by_category;
         }
      }
      $view_data['list_forums'] = $list_forums;
      
      $this->load->view('tf/header');
      $this->load->view('forums/forums', $view_data);
      $this->load->view('tf/footer');
   }
   #############################################################################
   public function add_category()
   {
      if(isset($_POST['add']))
      {
         $data = array(         
              'title'=>$_POST['title'],
              'description'=>$_POST['description'],              
         );
         
         $this->model_forums->add_category($data);
      }
      
      $this->load->view('tf/header');
      $this->load->view('forums/add_category');
      $this->load->view('tf/footer');
   }
   #############################################################################
   public function add_forum()
   {
      if(isset($_GET['add_forum']))
      {
         $data = array(         
              'title'=>'',
              'description'=>'',
              'tf_category_id'=>$_POST['category_id']
         );
         
         $this->model_forums->add_forum($data);
      }
      
      $view_data['list_categories'] = $list_categories = $this->model_forums->get_list_categories();
      
      
      $this->load->view('tf/header');
      $this->load->view('forums/add_forum', $view_data);
      $this->load->view('tf/footer');
   }
}