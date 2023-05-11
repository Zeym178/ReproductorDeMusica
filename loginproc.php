<?php
include_once 'db.php';

$email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

$query = "SELECT * FROM users WHERE email_u = :email_u AND password_u = :password_u";
$stmt = $db->prepare($query);
$stmt->execute(array(':email_u' => $email, ':password_u' => $password));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    session_start();
    $_SESSION['user_id'] = $row['id_u'];
    $_SESSION['user_name'] = $row['nombre_u'];
    $_SESSION['user_urlimg'] = $row['urlimg'];

    echo json_encode(array('valid' => true));
} else {
    echo json_encode(array('valid' => false));
}
?>
