<?php

header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

$action = $_POST["action"];

switch($action){
	case "REGISTERSESSION": registerUserFunction();
					break;	
	case "LOGINSESSION" : loginSessionFunction();
					break;
	case "LOGOUTSESSION": logoutSessionFunction();
					break;
	case "STARTSESSION": startSessionFunction();
					break;
    case "VERIFYSESSION": verifySessionFunction();
					break;
	case "INSERTCOMMENT": insertCommentFunction();
					break;	
    case "GETCOMMENTS": getCommentsFunction();
					break;	
	case 'MOSTRARIMAGENESBUSCADORNOMBRE': mostrarImagenesBuscadorNombre();
					break;
	case 'MOSTRARIMAGENESBUSCADORCATEGORIA': mostrarImagenesBuscadorCategoria();
					break;
	case 'MOSTRARIMAGENES' : mostrarImagenes();
					break;
}

function registerUserFunction() {
	$userFName = $_POST["fName"];
	$userLName = $_POST["lName"];
	$userEmail = $_POST["email"];
	$userPassword = encryptPassword();

	$result = attemptRegisterFunction($userFName, $userLName, $userEmail, $userPassword);

	if ($result["status"] == "SUCCESS"){
		echo json_encode(array("status" => "SUCCESS", "message" => "You have been registered"));
	}
	else {
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function loginSessionFunction(){
	$userEmail = $_POST["email"];
	$rememberData = $_POST["rememberData"];

	$cookie_userName = "currentlyLogged";

	$result = attemptLoginFunction($userEmail);

	if ($result["status"] == "SUCCESS"){
		$decryptedPassword = decryptPassword($result['password']);
		$userPassword4 = $_POST['password'];
		if ($decryptedPassword === $userPassword4) {
			if ($rememberData === "true") {
				setcookie($cookie_userName, $userEmail, time() + (3600 * 24 * 20), "/");
			}
			//inicia session
			session_start();
			session_destroy();
			session_start();

			$_SESSION['userEmail'] = $userEmail;
			$_SESSION['firstName'] = $result["firstName"];
			$_SESSION['lastName'] = $result["lastName"];
			$_SESSION['rol'] = $result["rol"];
			echo json_encode(array("message" => "Login Successful", "firstName"=>$result["firstName"], "lastName"=>$result["lastName"], "rol"=>$result["rol"]));
		}
		else{
			header('HTTP/1.1 306 Wrong credentials');
			die("Wrong credentials");
		}

		
	}	
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}	
}

function logoutSessionFunction(){
	$cookie_userName = "currentlyLogged";
	//eliminar cookie
	if (isset($_COOKIE[$cookie_userName])) {
	    unset($_COOKIE[$cookie_userName]);
	    setcookie($cookie_userName, '', time() - 20, '/'); 
	}
	//terminar session
	session_start();
	session_destroy();
	session_unset();
	session_regenerate_id(true);

	echo json_encode(array("message" => "Succesfully logout"));
}

function startSessionFunction() {
	$cookie_userName = "currentlyLogged";

	if(isset($_COOKIE[$cookie_userName])) {
		$userEmail = $_COOKIE[$cookie_userName];

		$result = attemptStartSessionFunction($userEmail);

		if ($result["status"] == "SUCCESS"){
			session_start();
			session_destroy();
			session_start();

			$_SESSION['userEmail'] = $userEmail;
			$_SESSION['firstName'] = $result["firstName"];
			$_SESSION['lastName'] = $result["lastName"];
			$_SESSION['rol'] = $result["rol"];

			echo json_encode(array("status"=>"SUCCESS", "message"=>"Session restored"));
		}
		else if($result["status"] == "USERNAME NOT FOUND"){
			setcookie($cookie_userName, "", time() - 20);

			header('HTTP/1.1 406 User not found');
			die("There was an error with your session");
		}
		else {
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else {
		echo json_encode(array("status"=>"NOT STARTED", "message"=>"You are not logged in"));
	}
}

function verifySessionFunction() {
    session_start();
 
    //verifica si ya está iniciada session
    if(empty($_SESSION['userEmail'])) {
        echo json_encode(array("status" => "NOT STARTED"));
    }
    else {
    	echo json_encode(array("status" => "SUCCESS", "email"=>$_SESSION['userEmail'], "firstName"=>$_SESSION['firstName'], "lastName"=>$_SESSION['lastName'], "rol"=>$_SESSION["rol"]));
    }
}

function insertCommentFunction() {
	session_start();
	$userEmail = $_SESSION['userEmail'];
	$userComment = $_POST["comment"];

	$result = attemptInsertCommentFunction($userEmail, $userComment);

	if ($result["status"] == "SUCCESS"){
		echo json_encode(array("status" => "SUCCESS", "message" => "Succesfully commented"));
	}
	else {
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function getCommentsFunction() {
	$result = attemptGetCommentsFunction();

	if ($result["status"] === "BAD CONNECTION") {
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}

	else {
		echo json_encode($result);
	}
}

function encryptPassword(){
	$userPassord = $_POST["password"];

	$key = pack('H*',"bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
	$key_size = strlen($key);

	$plaintext = $userPassord;

	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    
	$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
	$ciphertext = $iv . $ciphertext;
	    
	$userPassword = base64_encode($ciphertext);

	return $userPassword;
}

function decryptPassword($password){
	$key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

	$ciphertext_dec = base64_decode($password);
	$iv_dec = substr($ciphertext_dec, 0, $iv_size);
	$ciphertext_dec = substr($ciphertext_dec, $iv_size);

	$password = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

	$aux = 0;
	$lenght = strlen($password);

	for ($i = $lenght - 1; $i >= 0; $i --) { 
		if (ord($password{$i}) === 0) {
			$aux ++;
		}
	}

	$password = substr($password, 0, $lenght - $aux);
	return $password;
}

function mostrarImagenes() {
	$result = desplegarProductos();

	if ($result["status"] === "BAD CONNECTION") {
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
	else {
		echo json_encode($result);
	}
}

function mostrarImagenesBuscadorNombre(){
	$nombre = $_POST["name"]; 
	//connect  to the database 
	$result = desplegarProductosBuscadorNombre($nombre);
	if ($result["status"] === "BAD CONNECTION") {
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
	else {
		echo json_encode($result);
	}
}

function mostrarImagenesBuscadorCategoria(){
	$categoria = $_POST["nameCat"]; 
	//connect  to the database 
	$result = desplegarProductosBuscadorCategoria($categoria);
	if ($result["status"] === "BAD CONNECTION") {
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
	else {
		echo json_encode($result);
	}
}
?>