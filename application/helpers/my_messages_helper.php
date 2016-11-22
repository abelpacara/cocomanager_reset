<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

   function display_messages_alert($array_messages)
   {
      if(!empty($array_messages) AND count($array_messages)>0)
      {
         $any_message =false;         
         ob_start();
         ?>
         <div class='alert_box'>
            <ul>
            <?php
            foreach($array_messages AS $key=>$value)
            {
               if(strcasecmp(trim($value),"")!=0)
               {
                  $any_message = true;
                  ?>
                  <li class="item_list">
                     <?php
                  echo $value;
                  ?>
                  </li>
               <?php
               }
            }
            ?>
            </ul>
         </div>
         <?php
         $content_msg = ob_get_clean();
         if($any_message)
         {
            echo $content_msg;
         }
      }
   }
   
   function display_messages($array_messages)
   {
      if(! empty($array_messages) AND count($array_messages)>0)
      {?>
         <div class="message_alert">
         <?php
         foreach ($array_messages AS $key => $value)
         {
            if(is_array($value))
            {
               foreach ($value AS $value_key => $value_value)
               {
                  echo "<div>".$value_value."</div>";
               }
            }
            else
            {
               echo "<div>".$value."</div>";
            }
         }
         ?>
         </div>
         <br/>
         <?php
      }
   }
   
   #######################################################################################################################
   function get_legend_times()
   {?>
      <div>
         <fieldset class="panel_legend_times">
            <legend>Leyendas</legend>
            <div class="legend_time_width function_cross_week">Tiempo cruza el rango</div>
            <div class="legend_time_width  time_observed_link">Observado</div>
            <div class="legend_time_width  time_corrected_link">Corregido</div>
            <div class="legend_time_width  function_cross">Tiempo cruzado(erroneo)</div>
            <div class="legend_time_width ">Tiempo sin novedades</div>            
         </fieldset>
      </div>
   <?php
   }
   
   #######################################################################################################################
   function get_message_warning_without_view($str_message)
   {
      echo "<div class='alert_box'>".$str_message."</div>";
   }
   function get_message_warning($str_message)
   {
      return "<div class='info_box'>".$str_message."</div>";
   }
   #######################################################################################################################
   function get_message_information($str_message)
   {
      return "<div class='alert_box'>".$str_message."</div>";
   }
   
   function get_go_back_url($title, $personal_url_back=null)
   {
      $url_go_back="";
      if(isset($personal_url_back) AND strcasecmp($personal_url_back,"")!=0)
      {
         $url_go_back=$personal_url_back;         
      }
      else
      {
         //$url_go_back=$_SERVER['HTTP_REFERER'];
         $url_go_back="javascript:history.go(-1);";
      }
      return "<a href='".$url_go_back."'>".$title."</a>";
   }
?>
