<?php
include_once 'db.php';
session_start();

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare('SELECT id, title, artist, url, urlimg FROM songs WHERE user_id = :user_id');
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$songs = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $songs[] = $row;
}

echo json_encode($songs);
?>
