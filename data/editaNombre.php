<?php 
header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

	//para editar solamente el nombre de una imagen
	$nom = $_REQUEST["txtnomEdit"];
	$foto = $_FILES["foto"]["name"];
	$ruta = $_FILES['foto']['tmp_name'];
	$destino = "images/".$foto;
	$result = attemptEditarNombre($nom, $destino);
	header('Location: ../products.html');
?>