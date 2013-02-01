<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
if(!isset($_SESSION['id'],$_POST['curso'])) die();
require_once('condb.php');
$curso = $db->real_escape_string($_POST['curso']);
if($curso == 0) die();
$res = array();
if($db->real_query("SELECT a.* FROM usuarios AS a INNER JOIN usuarios_materias AS b ON a.ID=b.ID_Usuario AND b.ID_Curso=$curso"))
{
	$res['success'] = true;
	$res['objetos'] = array();
	$result = $db->store_result();
	while($fila=$result->fetch_assoc())
		$res['objetos'][] = $fila;
	$result->free();
}
echo json_encode($res);
require_once('cerdb.php');
?>