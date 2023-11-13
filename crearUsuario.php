<?php
    $nombre=$_POST["nombre"];
    $usuario=$_POST["usuario"];
    $pass=$_POST["password"];
    $rol=$_POST["rol"];

    // URL de la solicitud POST
    $url = 'http://localhost:3001/usuarios/';

    // Datos que se enviarán en la solicitud POST
    $data = array(
        'nombre' => $nombre,
        'usuario' => $usuario,
        'password' => $pass,
        'rol' => $rol,
    );
    $json_data = json_encode($data);

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud POST
    $response = curl_exec($ch);

    // Manejar la respuesta
    if ($response===false){
        header("Location:index.html");
        exit;
    }
    // Cerrar la conexión cURL
    curl_close($ch);
    session_start();
    $_SESSION["mensaje_exito"] = "El usuario se ha creado con éxito. ✅ " ;

    header("Location:admin.php");
    exit;

?>