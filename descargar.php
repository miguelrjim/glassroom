<?php
session_start();
if(!isset($_SESSION['id'],$_GET['id'])) die();
require_once('condb.php');
$id_arch = $db->real_escape_string($_GET['id']);
$db->real_query("SELECT * FROM archivos_dashboard WHERE ID=$id_arch");
$result=$db->store_result();
$fila=$result->fetch_assoc();
$result->free();
header("Content-type: {$fila['MIME']}");

// It will be called downloaded.pdf
header("Content-Disposition: attachment; filename={$fila['Nombre']}");

// The PDF source is in original.pdf
readfile("archivos/{$fila['ID_Dashboard']}");
require_once('cerdb.php');
?>