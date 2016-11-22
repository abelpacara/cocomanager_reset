<?php
echo validation_errors();
echo $my_messages;
?>
    <div id="content_box">
       
   <script>
	$(function() {
		$( "#date_in" ).datepicker({ dateFormat: 'yy-mm-dd' });      
	});
   
   $(function() {
		$( "#date_out").datepicker({ dateFormat: 'yy-mm-dd' });      
	});
   </script>    
       <form action="<?php echo site_url("times/add_time_record")?>" method="POST">
       <table id="hor-minimalist-b" summary="Employee Pay Sheet">
         <thead>
            <tr>
               <td colspan="2"><h2><?php echo $week_interval_literal;?></h2></td>
               <input type="hidden" name="week_number" value="<?php echo $week_number?>"/>
               <input type="hidden" name="year_of_week" value="<?php echo $year_of_week?>"/>
            </tr>
         </thead>
         <thead>
         <tr>
         <th scope="col">Ingreso</th>
         <th scope="col">Salida</th>         
         </tr>
         </thead>
         <tbody>
         <?php
         for($i=0; $i<count($list_times);$i++)
         {?>
          <tr>
            <td class="style_no_wrap"><?php echo $list_times[$i]['time_in_literal'];?></td>
            <td class="style_no_wrap"><?php echo $list_times[$i]['time_out_literal'];?></td>
          </tr>
         <?php
         }
         ?>
       </table>
       
       
      
         <h2>Adicionar Nueva Hora</h2>
        
         Entrada
         <p>
         Fecha : <input name = "date_in" id = "date_in" type="text" value="<?php echo $date_in?>"/>
         Hora : 
         
          <?php
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
            
            echo form_dropdown('hour_in', $array_hours, $hour_in);
            echo " : ";
            echo form_dropdown('minute_in', $array_minutes, $minute_in);       
            echo " : ";
            echo form_dropdown('second_in', $array_seconds, $second_in);       
            ?>
         
         
         </p>
         <br>
         Salida
         <p>
         Fecha : <input name = "date_out" id = "date_out" type="text" value="<?php echo $date_out?>"/>
         Hora : 
         
            <?php            
            echo form_dropdown('hour_out', $array_hours, $hour_out);
            echo " : ";
            echo form_dropdown('minute_out', $array_minutes, $minute_out);       
            echo " : ";
            echo form_dropdown('second_out', $array_seconds, $second_out);       
            ?>
                
         </p>

         <input type="submit" name="add_time_record" value="Guardar"/>
         
      </form>
    </div>