<?php

require('includes/config.php');
$_POST['comment'];
$fecha = date("d") . "/" . date("m") . "/" . date("Y");

$stmt = $db->prepare('INSERT INTO novedades (novedad, user, fecha) VALUES (:novedad, :user, :fecha)');
$stmt->execute(array(
    ':novedad' => $_POST['comment'],
    ':user' => $_SESSION[username],
    ':fecha' => $fecha
));
header('Location: memberpage.php');
?>
