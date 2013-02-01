<?php
session_start();
date_default_timezone_set('America/Monterrey');
if(!isset($_SESSION['id']))
{
	header("Location: index.php");
	die();
}
require_once('condb.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="gr.css" rel="stylesheet" type="text/css" /> 
<link href="jquery.datepick.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Glassroom</title>
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery.datepick.pack.js"></script>
<script type="text/javascript">
var materia_ele=0;
var tareas_ind=0;
var anuncios_ind=0;
var urgentes_ind=0;
var vault_ind=0;
var chat_ind=0;
var timer = null;
var timerc = null;
$(document).ready(function(e) {
	$("#materias").on("click", "li", function(e) {
		if(materia_ele == $(this).attr("id").substr(3)) return;
		$("#dashboard-anuncios,#dashboard-tareas,#dashboard-urgentes,#vault-posts,#usuarios,#chat-msjs").html("");
		$("#mat" + materia_ele).removeClass("sel");
		materia_ele = $(this).attr("id").substr(3);
		$("#mat" + materia_ele).addClass("sel");
		if(materia_ele == 0)
			$("#f-dashboard,#f-vault,#chat").hide();
		else
			$("#f-dashboard,#f-vault,#chat").show();
		chat_ind = 0;
		nuevo_feed();
		clearTimeout(timer);
		if(timerc != null) clearTimeout(timerc);
		timer = setInterval(function() {
			feed();
		}, 5000);
		if(materia_ele != 0) { leerChat(); usuarios(); } 
	});
	$("#a-dashboard").click(function(e) {
        $("#dashboard").show();
		$("#vault").hide();
		$("#a-dashboard").addClass("sel");
		$("#a-vault").removeClass("sel");
    }).addClass("sel");
	$("#a-vault").click(function(e) {
        $("#vault").show();
		$("#dashboard").hide();
		$("#a-vault").addClass("sel");
		$("#a-dashboard").removeClass("sel");
    });
	// Obtener contenido de la pagina
	$("#vault-texto").change(function(e) {
        
    });
	$("#f-dashboard").submit(function(e) {
        e.preventDefault();
		var data = new FormData($("#f-dashboard")[0]);
		data.append("curso", materia_ele);
		var that=this;
		$.ajax({  
			url: "postear.php?r="+Math.random(),  
			type: "POST",  
			data: data,  
			processData: false,  // tell jQuery not to process the data  
			contentType: false,  // tell jQuery not to set contentType  
			success: function(data) {
				limpiar(that);
				data = JSON.parse(data);
				if(data.success)
				{}
			},
			error: function() {
				limpiar(that);
			},
			timeout: function() {
				limpiar(that);
			},
			abort: function() {
				limpiar(that);
			},
			parsererror: function() {
				limpiar(that);
			}
		});
    });
	$("#f-vault").submit(function(e) {
        e.preventDefault();
		var data = new FormData($("#f-vault")[0]);
		data.append("curso", materia_ele);
		var that=this;
		$.ajax({  
			url: "postearv.php?r="+Math.random(),  
			type: "POST",  
			data: data,  
			processData: false,  // tell jQuery not to process the data  
			contentType: false,  // tell jQuery not to set contentType  
			success: function(data) {
				limpiar(that);
				data = JSON.parse(data);
				if(data.success)
				{}
			},
			error: function() {
				limpiar(that);
			},
			timeout: function() {
				limpiar(that);
			},
			abort: function() {
				limpiar(that);
			},
			parsererror: function() {
				limpiar(that);
			}
		});
    });
	$("#f-chat").submit(function(e) {
        e.preventDefault();
		var that=this;
		$.post("postearc.php", {curso: materia_ele, texto: $("#chat-post").val()}, function(data) {
			limpiar(that);
			data = JSON.parse(data);
			if(data.success)
			{}
		});
    });
	$("#dashboard,#vault").on("click", ".mostrar", function(e) {
		$(this).parent().toggleClass("completo");
		$(this).toggleClass("fase");
	});
	$("#nota").blur(function(e) {
        GuardaNota();
    });
	$(window).unload(function(e) {
        GuardaNota();
    });
	$("#chat-post").keypress(function(e) {
        if(e.keyCode == 13)
		{
			e.preventDefault();
			$(this).parent().submit();
		}
    });
	$("form").submit(function(e) {
        $(this).find(".bloqueo").show();
		$(this).find("input[type='submit']").attr("disabled", "disabled");
    });
	$('#fecha_inicio,#fecha_fin').datepick({dateFormat: 'yyyy-mm-dd'});
	$.getJSON("notasl.php", function(data) {
		if(data.success)
			$("#nota").val(data.nota);
	});
	nuevo_feed() 
	timer = setInterval(function() {
		feed();
	}, 5000);
		
});

function limpiar(that)
{
	$(that).find("input[type='text'],textarea").val("");
	$(that).find("input[type='submit']").removeAttr('disabled');
	$(that).find(".bloqueo").hide();
}

function nuevo_feed()
{
	tareas_ind = 0;
	anuncios_ind = 0;
	urgentes_ind = 0;
	vault_ind = 0;
	feed();
}

function feed()
{
	actualizar(1, urgentes_ind, function(data) {
		escribirUrgente(data.ID, data.Autor, data.ID_Autor, data.Titulo, data.Descripcion, data.Archivos);
		urgentes_ind = data.ID;
	});
	actualizar(2, tareas_ind, function(data) {
		escribirTarea(data.ID, data.Autor, data.ID_Autor, data.Titulo, data.Descripcion, data.Fecha_Fin, data.Archivos);
		tareas_ind = data.ID;
	});
	actualizar(3, anuncios_ind, function(data) {
		escribirAnuncio(data.ID, data.Autor, data.ID_Autor, data.Titulo, data.Descripcion, data.Archivos);
		anuncios_ind = data.ID;
	});
	actualizarv(vault_ind, function(data) {
		escribirVault(data.ID, data.Autor, data.ID_Autor, data.Titulo, data.Descripcion, data.Archivos);
		vault_ind = data.ID;
	});

}

function actualizar(tipo, inicio, funcion)
{
	$.post("leer.php?rand="+Math.random(), {tipo: tipo, inicio: inicio, curso: materia_ele}, function(data) {
		data = JSON.parse(data);
		if(data.success)
			for(var i=data.objetos.length-1;i>=0;i--)
				funcion(data.objetos[i]);
	});
}


function actualizarv(inicio, funcion)
{
	$.post("leerv.php?rand="+Math.random(), {inicio: inicio, curso: materia_ele}, function(data) {
		data = JSON.parse(data);
		if(data.success)
			for(var i=data.objetos.length-1;i>=0;i--)
				funcion(data.objetos[i]);
	});
}

function escribirUrgente(id, autor, autor_id, titulo, descripcion, archivos)
{
	var div=$("<div></div>");
	$("<img></img>").addClass("autor_foto").attr("src", "usuarios/" + autor_id + ".png").appendTo(div).load(function(e) {
        if($(this).parent().height() > 100)
			$("<span></span>").addClass("mostrar fase1").appendTo($(this).parent().parent());
    });
	$("<div></div>").addClass("titulo").text(titulo).appendTo(div);
	$("<div></div>").addClass("autor").text(autor).appendTo(div);
	$("<div></div>").addClass("descripcion").html(br(descripcion)).appendTo(div);
	generarArchivos(archivos, "descargar.php").appendTo(div);
	var div2=$("<div></div>").attr("id", "dash" + id).addClass("urgente");
	div.appendTo(div2);
	div2.prependTo("#dashboard-urgentes");
}

function escribirTarea(id, autor, autor_id, titulo, descripcion, fechaEnt, archivos)
{
	var div=$("<div></div>");
	$("<img></img>").addClass("autor_foto").attr("src", "usuarios/" + autor_id + ".png").appendTo(div).load(function(e) {
        if($(this).parent().height() > 100)
			$("<span></span>").addClass("mostrar fase1").appendTo($(this).parent().parent());
    });
	$("<div></div>").addClass("titulo").text(titulo).appendTo(div);
	$("<div></div>").addClass("autor").text(autor).appendTo(div);
	$("<div></div>").addClass("descripcion").html(br(descripcion)).appendTo(div);
	$("<div></div>").addClass("fechaEnt").text(fechaEnt).appendTo(div);
	generarArchivos(archivos, "descargar.php").appendTo(div);
<?php
if($_SESSION['tipo'] == 2)
{
?>
	$("<a></a>").addClass("subir").text("Subir tarea").appendTo(div);
<?php
}
?>
	var div2=$("<div></div>").attr("id", "dash" + id).addClass("tarea");
	div.appendTo(div2);
	div2.prependTo("#dashboard-tareas");
}

function escribirAnuncio(id, autor, autor_id, titulo, descripcion, archivos)
{
	var div=$("<div></div>");
	$("<img></img>").addClass("autor_foto").attr("src", "usuarios/" + autor_id + ".png").appendTo(div).load(function(e) {
        if($(this).parent().height() > 100)
			$("<span></span>").addClass("mostrar fase1").appendTo($(this).parent().parent());
    });
	$("<div></div>").addClass("titulo").text(titulo).appendTo(div);
	$("<div></div>").addClass("autor").text(autor).appendTo(div);
	$("<div></div>").addClass("descripcion").html(br(descripcion)).appendTo(div);
	generarArchivos(archivos, "descargar.php").appendTo(div);
	var div2 = $("<div></div>").attr("id", "dash" + id).addClass("anuncio");
	div.appendTo(div2);
	div2.prependTo("#dashboard-anuncios");
}

function escribirVault(id, autor, autor_id, titulo, descripcion, archivos)
{
	var div=$("<div></div>");
	$("<img></img>").addClass("autor_foto").attr("src", "usuarios/" + autor_id + ".png").appendTo(div).load(function(e) {
        if($(this).parent().height() > 100)
			$("<span></span>").addClass("mostrar fase1").appendTo($(this).parent().parent());
    });
	$("<div></div>").addClass("titulo").text(titulo).appendTo(div);
	$("<div></div>").addClass("autor").text(autor).appendTo(div);
	$("<div></div>").addClass("descripcion").html(br(descripcion)).appendTo(div);
	generarArchivos(archivos, "descargarv.php").appendTo(div);
	var div2=$("<div></div>").attr("id", "vault" + id).addClass("vault");
	div.appendTo(div2);
	div2.prependTo("#vault-posts");
}

function generarArchivos(archivos, ruta)
{
	var div=$("<div></div>").addClass("archivos");
	for(var i in archivos)
		$("<a></a>").addClass(archivos[i].mime.replace("/", "-").replace(".", "_") + " archivo").attr({href: ruta + "?id=" + archivos[i].id, target: "_blank", title: archivos[i].nombre}).appendTo(div);
	return div;
}

function br(texto)
{
	return texto.replace(/(\r\n|\n\r|\n|\r)/g, "<br/>");
}

function GuardaNota()
{
	$.post('notas.php',{nota: $("#nota").val()},function(data){
		data=JSON.parse(data);
		if(!data.success)
		{
			setTimeout(function(){
				GuardaNota();
			},5000);
		}
	});
}

function leerChat()
{
	$.post("leerc.php", {curso: materia_ele, inicio: chat_ind}, function(data) {
		data = JSON.parse(data);
		if(data.success)
			for(var i=data.objetos.length-1;i>=0;i--)
			{
				anadirChat(data.objetos[i].Autor, data.objetos[i].Texto);
				chat_ind = data.objetos[i].ID;
			}
			timerc = setTimeout(function() {
				leerChat();
			}, 5000);
	});
}

function anadirChat(autor, texto)
{
	var div=$("<div></div>");
	$("<span></span>").addClass("chat-autor").text(autor + ': ').appendTo(div);
	$("<span></span>").addClass("chat-texto").text(texto).appendTo(div);
	div.appendTo("#chat-msjs");
	$("#chat-msjs").scrollTop($("#chat-msjs").prop("scrollHeight"));
}

function usuarios()
{
	$.post("usuarios.php", {curso: materia_ele}, function(data) {
		data = JSON.parse(data);
		if(data.success)
			for(var i in data.objetos)
				anadirUsuario(data.objetos[i].Nombre, data.objetos[i].Apellido);
	});
}

function anadirUsuario(nombre, apellido)
{
	$("<div></div>").text(nombre + " " + apellido).appendTo("#usuarios");
}
</script>
</head>
<body>
<div id="head">		
	<div id="headcontain"><div id="headleft"><img src="images/logo.png" alt="Glassroom"/></div><div id="headright">
     <ul class="menu"><li><a href="#">Intercampus</a> </li> <li> <img src="images/set.png" alt="Settings"/><a href="#">Settings</a></li><li><a href="logout.php">Logout</a></li></ul></div></div>
</div>
<div class="name"><h3><?=$_SESSION['nombre'] . ' ' . $_SESSION['apellido']?></h3></div>
<div class="contenidoalumno">
	<div class="columnaizq">
    	<div class="foto"><img class="foto" src="usuarios/<?=$_SESSION['id']?>.png"/></div>
			<p><?=$_SESSION['clave']?><br/><?=$_SESSION['carrera']?><br/><?=$_SESSION['campus']?></p> 
        <div id="chat" style="display:none">
        	Group Chat
            <div id="usuarios"></div>
            <div id="chat-msjs"></div>
            <form id="f-chat">
            	<div class="bloqueo"></div>
            	<textarea id="chat-post" placeholder="Write here..."></textarea>
            </form>
        </div>   
    </div>
    
	<div class="columnacentro">
    	<ul class="menumat" id="materias">
        <li id="mat0" class="sel"><a href="#">All</a></li>
<?php
$result=$db->query("SELECT a.*,b.* FROM cursos AS a INNER JOIN materias AS b ON a.Clave_Materias=b.Clave INNER JOIN usuarios_materias AS c ON a.ID=c.ID_Curso AND c.ID_Usuario={$_SESSION['id']}");
while($fila=$result->fetch_assoc())
{
?>
        	<li id="mat<?=$fila['ID']?>"><a href="#"><?=$fila['Nombre']?></a></li>
<?php
}
$result->free();
?>
        </ul>
    		<div class="materias">
          	  
            	<ul class="menudash">
                	<li><a href="#" id="a-dashboard" class="sel">Dashboard</a></li>
               		<li><a href="#" id="a-vault">Vault</a></li>
             	</ul>
              <div class="materiain">
              <div id="dashboard">
<?php
if($_SESSION['tipo'] == 1)
{
?>
              <form id="f-dashboard" enctype="multipart/form-data" action="" style="display:none">
              <div class="bloqueo"></div>
              <div class="top">
              <input type="radio" name="tipo" value="1" /><span class="urgente">Urgent</span>
              <input type="radio" name="tipo" value="2" /><span class="tarea">Homework</span>
              <input type="radio" name="tipo" value="3" /><span class="anuncio">Announcement</span>
              </div>
              <input type="text" name="titulo" placeholder="Title:" class="titulo" /><br />
              <textarea id="dashboard-texto" name="descripcion" class="descripcion" placeholder="Description:"></textarea><br />
              <div class="linea">Start date: <input type="text" value="<?=date('Y-m-d')?>" id="fecha_inicio" name="fecha_inicio" class="linea" /></div>
              <div class="linea">End date: <input type="text" value="" id="fecha_fin" name="fecha_fin" class="linea" /></div>
              <div class="linea">File: <input type="file" name="archivo" class="linea" />
              </div>
              <input type="submit" class="boton" value="Post It" />
              </form>
<?php
}
?>
              <div id="dashboard-urgentes"></div>
              <div id="dashboard-tareas"></div>
              <div id="dashboard-anuncios"></div>
              </div>
              <div id="vault">
              <form id="f-vault" enctype="multipart/form-data" action="" style="display:none">
              <div class="bloqueo"></div>
              <input type="text" name="titulo" placeholder="Title:" class="titulo" /><br />
              <textarea id="vault-texto" name="descripcion" placeholder="Description:" class="descripcion"></textarea><br />
              <div id="vault-preview"></div>
              <div class="linea">File: <input type="file" name="archivo" class="linea" /></div>
              <input type="submit" class="boton" value="Post It" />
              </form>
              <div id="vault-posts"></div>
              </div>
              </div>
            </div>
    </div>
    
	<div class="columnader">
    	<div><img src="images/calendar.png" class="calendar"/></div>
    	<div class="notes">
        	<div class="titulo">Notes</div>
        	<textarea id="nota" placeholder="Type here to save notes..."></textarea>
        </div>
    </div>		


<div id="foot"><p>&copy; Glassroom 2012</p></div>
</div>
</body>
</html>
<?php
require_once('cerdb.php');
?>