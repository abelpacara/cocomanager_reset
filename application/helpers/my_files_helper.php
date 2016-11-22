<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  $test_array = array ('bla' => 'blub', 'foo' => 'bar',
         'another_array' => array ('stack' => 'overflow',), );
   echo array_to_xml($test_array, new SimpleXMLElement('<root/>'))->asXML();
 */
/************************************************************/
function file_append_contents($file, $content)
{
  $temp = file_get_contents($file);
  $content = $content.$temp;  
  file_put_contents($file, $content, FILE_APPEND);
}


function array_to_xml(array $array)
{
   $result = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root></root>');
   //$result = new SimpleXMLElement("<root></root>");
   real_array_to_xml($array, $result);
   return $result;
}

// function defination to convert array to xml
function real_array_to_xml($student_info, &$xml_student_info) {
    foreach($student_info as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                real_array_to_xml($value, $subnode);
            }
            else{
                real_array_to_xml($value, $xml_student_info);
            }
        }
        else {
            $xml_student_info->addChild("$key","$value");
        }
    }
}

################################################################################
function human_filesize($bytes, $decimals = 2) {
   $sz = 'BKMGTP';
   $factor = floor((strlen($bytes) - 1) / 3);
   return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) ." ". @$sz[$factor];
}
################################################################################
/*
function array_to_xml(array $arr)
{
   return real_array_to_xml($arr,new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><root></root>'));
}
function real_array_to_xml(array $arr, SimpleXMLElement $xml)
{
   foreach ($arr as $k => $v) 
   {
      is_array($v)
      ? real_array_to_xml($v, $xml->addChild($k))
      : $xml->addChild($k, $v);
   }
   return $xml;
}*/
################################################################################
function create_directory($path_dir)
{
   create_directory_recursively($path_dir);   
}
################################################################################
function get_filename_uploaded($filename) 
{
   return str_replace(" ", "_", $filename);
}
################################################################################
function create_directory_recursively($path_dir) 
{
        $parts_path=explode("/",$path_dir);
        $path_for_level=$parts_path[0];
        $i=0;
        do
        {
              //echo("<BR> crea ".$path_for_level);
              if(!is_dir($path_for_level) && isset($path_for_level) && strcasecmp($path_for_level,"")!=0)
              {
                  //echo("#############-".$path_for_level."-##################");

                  mkdir($path_for_level);
                  chmod($path_for_level,0777);                  
              }
              $i++;
              if($i<count($parts_path))
              {
                     $path_for_level=$path_for_level."/".$parts_path[$i];
              }
        }
        while($i<count($parts_path));
 }
 ###############################################################################
 function delete_file($path_file)
 {  
    if(file_exists($path_file))
    {
      return unlink($path_file);		
    }
 }
 ###############################################################################
 function delete_directory($path_dir)
 {
    delete_recursively_directory($path_dir);
 } 
 ###############################################################################
 function delete_recursively_directory($path_dir)
 {
      if(is_dir($path_dir) && !is_link($path_dir))
      {
        if($dh = opendir($path_dir))
        {
         while(($sf = readdir($dh)) !== false)
         { 
            if($sf == '.' || $sf == '..')
            {
             continue;
            }

            if(!delete_recursively_directory($path_dir.'/'.$sf))
            {
              echo("<P>".$filepath.'/'.$sf.' Could not delete.');
              return;
            }
         }
         closedir($dh);
        }
       return rmdir($path_dir);
      }

      if(file_exists($path_dir))
      {
        return unlink($path_dir);
      }
}