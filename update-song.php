<?php
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $artist = filter_var($_POST['artist'], FILTER_SANITIZE_STRING);
    $audio_file = $_FILES['audio'];
    $img_file = $_FILES['img'];

    $stmt = $db->prepare('SELECT * FROM songs WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($audio_file['size'] > 0) {
        $audio_path = $song['url'];
        if (file_exists($audio_path)) {
            unlink($audio_path);
        }

        $audio_filename = $audio_file['name'];
        $audio_ext = strtolower(pathinfo($audio_filename, PATHINFO_EXTENSION));
        $audio_path = 'musicas/'. uniqid() . '.' . $audio_ext;
        move_uploaded_file($audio_file['tmp_name'], $audio_path);
    } else {
        $audio_path = $song['url'];
    }

    if ($img_file['size'] > 0) {
        $img_path = $song['urlimg'];
        if (file_exists($img_path)) {
            unlink($img_path);
        }

        $img_filename = $img_file['name'];
        $img_ext = strtolower(pathinfo($img_filename, PATHINFO_EXTENSION));
        $img_path = 'images/'. uniqid() . '.' . $img_ext;
        move_uploaded_file($img_file['tmp_name'], $img_path);
    } else {
        $img_path = $song['urlimg'];
    }

    $stmt = $db->prepare('UPDATE songs SET title = :title, artist = :artist, url = :url, urlimg = :urlimg WHERE id = :id');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':url', $audio_path);
    $stmt->bindParam(':urlimg', $img_path);
    $stmt->bindParam(':id', $id);
    $result = $stmt->execute();

    if ($result) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false, 'message' => 'Error al actualizar la canción.');
    }

    echo json_encode($response);
}
