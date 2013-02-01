<?php
session_start();
if(!isset($_SESSION['id'],$_POST['nota'])) die();
include 'condb.php';
$res = array();
if($db->real_query("REPLACE INTO notas (ID_Usuario,Nota) VALUES ({$_SESSION['id']}, '{$_POST['nota']}')"))
	$res['success'] = $db->affected_rows == 1 ? true : false;
else
	$res['success'] = false;
echo json_encode($res);
include 'cerdb.php';
?>