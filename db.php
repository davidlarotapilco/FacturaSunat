<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "factura";

$conn = new mysqli($servername, $username, $password, $dbname);
//$token = "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6ImRhdmlkZWZxb24iLCJpYXQiOjE3MjE4NDE0MzUsImV4cCI6MTcyMTkyNzgzNX0.UunGOoFyrBimx00GWn1k54K7F1NG9SfV3E3hUS0_NR7D2EWvvDCIV8GNc_rHD6GJ2sZvsFBkAW0Cf1WCUgovqNitSkdc_5mx5AN7TL_d7FT5e1WS2Lt7vch9f9RDBH2_8BFikREEhepa7j7wP9-4AvkLri6opM35Pz97ceo41XzkxuuCyTDkD3YMPF8wNnse1CsmEfz1vYD3qncP1PRjGR_G8vCmuiSlG00QRoP1hAqjX1JiY5IkIsHMxoh9A9LRW2I0HdPejja-ZSarMfTT728KXgdG9rfg6dsIpX0SnsINFcPCty_jrw3rjOyWeCtWxkKqRm95vqp01Sdjmg5aceLVEY524XQJ9LE3HpmhRACS72MR_dXwjaIipeF_So-lsPVqel_g7mcJuRkXcCCe67L1mCM59ykkacDV6cN4RjRU685y0wapdn76Y-HY3kIm64CabiaO5LmWbG4lv2jBOaakYQBvDv6yn1aj_UgDaNCWqikTC3dIKH8-ae-pkV4tachqSER38gebp5AR_xIs7-Yv7hvrgNmMAfjH9ro3WhXugxw4DdVl3yV4-5lizA74Ki0XKuRK47F89XyD50QXi0PCbjurbQIN80JuqDHUqAEarfmfP2qlPQZD7MIPogt1Gdj_apz4L7iaAcXiqYBtwv4avc4NuHm8vPnObq6ajRY" 

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
