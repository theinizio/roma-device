<?php
class Relays{
	public static function getData(){
		$results=[];
		//multiplexor address pins are 2 0 6
		//multiplexor signal  pin   is 3
		exec("sudo gpio mode 3 input");
		
		exec("sudo gate 2 o l");
		exec("sudo gate 0 o l");
		exec("sudo gate 6 o l");
		exec("sudo gpio read 3", $answer1);
		
		
		//echo "Relay 1 is $answer1[0]"."<br/>";
		$results[] = $answer1[0];
		exec("sudo gate 2 o h");
		exec("sudo gate 0 o l");
		exec("sudo gate 6 o l");
		exec("sudo gpio read 3", $answer2);
		$results[] = $answer2[0];
		//echo "Relay 2 is $answer2[0]"."<br/>";
		
		exec("sudo gate 2 o l");
		exec("sudo gate 0 o h");
		exec("sudo gate 6 o l");
		exec("sudo gpio read 3", $answer3);
		$results[] = $answer3[0];
		//echo "Relay 3 is $answer3[0]"."<br/>";
		
		exec("sudo gate 2 o h");
		exec("sudo gate 0 o h");
		exec("sudo gate 6 o l");
		exec("sudo gpio read 3", $answer4);
		$results[] = $answer4[0];
		//echo "Relay 4 is $answer4[0]"."<br/>";
		
		exec("sudo gate 2 o l");
		exec("sudo gate 0 o l");
		exec("sudo gate 6 o h");
		exec("sudo gpio read 3", $answer5);
		$results[] = $answer5[0];
		//echo "Relay 5 is $answer5[0]"."<br/>";
		
		exec("sudo gate 2 o h");
		exec("sudo gate 0 o l");
		exec("sudo gate 6 o h");
		exec("sudo gpio read 3", $answer6);
		$results[] = $answer6[0];
		//echo "Relay 6 is $answer6[0]"."<br/>";
		
		exec("sudo gate 2 o l");
		exec("sudo gate 0 o h");
		exec("sudo gate 6 o h");
		exec("sudo gpio read 3", $answer7);
		$results[] = $answer7[0];
		//echo "Relay 7 is $answer7[0]"."<br/>";
		
		exec("sudo gate 2 o h");
		exec("sudo gate 0 o h");
		exec("sudo gate 6 o h");
		exec("sudo gpio read 3", $answer8);
		$results[] = $answer8[0];
		//echo "Relay 8 is $answer8[0]"."<br/>";
		
		return $results;

	}
}
?>
