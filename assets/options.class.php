<?php
class Options{
	public $all=[];
	function __construct($db){
	    
	    $data   =  $db->query("select * from `options`");
	    //var_dump($data);
	    foreach($data as $value){
			$this->all[$value['param']] = $value['value'];
		}
		
	}
}
?>
