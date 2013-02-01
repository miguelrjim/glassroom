<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
if(!isset($_SESSION['id'],$_POST['curso'])) die();
require_once('condb.php');
$inicio = isset($_POST['inicio']) ? $db->real_escape_string($_POST['inicio']) : 0;
$curso = $db->real_escape_string($_POST['curso']);
$res = array();
if($db->real_query("SELECT a.*,CONCAT(b.Nombre, ' ', b.Apellido) as Autor FROM chat AS a INNER JOIN usuarios AS b ON a.ID_Autor=b.ID WHERE a.ID > $inicio ORDER BY a.ID DESC"))
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