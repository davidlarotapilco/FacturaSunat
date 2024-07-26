<?php
session_start();
include 'db.php';
$_SESSION['carrito'] = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];

    // Consultar si el cliente ya existe
    $sql = "SELECT * FROM datos_cli WHERE dni = '$dni'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // El cliente existe
        $row = $result->fetch_assoc();
        $datos_cliente = $row;
        $_SESSION['id_cliente'] = $row['id_datoscli'];
        // Guardar el id_cliente en la sesión
        $sql_cliente = "SELECT id_cliente FROM cliente WHERE d_id_datoscli = " . $row['id_datoscli'];
        $result_cliente = $conn->query($sql_cliente);
        if ($result_cliente->num_rows > 0) {
            $row_cliente = $result_cliente->fetch_assoc();
            //$_SESSION['id_cliente'] = $row_cliente['id_cliente'];
            //$_SESSION['id_cli'] = $result_cliente['id_datoscli'];
            
        }
    } else {
        // El cliente no existe
        $datos_cliente = null;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cliente</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Registrar Cliente</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" required>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <?php if ($datos_cliente): ?>
            <h3 class="mt-5">Datos del Cliente</h3>
            <p>Nombre Completo: <?php echo $datos_cliente['nombrecompleto']; ?></p>
            <p>Apellido Paterno: <?php echo $datos_cliente['apellidop']; ?></p>
            <p>Apellido Materno: <?php echo $datos_cliente['apellidom']; ?></p>
            <p>Dirección: <?php echo $datos_cliente['direccion']; ?></p>
            
            <p>ID Cliente: <?php echo $datos_cliente['id_datoscli']; ?></p>
            <p>ID Cliente: <?php echo $_SESSION['id_cliente']; ?></p>

            <!-- Formulario para registrar la factura -->
            <form method="get" action="registro_compra.php">
                <button type="submit" class="btn btn-success">Registrar Factura</button>
            </form>
        <?php else: ?>
            <h3 class="mt-5">Registrar Nuevo Cliente</h3>
            <form method="post" action="registrar_cliente.php">
                <input type="hidden" name="dni" value="<?php echo $dni; ?>">
                <div class="form-group">
                    <label for="nombrecompleto">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombrecompleto" name="nombrecompleto" required>
                </div>
                <div class="form-group">
                    <label for="apellidop">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellidop" name="apellidop" required>
                </div>
                <div class="form-group">
                    <label for="apellidom">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellidom" name="apellidom" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
                <button type="submit" class="btn btn-success">Registrar Cliente</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
