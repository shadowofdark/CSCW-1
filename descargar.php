<?php

require('includes/config.php');

$nombreUsu = $_GET['usu'];
$archivo = $_GET['nombre'];
$local_file = $_GET['nombre']; //Nombre archivo en nuestro PC
$server_file = "/home/ubuntu/archivos/" . $nombreUsu . "/"; //Nombre archivo en FTP
// Establecer la conexión
$ftp_server = '192.168.1.9';
$ftp_user_name = 'ubuntu';
$ftp_user_pass = '1';
$conn_id = ftp_connect($ftp_server);

// Loguearse con usuario y contraseña
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);


@ftp_chdir($conn_id, $server_file);

header("Content-Disposition: attachment; filename=$archivo\n\n");
header("Content-Type: application/octet-stream");
header("Content-Length: " . filesize($server_file));


ftp_close($conn_id);
?>