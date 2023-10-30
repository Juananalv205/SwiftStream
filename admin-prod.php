<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Administración de Productos</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
            color: white;
        }
        .navbar .navbar-brand {
            font-size: 2rem;
        }
        .navbar .navbar-toggler-icon {
            background-color: white;
        }
        .navbar .navbar-text a {
            color: white;
        }
        .container {
            padding: 2rem;
        }
        .table {
            background-color: white;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .modal-title {
            color: #007bff;
        }
        .modal-content {
            border: none;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        $us = $_SESSION["usuario"];
        if ($us == "") {
            header("Location: index.html");
        }
    ?>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Almacen ABC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-prod.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-ord.php">Ordenes</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <?php echo "<a class='nav-link' href='logout.php'>Logout $us</a>"; ?>
                </span>
            </div>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Inventario</th>
                    <th scope="col">Eliminar / Edit Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $servurl = "http://localhost:3002/productos";
                    $curl = curl_init($servurl);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);

                    if ($response === false) {
                        curl_close($curl);
                        die("Error en la conexión");
                    }

                    curl_close($curl);
                    $resp = json_decode($response);
                    $long = count($resp);

                    for ($i = 0; $i < $long; $i++) {
                        $dec = $resp[$i];
                        $id = $dec->id;
                        $nombre = $dec->nombre;
                        $precio = $dec->precio;
                        $inventario = $dec->inventario;
                ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo $nombre; ?></td>
                    <td><?php echo $precio; ?></td>
                    <td><?php echo $inventario; ?></td>
                    <td>
                        <button class="btn btn-danger" onclick="eliminarProducto('<?php echo $id; ?>')">🗑️</button>
                        <button class="btn btn-primary" onclick="modificarInventario('<?php echo $id; ?>','<?php echo $nombre; ?>','<?php echo $inventario; ?>')">✏️</button>
                    </td>
                </tr>
                <?php 
                    }
                ?>   
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            CREAR PRODUCTO
        </button>
    </div>
    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREAR PRODUCTO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crearProducto.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="text" name="precio" class="form-control" id="precio" required >
                        </div>
                        <div class="mb-3">
                            <label for="inventario" class="form-label">Inventario</label>
                            <input type="text" name="inventario" class="form-control" id="inventario" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Producto</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Stock del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editProducto.php" method="post">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Producto</label>
                            <input type="text" name="productName" class="form-control" id="productName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="newStock" class="form-label">Nuevo Stock</label>
                            <input type="text" name="newStock" class="form-control" id="newStock" required>
                        </div>
                        <input type="hidden" name="productId" id="productId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
                </form>
            </div>
        </div>
    </div>




    <!-- Agrega este bloque de código JavaScript justo antes del cierre del cuerpo </body> -->
<script>
    // Verifica si la variable de sesión existe
    var mensajeExito = "<?php echo isset($_SESSION['mensaje_exito']) ? $_SESSION['mensaje_exito'] : ''; ?>";
    
    // Si el mensaje de éxito existe, muestra un mensaje emergente o una notificación
    if (mensajeExito !== "") {
        alert(mensajeExito); // Muestra una alerta simple, puedes personalizar esto
        <?php unset($_SESSION['mensaje_exito']); ?>;
   }
</script>

<script>
    function eliminarProducto(productoId) {
        
        if (confirm("¿Estás seguro de que deseas eliminar este Producto")) {
            // Realiza una solicitud para eliminar al usuario
            var url = 'eliminarProducto.php?id=' + productoId;
            fetch(url)
                .then(response => {
                    console.log(response)
                    if (!response.ok) {
                        throw new Error('Error en la solicitud.');
                    }
                    location.reload();
                    alert("✅🗑️ Se ha eliminado de manera exitosa el usuario" ); 
                    
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
</script>
<script>
        function modificarInventario(productId, productName, currentStock) {
            // Rellena la ventana modal con el nombre del producto y el inventario actual
            document.getElementById('productName').value = productName;
            document.getElementById('newStock').value = currentStock;
            // Almacena el ID del producto en un campo oculto
            document.getElementById('productId').value = productId;
            // Abre la ventana modal para editar el stock
            $('#editProductModal').modal('show');
        }
</script>


</body>

</html>