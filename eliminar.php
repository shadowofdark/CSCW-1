<?php

require('includes/config.php');
$archivo = $_GET['nombre'];
$idArchivo = $_GET['id'];

/*
  $stmt = $db->prepare('SELECT * FROM Files WHERE prop = :username');
  $stmt->execute(array(':username' => $_SESSION['username']));
  $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

  unlink($_SESSION['username']."/".$row[0]['nombre']);

  $stmt = $db->prepare('DELETE  FROM Files WHERE id_file = :idArchivo');
  $stmt->execute(array(':idArchivo' => $idArchivo));


  header('Location: memberpage.php');
 */
echo $file = '/home/ubuntu/archivos/' . $_SESSION['username'] . '/' . $archivo;
$ftp_server = '192.168.1.9';
$ftp_user_name = 'ubuntu';
$ftp_user_pass = '1';
// establecer conexión básica
$conn_id = ftp_connect($ftp_server);

// iniciar sesión con nombre de usuario y contraseña
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// intentar eliminar el archivo $file
if (ftp_delete($conn_id, $file)) {
    //echo "$file se ha eliminado satisfactoriamente\n";
    $stmt = $db->prepare('DELETE  FROM Files WHERE id_file = :idArchivo');
    $stmt->execute(array(':idArchivo' => $idArchivo));
} else {
    //echo "No se pudo eliminar $file\n";
}

// cerrar la conexión ftp
ftp_close($conn_id);

header('Location: memberpage.php')
?>