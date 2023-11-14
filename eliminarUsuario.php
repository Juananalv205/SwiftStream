<?php
if (isset($_GET['usuario'])) {
    $usuarioAEliminar = $_GET['usuario'];

    // URL del servicio de eliminación de usuario
    $servurl = "http://192.168.101.2:3001/usuarios/$usuarioAEliminar";

    // Inicializar cURL
    $curl = curl_init($servurl);

    // Establecer las opciones de cURL para realizar una solicitud DELETE
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud DELETE
    $response = curl_exec($curl);

    // Verificar si la eliminación fue exitosa
    if ($response == false) {
        header("Location:index.html");
        exit;
    } 
    
    // Cerrar la conexión cURL
    curl_close($curl);

    session_start();
    $_SESSION["mensaje_exito"] = "Eliminado correctamente  : " . $suarioAEliminar  . "✅";
    
    header("Location:admin-prod.php");
    exit;
   


} else {
    echo "No se proporcionó el nombre de usuario a eliminar.";
   
}
?>
