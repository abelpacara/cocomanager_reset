<?php
function deploy_hour_minute($posfix, $label_hour="Hora",$label_minute="Minute",$defaul_hour="",$defaul_minute="")
{

   //echo "<br>GET_deploy_hour_minute=".$defaul_hour.":".$defaul_minute;

   ?>
   <div>
      <div style="float: left; margin-right: 7px;">
         
           <label for="hour<?php echo($posfix);?>"><?php echo $label_hour?></label>
            <select name="hour<?php echo($posfix);?>">
             <?php
             for($i=0;$i<=23;$i++)
             {
             ?>
               <option value="<?php echo($i);?>"
                       <?php
                       if(strcasecmp(ltrim($defaul_hour,"0"),$i)==0)
                       {
                          echo " SELECTED ";
                       }
                       ?>
                       ><?php echo($i);?>
               </option>
             <?php
             }?>
            </select>
      </div>
      <div style="float: left;margin-right: 7px;">:</div>
      <div style="float: left">
         
           <label for="minute<?php echo($posfix);?>"><?php echo $label_minute?></label>
           <select name="minute<?php echo($posfix);?>">
             <?php
             for($i=0;$i<=59;$i++)
             {
             ?>
               <option value="<?php echo($i);?>"
                       <?php
                       if(strcasecmp(ltrim($defaul_minute,"0"),$i)===0)
                       {
                          echo " SELECTED ";
                       }
                       ?>
                       ><?php echo($i);?></option>
             <?php
             }?>
            </select>
         
      </div>
   </div>
<?php
}
?>