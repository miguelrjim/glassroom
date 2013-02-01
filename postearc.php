<?php
session_start();
if(!isset($_SESSION['id'],$_POST['curso'],$_POST['texto'])) die();
require_once('condb.php');
$curso = $db->real_escape_string($_POST['curso']);
$texto = $db->real_escape_string($_POST['texto']);
$res = array();
if($db->real_query("INSERT INTO chat (ID_Curso,ID_Campus,ID_Autor,Texto) VALUES ($curso,{$_SESSION['id_campus']},{$_SESSION['id']},'$texto')"))
	$res['success'] = $db->affected_rows == 1 ? true : false;
echo json_encode($res);
require_once('cerdb.php');
?>