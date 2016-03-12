<?php

require_once('config.php');//設定ファイルを読み込む
require_once('function.php');//設定ファイルを読み込む

$dbh = connectDb();

$sql = 'select * from tasks';
$stmt = $dbh->prepare($sql);
$stmt->execute();//sql実行される
//select文の実行結果を$tasksに連想配列で代入
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
  $title = $_POST['title'];
  $tag = $_POST['tag'];
    //バリデーション
    $errors = array();//エラーの情報を格納する配列
    if ($title == "")
    {
      $errors['title'] = 'タスク名を入力してください。';
    }

    if ($tag == "")
    {
      $errors['tag'] = 'タグを入力してつかーさい';
    }

    //エラーがないか確認 emptyは純粋に空かどうか検証する
    if (empty($errors))
    {
      $sql = 'insert into tasks (title, created_at, updated_at, tag) values (:title, now(), now(), :tag)';

      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':tag', $tag);
      $stmt->execute();
        //自分自身にリダイレクトする
      header('Location: index.php');
        exit;//一旦強制終了
      }
    }
    ?>

    <!DOCTYPE html>
    <html lang="ja">
    <head>
      <meta charset="utf-8">
      <title>マイタスク管理</title>

      <!-- bootstrap適用 -->
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

      <!-- Latest compiled and minified JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

      <link rel="stylesheet" media="(min-width: 690px)" href="style.css">
      <link rel="stylesheet" media="(max-width: 689px)" href="style.css">
    </head>
    <body>
      <div class="row">

       <nav class="navbar navbar-inverse">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./index.php">Tasks!</a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">

              <li><a href="">UserName</a></li>
              <li><img style="margin: 5px;" src="img/sun.png" height="40" width="40" alt=""></li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>

      <div class="container">
        <div class="col-lg-12">
          <div class="main">
            <form action="" method="post" class="form-inline">
              <div class="form-group">
                <input type="text" name="tag" placeholder="タグ" class="form-control">
              </div>
              <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="タスク名" maxlength="20">
              </div>
              <button type="submit" class="btn btn-default">追加</button>
              <span style="color: red;">
               <?php echo $errors['title'] ?>
               <?php echo $errors['tag'] ?>
             </span>
           </form>

           <div class="board">
            <p>現在のタスク</p>
            <div class="card">
              <?php foreach ($tasks as $task) : ?>
                <?php if ($task['status'] == 'notyet') : ?>
                  <div class="tag">
                    <?php echo h($task['tag']) ?>
                  </div>
                  <div class="content">
                    <a href="done.php?id=<?php echo $task['id'] ?>" class="btn-xs btn-primary">完了</a>
                    <a href="delete.php?id=<?php echo $task['id'] ?>" class="btn-xs btn-warning">ゴミ箱</a>
                    <?php echo h($task['title']) ?>
                  </div>

                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="board">
            <p>完了したタスク</p>
            <div class="card">
              <?php foreach ($tasks as $task) : ?>
                <?php if ($task['status'] == 'done') : ?>
                  <div class="tag">
                    <?php echo h($task['tag']) ?>
                  </div>
                  <div class="content">
                    <a href="notyet.php?id=<?php echo $task['id'] ?>" class="btn-xs btn-primary">戻す</a>
                    <a href="delete.php?id=<?php echo $task['id'] ?>" class="btn-xs btn-warning">ゴミ箱</a>
                    <?php echo h($task['title']) ?>
                <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="board">
            <p>ゴミ箱</p>
            <div class="card">
              <?php foreach ($tasks as $task) : ?>
                <?php if ($task['status'] == 'delete' && $task['flag'] == "") : ?>
                  <div class="tag">
                    <?php echo h($task['tag']) ?>
                  </div>
                  <div class="content">
                    <a href="notyet.php?id=<?php echo $task['id'] ?>" class="btn-xs btn-primary">戻す</a>
                    <a href="delete.php?id=<?php echo $task['id'] ?>&flag=<?php echo $task['flag'] = 'delete' ?>" class="btn-xs btn-warning">ゴミ箱</a>
                    <?php echo h($task['title']) ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
<!--       <ul>
        <?php foreach ($tasks as $task) : ?>
          <?php if ($task['status'] == 'done'): ?>
            <li><?php echo h($task['title']) ?></li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul> -->
    </body>
    </html>
