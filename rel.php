<!DOCTYPE html>
<html>
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>

<h2>Toggle Switch</h2>
collector
<label class="switch" >
  <input type="checkbox" id="collector">
  <span class="slider"></span>
</label>
heater
<label class="switch" >
  <input type="checkbox" checked id="heater">
  <span class="slider"></span>
</label><br><br>
<span id="temp">тóт бóдет температóра</span><br/>
<label class="switch">
  <input type="checkbox">
  <span class="slider round"></span>
</label>

<label class="switch">
  <input type="checkbox" checked>
  <span class="slider round"></span>
</label>

</body>
<script>
$("#collector").on("change",function(event){
	if($("#collector").is(":checked")){
		$.ajax('/temp.php').done(function(data){
				$("#temp").html(data);
				}
				);
			
		
	}   
});
$("#heater").on("change",function(event){
	if($("#heater").is(":checked"))
	    window.alert("heater checked");
});
</script>
</html> 
