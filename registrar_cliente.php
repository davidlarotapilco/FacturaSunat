<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $nombrecompleto = $_POST["nombrecompleto"];
    $apellidop = $_POST["apellidop"];
    $apellidom = $_POST["apellidom"];
    $direccion = $_POST["direccion"];

    // Insertar datos en la tabla datos_cli
    $sql = "INSERT INTO datos_cli (dni, nombrecompleto, apellidop, apellidom, direccion)
            VALUES ('$dni', '$nombrecompleto', '$apellidop', '$apellidom', '$direccion')";
    
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;

        // Insertar datos en la tabla cliente
        $sql = "INSERT INTO cliente (d_id_datoscli, nombre) VALUES ('$last_id', '$nombrecompleto')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Cliente registrado exitosamente')</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Registro Exitoso</h2>
    <a href="registro_compra.php" class="btn btn-primary">Registrar Compra</a>
</div>
</body>
</html>
