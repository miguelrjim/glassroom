<?php
session_start();
if(!isset($_SESSION['id'],$_POST['curso'],$_POST['titulo'],$_POST['descripcion'])) die();
require_once('condb.php');
$curso = $db->real_escape_string($_POST['curso']);
$titulo = $db->real_escape_string($_POST['titulo']);
$descripcion = $db->real_escape_string($_POST['descripcion']);
$res = array();
if($db->multi_query("INSERT INTO vault (ID_Curso,Titulo,Descripcion,Fecha,ID_Autor,ID_Campus,Clave_Materia) VALUES ($curso,'$titulo','$descripcion',NOW(),{$_SESSION['id']},{$_SESSION['id_campus']},(SELECT Clave_Materias FROM cursos WHERE ID=$curso));SELECT ID FROM vault ORDER BY ID DESC LIMIT 1"))
{
	$res['success'] = $db->affected_rows == 1 ? true : false;
	$db->next_result();
	$result = $db->store_result();
	$fila = $result->fetch_assoc();
	$id_vault = $fila['ID'];
	$result->free();
	if(isset($_FILES['archivo']) && $_FILES['archivo']['err'] == 0 && $res['success'])
	{
		$nombre = $db->real_escape_string($_FILES['archivo']['name']);
		$mime = $db->real_escape_string($_FILES['archivo']['type']);
		if($db->real_query("INSERT INTO archivos_vault (ID_Vault,Nombre,MIME) VALUES ($id_vault,'$nombre','$mime')"))
		{
			if($db->affected_rows == 1)
				move_uploaded_file($_FILES['archivo']['tmp_name'], "archivos/$id_vault");
			else
				unlink($_FILES['archivo']['tmp_name']);
		}
	}
}
else
	$res['success'] = false;
echo json_encode($res);
require_once('cerdb.php');
?>