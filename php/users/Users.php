<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$servidor = "localhost";
$usuario = "";
$contrasenia = "";
$nombreBaseDatos = "spare";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);


if (isset($_GET["image"])) {

    $ori_fname = $_FILES['file']['name'];
    $ext = pathinfo($ori_fname, PATHINFO_EXTENSION);
    $target_path2 = "../../../frontend/src/assets/subidas/";
    $name = $_FILES['file']['name'];
    $target_path = '1' . $name . "." . $ext;
    $id = $_POST['id'];
    $target_path3 = $target_path2 . $target_path;
    $result = array();

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path3)) {
        $sqlEmpleaados = mysqli_query($conexionBD, "UPDATE usuarios SET imagen='$target_path' WHERE user_id='$id'");
        $result["status"] = 1;
        $result["message"] = "Uploaded file successfully.";
    } else {
        $result["status"] = 0;
        $result["message"] = "File upload failed. Please try again.";
    }
    echo json_encode($result);
}

if (isset($_GET["consultar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $correo = $data->correo;
    $contrasena = $data->contrasena;
    $sqlEmpleaados = mysqli_query($conexionBD, "SELECT * FROM usuarios WHERE correo='$correo' AND contrasena='$contrasena'");
    if (mysqli_num_rows($sqlEmpleaados) > 0) {
        $empleaados = mysqli_fetch_all($sqlEmpleaados, MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["borrar"])) {
    $sqlEmpleaados = mysqli_query($conexionBD, "DELETE FROM usuarios WHERE user_id=" . $_GET["borrar"]);
    if ($sqlEmpleaados) {
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["insertar"])) {

    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $apellido = $data->apellido;
    $correo = $data->correo;
    $contrasena = $data->contrasena;
    $telefono = $data->telefono;

    if (($correo != "") && ($nombre != "")) {
        $sqlEmpleaados = mysqli_query($conexionBD, "INSERT INTO usuarios(nombre,correo,apellido,contrasena,telefono,imagen) VALUES ('$nombre','$correo','$apellido','$contrasena','$telefono', 'user_default.png'); ");
        echo json_encode(["success" => 1]);
    }
    exit();
}

if (isset($_GET["actualizar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = (isset($data->id)) ? $data->id : $_GET["actualizar"];
    $nombre = $data->nombre;
    $apellido = $data->apellido;
    $correo = $data->correo;
    $contrasena = $data->contrasena;
    
    $sqlEmpleaados = mysqli_query($conexionBD, "UPDATE usuarios SET nombre='$nombre',correo='$correo', apellido='$apellido', contraseña='$contrasena' WHERE id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}

$sqlEmpleaados = mysqli_query($conexionBD, "SELECT * FROM usuarios ");
if (mysqli_num_rows($sqlEmpleaados) > 0) {
    $empleaados = mysqli_fetch_all($sqlEmpleaados, MYSQLI_ASSOC);
    echo json_encode($empleaados);
} else {
    echo json_encode([["success" => 0]]);
}
