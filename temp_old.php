<?php 
/*<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="refresh" content="10">
    </head>
    <body>

*/
$link=new mysqli('localhost', 'kotel', 'new_password', 'alldata');
if($link->connect_errno){
	die('Ошибка соединения:'.$link->connect_error);
}

$link->set_charset("utf8");
if($result = $link->query("select t_address,t_name from temp_sensors;")){
	
		while($row=$result->fetch_array()){
			$sensors2[$row[0]]=$row[1];
		}
	$result->close();
	$link->next_result();              
}

if($result = $link->query("select relay_number,contact_number from relays;")){
	
		while($row=$result->fetch_array()){
			$relays[$row[0]]=$row[1];
		}
	$result->close();
	$link->next_result();              
}



$dir = '/sys/bus/w1/devices/';
$elements = array_diff(scandir($dir), array('..', '.', 'w1_bus_master1'));

$sensor_number=1;
$tobd=[];
foreach ($elements as $key=>$value){
	 echo $sensors2[$value]." -> ";
     if(is_file($dir.$value.'/w1_slave')){
	     $sensor = $dir.$value.'/w1_slave';
           $sensorhandle = fopen($sensor, "r");
	    
	    $thermometerReading = fread($sensorhandle, filesize($sensor));
                 fclose($sensorhandle);
                 // We want the value after the t= on the 2nd line
                 preg_match("/t=(.+)/", preg_split("/\n/", $thermometerReading)[1], $matches);
                 $celsius = round($matches[1] / 1000); //round the results
	    
	    echo $celsius.'&deg;C';
	    $sensors_data["t".$sensor_number++]=$celsius;
		
	   
	     $tobd[]=$celsius;
	    
     } 	
     echo "<br/>\n";
}

//echo $tobd[0]."<br/>";

//echo "<br/>";

//multiplexor address pins are 2 0 6
//multiplexor signal  pin   is 3
exec("sudo gpio mode 3 input");

exec("sudo gate 2 o l");
exec("sudo gate 0 o l");
exec("sudo gate 6 o l");
exec("sudo gpio read 3", $answer1);


echo "Relay 1 is $answer1[0]"."<br/>";
exec("sudo gate 2 o h");
exec("sudo gate 0 o l");
exec("sudo gate 6 o l");
exec("sudo gpio read 3", $answer2);
echo "Relay 2 is $answer2[0]"."<br/>";
exec("sudo gate 2 o l");
exec("sudo gate 0 o h");
exec("sudo gate 6 o l");
exec("sudo gpio read 3", $answer3);
echo "Relay 3 is $answer3[0]"."<br/>";
exec("sudo gate 2 o h");
exec("sudo gate 0 o h");
exec("sudo gate 6 o l");
exec("sudo gpio read 3", $answer4);
echo "Relay 4 is $answer4[0]"."<br/>";
exec("sudo gate 2 o l");
exec("sudo gate 0 o l");
exec("sudo gate 6 o h");
exec("sudo gpio read 3", $answer5);
echo "Relay 5 is $answer5[0]"."<br/>";
exec("sudo gate 2 o h");
exec("sudo gate 0 o l");
exec("sudo gate 6 o h");
exec("sudo gpio read 3", $answer6);
echo "Relay 6 is $answer6[0]"."<br/>";
exec("sudo gate 2 o l");
exec("sudo gate 0 o h");
exec("sudo gate 6 o h");
exec("sudo gpio read 3", $answer7);
echo "Relay 7 is $answer7[0]"."<br/>";
exec("sudo gate 2 o h");
exec("sudo gate 0 o h");
exec("sudo gate 6 o h");
exec("sudo gpio read 3", $answer8);
echo "Relay 8 is $answer8[0]"."<br/>";




$link->query("INSERT INTO `data` (`time`, `t1`, `t2`, `t3`, `t4`, `t5`, `t6`, `t7`, `t8`, `t9`, `t10`, `r1`, `r2`, `r3`, `r4`, `r5`, `r6`, `r7`, `r8`) VALUES (CURRENT_TIMESTAMP, '$tobd[0]', '$tobd[1]', '$tobd[2]', '$tobd[3]', '0', '0', '0', '0', '0', '0',$answer1[0], $answer2[0], $answer3[0], $answer4[0], $answer5[0], $answer6[0], $answer7[0], $answer8[0]);");


/*

for($j=1;$j<9;$j++){
	
	$command=sprintf("gpio -g read %d",$relays[$j]);
	echo "zz".$command."<br/>";
	$relay_data["r".$j]=exec($command,$buf);
	print_r( $buf);//[0]."<br/>";
}
print_r($relay_data);

*/

$link->close();

/*

function tun_relay($$relay_number, $state){
	$state_print="h";
	if($state&&($state=="on"||$state=="ON")){
		$state_print="l";
	}
	
	$relay_print="1";
	switch($$relay_number){
		case 1:
			$relay_print="10";
			break;
		case 2:
			$relay_print="12";
			break;
		case 3:
			$relay_print="13";
			break;
		default: return;
	}
	exec("sudo gate $relay_print o $state_print");
}
	
*/	
	
	
	
?>
  </body>
</html>
