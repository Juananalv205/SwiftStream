<?php
// Obtiene el ID del producto a eliminar desde la URL
if (isset($_GET['id'])) {
    $productoId = $_GET['id'];

    // URL de la API que eliminará el producto específico
    $apiUrl = "http://192.168.101.2:3002/productos/" . $productoId;

    // Inicializa cURL
    $ch = curl_init($apiUrl);

    // Configura la solicitud HTTP DELETE
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecuta la solicitud DELETE
    $response = curl_exec($ch);

    // Verifica si la eliminación fue exitosa
    if ($response === false) {
        // Error al realizar la solicitud DELETE
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
    } else {
        // La eliminación fue exitosa
        echo json_encode(['success' => true, 'message' => 'Producto eliminado con éxito']);
    }

    // Cierra la conexión cURL
    curl_close($ch);
} else {
    // El ID del producto no se proporcionó
    echo json_encode(['success' => false, 'message' => 'ID del producto no especificado']);
}
?>
