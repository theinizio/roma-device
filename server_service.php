<?php


require_once("relays.php");
require_once("sensors.php");
require_once("assets/db.class.php");
require_once("assets/errors.class.php");
require_once("assets/common.class.php");
/*
$link=new mysqli('localhost', 'kotel', 'new_password', 'alldata');
if($link->connect_errno){
        die('РћС€РёР±РєР° СЃРѕРµРґРёРЅРµРЅРёСЏ:'.$link->connect_error);
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


*/

$db = new PDOWrapper\DB();

$prev_sensors = [];
$prev_relays  = [];
$prev_errors  = 0;

$to_resend    = [];
$no_internet_time = Common::millitime();

 while(1){

	$sensors = Sensors::getData();
	 var_dump($sensors);echo"<br>\n";
	$relays  = Relays ::getData();	
	var_dump($relays);echo"<br>\n";
	$errors  = Errors ::getErrors($db);
	var_dump($errors);echo"<br>\n";
	$date    = new DateTime();
	date_default_timezone_set("Europe/Kiev");
	$date    = date('Y-m-d H:i:s');
		
	
	if((Common::millitime()-$no_internet_time) > 120000)Errors::saveError($db, 10, "РґР°РІРЅРѕ РЅРµ Р±С‹Р»Рѕ СЃРІСЏР·Рё СЃ СЃРµСЂРІРµСЂРѕРј");
		
		
		
	
		
	if(!($sensors === $prev_sensors) || !($relays === $prev_relays) || ($errors != $prev_errors)){
		 
		if(!Common::sendParams($db, $sensors, $relays, $errors)) {
		    echo "not sent<br>";	
			$to_resend[] = Common::saveParamsLocally($db, $sensors, $relays);
			$no_internet_time = Common::millitime();
		}
		$prev_sensors = $sensors;
		$prev_relays  = $relays;
		$prev_errors  = $errors;
		$prev_date    = $date;		 
		
	}else {
		if(Common::resendPackages($db, $to_resend)){
			$to_resend = [];
		}else{
			Common::getSettings($db);
		}
		$no_internet_time = Common::millitime();
		
	}
	
	
	sleep(3);
}
?>
