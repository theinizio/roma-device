<?php
class Sensors{
	
	public static function getData(){
		$dir = '/sys/bus/w1/devices/';
		$elements = array_diff(scandir($dir), array('..', '.', 'w1_bus_master1'));
		
		$sensor_number=1;
		$temp_sensors_values=[];
		foreach ($elements as $key=>$value){
		      
		     if(is_file($dir.$value.'/w1_slave')){
		             $sensor = $dir.$value.'/w1_slave';
		           $sensorhandle = fopen($sensor, "r");
		
		            $thermometerReading = fread($sensorhandle, filesize($sensor));
		                 fclose($sensorhandle);
		                 // We want the value after the t= on the 2nd line
		                 preg_match("/t=(.+)/", preg_split("/\n/", $thermometerReading)[1], $matches);
		                 $celsius = round($matches[1] / 1000); //round the results
		
		      
		            $sensors_data["t".$sensor_number++]=$celsius;
		
		
		             $temp_sensors_values[]=$celsius;
		
		     }
		     
		}
		return $temp_sensors_values;
	}
	
}

?>
