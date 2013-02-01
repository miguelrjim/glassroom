<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
if(!isset($_SESSION['id'],$_POST['curso'])) die();
require_once('condb.php');
$inicio = isset($_POST['inicio']) ? $db->real_escape_string($_POST['inicio']) : 0;
$curso = $db->real_escape_string($_POST['curso']);
$subq = $curso == 0 ? "AND a.ID_Curso IN (SELECT ID_Curso FROM usuarios_materias WHERE ID_Usuario={$_SESSION['id']})" : "AND a.ID_Curso=$curso";
$res = array();
if($db->real_query("SELECT a.*,CONCAT(b.Nombre, ' ', b.Apellido) as Autor FROM vault AS a INNER JOIN usuarios AS b ON a.ID_Autor=b.ID WHERE a.ID > $inicio $subq ORDER BY a.ID DESC"))
{
	$res['success'] = true;
	$res['objetos'] = array();
	$result = $db->store_result();
	while($fila=$result->fetch_assoc())
		$res['objetos'][] = $fila;
	$result->free();
	$id_vault;
	$stmt = $db->prepare('SELECT * FROM archivos_vault WHERE ID_Vault=?');
	$stmt->bind_param('i', $id_vault);
	foreach($res['objetos'] as &$fila)
	{
		$id_dash = $fila['ID'];
		$stmt->execute();
		$fila['Archivos'] = array();
		$arch = array();
		$stmt->bind_result($arch['id'], $arch['vault'], $arch['nombre'], $arch['mime']);
		while($stmt->fetch())
			$fila['Archivos'][] = $arch;
	}
	$stmt->close();
}
echo json_encode($res);
require_once('cerdb.php');
?>