<?php
$usuario = $_POST['usuario'];

$items = array();

// Recorrer los valores de cantidad enviados por el formulario
foreach ($_POST['cantidad'] as $id => $cantidad) {
    if ($cantidad>0){
    $item['id'] = $id;
    $item["cantidad"] = $cantidad;
    array_push($items, $item);
}

}

$orden['usuario']=$usuario;
$orden['items']=$items;

$json = json_encode($orden);
//echo $json;


$url = 'http://192.168.101.2:3003/ventas';

// Inicializar cURL
$ch = curl_init();

// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
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


$mensajeExito = "Su orden se ha creado con éxito. ✅ Resumen de compra:";

foreach ($items as $item) {
    $mensajeExito .= "Id producto :  " . $item["id"] . " , Cantidad: " . $item["cantidad"] . "";
}

// Establecer el mensaje de éxito en la variable de sesión
session_start();
$_SESSION["mensaje_exito"] = $mensajeExito;

header("Location:usuario.php");
exit;

?>