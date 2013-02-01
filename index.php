<?php
session_start();
require_once('AppInfo.php');
require_once('utils.php');
require_once('sdk/src/facebook.php');
require_once('condb.php');
$facebook = new Facebook(array(
  'appId'  => AppInfo::appID(),
  'secret' => AppInfo::appSecret()
));
if($facebook->getUser() > 0)
	$_SESSION['facebook'] = $facebook->getUser();
if(isset($_SESSION['facebook'],$_GET['fb']) && !isset($_SESSION['id']))
{
	$result=$db->query("SELECT a.*,b.Nombre as Campus FROM usuarios AS a,campus AS b WHERE Facebook={$_SESSION['facebook']}");
	if($result->num_rows == 1)
	{
		$fila = $result->fetch_assoc();
		$_SESSION['id'] = $fila['ID'];
		$_SESSION['nombre'] = $fila['Nombre'];
		$_SESSION['apellido'] = $fila['Apellido'];
		$_SESSION['carrera'] = $fila['Carrera'];
		$_SESSION['id_campus'] = $fila['ID_Campus'];
		$_SESSION['campus'] = $fila['Campus'];
		$_SESSION['clave'] = $fila['Clave'];
		$_SESSION['carrera'] = $fila['Carrera'];
		$_SESSION['tipo'] = $fila['Tipo'];
	}
	$result->free();
}
if(isset($_POST['clave'],$_POST['password']) && !isset($_SESSION['id']))
{
	$clave=$db->real_escape_string($_POST['clave']);
	$password=$db->real_escape_string($_POST['password']);
	if($db->real_query("SELECT a.*,b.Nombre AS Campus FROM usuarios AS a,campus AS b WHERE Clave='$clave' AND Password='$password' AND a.ID_Campus = b.ID"))
	{
		$result = $db->store_result();
		if($result->num_rows == 1)
		{
			$fila = $result->fetch_assoc();
			$_SESSION['id'] = $fila['ID'];
			$_SESSION['nombre'] = $fila['Nombre'];
			$_SESSION['apellido'] = $fila['Apellido'];
			$_SESSION['carrera'] = $fila['Carrera'];
			$_SESSION['id_campus'] = $fila['ID_Campus'];
			$_SESSION['campus'] = $fila['Campus'];
			$_SESSION['clave'] = $fila['Clave'];
			$_SESSION['carrera'] = $fila['Carrera'];
			$_SESSION['tipo'] = $fila['Tipo'];
			$result->free();
			if(!isset($fila['Facebook']) && isset($_SESSION['facebook']))
				$db->query('UPDATE usuarios SET Facebook=' . $facebook->getUser() . ' WHERE ID=' . $_SESSION['id']);
		}
		else
			$erroneo = 'Usuario o Password erroneo';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#login").click(function(e) {
        $("#accion").val("login").parent().submit();
    });
	$("#registrate").click(function(e) {
        $("#accion").val("registrar").parent().submit();
    });
});
window.fbAsyncInit = function() {
	FB.init({
		appId      : '378150628871115',
		status     : true, 
		cookie     : true,
		xfbml      : true,
		oauth      : true,
	});
	FB.Event.subscribe('auth.login', function () {
		window.location.href = "index.php?fb=true&rand=" + Math.random();
	});
};
(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
}(document));
</script>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="fb-root"></div>
<div id="principal">
<?php
if(isset($_SESSION['id']))
{
?>
<div id="flogin">
Bienvenido <?=$_SESSION['nombre'] . ' ' . $_SESSION['apellido']?><br />
Te redirigiremos a la pagina en unos segundos
<script type="text/javascript">
setTimeout(function() {
	window.location.href = 'sistema.php';
}, 3000);
</script>
</div>
<?php
}
else {
?>
<form id="flogin" action="index.php" method="post">
<img src="images/logoglassroom.png" id="logo" /><br />
Matricula<br />
<input type="text" name="clave" placeholder="matricula:" /><br />
<input type="password" name="password" placeholder="password:" /><br />
<input type="hidden" name="accion" id="accion" />
<?php
if(isset($erroneo))
{
?>
<span><?=$erroneo?></span><br />
<?php
}
?>
<a href="#" id="login" class="boton">Login</a>
<?php
if(!isset($_SESSION['facebook']))
{
?>
or <div class="fb-login-button" data-scope="publish_stream,offline_access">Inicia con facebook</div>
<?php
}
?>
</form>
<?php
}
require_once('cerdb.php');
?>
</div>
</body>
</html>
