<?php
include_once 'db.php';

$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $db->prepare("DELETE FROM playlist_det WHERE id = ?");
$stmt->execute([$id]);

$response = array('success' => true, 'message' => 'La canción no se eliminó de la playlist.');
echo json_encode($response);
