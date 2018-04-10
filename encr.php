<?php



function char_encode($str){
	//echo strlen($str);
	//echo $str;
	$str=gzencode($str,9);
	//echo ":".strlen($str);
	$pub=file_get_contents("/var/www/html/public.pem");
	$pk  = openssl_get_publickey($pub);
	//var_dump($pk);
	$encrypted="";
	//echo "<br>$str<br>";
	openssl_public_encrypt($str, $encrypted, $pk);
	//echo "<font color=red><br>$encrypted<br></font>";
	//echo ":".strlen($encrypted)."<br>";
	return $encrypted;
}




function char_decode($str){
	//echo "dev_dec:".strlen($str)."<br>";  
	$key=file_get_contents("/var/www/html/private.pem");
	$private_key  = openssl_pkey_get_private($key, "theinizio");
	//var_dump($private_key);
	openssl_private_decrypt($str, $out, $private_key);
	//var_dump($out);
	return gzdecode($out);
}




?>


