<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$cliente_id = $_SESSION['id_cliente'];
    $cliente_id = $_SESSION['id_vclien'];
    $metodo_pago_id = 1; // Esto debería obtenerse de alguna manera (p. ej., formulario)
    $fecha = date('Y-m-d');

    // Insertar datos en la tabla factura
    $sql2 = "INSERT INTO factura (c_id_cliente, mp_id_mpago, fecha) VALUES ($cliente_id, $metodo_pago_id, $fecha)";

    if ($conn->query($sql2) === TRUE) {
        $factura_id = $conn->insert_id;
        $_SESSION['id_factura'] = $factura_id;
        // Insertar datos en la tabla detalle_fac
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidades'];
        
        for ($i = 0; $i < count($productos); $i++) {
            $producto_id = $productos[$i];
            $cantidad = $cantidades[$i];
            $sql3 = "INSERT INTO detalle_fac (f_id_fac, p_id_producto, cantidad) VALUES ('$factura_id', '$producto_id', '$cantidad')";
            $conn->query($sql3);
        }
        echo "Venta registrada con éxito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



//header("Location: registro_compra.php");
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta Procesada</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Venta Procesada</h2>
    <a href="index.php" class="btn btn-primary">Regresar al Inicio</a>
</div>
</body>
</html>
