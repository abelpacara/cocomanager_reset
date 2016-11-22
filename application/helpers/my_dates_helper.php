<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

   function ez_date($d) { 
        $ts = time() - strtotime(str_replace("-","/",$d)); 
        
        if($ts>31536000) $val = round($ts/31536000,0).' year'; 
        else if($ts>2419200) $val = round($ts/2419200,0).' month'; 
        else if($ts>604800) $val = round($ts/604800,0).' week'; 
        else if($ts>86400) $val = round($ts/86400,0).' day'; 
        else if($ts>3600) $val = round($ts/3600,0).' hour'; 
        else if($ts>60) $val = round($ts/60,0).' minute'; 
        else $val = $ts.' second'; 
        
        if($val>1) $val .= 's'; 
        return $val; 
   } 

   function form_dropdown_time($prefix_name, $set_time=null, $styles_mask="")
   {
      if(isset($set_time) AND strcasecmp($set_time,"")!=0)
      {
         list($hour, $minute, $second) = explode(":", $set_time);
      }
      else
      {
         list($hour, $minute, $second) = array("","","");
      }
      
      $array_hours=array();
      for($i=0; $i<=23; $i++)
      {
         $array_hours[$i]=$i;
      }

      $array_minutes=array();
      $array_seconds=array();

      for($i=0; $i<=59; $i++)
      {
         $array_minutes[$i] = $i;
         $array_seconds[$i] = $i;
      }
      
      ob_start();
      echo form_dropdown($prefix_name.'hour', $array_hours, $hour, $styles_mask);
      
      echo " <span class='colon_time'>:</span> ";
      echo form_dropdown($prefix_name.'minute', $array_minutes, $minute, $styles_mask);
      //echo " <span class='colon_time'>:</span> ";
      //echo form_dropdown($prefix_name.'second', $array_seconds, $second, $styles_mask);      
      $res = ob_get_clean();
      return $res;
   }
   #######################################################################################################
   function date_is_cross_interval($target_date_time, $dt_begin_interval, $dt_end_interval)
	{
		if( strtotime($target_date_time) < strtotime( $dt_begin_interval) OR         
          strtotime($target_date_time) > strtotime( $dt_end_interval ))
		{
			return TRUE;
		}
      return FALSE;
	}
   #######################################################################################################
   function get_past_time_literal($date_time1, $date_time2)
   {
      $time_stamp_diff = abs( strtotime($date_time1) - strtotime($date_time2) );
      
      $array_name_intervals_per_second = array(
         "seconds(s)"=>1,
         "minutes(s)"=>60,
         "hous(s)"=>60*60,
         "days(s)"=>60*60*24,
         "weeks(s)"=>60*60*24*7,
         );
      
      $array_keys = array_keys($array_name_intervals_per_second);
      
      for($i=count($array_name_intervals_per_second)-1; $i>=0 ; $i--)
      { 
         $diff = $time_stamp_diff/$array_name_intervals_per_second[$array_keys[$i]];
         
         if($diff > 1)
         {
            $array_return = array('diff'=>$diff,
                                  'name_literal'=>$array_keys[$i]);       
            return $array_return;
         }
      }
   }
   #######################################################################################################
   function get_date_diff_hours($date_time1, $date_time2)
   {
      $time_stamp_diff = strtotime($date_time1) - strtotime($date_time2);

      return $time_stamp_diff/(60*60); //for day =(60*60*24);
   }
   #######################################################################################################
   function get_date_literal_english($date_unfriendly_format_mysql="")
   {
       $array_date_time = explode(" ", $date_unfriendly_format_mysql);      
       
       $date = $array_date_time[0];
       $time="";
       if(isset($array_date_time[1]))
       {
          $time = $array_date_time[1];
       }
       return get_day_literal_english($date_unfriendly_format_mysql)."  ".date("F j, Y", strtotime($date))." ".$time;		 
	}
   #######################################################################################################
   function get_date_literal_spanish($date_unfriendly_format_mysql="")
   {
		$date_friendly="";
		if(strcasecmp( trim($date_unfriendly_format_mysql),"") == 0)
		{
			$date_unfriendly_format_mysql = date("Y-m-d");
		}
      else{
         $position_found = stripos($date_unfriendly_format_mysql, ':');
         $part_date="";
         $part_time="";
         if ($position_found === false)
         {
            $part_date = $date_unfriendly_format_mysql;

         }
         else{
            list($part_date, $part_time)= explode(" ", $date_unfriendly_format_mysql);
         }
         $parts_date = explode("-",$part_date);         
         if(count($parts_date)==3)
         {
            list($year, $month, $day) = $parts_date;
         }
         else
         {
            echo "<br> ERROR in PART_DATE = ".$part_date." <br>";
         }
         $time_text=$hour=$minute=$second="";

         if(strcasecmp($part_time,"")!=0)
         {
            list($hour, $minute, $second)=explode(":",$part_time);
            $time_text=" ".$hour.":".$minute.":".$second;
         }
         if(checkdate($month,$day,$year))
         {
            $date_friendly = get_day_literal_spanish($part_date).", ".$day." de ".get_month_literal_spanish($month)." del ".$year.$time_text;
         }
      }
		return $date_friendly;
	}
   #######################################################################################################
	function get_short_day_literal_english($date_format_mysql)
	{
      return date('D', strtotime($date_format_mysql));      
	}
   #######################################################################################################
	function get_day_literal_english($date_format_mysql)
	{
      $day_D = date('D', strtotime($date_format_mysql));
    
      switch ($day_D)
      {
         case 'Sun':
            $day_english="Sunday";
            break;
         case 'Mon':
            $day_english="Monday";
            break;
         case 'Tue':
            $day_english="Tuesday";
            break;
         case 'Wed':
            $day_english="Wednesday";
            break;
         case 'Thu':
            $day_english="Thursday";
            break;
         case 'Fri':
            $day_english="Friday";
            break;
         case 'Sat':
            $day_english="Saturday";
            break;
      }
      
		return $day_english;
	}
   #######################################################################################################
	function get_short_day_literal_spanish($date_format_mysql)
	{
      $day_spanish='';

      list($year,$month,$day)=explode("-",$date_format_mysql);

      if(checkdate($month,$day,$year))
      {

         $day_english=date('D',mktime(0,0,0,$month, $day, $year));

         switch ($day_english)
         {
            case 'Sun':
               $day_spanish="Dom";
               break;
            case 'Mon':
               $day_spanish="Lun";
               break;
            case 'Tue':
               $day_spanish="Mar";
               break;
            case 'Wed':
               $day_spanish="Mie";
               break;
            case 'Thu':
               $day_spanish="Jue";
               break;
            case 'Fri':
               $day_spanish="Vie";
               break;
            case 'Sat':
               $day_spanish="Sab";
               break;
         }
      }
		return $day_spanish;
	}
   #######################################################################################################
	function get_day_literal_spanish($date_format_mysql)
	{
      $day_spanish='';

      list($year,$month,$day)=explode("-",$date_format_mysql);

      if(checkdate($month,$day,$year))
      {

         $day_english=date('D',mktime(0,0,0,$month, $day, $year));

         switch ($day_english)
         {
            case 'Sun':
               $day_spanish="Domingo";
               break;
            case 'Mon':
               $day_spanish="Lunes";
               break;
            case 'Tue':
               $day_spanish="Martes";
               break;
            case 'Wed':
               $day_spanish="Miercoles";
               break;
            case 'Thu':
               $day_spanish="Jueves";
               break;
            case 'Fri':
               $day_spanish="Viernes";
               break;
            case 'Sat':
               $day_spanish="Sabado";
               break;
         }
      }
		return $day_spanish;
	}
   #######################################################################################################
	function get_month_literal_spanish($month_numeric)
	{
		$month_literal=0;
		switch ($month_numeric)
		{
			case 1:
				$month_literal="Enero";
				break;
			case 2:
				$month_literal="Febrero";
				break;
			case 3:
				$month_literal="Marzo";
				break;
			case 4:
				$month_literal="Abril";
				break;
			case 5:
				$month_literal="Mayo";
				break;
			case 6:
				$month_literal="Junio";
				break;
			case 7:
				$month_literal="Julio";
				break;
			case 8:
				$month_literal="Agosto";
				break;
			case 9:
				$month_literal="Septiembre";
				break;
			case 10:
				$month_literal="Octubre";
				break;
			case 11:
				$month_literal="Noviembre";
				break;
			case 12:
				$month_literal="Diciembre";
				break;
		}
		return $month_literal;
	}
	#######################################################################################################
	



   function get_total_sum($matrix_assoc, $key_colum)
   {
      $accumulated=0;

      for($i=0; $i<count($matrix_assoc); $i++)
      {
         $accumulated += $matrix_assoc[$i][$key_colum];
      }
      return $accumulated;
   }
   /**
    * @param    $date_time_pivot Is any date on which it is going to get past weeks including it
    * @param    $quantity_past_weeks Specifies the number of weeks
    * @param    $format              Specifies the date format
    *
    * @return        array(
    *                      'begin'=>[DATE According format],
    *                      'end'=>[DATE According format]
    *                      );
    *                the array will may have ONE or MORE ROWS
    */
   function get_list_past_weeks($date_time_pivot, $quantity_past_weeks=20, $format = 'Y-m-d')
   {
      $list_week_intervals = array();     
      
      $dt_before = $date_time_pivot; //= "2012-01-04";
      
      $week_ = get_week_interval_arround_date($dt_before);      
      
      $list_week_intervals[0] = $week_;
      
      for($i=1;$i<$quantity_past_weeks; $i++)
      {  
         $dt_before = date("Y-m-d H:i:s", strtotime('-7 days',    strtotime($dt_before) ) );         
         $week_= get_week_interval_arround_date($dt_before);
         
         $list_week_intervals[$i] = $week_;
      }
      /*
      echo "<br><pre>";
      print_r( $list_week_intervals );
      echo "</pre><BR>";
      */

      return $list_week_intervals;
   }
   /**
    * @param    $date_time_pivot  Is any date which is going to get the number of weeks
    *
    * @return   [INTEGER]  Values ​​may be 50, 51, 53 depending on how many weeks has the year
    */
   ##################################################################
   function get_week_number($date_time_pivot)
   {
      $time_stamp = strtotime(date('o-\\WW', strtotime($date_time_pivot)));
      $week_number = date('W', $time_stamp);
      return $week_number;
   }
   ###################################################################
   function get_week_interval_dates($week_number, $year)   
   {  
      $fecha = new DateTime();
      $fecha->setISODate($year, $week_number, 1);
      $interval['begin'] = $fecha->format('Y-m-d');
      
      $fecha->setISODate($year, $week_number, 7);
      $interval['end'] = $fecha->format('Y-m-d');
      
     
      return $interval;      
   }   
   #######################################################################################
   function get_week_interval_arround_date( $date, $week=null, $year=null)
   {  
      $dates = array();
      $time = strtotime($date);

      $start = strtotime(date('o-\\WW', $time));

      //$start = strtotime('monday this week', $isoWeekStartDate);
      //$start = strtotime('monday this week', $time);

      $dates['date'] = date('Y-m-d', strtotime($date));
      $dates['begin'] = date('Y-m-d' , $start );   
      $dates['end'] = date('Y-m-d' , ( $start + ( 6 * ( 60 * 60 * 24 ) ) ) );
      $dates['week_number'] = date('W', $start);
      $dates['year'] = date("Y", strtotime( $dates['end'] ));
      return $dates;
   }
   
   /**
    * @param    $date_time_pivot Is any date on which it is going to get past weeks including it
    * @param    $format              Specifies the date format
    *
    * @return        array(
    *                      'begin'=>[DATE According format],
    *                      'end'=>[DATE According format]
    *                      );
    *                the array will may have ONE or MORE ROWS
    */
   
   
   /**
    * date and time separated
    * 
    * @param   date_time or time [  DATE '2011-03-25' or DATE_TIME '2011-03-25 08:00:00']
    *
    * @return  array(
    *          'date'=>[DATE '2011-03-25' or '']
    *          'time'=>[TIME '08:00:00' or '']);
    * 
    *          according to the param
    */
   function get_array_date_time($date_time_or_date)
   {
      $array_date_time = array();

      if(isset($date_time_or_date) AND strcasecmp($date_time_or_date,"")!==0 )
      {
         $parts_ts = explode(" ", $date_time_or_date);

         if(isset($parts_ts) AND count($parts_ts)>0)
         {
            list($part_date, $part_time) = $parts_ts;
            $array_date_time['date']=$part_date;
            $array_date_time['time']=$part_time;
         }
         else//onlly date
         {
            $array_date_time['date']=$date_time_or_date;
            $array_date_time['time']=null;
         }
      }
      return $array_date_time;
   }
   function get_interval_week_arround_date_time($date_time_pivot, $format = 'Y-m-d')
   {
      $week_number = get_week_number($date_time_pivot);
      $time_stamp = strtotime($date_time_pivot);

      $year = date("Y", $time_stamp);

      return get_week_interval_dates($week_number, $year ,  $format);
   }
   
?>