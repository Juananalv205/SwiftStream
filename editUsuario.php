<?php
// Recupera los datos del formulario de edición
$usuario = $_POST["editUsuarioId"];
$nuevoNombre = $_POST["editNombre"];
$nuevoEmail = $_POST["editEmail"];
$nuevoUsuario = $_POST["editUsuario"];
$nuevaPassword = $_POST["editPassword"];
$nuevoRol = $_POST["editRol"];

// Prepara los datos para enviar al servidor
$datosUsuario = json_encode(array(
    "nombre" => $nuevoNombre,
    "email" => $nuevoEmail,
    "usuario" => $nuevoUsuario,
    "password" => $nuevaPassword,
    "rol" => $nuevoRol
));

// Configura la solicitud HTTP
$url = "http://localhost:3001/usuarios/" . $usuario;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $datosUsuario);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecuta la solicitud
$response = curl_exec($ch);

// Verifica si la solicitud fue exitosa
if ($response === false) {
    die("Error en la conexión");
}

// Cierra la conexión
curl_close($ch);
session_start();
$_SESSION["mensaje_exito"] = "El usuario ha sido actualizado. ✅";
// Redirige de vuelta a la página de administración de usuarios
header("Location: admin.php");
?>
