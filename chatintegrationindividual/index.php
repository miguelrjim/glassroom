<?
session_start();
$_SESSION['username']="Victor"
?>

<html>
    <head>
        <title>Prueba Chat integration</title>
    <style>
        body{
            padding: 0;
            margin:0 auto;
            font-family: sans-serif, cursive,cambria;
            font-size: 11px;
        }
    </style>
    <link type="text/css" rel="stylesheet" media="all" href="chat.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="screen.css" 
    </head>
    <body>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="chat.js"></script>
   <div id="main_container">
 
    <a href="javascript:void(0)" onclick="javascript:chatWith('Juan')">Chatear con A00948696</a>
   </div>

    </body>
    
    
</html>
