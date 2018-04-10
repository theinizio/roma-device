<?php
require_once 'functions.php';

 if (file_get_contents('php://input')) {
    $json = file_get_contents('php://input');
    $parsedJSON = json_decode($json, true);
    $dataType = (isset($parsedJSON['dataType'])) ? $parsedJSON['dataType'] : '';
     if(strlen($dataType)>0&&$dataType=="change_mode"){
         $heater_mode    = (isset($parsedJSON['heater_mode']))    ? $parsedJSON['heater_mode']    : 'm0';
         $collector_mode = (isset($parsedJSON['collector_mode'])) ? $parsedJSON['collector_mode'] : 'm0';
         $boiler_mode    = (isset($parsedJSON['boiler_mode']))    ? $parsedJSON['boiler_mode']    : 'm0';
         //error_log ("\n\nheater = ".$modes[$heater_mode]." collector = ".$modes[$collector_mode]." boiler = ".$modes[$boiler_mode]."\n");
         $ini_array[data]['heater_mode']    =  $heater_mode;
         $ini_array[data]['collector_mode'] =  $collector_mode;
         $ini_array[data]['boiler_mode']    =  $boiler_mode;

         write_ini_file($ini_array);

     }
 }


?>
