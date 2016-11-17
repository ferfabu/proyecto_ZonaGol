<?php 
header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

	$nom = $_REQUEST["txtnom"];
	$categoria = $_REQUEST["txtcategoria"];
	$foto = $_FILES["foto"]["name"];
	$ruta = $_FILES['foto']['tmp_name'];
	$destino = "images/".$foto;
	$destino2 ="../images/".$foto;
	copy($ruta, $destino2);
	$result = attemptValidarFoto($nom, $destino, $categoria);
	header('Location: ../products.html');
?>