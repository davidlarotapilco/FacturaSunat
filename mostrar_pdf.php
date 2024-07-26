<?php
if (isset($_GET['file'])) {
  $file = $_GET['file'];
  $id_user = $_SESSION['id_vclien'];
  

  if (file_exists($file)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($file) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    readfile($file);
  } else {
    echo "El archivo no existe.";
  }
} else {
  echo "No se especificó ningún archivo.";
}
?>
