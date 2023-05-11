<?php
include_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_FILES['audio'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $artist = filter_var($_POST['artist'], FILTER_SANITIZE_STRING);

    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img = $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $img_name = uniqid() . '.' . $img_ext;
        move_uploaded_file($img_tmp_name, 'images/' . $img_name);
        $urlimg = "images/".$img_name;
    } else {
        $urlimg = "images/music-logo.jpg";
    }

    $audio = $_FILES['audio']['name'];
    $audio_tmp_name = $_FILES['audio']['tmp_name'];
    $audio_ext = strtolower(pathinfo($audio, PATHINFO_EXTENSION));
    $audio_name = uniqid() . '.' . $audio_ext;
    move_uploaded_file($audio_tmp_name, 'musicas/' . $audio_name);
    $url = "musicas/".$audio_name;
    
    $stmt = $db->prepare('INSERT INTO songs (title, artist, user_id, url, urlimg) VALUES (:title, :artist, :user_id, :url, :urlimg)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':artist', $artist);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':urlimg', $urlimg);
    $stmt->execute();

    $new_song_id = $db->lastInsertId();

    $stmt = $db->prepare('SELECT * FROM songs WHERE id = :id');
    $stmt->bindParam(':id', $new_song_id);
    $stmt->execute();
    $new_song = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($new_song);
} else {
    echo 'No se envió ningún archivo';
}
