<?php
include_once 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);

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

$stmt = $db->prepare('INSERT INTO playlist (user_id, titulo, urlimg) VALUES (:user_id, :titulo, :urlimg)');
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':titulo', $title);
$stmt->bindParam(':urlimg', $urlimg);
$stmt->execute();

$new_user_id = $db->lastInsertId();

$stmt = $db->prepare('SELECT * FROM playlist WHERE id = :id');
$stmt->bindParam(':id', $new_user_id);
$stmt->execute();
$new_user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($new_user);
