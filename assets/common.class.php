<?php

class Common{
	
	
	
	const my_id="826khyQ9995TP20";
	const url = "http://zaimka.cloudaccess.host/login/slim.php";
	
	
	public static function saveParamsLocally($db, $sensors, $relays){
		
		
			$query = "INSERT INTO `data` (`time`,             `sensors`, `relays`)
		                          VALUES (CURRENT_TIMESTAMP,  :sensors,  :relays )";
		$db -> bind("sensors",serialize($sensors));
		$db -> bind("relays", serialize($relays));
		$db -> query($query);
		$result = $db -> lastInsertId();
		if ($result == 0) return false;
		return $result;
	}
	
	
	public static function getSettings($db, $arr=""){
		$post_data=['id' => self::my_id,
		            'p'  => $arr
		           ];
		
		
		
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($post_data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents(self::url, false, $context);
		if ($result === FALSE) { die( "Handle error "); }
		
		//var_dump($url);
		
		//echo $url."<br>";
		
		$name=$result;
		//file_put_contents("/var/www/html/receive.log", $name."\n", FILE_APPEND);
		//var_dump($name);
		$name=base64_decode($name);
		//var_dump($name);
		$name=char_decode($name);
		var_dump($name);
		if($name&&$name!='NULL'){
			$data_from_device=json_decode($name,true);
		
		
	}
	return self::saveSettingsFromServer($db, $data_from_device);
}
	
	
	
	public static function sendParams($db, $sensors, $relays, $errors){
		$date    = new DateTime();
		$date    = date('Y-m-d H:i:s',$date->getTimestamp());
		 $arr = array("sensors"   => $sensors,
					  "relays"    => $relays,
					  "errors"    => $errors,
					  "timestamp" => $date
		             );
		
		
		require_once("/var/www/html/encr.php");
		$arr=    json_encode($arr);
		
		$arr =   char_encode($arr);
		
		$arr = base64_encode($arr);
		

		if(self::getSettings($db, $arr)){
			 return true;
		} else return false;
		
		return false;
	}
	
	
		
	public static function millitime() {
	  $microtime = microtime();
	  $comps = explode(' ', $microtime);
	
	  // Note: Using a string here to prevent loss of precision
	  // in case of "overflow" (PHP converts it to a double)
	  //return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
	  //var_dump($comps);
	  return intval($comps[1]);
	}
	
	
	public static function resendPackages($db, $resend_ids){
		foreach($resend_ids as $val){
			if(is_numeric($val)){
				$db -> bind("id", $val);
				$result = $db -> row("select * from `data` where `id` = :id limit 1");
				//var_dump($result);
			}
		}
	}
	
	
	private static function saveSettingsFromServer($db, $settings){

		 $insert_result = "INSERT INTO settings
								(
								`timestamp`,
								`kotel_mode`,
								`boiler_mode`,
								`t_kotel`,
								`t_boiler`,
								`t_room`,
								`t_collector`
								)
								VALUES
								(\"".
								$settings["timestamp"]."\",".
								$settings["kotel_mode"].",".
								$settings["boiler_mode"].",".
								$settings["t_kotel"].",".
								$settings["t_boiler"].",".
								$settings["t_room"].",".
								$settings["t_collector"].");
								";


		
		                       
		
		                            if($settings["clear_errors"]==1){
		                                var_dump($db -> query("UPDATE `errors` SET `cleared` = b'1' WHERE `cleared`= b'0'; "));
		                            }
		
		self::saveTimetable($db, $settings['timetables']);
		
		return $db->query($insert_result);
		                        
	}
	
	
	private static function saveTimetable($db, $timetable){
		//var_dump( $timetable, "<br>");
		if(count($db->row("SHOW TABLES LIKE 'timetables'"))!=1) {
			$tt_sql = "CREATE TABLE IF NOT EXISTS `timetables` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  
				  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `what` VARCHAR(48) NOT NULL
				  `days_of_week` int(2) unsigned NOT NULL,
				  `from_time` time NOT NULL,
				  `till_time` time NOT NULL,
				  `temp` int(2) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;
				
				INSERT INTO `timetables` (`id`, `device_id`, `timestamp`, `what`, `days_of_week`, `from_time`, `till_time`, `temp`) VALUES
				( 1, 1, '2018-03-05 14:16:30', 'nagrev', 127, '00:00:00', '23:30:00', 22),
				(57, 1, '2018-03-07 23:26:52', 'nagrev', 127, '07:00:00', '23:00:00', 30),
				(58, 1, '2018-03-07 23:27:30', 'nagrev', 127, '23:00:00', '07:00:00', 65);
				";
			$db->query($tt_sql);
		}else{
			$db->query("delete from `timetables` where `id` > 1");
			
			foreach($timetable as $tt){
				
				$sql = "insert into `timetables` (`what`, `days_of_week`, `from_time`, `till_time`, `temp`) 
										   VALUES(:what, :days_of_week,  :from_time,  :till_time,  :temp)";
				$db->bind("what", $tt['what']);						   
				$db->bind("days_of_week", $tt['days_of_week']);
				$db->bind("from_time", $tt['from_time']);
				$db->bind("till_time", $tt['till_time']);
				$db->bind("temp", $tt['temp']);
				$db->query($sql);
			}
		}		
			
	}

}
?>
