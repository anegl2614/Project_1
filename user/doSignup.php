<?php
session_start();
require_once("../db_connect_bark_bijou.php");

if (!isset($_POST["account"])) {
    die("請循正常管道進入此頁");
}

$name = $_POST['name'];
$gender_id = $_POST['gender_id'];
$account = $_POST['account'];
$password = $_POST['password'];
$repassword = $_POST["repassword"];
$email = $_POST['email'];
$phone = $_POST['phone'];
$birth_date = $_POST['birth_date'];

if (strlen($account) < 4 || strlen($account) > 20) {
    die("請輸入4~20字元的帳號");
}

$sql = "SELECT *FROM users WHERE account='$account'";
$result = $conn->query($sql);
$userCount = $result->num_rows;
// echo $userCount;
if ($userCount == 1) {
    die("該帳號已經存在");
}

if (strlen($password) < 4 || strlen($password) > 20) {
    die("請輸入4~20字元的密碼");
}

if ($password != $repassword) {
    die("密碼不一致");
}
// 加密
$password = md5($password);

$now = date("Y-m-d");
$sql = "INSERT INTO users (name, gender_id, account, password, email, phone, birth_date, created_at, valid)
	VALUES ('$name', '$gender_id', '$account', '$password', '$email', '$phone', '$birth_date', '$now', 1)";

if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id;  // 取得剛插入資料的 ID
    $_SESSION['user_id'] = $user_id; // 儲存使用者 ID 到 SESSION
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    die;
}

$conn->close();

header("location: sign_up_3.php");
