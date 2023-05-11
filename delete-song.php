<?php
include_once 'db.php';

$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $db->prepare("SELECT * FROM songs WHERE id = ?");
$stmt->execute([$id]);
$song = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("DELETE FROM songs WHERE id = ?");
$stmt->execute([$id]);

$stmt = $db->prepare("DELETE FROM playlist_det WHERE song_id = ?");
$stmt->execute([$id]);

if ($song['urlimg']) {
    $img_path = $song['urlimg'];
    if (file_exists($img_path) && $img_path!='images/music-logo.jpg') {
        unlink($img_path);
    }
}

if ($song['url']) {
    $song_path = $song['url'];
    if (file_exists($song_path)) {
        unlink($song_path);
    }
}

$response = array('success' => true, 'message' => 'La canción no se eliminó correctamente.');
echo json_encode($response);
