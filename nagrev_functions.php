<?php




function parse_days_of_week($dw){
	
	$val=decbin($dw);
	
	while(strlen($val)<7)$val="0".$val;
	
	$val=str_split($val);
	
	
	$arr="";
	
	if($val[0]=="1")$arr.=" Mon ";
	if($val[1]=="1")$arr.=" Tue ";
	if($val[2]=="1")$arr.=" Wed ";
	if($val[3]=="1")$arr.=" Thu ";
	if($val[4]=="1")$arr.=" Fri ";
	if($val[5]=="1")$arr.=" Sat ";
	if($val[6]=="1")$arr.=" Sun ";
	
	return $arr;
}


function millitime() {
  $microtime = microtime();
  $comps = explode(' ', $microtime);

  return intval($comps[1]);
}


function get_temp(){
	global $opts;
	$name = $opts->all['nagrev_temp_sensor'];
	
	$sensor = "/sys/bus/w1/devices/$name/w1_slave";
	$sensorhandle = fopen($sensor, "r");

	$thermometerReading = fread($sensorhandle, filesize($sensor));
	fclose($sensorhandle);
	// We want the value after the t= on the 2nd line
	preg_match("/t=(.+)/", preg_split("/\n/", $thermometerReading)[1], $matches);
	$celsius = round($matches[1] / 1000); //round the results

	return $celsius;

}



function turn_relay( $state){
	global $opts;
	$relay = $opts->all['nagrev_relay']?$opts->all['nagrev_relay']:12;
		$state_string="l";
		   //echo "rel_num = $relay \n";
		$_GLOBALS['relay']=true;
		if($state==HIGH){$state_string="h";$_GLOBALS['relay']=false;}
        if(($state==LOW||$state==HIGH)){
			
               system("sudo /bin/gate $relay o $state_string");
        }
}


function get_max_temp($db){
	
	$maxtemp = 30;
	date_default_timezone_set('Europe/Kiev');
	$hour = date("H");
	
	if(intval(date("i"))>30)$hour += 0.5;
	//var_dump($hour);
    foreach($db->query("select * from `timetables` where `what` = 'nagrev'") as $tt){
		//var_dump($tt);
	 


                $from_time=explode(":", $tt['from_time']);
				$half=0;
				if(intval($from_time[1])>0)$half=0.5;
				$from_time=intval($from_time[0]) + $half;
				
				$till_time=explode(":", $tt['till_time']);
				$half=0;
				if(intval($till_time[1])>0)$half=0.5;
				$till_time=intval($till_time[0]) + $half;
				//var_dump($from_time, $till_time);


 
	  
	  $days = parse_days_of_week($tt['days_of_week']);
	  $temp = $tt['temp'];
	  echo "hour = $hour \n";
	  date_default_timezone_set('Europe/Kiev');
	  //echo date("H")." ".date_default_timezone_get()."\n";;
	  if(strpos($days, date("D"))){
		  if($from_time<$till_time){
			  if($hour >= $from_time && $hour < $till_time){
				  $maxtemp = $temp;
				  var_dump($maxtemp, "day");
			  }
		  }else{
			  if($hour >= $from_time || $hour < $till_time){
				  $maxtemp = $temp;
				  var_dump($maxtemp, "night");
			  }
				  
		  }
	  }
   }/*foreach*/
   //var_dump($maxtemp);
   return $maxtemp;
}




function saveError($error){
	global $db;
	switch(intval($error)){
		case  3:
			file_put_contents("/root/nagrev.log",date("d/m/Y H:i")." \n   ERROR-ПЕРЕГРЕВ \n",FILE_APPEND);
            $db->query(
				"INSERT INTO `errors`(`err_no`, `description`, `cleared`) 
				              VALUES (\"3\",    \"Перегрев нагревателя\",   0 )"
				         );
		    break;
		    
		case 4:
		    file_put_contents("/root/nagrev.log",date("d/m/Y H:i")." \nERROR-НЕ НАГРЕВАЕТСЯ  \n\n",FILE_APPEND);
            $db->query(
				"INSERT INTO `errors`(`err_no`, `description`, `cleared`) 
				              VALUES (\"4\",    \"Нагреватель не нагревается, хотя реле включены\",   0 )"
				         );
			break;
		
	}
}


?>
