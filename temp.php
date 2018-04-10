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
$temp_sensors_values=[];
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


             $temp_sensors_values[]=$celsius;

     }
     echo "<br/>\n";
}
/*
if(!is_int($temp_sensors_values[0])){
	$i=999;
	while($i){
		$temp_sensors_values[--$i]=0;
	}
} 
*/
//echo $temp_sensors_values[0]."<br/>";

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



//echo "\n\n\nzzzz\n\n\n";
//var_dump(is_int($temp_sensors_values[0]),"\n");
$query = "INSERT INTO `data` (`time`, `t1`, `t2`, `t3`, `t4`, `t5`, `t6`, `t7`, `t8`, `t9`, `t10`, `r1`, `r2`, `r3`, `r4`, `r5`, `r6`, `r7`, `r8`)
                        VALUES (CURRENT_TIMESTAMP, '$temp_sensors_values[0]', '$temp_sensors_values[1]', '$temp_sensors_values[2]', '$temp_sensors_values[3]',
                        '0', '0', '0', '0', '0', '0',
                        $answer1[0], $answer2[0], $answer3[0], $answer4[0], $answer5[0], $answer6[0], $answer7[0], $answer8[0]);";
//echo $query."\n";
$link->query($query);



if($result = $link->query("select `err_no` from errors where `cleared`=0;")){
                                $errors=[];
                while($row=$result->fetch_array()){
                       $errors[$row[0]]="1";
                }
                $errors=implode($errors);
         //   echo "<br>".$errors." = ";
        // $errors=bindec($errors);
         //echo $errors."<br>";
        $result->close();
        $link->next_result();
}else $errors=0;


$errors = bindec($errors);
$date = new DateTime();
//echo $date->getTimestamp();
$arr = array("t1" => $temp_sensors_values[0],
                                "t2" => $temp_sensors_values[1],
                                "t3" => $temp_sensors_values[2],
                                "t4" => $temp_sensors_values[3],
                                "t5" => rand(-30,120),
                                "t6" => rand(-30,120),
                                "t7" => rand(-30,120),
                                "t8" => rand(-30,120),
                                "t9" => rand(-30,120),
                                "t10" => rand(-30,120),
                                "r1" => $answer1[0],
                                "r2" => $answer2[0],
                                "r3" => $answer3[0],
                                "r4" => $answer4[0],
                                "r5" => $answer5[0],
                                "r6" => $answer6[0],
                                "r7" => $answer7[0],
                                "r8" => $answer8[0],
                                "kotel_mode" => 1,
                                "boiler_mode" =>1,
                                "timestamp" =>date('Y-m-d H:i:s',$date->getTimestamp()),
                                "errors" =>$errors
                        );


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);



error_reporting(E_ALL); // Error engine

ini_set('display_errors', TRUE); // Error display

ini_set('log_errors', TRUE); // Error logging

ini_set('error_log', '/root/errors.log'); // Logging file

ini_set('log_errors_max_len', 1024); // Logging file size




require_once("/var/www/html/encr.php");
$arr=    json_encode($arr);
//var_dump($arr);
$arr =   char_encode($arr);
//var_dump($arr);
$arr = base64_encode($arr);
//file_put_contents("/var/www/html/send.log", $arr."\n", FILE_APPEND);
//var_dump((($arr)));
//echo"<br><br>";
$my_id="826khyQ9995TP20";
$url = "http://zaimka.cloudaccess.host/login/slim.php";//?id=$my_id&p=$arr";
$post_data=['id'=>$my_id,
                        'p'=>$arr
                        ];


// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($post_data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { die( "Handle error "); }

//var_dump($url);

//echo $url."<br>";

$name=$result;
//file_put_contents("/var/www/html/receive.log", $name."\n", FILE_APPEND);
//var_dump($name);
$name=base64_decode($name);
//var_dump($name);
$name=char_decode($name);
//var_dump($name);
$data_from_device=json_decode($name,true);
                                //var_dump($data_from_device);
                                //echo "<font color=green>";
                                //var_dump($data_from_device);
                                //echo"</font><br>";
                                var_dump( $data_from_device["timetables"],"<br>");
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
                                                                        $data_from_device["timestamp"]."\",".
                                                                        $data_from_device["kotel_mode"].",".
                                                                        $data_from_device["boiler_mode"].",".
                                                                        $data_from_device["t_kotel"].",".
                                                                        $data_from_device["t_boiler"].",".
                                                                        $data_from_device["t_room"].",".
                                                                        $data_from_device["t_collector"].");
                                                                        ";



                        /**
                         *              @clear_errors procedure, if $clear_errors is set
                         **/

                            if($data_from_device["clear_errors"]==1){
                                var_dump($link->query("UPDATE `errors` SET `cleared` = b'1' WHERE `cleared`= b'0'; "));
                            }


                        //echo $insert_result;
                        if($link->query($insert_result)){
                             echo "<b><br>settings saved<br></b>";
                        } else echo "ha-ha";








$link->close();



?>
  </body>
</html>

