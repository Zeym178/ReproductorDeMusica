<?php
include_once 'db.php';

$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

$captchaResponse = $_POST['g-recaptcha-response'];
$secretKey = '6LeiGxYmAAAAAFlmtV7LSyKraZTytFbU_6xPF6_0'; 

$responseCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}");
$responseDataCaptcha = json_decode($responseCaptcha);

if (!$responseDataCaptcha->success) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Error en el CAPTCHA'));
    exit;
}

$query = "SELECT * FROM users WHERE email_u = :email_u";
$stmt = $db->prepare($query);
$stmt->bindParam(':email_u', $email);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && password_verify($password, $row['password_u'])) {
    session_start();
    $_SESSION['user_id'] = $row['id_u'];
    $_SESSION['user_name'] = $row['nombre_u'];
    $_SESSION['user_urlimg'] = $row['urlimg'];

    echo json_encode(array('valid' => true));
} else {
    echo json_encode(array('valid' => false));
}
?>
