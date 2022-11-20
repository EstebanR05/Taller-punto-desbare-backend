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
    $sqlEmpleaados = mysqli_query($conexionBD, "SELECT * FROM spare WHERE id_user=" . $_GET["consultar"]);
    if (mysqli_num_rows($sqlEmpleaados) > 0) {
        $empleaados = mysqli_fetch_all($sqlEmpleaados, MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["borrar"])) {
    $sqlEmpleaados = mysqli_query($conexionBD, "DELETE FROM spare WHERE spare_id=" . $_GET["borrar"]);
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
    $info = $data->info;
    $f_inicial = $data->f_inicial;
    $f_final = $data->f_final;
    $id = $data->id;
    if (($id != "") && ($nombre != "")) {

        $sqlEmpleaados = mysqli_query($conexionBD, "INSERT INTO spare(nombre,info,f_inicial,f_final,imagen,id_user) VALUES('$nombre','$info','$f_inicial','$f_final', '$id', 'user_default.png') ");
        echo json_encode(["success" => 1]);
    }
    exit();
}

if (isset($_GET["actualizar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = (isset($data->id)) ? $data->id : $_GET["actualizar"];
    $nombre = $data->nombre;
    $info = $data->info;
    $f_inicial = $data->f_inicial;
    $f_final = $data->f_final;

    $sqlEmpleaados = mysqli_query($conexionBD, "UPDATE spare SET nombre='$nombre',info='$info', f_inicial='$f_incial', f_final='$f_final' WHERE spare_id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}
// Consulta todos los registros de la tabla empleados
$sqlEmpleaados = mysqli_query($conexionBD, "SELECT * FROM spare ");
if (mysqli_num_rows($sqlEmpleaados) > 0) {
    $empleaados = mysqli_fetch_all($sqlEmpleaados, MYSQLI_ASSOC);
    echo json_encode($empleaados);
} else {
    echo json_encode([["success" => 0]]);
}
