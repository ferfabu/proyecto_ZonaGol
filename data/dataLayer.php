<?php
	//conexion con la database
	function connDB(){
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "loginsystem3";

		$conn = new mysqli($servername, $username, $password, $dbname);
		
		if ($conn->connect_error){
			return null;
		}
		else{
			return $conn;
		}
	}

	function attemptLoginFunction($userEmail){
		$conn = connDB();

		if ($conn != null){
			$sql = "SELECT passwrd, fName, lName, rol FROM Users WHERE email='$userEmail'";
		
			$result = $conn->query($sql);

			if ($result->num_rows == 1) {
				while ($row = $result->fetch_assoc()) {
					$firstName = $row["fName"];
					$lastName = $row["lName"];
					$password = $row["passwrd"];
					$rol = $row["rol"];
				}
				$conn -> close();
				return array("status" => "SUCCESS", "firstName"=>$firstName, "lastName"=>$lastName, "password"=>$password, "rol"=>$rol);
			}
			else{
				$conn -> close();
				return array("status" => "ERROR");
			}
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptStartSessionFunction($userEmail){

		$conn = connDB();

		if ($conn != null){
			$sql = "SELECT fName, lName, rol FROM Users WHERE email='$userEmail'";
		
			$result = $conn->query($sql);

			if ($result->num_rows == 1) {
				while ($row = $result->fetch_assoc()) {
					$firstName = $row["fName"];
					$lastName = $row["lName"];
					$rol = $row["rol"];
				}
				$conn -> close();
				return array("status" => "SUCCESS", "firstName"=>$firstName, "lastName"=>$lastName, "rol"=>$rol);
			}
			else{
				$conn -> close();
				return array("status" => "ERROR");
			}
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptInsertCommentFunction($userEmail, $userComment) {
		$conn = connDB();

		if ($conn != null){
			$successInsert = mysqli_query($conn,"INSERT INTO Comments (commentDB,emailComment) 
									VALUES ('$userComment','$userEmail')");
			if ($successInsert) {
				$conn -> close();
				return array("status" => "SUCCESS");
			}
			else {
				$conn -> close();
				return array("status" => "ERROR");
			}
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptGetCommentsFunction(){

		$conn = connDB();

		if ($conn != null){
			$sql = "SELECT fName, lName, email, commentDB FROM Comments, Users WHERE emailComment = email";
		
			$result = $conn->query($sql);
			$resultArray = array();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$resultArray[] = $row;
				}	
			}
			$conn -> close();
			return $resultArray;
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptRegisterFunction($userFName, $userLName, $userEmail, $userPassword) {
		$conn = connDB();

		if ($conn != null){
			$successUserInsert = mysqli_query($conn,"INSERT INTO Users (fName,lName,email,passwrd) 
								VALUES ('$userFName','$userLName','$userEmail','$userPassword')");
			
			if ($successUserInsert) {
				$conn -> close();
				return array("status" => "SUCCESS");
			}
			else {
				$conn -> close();
				return array("status" => "ERROR");
			}
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptValidarFoto($nom, $destino, $categoria){
		$conn = connDB();

		if ($conn != null) {
			$successFotoInsert = mysqli_query($conn,"INSERT INTO Foto (categoria, nombre, foto) VALUES('$categoria', '$nom','$destino')");
			if ($successFotoInsert) {
					$conn -> close();
					return array("status" => "SUCCESS");
			}
			else {
				$conn -> close();
				return array("status" => "ERROR");
			}	
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function desplegarProductos(){
		$conn = connDB();

		if ($conn != null){
			$sql = "SELECT * FROM Foto";
		
			$result = $conn->query($sql);
			$resultArray = array();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$resultArray[] = $row;
				}	
			}
			$conn -> close();
			return $resultArray;
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function desplegarProductosBuscadorNombre($nombre){
		$conn = connDB();

		if ($conn != null){
			$sql = "SELECT * FROM Foto WHERE nombre like  '%$nombre%' ";
			$result = $conn->query($sql);
			$resultArray = array();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$resultArray[] = $row;
				}	
			}
			$conn -> close();
			return $resultArray;
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function desplegarProductosBuscadorCategoria($categoria){
		$conn = connDB();

		if ($conn != null){
			$sql = "SELECT * FROM Foto WHERE categoria like  '%$categoria%' ";
			$result = $conn->query($sql);
			$resultArray = array();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$resultArray[] = $row;
				}	
			}
			$conn -> close();
			return $resultArray;
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptEliminarProducto($destino){
		$conn = connDB();

		if ($conn != null){
			$sql = "DELETE FROM Foto WHERE foto = '$destino'";
			$result = $conn->query($sql);
			$resultArray = array();
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$resultArray[] = $row;
				}	
			}
			$conn -> close();
			return $resultArray;
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptEditarNombre($nom, $destino){
		$conn = connDB();

		if ($conn != null) {
			$successFotoInsert = mysqli_query($conn,"UPDATE Foto SET nombre = '$nom' WHERE destino = '$destino' ");
			if ($successFotoInsert) {
					$conn -> close();
					return array("status" => "SUCCESS");
			}
			else {
				$conn -> close();
				return array("status" => "ERROR");
			}	
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptEditarNombreyFoto($destinoanterior, $destinonueva){
		$conn = connDB();

		if ($conn != null) {
			$successFotoInsert = mysqli_query($conn,"UPDATE Foto SET foto = '$destinonueva' WHERE destino = '$destinoanterior'");
			if ($successFotoInsert) {
					$conn -> close();
					return array("status" => "SUCCESS");
			}
			else {
				$conn -> close();
				return array("status" => "ERROR");
			}	
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

	function attemptEditarNombreyFoto2($nom, $destinoanterior, $destinonueva){
		$conn = connDB();

		if ($conn != null) {
			$successFotoInsert = mysqli_query($conn,"UPDATE Foto SET nombre = '$nom', foto = '$destinonueva' WHERE destino = '$destinoanterior' ");
			if ($successFotoInsert) {
					$conn -> close();
					return array("status" => "SUCCESS");
			}
			else {
				$conn -> close();
				return array("status" => "ERROR");
			}	
		}else{
			$conn -> close();
			return array("status" => "BAD CONNECTION");
		}
	}

?>