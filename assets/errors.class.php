<?php
class Errors{
	public static function getErrors($db, $uncleared=true){
		$errors=[0,0,0,0,0,0,0,0,0,0];
		
		if($uncleared){$db->bind("cleared", "0");}
			else      {$db->bind("cleared", "1");}
		
		$result = $db->column("select `err_no` from errors where `cleared`=:cleared");
		//var_dump($result);
		
			if(count($result)>0)foreach($result as $val){
			   $errors[$val-1]="1";
			}
		//echo "\n<errors>";
		//var_dump($errors);	
		$errors = array_reverse($errors);
		$errors = implode($errors); //объединяем массив в число
		//var_dump($errors);
		$errors =  bindec($errors); //преобразуем в десятичное число для педеачи на сервер
		//var_dump($errors);
		//echo "</errors>\n";
		return $errors;
	}

	public static function saveError($db, $err_num, $err_description=""){
		if(is_numeric($err_num)){
			$db->bind("err_num", $err_num);
			$db->bind("description", $err_description);
			return $db->query(
				"INSERT INTO `errors`(`err_no`, `description`, `cleared`) 
				              VALUES (:err_num, :description,   0 )"
				         );
		}else return -1;
	}


}
?>
