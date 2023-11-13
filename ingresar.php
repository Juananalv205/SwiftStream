<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener los datos del formulario
$user = $_POST["usuario"];
$pass = $_POST["password"];

echo " info" , $user, $pass;
// Construir la URL para la solicitud al servicio web
$servurl = "http://localhost:3001/usuarios/$user/$pass";

// Inicializar cURL
$curl = curl_init($servurl);

// Configurar cURL para que devuelva el resultado en lugar de imprimirlo
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud cURL
$response = curl_exec($curl);

// Cerrar la sesión cURL
curl_close($curl);

// Verificar si la solicitud fue exitosa
if ($response === false) {
    header("Location: index.html");
    exit; // Termina el script
}

// Decodificar la respuesta JSON
$resp = json_decode($response);

// Verificar si la decodificación JSON fue exitosa
if (json_last_error() === JSON_ERROR_NONE) {
    // Decodificación exitosa, el JSON es válido
    $rol = $resp;
    // Iniciar una sesión y almacenar el nombre de usuario
    session_start();
    $_SESSION["usuario"] = $user;

    // Redirigir según el rol del usuario
    if ($rol === "Vendedor") {
        header("Location: admin.php");
    } elseif ($rol === "Clientes") {
        header("Location: usuario.php");
    }

} else {
    // Error de decodificación JSON, redirigir a otra página
    header("Location: index.html");
}
