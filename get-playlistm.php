<?php
include_once 'db.php';
session_start();

$playlist_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $db->prepare("SELECT p.id, m.title, m.artist, m.url, m.urlimg FROM playlist_det p INNER JOIN songs m ON m.id = p.song_id WHERE p.playlist_id = :playlist_id");
$stmt->bindParam(':playlist_id', $playlist_id);
$stmt->execute();

$songs = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $songs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($songs);
?>
