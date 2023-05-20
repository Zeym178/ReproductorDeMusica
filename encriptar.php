<?php
$id = $_POST['id'];

$idEncriptado = base64_encode($id);
$idEncriptado = base64_encode($idEncriptado);

echo $idEncriptado;
?>