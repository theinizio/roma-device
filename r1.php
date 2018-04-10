<?php
exec("sudo gpio mode 3 input");exec("sudo gate 2 o l");exec("sudo gate 0 o l");exec("sudo gate 6 o l");exec("sudo gpio read 3", $answer1); echo $answer1[0]." "; if(!intval($answer1[0])) echo" checked ";
?>
