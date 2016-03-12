<?php
require_once('config.php');
require_once('function.php');

$dbh = connectDb();

//シングルクォーテションがかぶらないように注意！！
$sql = "update tasks set status = 'notyet' where id = :id";
$id = $_GET['id'];

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

//index.phpにリダイレクトする
header('Location: index.php');
exit;
