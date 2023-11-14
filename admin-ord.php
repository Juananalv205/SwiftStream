<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Administración de Órdenes</title>
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
            <a class="navbar-brand" href="admin.php">SwiftStream</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-prod.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-ord.php">Ordenes</a>
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
                    <th scope="col">Fecha</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Totalventas</th>

                    <th scope="col">Eliminar Venta</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                    $servurl = "http://192.168.101.2:3003/ventas";
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
                        $id = $dec->ID;
                        $fecha = $dec->Fecha;
                        $nombre = $dec->Nombre;
                        $codigo = $dec->Codigo;
                        $producto = $dec->Producto;
                        $cantidad = $dec->Cantidad;
                        $totalventas = $dec->Totalventas;
                ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $nombre; ?></td>
                    <td><?php echo $codigo; ?></td>
                    <td><?php echo $producto; ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo $totalventas; ?></td>
                    <td>
                        <button class="btn btn-danger" onclick="eliminarProducto('<?php echo $id; ?>')">🗑️</button>
                    <td>
                </tr>
                <?php 
                    }
                ?>   
            </tbody>
        </table>
    </div>
</body>
</html>


<script>
    function eliminarProducto(ventaId) {
        
        if (confirm("¿Estás seguro de que deseas eliminar esta venta")) {
            // Realiza una solicitud para eliminar al usuario
            var url = 'eliminarventas.php?id=' + ventaId;
            fetch(url)
                .then(response => {
                    console.log(response)
                    if (!response.ok) {
                        throw new Error('Error en la solicitud.');
                    }
                    location.reload();
                    alert("✅🗑️ Se ha eliminado de manera exitosa la venta" ); 
                    
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
</script>