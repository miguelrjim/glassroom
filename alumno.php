<?
session_start();
$_SESSION['username']="alumno";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="gr.css" rel="stylesheet" type="text/css" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
<title>glassroom</title>
   <style>
        body{
            padding: 0;
            margin:0 auto;
            font-family: sans-serif, cursive,cambria;
            font-size: 11px;
        }
    </style>
     <link type="text/css" rel="stylesheet" media="all" href="chat.css"/>
     <link type="text/css" rel="stylesheet" media="all" href="screen.css">       
</head>
<body>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="chat.js"></script>
<div id="head">		
	<div id="headcontain"><div id="headleft"><img src="images/logo.png" alt="Glassroom"/></div><div id="headright">
     <ul class="menu"><li><a href="#">intercampus</a></li><li><a href="#">settings</a></li></ul></div></div>
</div>

<div class="contenidoalumno">
<div style="margin-bottom:10px;"><h3>Luis Antonio Villarreal Castilla</h3></div>
	<div class="columnaizq">
    	   <div class="foto"><img src="images/foto.png" style="-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px;"/></div>
			<p>A00999019<br/>Technology and Business Engineer<br/>Sixth semester<br/>Campus Saltillo</p> 
        <div class="chat"></div><br/>
		<a href="javascript:void(0)" onclick="javascript:chatWith('maestro')">Chatear con prof</a>
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
    	<div class="calendar"><img src="images/calendar.png"/></div><br/>
       	<div class="notes"><img src="images/notes.png"/></div><br/>
        <div class="notifications"><img src="images/face.png"/></div><br/>

    
    </div>		


<div id="foot"><p>&copy; Glassroom 2012</p></div>
</div>
</body>
</html>
