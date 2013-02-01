<?php
session_start();
if(!isset($_SESSION['id'])) die();
include 'condb.php';
$res = array();
if($db->real_query("SELECT Nota FROM notas WHERE ID_Usuario={$_SESSION['id']}"))
{
	$result = $db->store_result();
	$res['success'] = $result->num_rows == 1 ? true : false;
	if($result->num_rows == 1)
	{
		$fila = $result->fetch_assoc();
		$res['nota'] = $fila['Nota'];
	}
}
else
	$res['success'] = false;
echo json_encode($res);
include 'cerdb.php';
?>