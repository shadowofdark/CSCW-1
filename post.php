<?php

/*
  require('includes/config.php');
  require_once 'class.upload.php';

  $handle = new Upload($_FILES['file']);
  $handle->allowed = 'image/*';

  $fecha = date("d") . " del " . date("m") . " de " . date("Y");

  if ($handle->uploaded) {

  //mkdir($_SESSION[username], 0700);

  $handle->Process('' . $_SESSION[username]);
  if ($handle->processed) {

  $stmt = $db->prepare('INSERT INTO Files(direc, nombre, prop,hora,peso) VALUES (:direc, :nombre, :prop, :hora, :peso)');
  $stmt->execute(array(
  ':direc' => '' . $_SESSION[username],
  ':nombre' => $_FILES['file']['name'],
  ':prop' => $_SESSION[username],
  ':hora' => $fecha,
  ':peso' => $_FILES['file']['size']
  ));
  } else {
  echo 'error';
  }
  } */

require('includes/config.php');
# Comprovamos que se haya enviado algo desde el formulario
if (is_uploaded_file($_FILES['file']['tmp_name'])) {
# Definimos las variables
$host = "192.168.1.9";
$port = 21;
$user = "ubuntu";
$password = "1";
$ruta = "/home/ubuntu/archivos/";

$fecha = date("d") . " del " . date("m") . " de " . date("Y");


# Realizamos la conexion con el servidor
$conn_id = @ftp_connect($host, $port);
if ($conn_id) {
    # Realizamos el login con nuestro usuario y contraseña

    if (@ftp_login($conn_id, $user, $password)) {
        # Canviamos al directorio especificado

        if (@ftp_chdir($conn_id, $ruta)) {
            if (!@ftp_chdir($conn_id, $ruta . "/" . $_SESSION['username'])) {


                ftp_mkdir($conn_id, $ruta . "" . $_SESSION['username']); //echo "FC";
                $ruta = $ruta . "" . $_SESSION['username'];
                @ftp_chdir($conn_id, $ruta);
            } else {

                echo $ruta = $ruta . "" . $_SESSION['username'] . "/";
                @ftp_chdir($conn_id, $ruta);
            }

            # Subimos el fichero
            if (@ftp_put($conn_id, $_FILES["file"]["name"], $_FILES["file"]["tmp_name"], FTP_BINARY)) {
                echo "Fichero subido correctamente";
                $stmt = $db->prepare('INSERT INTO Files(direc, nombre, prop,hora,peso) VALUES (:direc, :nombre, :prop, :hora, :peso)');
                $stmt->execute(array(
                    ':direc' => "" . $ruta . "" . $_FILES['file']['name'],
                    ':nombre' => $_FILES['file']['name'],
                    ':prop' => $_SESSION['username'],
                    ':hora' => $fecha,
                    ':peso' => round($_FILES['file']['size']/1024)));
            } else
                echo "No ha sido posible subir el fichero";
        } else
            echo "No existe el directorio especificado";
    } else
        echo "El usuario o la contraseña son incorrectos";
    # Cerramos la conexion ftp
    ftp_close($conn_id);
} else
    echo "No ha sido posible conectar con el servidor";
}else {
echo "Selecciona un archivo...";
}
header('Location: memberpage.php');
?>
