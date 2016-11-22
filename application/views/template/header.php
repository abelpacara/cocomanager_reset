<?php $this->load->helper('url');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Company Control Manager</title>
<link href="<?php echo base_url(); ?>public/css/general.css" rel="stylesheet" type="text/css" />

 <link href="<?php echo base_url(); ?>public/datepicker1.8/css/ui-lightness/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css"/>
 <script src="<?php echo base_url(); ?>public/datepicker1.8/js/jquery-1.5.1.min.js"></script>
 <script src="<?php echo base_url(); ?>public/datepicker1.8/js/jquery-ui-1.8.14.custom.min.js"></script>

 
 <script src="<?php echo base_url(); ?>public/js/plugin_dropdown.js"></script>
 
 
 <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/stylesheets/jquery.megamenu.css" type="text/css" media="screen" />
 
 <link rel="shortcut icon" href="<?php echo base_url(); ?>public/images/favicon.ico" type="image/x-icon"/>
  
 
 <script src="<?php echo base_url(); ?>public/js/jquery.megamenu.js" type="text/javascript"></script>
    <script type="text/javascript">
      jQuery(function(){
        var SelfLocation = window.location.href.split('?');
        switch (SelfLocation[1]) {
          case "justify_right":
            jQuery(".megamenu").megamenu({ 'justify':'right'});
            break;
          case "justify_left":
          default:
            jQuery(".megamenu").megamenu();
        }
      });
      
      $(".menuitem").hover(              
        function () {           
         $(this).addClass("selected_menuitem");
        },
        function () {          
          $(this).removeClass("selected_menuitem");       
        }
       );
          
      /**************************************************************************/    
      $(document).ready(function(){
         $(".tr_row_table").click(function () {
            $(this).toggleClass("tr_row_table_highlight");          
         });
      });
      
      /*****************************************************************/
      function show_filesize_restrict(classname, maximum_size_mb, selector_current_filesizes)
      {
         $(classname).change( function() 
         {  
            var file_inputs = $(classname);
            var size_in_bytes = 0;

            var i=0;
            for(i=0; i<file_inputs.length;i++)
            {
               if($.browser.msie)
               {
                   var objFSO = new ActiveXObject("Scripting.FileSystemObject");
                   var sPath = $(classname)[i].value;
                   var objFile = objFSO.getFile(sPath);
                   size_in_bytes += objFile.size;
               }               
               else if(file_inputs[i].files[0]!=null)
               {
                  console.log(file_inputs[i].files[0].size);
                  size_in_bytes += file_inputs[i].files[0].size;
               }
            }

            var size_in_bytes_mb = size_in_bytes/(1024*1024);

            if( size_in_bytes_mb > maximum_size_mb )
            {
               alert('files sizes='+size_in_bytes_mb.toFixed(2)+'MB tried, limit='+maximum_size_mb+'MB exceeded');
               this.value='';
            }
            else
            {
               $(selector_current_filesizes).html(size_in_bytes_mb.toFixed(2)+"MB");
            }
         });
      }
      
 </script>

