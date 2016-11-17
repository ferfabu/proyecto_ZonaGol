<?php 
header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

	$foto = $_FILES["foto"]["name"];
	$ruta = $_FILES['foto']['tmp_name'];
	$destino = "images/".$foto;
	$destino2 ="../images/".$foto;
	unlink($destino2);
	$result = attemptEliminarProducto($destino);
	header('Location: ../products.html');
?>