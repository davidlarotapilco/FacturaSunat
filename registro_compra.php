<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    echo $producto_id;
    // Añadir producto al carrito
    $_SESSION['carrito'][] = array('producto' => $producto_id, 'cantidad' => $cantidad);
    echo '<pre>';
    //print_r($_SESSION['carrito']);
    echo '</pre>';
}

// Recuperar el ID del cliente desde la sesión
$id_cliente = $_SESSION['id_cliente'];
$id_vclien = 0;
$id_factura = $_SESSION['id_factura'];
echo "id del cliente";
echo $id_factura;
echo "id del cliente";
// Obtener la lista de productos
$sql1 = "SELECT * FROM cliente WHERE d_id_datoscli = '$id_cliente'";
    $result = $conn->query($sql1);
    
    if ($result->num_rows > 0) {
        // El cliente existe
        $row = $result->fetch_assoc();
        $id_vclien = $row['id_cliente'];
        $_SESSION['id_vclien'] = $id_vclien;
        
    } else {
        // El cliente no existe
        $datos_cliente = null;
    }

$sql = "SELECT * FROM detalle_fac WHERE detalle_fac.f_id_fac = '$id_factura'";
echo "id del cliente";
echo $id_factura;
echo "id del cliente";
$result = $conn->query($sql);

$detalles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }
}

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}


$sql = "SELECT * FROM detalle_fac WHERE detalle_fac.f_id_fac = '$id_factura'";//"SELECT * FROM datos_cli WHERE dni = '$dni'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // El cliente existe
    $row = $result->fetch_assoc();
    $datos_factura = $row;
} else {
    // El cliente no existe
    $datos_factura = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Compra</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

<div class="container">
    <h2 class="mt-5">Registrar Compra</h2>
    <form id="formCompra" method="post" action="">
        <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
        <p id="id_cliente">Id datos cliente : <?php echo $id_cliente; ?></p>
        <p id="id_cliente">Id cliente : <?php echo $id_vclien;?></p>
        <div class="form-group">
            <label for="producto">Producto</label>
            <select class="form-control" id="producto" name="producto">
                <?php foreach ($productos as $producto): ?>
                    <option value="<?php echo $producto['id_producto']; ?>">
                        <?php echo $producto['nombrep']; ?> - $<?php echo $producto['preciounitario']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad" require>Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
        </div>
        <button type="submit" form="formCompra" class="btn btn-success" id="agregarProducto">Agregar al Carrito</button>
    </form>

    <h3 class="mt-5">Carrito de Compras</h3>
    <ul id="carrito" class="list-group">
        <!-- Productos añadidos al carrito se mostrarán aquí -->
    </ul>
    <form method="post" action="procesar_venta.php">
        <button type="submit" class="btn btn-success">Registrar Factura</button>
    </form>
</div>




<div class="container">
    <h2 class="mt-5">Registrar Compra</h2>
    <form method="post" action="procesar_venta.php">
        <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
        
        <div id="productos-container">
            <div class="form-group">
                <label for="productos">Producto</label>
                <select name="productos[]" class="form-control">
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombrep']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="cantidades">Cantidad</label>
                <input type="number" name="cantidades[]" class="form-control" min="1" required>
            </div>
        </div>
        
        <button type="button" class="btn btn-secondary" onclick="agregarProducto()">Agregar Producto</button>
        <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
    </form>
</div>  

<?php if ($datos_factura): ?>
<div class="container">
    <h2 class="mt-5">Facturas del Cliente</h2>
    <?php if (!empty($detalles)): ?>
        <ul class="list-group">
            <?php foreach ($detalles as $detalle): ?>
                <li class="list-group-item">
                    <strong>id detalle factura:</strong> <?php echo $detalle['id_detallef']; ?><br>
                    <strong>id factura:</strong> <?php echo $detalle['f_id_fac']; ?><br>
                    <strong>id producto:</strong> <?php echo $detalle['p_id_producto']; ?><br>
                    <strong>cantidad:</strong> <?php echo $detalle['cantidad']; ?><br>
                    
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay detalles para este cliente.</p>
    <?php endif; ?>
</div>
<?php else: ?>
<?php endif; ?>

<?php if ($datos_factura): ?>
    <h3 class="mt-5">Datos de la compra</h3>
    <?php foreach ($detalles as $detalle): ?>
        <li class="list-group-item">
    <p>id detalle factura: <?php echo $datos_factura['id_detallef']; ?></p>
    <p>id factura: <?php echo $datos_factura['f_id_fac']; ?></p>
    <p>id producto: <?php echo $datos_factura['p_id_producto']; ?></p>
    <p>cantidad: <?php echo $datos_factura['cantidad']; ?></p>

    </li>
            <?php endforeach; ?>
    <!-- Formulario para registrar la factura -->
    <form method="get" action="registro_compra.php">
        <button type="submit" class="btn btn-success">Registrar Factura</button>
    </form>
    <?php else: ?>
    <?php endif; ?>

<script>
$(document).ready(function() {
    $('#agregarProducto').click(function() {
        var producto = $('#producto_id option:selected').text();
        var productoId = $('#producto_id option:selected').val();
        var cantidad = $('#cantidad').val();

        $('#carrito').append('<li class="list-group-item">' + producto + ' - Cantidad: ' + cantidad + 
            '<input type="hidden" name="productos[]" value="' + productoId + '">' +
            '<input type="hidden" name="cantidades[]" value="' + cantidad + '">' +
        '</li>');
    });
});
</script>
</body>
</html>
