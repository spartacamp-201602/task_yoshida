<?php

require_once('config.php');
require_once('function.php');

//データベースに接続する
$dbh = connectDb();

//編集のフォームにindexからタスクタイトルの情報を持ってっくる
$sql = "select * from tasks where id = :id";

//URLからidを取ってくる
$id = $_GET['id'];
// $title =$_GET['title'];

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

//select文の実行結果を$tasksに連想配列で代入
// $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
//レコードを１行だけ引っ張ってくる
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($post);

//編集ボタンが押された時
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //フォームに入力したものをとってくる
    $title = $_POST['title'];
    //バリデーション
    $errors = array();//エラーの情報を格納する配列
    if ($title == "")
    {
        $errors['title'] = 'タスク名を入力してください。';
    }

    if ($title == $post['title'])
    {
        $errors['title'] = 'タスク名が変更されていません。';
    }

    //エラーがないか確認 emptyは純粋に空かどうか検証する
    if (empty($errors))
    {
        $sql = 'update tasks set title = :title, updated_at = now() where id = :id';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        //自分自身にリダイレクトする
        header('Location: index.php');
        exit;//一旦強制終了
    }
}
//編集ボタンが押された時終了
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>編集画面</title>
</head>
<body>
<h2>タスクの編集</h2>
<p>
    <form action="" method="post">
        <input type="text" name="title" value="<?php echo h($post['title']) ?>">
        <!-- <input type="text" name="id" value="<?php echo $id ?>"> -->
        <input type="submit" value="更新">
        <span style="color:red;">
            <?php echo $errors['title'] ?>
        </span>
    </form>
</p>
</body>
</html>