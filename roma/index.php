<?php
if(htmlspecialchars($_POST["r1"])=="on"){ exec("sudo gate 10 o l");}else exec("sudo gate 10 o h");
if(htmlspecialchars($_POST["r2"])=="on"){ exec("sudo gate 12 o l");}else exec("sudo gate 12 o h");
if(htmlspecialchars($_POST["r3"])=="on"){ exec("sudo gate 13 o l");}else exec("sudo gate 13 o h");
if(htmlspecialchars($_POST["r4"])=="on"){ exec("sudo gate 5  o l");}else exec("sudo gate 5  o h");
if(htmlspecialchars($_POST["r5"])=="on"){ exec("sudo gate 11 o l");}else exec("sudo gate 11 o h");
if(htmlspecialchars($_POST["r6"])=="on"){ exec("sudo gate 1  o l");}else exec("sudo gate 1  o h");
if(htmlspecialchars($_POST["r7"])=="on"){ exec("sudo gate 16 o l");}else exec("sudo gate 16 o h");
if(htmlspecialchars($_POST["r8"])=="on"){ exec("sudo gate 15 o l");}else exec("sudo gate 15 o h");
?>


<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">

    <title>Контроллер Романа</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
   
    <link href="table.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/js/fancybox/jquery.fancybox.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Dont actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
 <body data-spy="scroll" data-offset="0" data-target="#theMenu">
	
<form name="form1" id="mainForm" method="post"enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
<!-- Rectangular switch -->

<?php
echo"
<span>Kotel 1</span>
<label class=\"switch\">
  <input type=\"checkbox\" name=\"r1\" id=\"r1\" onChange=\"submit();\"";
   exec("sudo gpio mode 3 input");exec("sudo gate 2 o l");exec("sudo gate 0 o l");exec("sudo gate 6 o l");exec("sudo gpio read 3", $answer1);if(!intval($answer1[0])) echo" checked ";
   
  echo ">
  <span class=\"slider\"></span>
</label>
<br>
";
?>
<span>Kotel 2</span>
<label class="switch">
  <input type="checkbox" name="r2" id="r2" onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o h");exec("sudo gate 0 o l");exec("sudo gate 6 o l");exec("sudo gpio read 3", $answer2);if(!intval($answer2[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>
<br>
<span>Kotel 3</span>
<label class="switch">
  <input type="checkbox" name="r3" id="r3"onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o l");exec("sudo gate 0 o h");exec("sudo gate 6 o l");exec("sudo gpio read 3", $answer3);if(!intval($answer3[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>
<br>
<span>Nagrevatel' </span>
<label class="switch">
  <input type="checkbox" name="r4" id="r4"onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o h");exec("sudo gate 0 o h");exec("sudo gate 6 o l");exec("sudo gpio read 3", $answer4);if(!intval($answer4[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>
<br>
<span>Relay 5</span>
<label class="switch">
  <input type="checkbox" name="r5" id="r5"onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o l");exec("sudo gate 0 o l");exec("sudo gate 6 o h");exec("sudo gpio read 3", $answer5);if(!intval($answer5[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>
<br>
<span>Relay 6</span>
<label class="switch">
  <input type="checkbox" name="r6" id="r6"onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o h");exec("sudo gate 0 o l");exec("sudo gate 6 o h");exec("sudo gpio read 3", $answer6);if(!intval($answer6[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>
<br>
<span>Relay 7</span>
<label class="switch">
  <input type="checkbox" name="r7" id="r7"onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o l");exec("sudo gate 0 o h");exec("sudo gate 6 o h");exec("sudo gpio read 3", $answer7);if(!intval($answer7[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>
<br>
<span>Relay 8</span>
<label class="switch">
  <input type="checkbox" name="r8" id="r8"onChange="submit();"<?php exec("sudo gpio mode 3 input");exec("sudo gate 2 o h");exec("sudo gate 0 o h");exec("sudo gate 6 o h");exec("sudo gpio read 3", $answer8);if(!intval($answer8[0])) echo" checked ";?>>
  <span class="slider"></span>
</label>

</form>



    <!-- Menu -->
    <nav class="menu" id="theMenu">
        <div class="menu-wrap">
            <h1 class="logo"><a href="#home">На главную</a></h1>
            <i class="fa fa-times menu-close"></i>
            <a href="#services" class="smoothScroll">Настройки</a>

        </div>

        <!-- Menu button -->
        <div id="menuToggle"><i class="fa fa-bars"></i></div>
    </nav>

 <section id="about" name="about"></section>
    <div id="aboutwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 name" >



<table id="customers" align=center   >


<?php
 require_once 'functions.php';
 
 $link=new mysqli('localhost', 'kotel', 'new_password', 'alldata');