</head>
   
   
<body>
<?php
if(isset($list_privileges) AND count($list_privileges)>0)
{
?>
<div id="general_wrapper">
  <div id="header_wrapper">
    <div id="header">
      <div id="main_menu">
        <ul id="menu">
           <li>
              <?php
              $prefix_logo="default";   
              if(isset($company_logged) AND count($company_logged)>0 AND isset($company_logged['id']) )
              {
                  $prefix_logo = $company_logged['id'];
              }
              ?>
              <a href="<?php echo base_url(); ?>" id="logo_home">
               <img src="<?php echo site_url($uri_images_companies."/default_home-logo.jpg");?>"/>
              </a>
           </li>
           <?php
              $previous_priv_diff_module = $list_privileges[0];
              
              $style_class_name_module = strtolower( $previous_priv_diff_module['module_name'] );

              if( strcasecmp( $previous_priv_diff_module['visibility'], 'when_required') !=0 )
              {
                 if( strcasecmp($first_segment_uri, $previous_priv_diff_module['module_uri'])==0 )
                 {
                    $style_class_name_module .= "_selected";
                 }
                 ?>
                 <li class="<?php echo $style_class_name_module;?>">
                    <a href="<?php echo site_url($previous_priv_diff_module['module_uri']);?>/">
                       
                     <?php echo $header_view_labels['modules'][$previous_priv_diff_module['module_name']];?>
                    </a>
                 </li>
              <?php
              }
              
              
              
              for($i=1; $i<count($list_privileges); $i++)
              {
                 if( strcasecmp( $list_privileges[$i]['visibility'],'when_required') !=0 )
                 {
                    if( strcasecmp( $previous_priv_diff_module['module_name'], $list_privileges[$i]['module_name']) != 0 )
                    {
                       $previous_priv_diff_module = $list_privileges[$i];

                       $style_class_name_module = strtolower( $list_privileges[$i]['module_name'] );

                       if( strcasecmp($first_segment_uri, $list_privileges[$i]['module_uri'])==0 )
                       {
                          $style_class_name_module .= "_selected";
                       }

                       ?>
                       <li class="<?php echo $style_class_name_module;?>">
                          <a href="<?php echo site_url($list_privileges[$i]['module_uri']);?>/">
                           <?php echo $header_view_labels['modules'][$list_privileges[$i]['module_name']];?>
                          </a>
                       </li>
                    <?php
                    }
                 }
              }
           
           ?>
        </ul>
      </div>
      <div id="profile_menu">
         
        <div class="profile_name">             
           <a class="profile_link" href="<?php echo site_url('auth/edit_company')?>">
               <div id="company_logo" class="enqueue_by_right">             
                  <img src="<?php echo site_url($uri_images_companies."/".$prefix_logo."_header-logo.jpg");?>"/>
               </div>
               <?php echo $company_logged['name']?>
             </a>
        </div>
        
        <div class="profile_space"></div>
        
        <div class="profile_name">
             <a class="profile_link" href="<?php echo site_url('/auth/edit_profile');?>">                
               <?php echo $username?>
             </a>
        </div>
        
        <div class="profile_space"></div>
                
        <div class="profile_picture">
           <?php
           $url_picture = '.'.$uri_images_users.'/'.$user_id.'_thumb_small.jpg';
           
           if( ! file_exists($url_picture))
           {
              $url_picture = '.'.$uri_images_users.'/default.jpg';
           }
           ?>
           <a href="<?php echo site_url('/auth/edit_profile');?>">
            <img src="<?php echo site_url($url_picture);?>"/>
           </a>
        </div>
        <div class="profile_space"></div>
        <div class="profile_icon">
           
           <ul class="megamenu">
            <li>
              <a href="javascript:void(0)" alt="Settings" id="settings">                  
                  <img src="<?php echo base_url('/public').'/images/settings.png';?>" 
                                        />
              </a>
              
              <div style="width: auto">
               
                 <?php
                 $view_current_segment = $this->uri->segment(1)."/".$this->uri->segment(2);
                 
                 $array_tools_menu = array($header_view_labels['profile_edit']=>'/auth/edit_profile',
                                          'Logout'=>'/auth/logout');
               
                 foreach($array_tools_menu AS $key=>$value)
                 {
                    if(strcasecmp($view_current_segment,$value) ==0 )
                    {
                       $class_menu_item="selected_menuitem";    
                    }
                    else
                    {
                       $class_menu_item="unselected_menuitem";                    
                    }
                    ?>
                     <a class="<?php echo $class_menu_item?> menuitem" href="<?php echo site_url($value)?>">
                        <div class="content_menuitem"><?php echo $key?></div>
                     </a>
                     <div class="separator-submenu"></div>
                 <?php
                 }?>
              </div>
            </li>
     </ul> 
  </div>
        
      </div>
      <div id="clear"></div>
    </div>
  </div>
</div>
<div id="content_wrapper">
  <div id="content">
    <div id="section_menu">
      <div id="section_menu_buttons">
        <div class="button_red">
        <?php
        for( $i=0; $i<count($list_privileges); $i++ )
        {
           $parts_uri = explode("/", $list_privileges[$i]['uri']);

           if(strcasecmp($list_privileges[$i]['name'], "home")!=0 AND
              strcasecmp($first_segment_uri, $parts_uri[0])==0 AND
              strcasecmp($list_privileges[$i]['visibility'],'special') ==0 
             )
           {
              ?>
              <a href="<?php echo site_url($list_privileges[$i]['uri']);?>">
               <?php 
               
               if(strcasecmp(trim($list_privileges[$i]['icon_uri']),"")!=0)
               {
                  ?>
                 <img src="<?php echo site_url($this->config->item("uri_privilege_icons")."/".$list_privileges[$i]['icon_uri']);?>"/>
                 <?php
               }
               else
               {
                  echo $header_view_labels['privileges'][$list_privileges[$i]['uri']];
               }
               ?>
              </a>
              <?php
           }
        }
        ?>
        </div>
      </div>
      <div id="section_menu_options">
        <ul class="section_menu_options_list">
          <?php
          for($i=0;$i<count($list_privileges);$i++)
          {
             $part_uri = explode("/",$list_privileges[$i]['uri']);
             
             //echo "<br><BR>COMPARE TO ".$first_segment_uri." == ".$part_uri[0];
             
            if(strcasecmp($first_segment_uri,$part_uri[0])==0 AND 
               strcasecmp($list_privileges[$i]['visibility'],'in_menu') ==0
              )
            {?>
               <li>
                  <a href="<?php echo site_url($list_privileges[$i]['uri']);?>">
                  <?php
                  if(strcasecmp($list_privileges[$i]['icon_uri'],"")!=0)
                  {?>
                     <img src="<?php echo site_url($this->config->item("uri_privilege_icons")."/".$list_privileges[$i]['icon_uri']);?>"/>
                  <?php
                  }
                  else
                  {
                     echo $header_view_labels['privileges'][$list_privileges[$i]['uri']];
                  }
                  ?>
                  </a>
               </li>
            <?php
            }
            else
            {
               
               
            }
          }
          ?>
        </ul>
      </div>
      <div id="clear"></div>
    </div>
     <?php
}

################################################################################
################################################################################
################################################################################
if(isset($my_messages) AND strcasecmp(trim($my_messages),"")!=0)
{
  //echo $my_messages;
}

?>
<div id="breadcrumb">
   <?php
   echo get_breadcrumb();
   ?>
</div>
<?php     
//display_messages_alert($array_messages);
display_messages($array_messages);
?>
  