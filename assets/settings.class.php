<?php
class Settings{
	public $all=[];
	function __construct($db){
	    
	    $result   =  $db->query("select * from `settings` order by `id` desc limit 1");
	    $this->all = $result[0];
	    
	   
		
	}
}
?>
