<?php
include_once 'db.php';

$usname = filter_var($_POST['registerUser'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['registerEmail'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['registerPassword'], FILTER_SANITIZE_STRING);
$hashpassword = password_hash($password, PASSWORD_DEFAULT);

$captchaResponse = $_POST['g-recaptcha-response'];
$secretKey = '6LeiGxYmAAAAAFlmtV7LSyKraZTytFbU_6xPF6_0'; 

$stmt = $db->prepare('SELECT * FROM users WHERE email_u = :email_u');
$stmt->bindParam(':email_u', $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'El correo electrónico ya está vinculado a otra cuenta.'));
    exit;
}

$responseCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}");
$responseDataCaptcha = json_decode($responseCaptcha);

if (!$responseDataCaptcha->success) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Error en el CAPTCHA'));
    exit;
}

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

$stmt = $db->prepare('INSERT INTO users (nombre_u, email_u, password_u, urlimg) VALUES (:nombre_u, :email_u, :password_u, :urlimg)');
$stmt->bindParam(':nombre_u', $usname);
$stmt->bindParam(':email_u', $email);
$stmt->bindParam(':password_u', $hashpassword);
$stmt->bindParam(':urlimg', $urlimg);
$stmt->execute();

$new_user_id = $db->lastInsertId();

$stmt = $db->prepare('SELECT * FROM users WHERE id_u = :id_u');
$stmt->bindParam(':id', $new_user_id);
$stmt->execute();
$new_user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($new_user);
