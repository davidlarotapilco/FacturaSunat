<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$cliente_id = $_SESSION['id_cliente'];
    $cliente_id = $_SESSION['id_vclien'];
    $metodo_pago_id = 1; // Esto debería obtenerse de alguna manera (p. ej., formulario)
    $fecha = date('Y-m-d');

    // Insertar datos en la tabla factura
    echo $cliente_id,   $metodo_pago_id,  $fecha;
    //$sql2 = "INSERT INTO factura (c_id_cliente, mp_id_mpago, fecha) VALUES ($cliente_id, $metodo_pago_id, $fecha)";
    $sql2 = "INSERT INTO factura (c_id_cliente, mp_id_mpago, fecha) VALUES ('$cliente_id', '$metodo_pago_id', '$fecha')";

    echo "factura creada";
    if ($conn->query($sql2) === TRUE) {
        $factura_id = $conn->insert_id;
        //$_SESSION['id_vclien'] = $factura_id;
        // Insertar datos en la tabla detalle_fac
        echo " ";
        echo $factura_id;
        echo " ";
        $productos = array_column($_SESSION['carrito'], 'producto');
        $cantidades = array_column($_SESSION['carrito'], 'cantidad');
        //$cantidades = $_POST['cantidades'];
        echo count($_SESSION['carrito']);
        echo '<pre>';
        print_r($_SESSION['carrito']);
        echo '</pre>';
        for ($i = 0; $i < count($_SESSION['carrito']); $i++) {
            $producto_id = $productos[$i];
            $cantidad = $cantidades[$i];
            echo " productos ";
            echo $producto_id;
            echo " cantidades  ";
            echo $cantidad;
            echo " i ";
            echo $i;
            //INSERT INTO `detalle_fac` (`id_detallef`, `f_id_fac`, `p_id_producto`, `cantidad`, `descuento`) VALUES (NULL, '31', '1', '1', NULL);
            $sql = "INSERT INTO detalle_fac (f_id_fac, p_id_producto, cantidad, descuento) VALUES ('$factura_id', '$producto_id', '$cantidad','1')";
            if ($conn->query($sql) === TRUE) {
                //$_SESSION['carrito'] = array();
                echo " ";
                echo $factura_id;
                echo " ";
            }
            else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        echo "Venta registrada con éxito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



$id_cliente = $_SESSION['id_vclien'];

$sql = "SELECT f.id_fac, 
f.c_id_cliente, 
f.mp_id_mpago, f.fecha, 
df.id_detallef, 
df.f_id_fac, 
df.cantidad,
p.nombrep, 
p.id_producto,
p.descripcion,
p.preciounitario,
dc.dni, 
dc.nombrecompleto, 
dc.apellidop, 
dc.apellidom, 
dc.direccion
FROM 
    factura f
INNER JOIN 
    detalle_fac df
    ON f.id_fac = df.f_id_fac
INNER JOIN 
    productos p
    ON p.id_producto = df.p_id_producto
INNER JOIN 
    cliente c
    ON c.id_cliente = f.c_id_cliente
INNER JOIN 
    datos_cli dc
    ON dc.id_datoscli = c.d_id_datoscli
WHERE 
    f.c_id_cliente = '$id_cliente'  
    AND f.id_fac = (
        SELECT MAX(id_fac)
        FROM factura
        WHERE c_id_cliente = '$id_cliente'
    );
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      echo "ID Factura: " . $row["id_fac"]. " - Cliente: " . $row["c_id_cliente"]. " - Pago: " . $row["mp_id_mpago"]. " - Fecha: " . $row["fecha"]. " - Producto: " . $row["nombrep"]. " - DNI: " . $row["dni"]. " - Nombre Completo: " . $row["nombrecompleto"]. " " . $row["apellidop"]. " " . $row["apellidom"]. " - Dirección: " . $row["direccion"]. "<br>";
      $ventas[] = [
        "codProducto" => $row['id_producto'], //$detalle['cod_producto'],
        "unidad" => 'NIU',//$detalle['unidad'],
        "descripcion" => $row['descripcion'],//$detalle['descripcion'],
        "cantidad" => $row['cantidad'],//$detalle['cantidad'],
        "mtoValorUnitario" => $row['preciounitario'],//$detalle['valor_unitario'],
        //$valorVenta = $row['valor_venta'];

        "mtoValorVenta" => 120,//$detalle['valor_venta'],
        "mtoBaseIgv" => 100,//$detalle['base_igv'],
        "porcentajeIgv" => 18,//$detalle['porcentaje_igv'],
        "igv" => 18,//$detalle['igv'],
        "tipAfeIgv" => 10,//$detalle['tipo_afectacion_igv'],
        "totalImpuestos" => 50,//$detalle['total_impuestos'],
        "mtoPrecioUnitario" => 100,//$detalle['precio_unitario']
    ];  
}
} else {
  echo "0 resultados";
}

echo '<pre>';
    print_r($ventas);
echo '</pre>';

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
    <a href="enviar_sunat.php" class="btn btn-primary">Imprimir factura</a>
</div>
</body>
</html>
