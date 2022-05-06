<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization, X-Requested-With");

//Conecta a la base de datos con usuario,contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root"; $contrasenia = ""; $nombreBaseDatos= "employes";
$conexionBD = new mysqli($servidor,$usuario,$contrasenia,$nombreBaseDatos);

//Consulta datos y recepcion una clave para consultar dichos datos con dicha clave
if (isset($_GET["consultar"])) {
	
	$sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM employes WHERE id=".$_GET["consultar"]);

	if (mysqli_num_rows($sqlEmpleaados) > 0){
		$empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
		echo json_encode($empleaados);
		exit();
	}
	else{
		echo json_encode(["success"=>0]);
	}
}

//borrar pero se le debe de enviar una clave (para ser borrado)
if (isset($_GET["borrar"])) {
	$sqlEmpleaados = mysqli_query($conexionBD,"DELETE FROM employes WHERE id=".$_GET["borrar"]);
	if ($sqlEmpleaados) {
		echo json_encode(["success"=>1]);
		exit();
	}else{
		echo json_encode(["success"=>0]);
	}
}
//Inserta un nuevo registro y recepciona en un metodo post los datos de nombre y correo
if (isset($_GET["insertar"])) {
	$data = json_decode(file_get_contents("php://input"));
	$nombre=$data->nombre;
	$correo=$data->correo;
	$telefono=$data->telefono;
		if (($correo!="")&&($nombre!="")&&($telefono!="")) {
			$sqlEmpleaados = mysqli_query($conexionBD,"INSERT INTO employes(nombre,correo,telefono) VALUES('$nombre','$correo','$telefono')");
			echo json_decode(["success"=>1]);
		}
		exit();
}
/////Actualiza datos pero no recepciona datos de nombre, correo y una clave para realizar la actualizacion
if (isset($_GET["actualizar"])) {

	$data = json_decode(file_get_contents("php://input"));

	$id=(isset($data->id))?$data->id:$_GET["actualizar"];
	$nombre=$data->nombre;
	$correo=$data->correo;
	$telefono=$data->telefono;

	$sqlEmpleaados = mysqli_query($conexionBD,"UPDATE employes SET nombre='$nombre',correo='$correo',telefono='$telefono' WHERE id='$id'");
	echo json_encode(["success"=>1]);
	exit();
}
////Consulta todos los registros de la tabla empleados
$sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM employes ");
if (mysqli_num_rows($sqlEmpleaados) > 0) {
	$empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
	echo json_encode($empleaados);	
}
else{
	echo "json_encode([["success"=>0]]);
}

?>