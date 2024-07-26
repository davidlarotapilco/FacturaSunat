<?php
include 'db.php';
session_start();

$curl = curl_init();

$id_cliente = $_SESSION['id_vclien'];
$fechaHoraActual = date('d-m-y H-i-s');

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
$SubTotal = 0;
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      echo "ID Factura: " . $row["id_fac"]. " - Cliente: " . $row["c_id_cliente"]. " - Pago: " . $row["mp_id_mpago"]. " - Fecha: " . $row["fecha"]. " - Producto: " . $row["nombrep"]. " - DNI: " . $row["dni"]. " - Nombre Completo: " . $row["nombrecompleto"]. " " . $row["apellidop"]. " " . $row["apellidom"]. " - Dirección: " . $row["direccion"]. "<br>";
      $punitario = $row['preciounitario'];
      $cantidad = $row['cantidad'];
      $SubTotal = $SubTotal + $cantidad * $punitario;
      $ventas[] = [
          "codProducto" => $row['id_producto'],
          "unidad" => 'NIU',
          "descripcion" => $row['nombrep'],
          "cantidad" => $cantidad,
          "mtoValorUnitario" => $punitario,
          "mtoValorVenta" => $cantidad * $punitario,
          "mtoBaseIgv" => 0.18 * $punitario,
          "porcentajeIgv" => 18,
          "igv" => 0.18 * $cantidad * $punitario,
          "tipAfeIgv" => 10,
          "totalImpuestos" => 0.18 * $cantidad * $punitario,
          "mtoPrecioUnitario" => $punitario * 1.18,  // Este valor es un ejemplo, debes ajustar según tu lógica
      ];   
}
} else {
  echo "0 resultados";
}

echo '<pre>';
    print_r($ventas);
echo '</pre>';

  
$detailsJson = json_encode($ventas);

$invoiceData = [
    "ublVersion" => "2.1",
    "tipoOperacion" => "0101",
    "tipoDoc" => "03",
    "serie" => "B001",
    "correlativo" => "1",
    "fechaEmision" => "2024-07-24T00:00:00-05:00",
    "formaPago" => [
      "moneda" => "PEN",
      "tipo" => "Contado"
    ],
    "tipoMoneda" => "PEN",
    "client" => [
      "tipoDoc" => "6",
      "numDoc" => 20000000002,
      "rznSocial" => "Cliente",
      "address" => [
        "direccion" => "Av. Laykakota 451",
        "provincia" => "PUNO",
        "departamento" => "PUNO",
        "distrito" => "PUNO",
        "ubigueo" => "21001"
      ]
    ],
    "company" => [
      "ruc" => 10755482370,
      "razonSocial" => "David Mercado",
      "nombreComercial" => "La tienda de Don Juan",
      "address" => [
        "direccion" => "Jr. Estudiante 123",
        "provincia" => "PUNO",
        "departamento" => "PUNO",
        "distrito" => "PUNO",
        "ubigueo" => "21002"
      ]
    ],
    "mtoOperGravadas" => $SubTotal,
    "mtoIGV" => 0.18*$SubTotal,
    "valorVenta" => $SubTotal*0.18 + $SubTotal,
    "totalImpuestos" => 18,
    "subTotal" => $SubTotal,
    "mtoImpVenta" => $SubTotal*0.18 + $SubTotal,
    "details" => $ventas,
    "legends" => [
      [
        "code" => "1000",
        "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
      ]
    ]
  ];
  
  // Convertir los datos de la factura a JSON
  $invoiceDataJson = json_encode($invoiceData);
  

curl_setopt_array($curl, [
  CURLOPT_URL => "https://facturacion.apisperu.com/api/v1/invoice/pdf",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $invoiceDataJson,
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6ImRhdmlkZWZxb24iLCJjb21wYW55IjoiMTA3NTU0ODIzNzAiLCJpYXQiOjE3MjE4NDMzNjcsImV4cCI6ODAyOTA0MzM2N30.WnHQXCEM-fiSFmXj8tlUwQNx96TARmyokFnPWjQuUSD9-r5QiiYDQegmMSv9innX_-jLceJdkBGaLsV9Qr-4RdT1myfqnd-9gqsf-p0CfmUxFWzdo99oyNdsy03S9h1kCVVjjc9PV62Co-qfSbLK8BMfFAxF9nXBfsNLs_qGrokxlEjbySwnHULVUueKR1_JekNYZ5BEUHJuJ0SMTIsdirYBudIa0WoOfKG1o-cmpA9BStnZS8touO-JYy7_OGElCH11H2s1DoK2bgAfC4RIqhjr8seVx1ut21B5Hos78bEpcDsJsU02sHX-cNsVS6dgAshuBwtwiE8IVjIVd6ot5Pl5WxrTXC7hDhO66svDW_slBDRSf_o7tvGLMzR1kiZx5USdp8uGVfs_DhRdnWQqKo-ciDNyzSrrMc5BU1sBsj7s1ZHpqKUc1sFd875QnJCnpcWtGKKtz_f4cf5AQvvdVL88uVDi0O2BBmw3RjXBwm8uz4Fw_OEHO5bqwvLfq3Br_2My4Y_gfWyfpC5gHeU63fs3IoFmpxQu7F0fne6SGEpnET8pflAEuTjvAijlQJpdF68Vt2fO4EY4E-X0FeDk8DF6bH9r4N8t3KTHt_53TAW6JUP70PpGChMZkTuLJhu8wnFeCN9ZfQhccf4cD6ZsZFDlWdGsQNimsunXIKh6JTU",
    "Content-Type: application/json",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  // Guardar el PDF temporalmente
  $pdfPath = 'boletas/factura' . $id_cliente . '_' . $fechaHoraActual . '.pdf';
  file_put_contents($pdfPath, $response);

  // Redirigir para mostrar el PDF
  header('Location: mostrar_pdf.php?file=' . $pdfPath);
  exit();
}
?>
