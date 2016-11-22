<?php
################################################################################
if(!function_exists('in_array_column'))
{
   function decode_chars_special($info)
	{
		 $info = mb_convert_encoding($info, "HTML-ENTITIES", "UTF-8");
       //$info = mb_convert_encoding($info, "HTML-ENTITIES", "ASCII");
      
      //$info = mb_convert_encoding($info, "HTML-ENTITIES", "ASCII");      
      //$info = preg_replace('~^(&([a-zA-Z0-9]);)~',htmlentities('${1}'),$info);
		
      return($info);
	} 
}
################################################################################
if(!function_exists('in_array_column'))
{
   function in_array_column($needle, $array, $name_column)
   {
      for($i=0; $i<count($array);$i++)
      {
         if(strcasecmp($array[$i][$name_column],$needle)==0)
         {
            return true;
         }
      }
      return false;
   }   
}

################################################################################
if(!function_exists('create_breadcrumb'))
{
	function get_breadcrumb()
	{
		  $ci = &get_instance();
		  $i=1;
		  $uri = $ci->uri->segment($i);
        
        $total_segments = $ci->uri->total_segments();
        
        
        
		  $link ="<ul><li class='enqueue_by_right'><a href='".site_url()."' class='begin_breadcrumb_link'>Dashboard</a></li>&nbsp;";
		 
		  while($uri != '')
        {
		     $prep_link = '';
           for($j=1; $j<=$i;$j++)
           {
             $prep_link .= $ci->uri->segment($j)."/";
           }

           if($ci->uri->segment($i+1) == '')
           {
             $link.="<li class='enqueue_by_right'>&nbsp;<span class='arrow_breadcrumb'>&gt;</span>&nbsp;<a class='link_breadcrumb_end' href='".site_url($prep_link)."'>".$ci->uri->segment($i)."</a></li> ";
           }
           else
           {
              $style_link="link_breadcrumb";


              $link.="<li class='enqueue_by_right'>&nbsp;<span class='arrow_breadcrumb'>&gt;</span>&nbsp;<a class='".$style_link."' href='".site_url($prep_link)."'>".$ci->uri->segment($i)."</a></li> ";
           }

           $i++;
           $uri = $ci->uri->segment($i);
		  }
        
		  $link .= '</ul>';
        
		  return $link;
  }
}