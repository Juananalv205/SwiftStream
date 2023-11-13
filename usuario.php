<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>SwiftStream</title>
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
        <a class="navbar-brand" href="usuario.php">SwiftStream</a>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
            </ul>
            <span class="navbar-text">
                <?php echo "<a class='nav-link text-white' href='logout.php'>Logout $us</a>"; ?>
            </span>
        </div>
    </div>
</nav>

    <div class="container">
        <form method="post" action="procesar.php">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Codigo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Disponible</th>
                        <th scope="col">Productos Seleccionados</th>
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
                            die("Error en la conexion");
                        }
                        curl_close($curl);
                        $resp = json_decode($response);
                        $long = count($resp);

                        for ($i = 0; $i < $long; $i++) {
                            $dec = $resp[$i];
                            $id = $dec->ID;
                            $codigo = $dec->Codigo;
                            $nombre= $dec->Nombre;
                            $precio = $dec->Precio;
                            $inventario = $dec->Inventario;
                    ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $codigo; ?></td>
                        <td><?php echo $nombre; ?></td>
                        <td><?php echo $precio; ?></td>
                        <td><?php echo $inventario; ?></td>
                        <td><?php echo '<input type="number" name="cantidad[' . $id . ']" value="0" min="0">'; ?></td>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="usuario" value="<?php echo $us; ?>">
            <input type="submit" class="btn btn-primary" value="Agregar a la orden">
        </form>
    </div>
<script>
    // Verifica si la variable de sesión existe
    var mensajeExito = "<?php echo isset($_SESSION['mensaje_exito']) ? $_SESSION['mensaje_exito'] : ''; ?>";
    
    // Si el mensaje de éxito existe, muestra un mensaje emergente o una notificación
    if (mensajeExito !== "") {
        alert(mensajeExito); // Muestra una alerta simple, puedes personalizar esto
        <?php unset($_SESSION['mensaje_exito']); ?>; // Esta línea de PHP no es necesaria en JavaScript
    }
</script>

</body>
</html>