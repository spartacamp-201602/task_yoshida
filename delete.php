<?php
require_once('config.php');
require_once('function.php');

$id = $_GET['id'];
$flag = $_GET['flag'];
$dbh = connectDb();

$sql = "update tasks set status = 'delete', flag = :flag where id = :id";


$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':flag', $flag);
$stmt->execute();

//index.phpにリダイレクトする
header('Location: index.php');
exit;