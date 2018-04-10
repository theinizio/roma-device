<?php

        define("RELAY_1",   10);
        define("RELAY_2",   12);
        define("RELAY_3",   13);
        define("BUFSIZE",  128);
        define("LOW",  		 0);
        define("HIGH", 		 1);
		define("OVERHEAT_TEMP", 75);
		define("DELTA", 2);

define("ANSI_COLOR_RED",     "\x1b[31m");
define("ANSI_COLOR_GREEN",   "\x1b[32m");
define("ANSI_COLOR_YELLOW",  "\x1b[33m");
define("ANSI_COLOR_BLUE",    "\x1b[34m");
define("ANSI_COLOR_MAGENTA", "\x1b[35m");
define("ANSI_COLOR_CYAN",    "\x1b[36m");
define("ANSI_COLOR_RESET",   "\x1b[0m" );



function millitime() {
  $microtime = microtime();
  $comps = explode(' ', $microtime);

  // Note: Using a string here to prevent loss of precision
  // in case of "overflow" (PHP converts it to a double)
  //return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
  //var_dump($comps);
  return intval($comps[1]);
}


function get_temp(){
	$sensor = "/sys/bus/w1/devices/28-041770a687ff/w1_slave";
	$sensorhandle = fopen($sensor, "r");

	$thermometerReading = fread($sensorhandle, filesize($sensor));
	fclose($sensorhandle);
	// We want the value after the t= on the 2nd line
	preg_match("/t=(.+)/", preg_split("/\n/", $thermometerReading)[1], $matches);
	$celsius = round($matches[1] / 1000); //round the results

	return $celsius;

}

function get_temp_room(){
	$sensor = "/sys/bus/w1/devices/28-041770902fff/w1_slave";
	$sensorhandle = fopen($sensor, "r");

	$thermometerReading = fread($sensorhandle, filesize($sensor));
	fclose($sensorhandle);
	// We want the value after the t= on the 2nd line
	preg_match("/t=(.+)/", preg_split("/\n/", $thermometerReading)[1], $matches);
	$celsius = round($matches[1] / 1000); //round the results

	return $celsius;

}



function get_relay_state($relay_num){
        if($relay_num==RELAY_1||$relay_num==RELAY_2||$relay_num==RELAY_3){

		exec("gpio mode 3 input");

        if($relay_num==RELAY_1){
				exec("gate 2 o l");
				exec("gate 0 o l");
				exec("gate 6 o l");
				exec("gpio read 3", $answer1);
                return intval($answer1[0]);
        }


        if($relay_num==RELAY_2){
                exec("gate 2 o h");
				exec("gate 0 o l");
				exec("gate 6 o l");
				exec("gpio read 3", $answer2);
                return intval($answer2[0]);
        }



        if($relay_num==RELAY_3){
                exec("gate 2 o l");
				exec("gate 0 o h");
				exec("gate 6 o l");
				exec("gpio read 3", $answer3);
                return intval($answer3[0]);
        }

        }
        return NULL;
}


function turn_relay($relay_num,  $state){
		$state_string="l";
		if($state==HIGH)$state_string="h";
        if(($relay_num==RELAY_1||$relay_num==RELAY_2||$relay_num==RELAY_3)&&($state==LOW||$state==HIGH)){
			
               exec("gate $relay_num o $state_string");
        }
}




$link=new mysqli('localhost', 'kotel', 'new_password', 'alldata');
if($link->connect_errno){die('Ошибка соединения:'.$link->connect_error);}
$link->set_charset("utf8");


			$kotel_mode =  1;
			$t_kotel    = 22;
			$t_room	    = 20;


/*
if($result = $link->query("select `kotel_mode`, `t_kotel`, `t_room` 
						     from `settings` order by `id` desc limit 1;")){

		$row=$result->fetch_array();
		//var_dump($row);
			$kotel_mode = $row[0];
			$t_kotel    = $row[1];
			$t_room	   = $row[2];
		$result->close();
		$link->next_result();
}

		
        $kotel_temp = get_temp();*/
        $last_time = 0;
        $val = 0;

turn_relay(RELAY_1, HIGH);
turn_relay(RELAY_2, HIGH);
turn_relay(RELAY_3, HIGH);

