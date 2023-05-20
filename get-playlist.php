<?php
include_once 'db.php';
session_start();

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare('SELECT id, titulo, urlimg FROM playlist WHERE user_id = :user_id');
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$playlist = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $playlist[] = $row;
}

echo json_encode($playlist);
?>
