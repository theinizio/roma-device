<?php 

     if(is_file('/etc/armbianmonitor/datasources/soctemp')){
	  while(1){ 
	    $temp = file('/etc/armbianmonitor/datasources/soctemp');
	    $temp=$temp[0];
	    
	    
	    
	    if($temp>45000)
			$output=exec('gate 2 o l',$buf);
		
		if($temp<40000)
			$output=exec('gate 2 o h',$buf);
		echo $temp/1000;
		echo " \xc2\xb0\C\n";
	sleep(5);
     }
  }
?>
