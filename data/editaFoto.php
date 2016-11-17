<?php 
header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

	//para cambiar una foto por una nueva
	if ($_POST["txtnomEdit2"] == "") {
		$fotoanterior = $_FILES["fotoanterior"]["name"];
		$rutaanterior = $_FILES['fotoanterior']['tmp_name'];
		$destinoanterior = "images/".$fotoanterior;
		$destinoanterior2 ="../images/".$fotoanterior;
		unlink($destinoanterior2);

		$fotonueva = $_FILES["fotonueva"]["name"];
		$rutanueva = $_FILES['fotonueva']['tmp_name'];
		$destinonueva = "images/".$fotonueva;
		$destinonueva2 ="../images/".$fotonueva;
		copy($rutanueva, $destinonueva2);

		$result = attemptEditarNombreyFoto($destinoanterior, $destinonueva);
		header('Location: ../products.html');	
	}else{
		$nom = $_POST["txtnomEdit2"];
		$fotoanterior = $_FILES["fotoanterior"]["name"];
		$rutaanterior = $_FILES['fotoanterior']['tmp_name'];
		$destinoanterior = "images/".$fotoanterior;
		$destinoanterior2 ="../images/".$fotoanterior;
		unlink($destinoanterior2);
		$fotonueva = $_FILES["fotonueva"]["name"];
		$rutanueva = $_FILES['fotonueva']['tmp_name'];
		$destinonueva = "images/".$fotonueva;
		$destinonueva2 ="../images/".$fotonueva;
		copy($rutanueva, $destinonueva2);

		$result = attemptEditarNombreyFoto2($nom, $destinoanterior, $destinonueva);
		header('Location: ../products.html');
	}
	
?>