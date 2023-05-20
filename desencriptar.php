<?php
include_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

$idEncriptado = $_POST['codigo'];

$id = base64_decode($idEncriptado);
$id = base64_decode($id);

$stmtSelect = $db->prepare('SELECT titulo, urlimg FROM playlist WHERE id = :id');
$stmtSelect->bindParam(':id', $id);
$stmtSelect->execute();

$row = $stmtSelect->fetch(PDO::FETCH_ASSOC);
$titulo = $row['titulo'];
$urlimg = $row['urlimg'];

$stmtInsert = $db->prepare('INSERT INTO playlist (user_id, titulo, urlimg) VALUES (:user_id, :titulo, :urlimg)');
$stmtInsert->bindParam(':user_id', $user_id);
$stmtInsert->bindParam(':titulo', $titulo);
$stmtInsert->bindParam(':urlimg', $urlimg);
$stmtInsert->execute();

$ultimoIDInsertado = $db->lastInsertId();

$stmtu = $db->prepare('SELECT song_id FROM playlist_det WHERE playlist_id = :playlist_id');
$stmtu->bindParam(':playlist_id', $id);
$stmtu->execute();

while ($row = $stmtu->fetch(PDO::FETCH_ASSOC)) {
    $song_id = $row['song_id'];

    $stmtSongs = $db->prepare('INSERT INTO playlist_det (playlist_id, song_id) VALUES (:playlist_id, :song_id)');
    $stmtSongs->bindParam(':playlist_id', $ultimoIDInsertado);
    $stmtSongs->bindParam(':song_id', $song_id);
    $stmtSongs->execute();
}

$response = array('success' => true, 'message' => 'No se pudo importar la playlist');
echo json_encode($response);
?>