if($link->connect_errno){
	die('Ошибка соединения:'.$link->connect_error);
}

$link->set_charset("utf8");
if($result = $link->query("select t_address,t_name from temp_sensors;")){
	
		while($row=$result->fetch_array()){
			$sensors2[$row[0]]=$row[1];
		}
	$result->close();
	$link->next_result();              
}

if($result = $link->query("select(select AVG(t1) FROM( select t1 from data order by time desc LIMIT 5) items)as t1,
							(select AVG(t2) FROM( select t2 from data order by time desc LIMIT 5) items)as t2,
							(select AVG(t3) FROM( select t3 from data  order by time desc LIMIT 5) items)as t3,
							(select AVG(t4) FROM( select t4 from data order by time desc LIMIT 5) items)as t4;")){
	
		while($row=$result->fetch_array()){
			$t1=$row[0];
			$t2=$row[1];
			$t3=$row[2];
			$t4=$row[3];
			
			//echo $t1.$t2.$t3.$t4."<BR/>";
		}
		
			//echo $result."<br/>";
	$result->close();
	$link->next_result();              
}


 
 
 
$heater_selector="";
$collector_selector="";
$boiler_selector="";
for($i=0;$i<count($modes)-1;$i++){
    $val = array_search("m".$i,$modes);

    $heater_selector.= "<option";
    if($data[heater_mode]=="m".$i)
        $heater_selector.= " selected";
    $heater_selector.= " value=\"m$i\">$val</option>\n";

    $collector_selector.= "<option";
    if($data[collector_mode]=="m".$i)
        $collector_selector.= " selected";
    $collector_selector.= " value=\"m$i\">$val</option>\n";

    $boiler_selector.= "<option";
    if($data[boiler_mode]=="m".$i)
        $boiler_selector.= " selected";
    $boiler_selector.= " value=\"m$i\">$val</option>\n";
}



/*
echo "

    <tr>
        <td>
            Помещение:<br/>
            
            $data[heater_temp]&deg;C,&nbsp;".array_search($data[heater_mode],$modes)."
         </td>
         <td>
            Улица:<br/>
            $data[collector_temp]&deg;C,&nbsp;".array_search($data[collector_mode],$modes)."
         </td>
         <td>
            Нагреватель:<br/>
            $data[boiler_temp]&deg;C,&nbsp;".array_search($data[boiler_mode],$modes)."
         </td>
    </tr>
*/
echo "

    <tr>
        <td>
            Помещение:<br/>
            
            $t1&deg;C,&nbsp;".array_search($data[heater_mode],$modes)."
         </td>
         <td>
            Улица:<br/>
            $t2&deg;C,&nbsp;".array_search($data[collector_mode],$modes)."
         </td>
         <td>
            Нагреватель:<br/>
            $t3&deg;C,&nbsp;".array_search($data[boiler_mode],$modes)."
         </td>
	<td>
		Котел:<br/>
		$t4&deg;C
	</td>
    </tr>


</table>
    </div><! --/col-lg-8-->

            </div><!-- /row -->
        </div><!-- /container -->
    </div><!-- /aboutwrap -->



    <! -- SERVICE SECTION -->
    <section id=\"services\" name=\"services\"></section>
    <div id=\"servicewrap\">
        <div class=\"container\">
                <div class=\"col-lg-3 service\">
                 <h3>Настройки</h3>
        <div>
          <nobr>
            Время контроллера
            <input type=text size=5 value=00:00 id=contr_time>
            <button onclick=change_time >Изменить</button>
          </nobr>
          <br>
           <button onclick=clear_errors>Удалить ошибки</button>
        </div>
        <table id=\"customers\" align=center   >
          <tr>
          <td >
            Котел:<br/>
           <select  name=\"heater_mode\" id=\"heater_mode\">
                   $heater_selector
           </select>
          </td>
          <td>
            Коллектор:<br/>
             <select  name=\"collector_mode\" id=\"collector_mode\">
                   $collector_selector
           </select>
         </td>
         <td>
            Нагреватель:<br/>
             <select  name=\"boiler_mode\" id=\"boiler_mode\">
                   $boiler_selector
             </select>
         </td>
    </tr>
    </table>
    <script>
    ";
?>     var time = document.getElementById("contr_time");
       var currentdate = new Date();
       time.value=
       currentdate.getHours() + ":"
                + currentdate.getMinutes();

    function change_time(){
        var data={};
        data.dataType="changeTime";
        data.time = time.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
        // send the collected data as JSON
        xhr.send(JSON.stringify(data));
        xhr.onloadend = get_values;
    }
      var e1 = document.getElementById("heater_mode");
      e1.onchange = send;
      var e2 = document.getElementById("collector_mode");
            e2.onchange = send;
      var e3 = document.getElementById("boiler_mode");
            e3.onchange = send;

   function send(){
       var data = {};
       var e1 = document.getElementById("heater_mode");

      var heater_mode = e1.selectedIndex;
      var e2 = document.getElementById("collector_mode");

      var collector_mode = e2.selectedIndex;
      var e3 = document.getElementById("boiler_mode");

      var boiler_mode = e3.selectedIndex;
      data.dataType = 'change_mode';
      data.heater_mode    = "m"+heater_mode;
      data.collector_mode = "m"+collector_mode;
      data.boiler_mode    = "m"+boiler_mode;

      //console.log("data.heater_mode="+e1.selectedIndex+"|"+data.heater_mode);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'save.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
      // send the collected data as JSON
      xhr.send(JSON.stringify(data));
      xhr.onloadend = get_values;
    }

    function get_values(){
        //console.log("get_values");
        window.location.reload();
    }
    </script>



        <div class="col-lg-3 service">
         <h3>Профили</h3>
          <table id="customers" align=center   >
            <tr>
              <td  rowspan=2 textalign=center align=center>
                Нормальный:<br/>
                </td>
                <td  colspan=3 textalign=center align=center>

                <nobr>Время работы
                с&nbsp;<input type=text size=5 value="00:00">
                 &nbsp;до&nbsp;
                <input type=text size=5 value="00:00"> </nobr>
              </td>
            </tr>
            <tr>
              <td>
          <nobr>В комнате:</nobr>
         <nobr> t= <input type=text size=3 value=<?php echo  $t4;?>&deg;C</nobr>
          </td>
          <td>
          Коллектор:
          t=&nbsp;46&deg;C
          </td>
          <td>
          <nobr>Горячая вода:</nobr>
          <nobr>t= <input type=text size=3 value=50>&deg;C </nobr>
          </td>
            </tr>
            <tr>

              <td  rowspan=2 textalign=center align=center>
              Эко_1:<br/>
              </td>
              <td colspan=3 textalign=center align=center>

               Время работы  <br/>
               <nobr>&nbsp;&nbsp;&nbsp;&nbsp;с
                <input type=text size=5 value="06:00">
                 &nbsp;до&nbsp;
                <input type=text size=5 value="10:00"></nobr><br/>
              <nobr>и c&nbsp;
              <input type=text size=5 value="17:00">
                 &nbsp;до&nbsp;
                <input type=text size=5 value="21:00"></nobr>
             </td>
           </tr>
           <tr>
          <td>
          <nobr>В комнате: </nobr>
          <nobr>t= <input type=text size=3 value=22>&deg;C </nobr>
          </td>
          <td>
          Коллектор:
          t=&nbsp;46&deg;C
          </td>
          <td>
          <nobr>Горячая вода: </nobr>
          <nobr>t= <input type=text size=3 value=50>&deg;C </nobr>
          </td>
          </tr>

           <tr>
             <td  rowspan=2 textalign=center align=center>
              Эко_2:<br/>
              </td>
              <td colspan=3 textalign=center align=center>
                 Время работы  <br/>
               <nobr>&nbsp;&nbsp;&nbsp;&nbsp;с
                <input type=text size=5 value="10:00">
                 &nbsp;до&nbsp;
                <input type=text size=5 value="17:00"></nobr><br/>
              <nobr>и c&nbsp;
              <input type=text size=5 value="21:00">
                 &nbsp;до&nbsp;
                <input type=text size=5 value="06:00"></nobr>
             </td>
           </tr>
           <tr>
          <td>
          <nobr>В комнате:</nobr>
          <nobr>t= <input type=text size=3 value=<?php echo $t2;?>>&deg;C </nobr>
          </td>
          <td>
          Коллектор:
          t=&nbsp;-3&deg;C
          </td>
          <td>
          <nobr>Горячая вода:</nobr>
          <nobr>t= <input type=text size=3 value=25>&deg;C</nobr>
          </td>
          </tr>
          </table>
	</div>


        </div><! --/container -->
    </div><! --/servicewrap -->


    <! -- SERVICE SECTION -->
          <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="assets/js/classie.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/smoothscroll.js"></script>
    <script src="assets/js/jquery.stellar.min.js"></script>
    <script src="assets/js/fancybox/jquery.fancybox.js"></script>
    <script src="assets/js/main.js"></script>
        <script>
        $(function(){
            $.stellar({
                horizontalScrolling: false,
                verticalOffset: 40
            });
        });
        </script>

        <script type="text/javascript">
      $(function() {
        //    fancybox
          jQuery(".fancybox").fancybox();
      });
       </script>


  </body>
</html>
