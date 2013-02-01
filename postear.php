<?php
session_start();
if(!isset($_SESSION['id'],$_POST['curso'],$_POST['titulo'],$_POST['descripcion'],$_POST['tipo'])) die();
require_once('condb.php');
$curso = $db->real_escape_string($_POST['curso']);
$titulo = $db->real_escape_string($_POST['titulo']);
$descripcion = $db->real_escape_string($_POST['descripcion']);
$tipo = $db->real_escape_string($_POST['tipo']);
$fecha_inicio = isset($_POST['fecha_inicio']) ? "'" . $db->real_escape_string($_POST['fecha_inicio']) . "'" : 'NOW()';
$fecha_fin = isset($_POST['fecha_fin']) ? "'" . $db->real_escape_string($_POST['fecha_fin']) . "'" : NULL;
$res = array();
if($db->multi_query("INSERT INTO dashboard (ID_Curso,Titulo,Descripcion,Fecha_Inicio,Fecha_Fin,ID_Autor,Tipo) VALUES ($curso,'$titulo','$descripcion',$fecha_inicio,$fecha_fin,{$_SESSION['id']},$tipo);SELECT a.*,CONCAT(b.Nombre, ' ', b.Apellido) AS Autor FROM dashboard AS a INNER JOIN usuarios AS b ON a.ID_Autor=b.ID ORDER BY a.ID DESC LIMIT 1"))
{
	$db->next_result();
	$result = $db->store_result();
	$res['success'] = true;
	$fila=$result->fetch_assoc();
	$result->free();
	$id_dash = $fila['ID'];
	$mensaje = $fila['Autor'] . ($fila['Tipo'] == 1 ? ' ha publicado una nota urgente en la plataforma' : ($fila['Tipo'] == 2 ? ' ha publicado una nueva tarea en la plataforma' : ' ha publicado un anuncio en la plataforma'));
	if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0)
	{
		$nombre = $db->real_escape_string($_FILES['archivo']['name']);
		$mime = $db->real_escape_string($_FILES['archivo']['type']);
		if($db->real_query("INSERT INTO archivos_dashboard (ID_Dashboard,Nombre,MIME) VALUES ($id_dash,'$nombre','$mime')"))
		{
			if($db->affected_rows == 1)
				move_uploaded_file($_FILES['archivo']['tmp_name'], "archivos/$id_dash");
			else
				unlink($_FILES['archivo']['tmp_name']);
		}
	}
	include 'notificarfb.php';
	$fbs=$db->query("SELECT a.Facebook FROM usuarios AS a INNER JOIN usuarios_materias AS b ON a.ID=b.ID_Usuario AND b.ID_Curso=$curso WHERE a.Facebook IS NOT NULL AND a.ID != {$fila['ID_Autor']}");
	while($fb=$fbs->fetch_assoc())
		notificarfb($fb['Facebook'], $mensaje);
}
echo json_encode($res);
require_once('cerdb.php');
?>