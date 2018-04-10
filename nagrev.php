<?php

        define("RELAY_1",   10);
        define("RELAY_2",   14);
        define("RELAY_3",   13);
        define("RELAY_4",   12);
        
        define("BUFSIZE",  128);
        define("LOW",  		 0);
        define("HIGH", 		 1);
		define("OVERHEAT_TEMP", 75);
		define("DELTA", 2);


date_default_timezone_set('Europe/Kiev');

file_put_contents("/root/nagrev.log", "");



require("assets/db.class.php");
$db = new PDOWrapper\DB();

require("assets/options.class.php");
$opts = new Options($db);

require("assets/settings.class.php");
$settings = new Settings($db);


require("nagrev_functions.php");
	
$boiler_temp = get_temp(); 
$overheat_temp = $opts->all['nagrev_overheat'];  
$last_time=0;



 		
while(1){     
	echo "\n\n\n";
	
	
	$opts = new Options($db);
	$settings = new Settings($db);
	
	$boiler_temp = get_temp();
    echo "temp = $boiler_temp\n";

	$maxtemp = get_max_temp($db);
	echo "maxtemp=$maxtemp\n";
	date_default_timezone_set('Europe/Kiev');
	$hour = date('H');	

  
	if($hour==0)
		file_put_contents("/root/nagrev.log","");   //clean log file on day start

	if($settings->all['boiler_mode']<2){
		echo "nagrev is off. \n";
		continue; //режим 0 или 1: бойлер выключен.  режим 2: работа по расписанию
	}
         
      if($boiler_temp > $overheat_temp){ 
         turn_relay(HIGH);
         echo "error3. ";
         echo "relay is off\n";
         
         saveError(3);
		 sleep(60);
      }
      
    
    if(($last_time>0)&&(millitime() - $last_time>360)&&(abs(get_temp()-$last_temp)>0)){
		 turn_relay(HIGH);
         echo "error 4. ";
         echo "relay is off\n";
         
         saveError(4);
	     $last_time=0;
		 sleep(59);
      }
         
         
          
          if ($boiler_temp >= $maxtemp){             
            
             file_put_contents("/root/nagrev.log",date("d/m/Y H:i")." \n       Выкл нагрев  \n",FILE_APPEND);
             file_put_contents("/root/nagrev.log","b_temp=$boiler_temp, max_t=$maxtemp \n\n",FILE_APPEND);
             
             turn_relay(HIGH);
             echo "relay is off\n";
             $last_time = 0;
             $last_temp = 0;
          }   
          
            
          if (($boiler_temp <= $maxtemp-DELTA)&&($last_time == 0)){              
         
             file_put_contents("/root/nagrev.log",date("d/m/Y H:i")." \n                Вкл нагрев  \n\n",FILE_APPEND);
             file_put_contents("/root/nagrev.log","b_temp=$boiler_temp, max_t=$maxtemp \n",FILE_APPEND);
             
             turn_relay( LOW);
             echo "relay is on\n";
             $last_time = millitime();
             $last_temp = get_temp();
          }

		
	sleep(10);
	}
	
	