while(1){
	
	
	if($result = $link->query("select `kotel_mode`, `t_kotel`, `t_room` 
						     from `settings` order by `id` desc limit 1;")){

		$row=$result->fetch_array();
		//var_dump($row);
			$kotel_mode = $row[0];
			$t_kotel    = $row[1];
			$t_room	   = $row[2];
		$result->close();
		$link->next_result();
}

		
        $kotel_temp = get_temp();

	
	/*
	
	printf(ANSI_COLOR_CYAN."температура котла=%d C, желаемая=%d C.\nВ комнате %d C, желаемая %d C".
		   ANSI_COLOR_RESET."\n",$kotel_temp, $t_kotel, get_temp_room(),$t_room);
	//echo get_relay_state(RELAY_1)." ".get_relay_state(RELAY_2)." ".get_relay_state(RELAY_3)."\n";
	* */
       
	if ($kotel_temp >= OVERHEAT_TEMP){ //в отдельный скетч " по максимальнома перегреву"
            turn_relay(RELAY_1, HIGH);
            turn_relay(RELAY_2, HIGH);
            turn_relay(RELAY_3, HIGH);
            //printf(ANSI_COLOR_GREEN." Выключил всё".ANSI_COLOR_RESET."\n");
            
            /**
             * TODO
             * вставить в описание ошибки параметры системы(датчики, реле)
             * */
            
            
			$link->query(
				"INSERT INTO `errors`(`err_no`, `description`, `cleared`) 
				              VALUES (\"1\",    \"t_kotla=$t_kotel, t\",   0 )"
				         );
				         
			printf(ANSI_COLOR_RED." ERROR-ПЕРЕГРЕВ".ANSI_COLOR_RESET."\n");// привязать "КОД ОШИБКИ" к панели управления (на почту ?)
			file_put_contents("/root/lotel.log",date("d/m/Y H:i")." \n   ERROR-ПЕРЕГРЕВ \n",FILE_APPEND);

      }

      $val = get_relay_state(RELAY_3); // 0     при максимальном включёных реле "если ничего не происходит" ОШИБКА
      
      $str_val="вкл";
      if($val)$str_val="выкл";
     // printf(ANSI_COLOR_BLUE." реле_3 $str_val".ANSI_COLOR_RESET."\n");
      
	if ($val==0){
		if (millitime() - $last_time > 600){
			turn_relay(RELAY_1, HIGH);
			turn_relay(RELAY_2, HIGH);
			turn_relay(RELAY_3, HIGH);
			//printf(ANSI_COLOR_GREEN." Выключил всё".ANSI_COLOR_RESET."\n");
			$link->query(
							"INSERT INTO `errors`(`err_no`, `description`, `cleared`) 
									      VALUES (\"2\",    \"не нагревается котел, хотя реле включены\",   0 )"
										);
			printf(ANSI_COLOR_RED."            ERROR 'noheat'".ANSI_COLOR_RESET."\n");// привязать "КОД ОШИБКИ" к панели управления (на почту ?)
			file_put_contents("/root/kotel.log",date("d/m/Y H:i")." \n   ERROR 'noheat' \n",FILE_APPEND);
			sleep(10);
		}
	}

      if ($kotel_temp >= $t_kotel){
            //printf(ANSI_COLOR_YELLOW."Выкл нагрев      NAGRETO".ANSI_COLOR_RESET."\n");
            turn_relay(RELAY_1, HIGH);
            turn_relay(RELAY_2, HIGH);
            turn_relay(RELAY_3, HIGH);

      }


      $val = get_relay_state(RELAY_1); // 0        если включено 1-е реле, через 120с. включаем 2-е реле
      $str_val="вкл";
      if($val)$str_val="выкл";
      //printf(ANSI_COLOR_BLUE." реле_1 $str_val".ANSI_COLOR_RESET."\n");
                 
			if ($val==0){     
				$time=millitime() - $last_time;
				//printf("%ld\n",$time);
				if ((get_relay_state(RELAY_2) == 1) && ($time > 120)){
					turn_relay(RELAY_2, LOW);
					//printf(ANSI_COLOR_GREEN." Включил реле 2".ANSI_COLOR_RESET."\n");

				}
			}else {
				if ((get_relay_state(RELAY_1) == 1)&&($kotel_temp <= $t_kotel-DELTA)){      // включение 1-го реле
	                // printf(ANSI_COLOR_GREEN." Включил реле 1".ANSI_COLOR_RESET."\n");
	                 turn_relay(RELAY_1, LOW);
	                 $last_time = millitime();

				}
			}

     $val = get_relay_state(RELAY_2); // 0     если включено 2-е реле, через 360с. после первого (240с после второго) включаем 3-е реле
     $str_val="вкл";
      if($val)$str_val="выкл";
     // printf(ANSI_COLOR_BLUE." реле_2 $str_val".ANSI_COLOR_RESET."\n");
                 if ($val==0){
                  if ((get_relay_state(RELAY_3) == 1)&&(millitime() - $last_time > 360)){
                   turn_relay(RELAY_3, LOW);
                 // printf(ANSI_COLOR_GREEN."  Включил реле 3".ANSI_COLOR_RESET."\n");
            }    // printf("%d\n",$val);
                 }


     sleep(10);
  }





?>

