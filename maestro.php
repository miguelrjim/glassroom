<?
session_start();
$_SESSION['username']="maestro";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="gr.css" rel="stylesheet" type="text/css" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
<title>Glassroom</title>
</head>
<body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="chat.js"></script>
<div id="head">		
	<div id="headcontain"><div id="headleft"><img src="images/logo.png" alt="Glassroom"/></div><div id="headright">
     <ul class="menu"><li><a href="#">intercampus</a></li><li><a href="#">settings</a></li></ul></div></div>
</div>

<div class="contenidoalumno">
<div style="margin-bottom:10px;"><h3>Victor Hugo Morales Carballo</h3></div>
	<div class="columnaizq">
    	   <div class="foto"><img src="images/fotomas.png" style="-webkit-border-radius:10px;"/></div>
			<p>A00999019<br/>Technology and Business Engineer<br/>Master in Management<br/>Campus Saltillo</p> 
        <div class="chat"></div><br/>
	<a href="javascript:void(0)" onclick="javascript:chatWith('alumno')">Chatear con alumno</a>
    </div>
    
	<div class="columnacentro">
    	<ul class="menumat">
        	<li><a href="#" class="sel">Technology</a></li>
            <li><a href="#">Math</a></li>
            <li><a href="#">Administration</a></li>
            <li><a href="#">Networks</a></li>
            <li><a href="#">Databases</a></li>
			<li><a href="#">OS</a></li>
        </ul>
    		<div class="materias">
          	  
            	<ul class="menudash">
                	<li><a href="#">Dashboard</a></li>/
               		<li><a href="#">Vault</a></li>
             	</ul>
              <div class="materiain">
              </div>
            </div>
    </div>
    
	<div class="columnader">
    	<img src="images/calendar.png"/><br/><br/>
       	<img src="images/notes.png"/>

    
    </div>		


<div id="foot"><p>&copy; Glassroom 2012</p></div>
</div>
</body>
</html>
