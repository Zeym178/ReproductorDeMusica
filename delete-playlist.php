<?php
include_once 'db.php';

$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $db->prepare("SELECT * FROM playlist WHERE id = ?");
$stmt->execute([$id]);
$playlist = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("DELETE FROM playlist WHERE id = ?");
$stmt->execute([$id]);

/*$stmt = $db->prepare("DELETE FROM playlist_det WHERE playlist_id = ?");
$stmt->execute([$id]);*/

if ($playlist['urlimg']) {
    $img_path = $playlist['urlimg'];
    if (file_exists($img_path) && $img_path!='images/music-logo.jpg') {
        unlink($img_path);
    }
}

$response = array('success' => true, 'message' => 'La playlist no se eliminó correctamente.');
echo json_encode($response);
