<?php
include_once 'db.php';

$playlist_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_NUMBER_INT);
$song_id = filter_var($_POST['song_id'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $db->prepare('INSERT INTO playlist_det (playlist_id, song_id) VALUES (:playlist_id, :song_id)');
$stmt->bindParam(':playlist_id', $playlist_id);
$stmt->bindParam(':song_id', $song_id);
$stmt->execute();

$response = array('success' => true, 'message' => 'La canción no se agrego.');
echo json_encode($response);