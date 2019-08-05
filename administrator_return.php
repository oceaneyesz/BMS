<?php
ini_set('date.timezone','Asia/Shanghai');
$conn_hostname = 'localhost';
$conn_database = 'BMS1';
$conn_username = 'root';
$conn_password = '';
try{
  $pdo = new PDO('mysql:host='.$conn_hostname.';dbname='.$conn_database,$conn_username, $conn_password);
  $pdo->exec('SET NAMES UTF8');
}
catch(Exception $e){
  echo '<h1>Error of database-link!</h1>';
  return;
}
session_start();
if ($_SESSION['user_name']==''&&$_SESSION['user_email']=='')
    header("Location:login.php");
else{
  $user_name=$_SESSION['user_name'];
  $user_type=$_SESSION['user_type'];
  $id=$_SESSION['user_id'];
}

$sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
$sql->bindValue(':user_name',$user_name);
$sql->execute();
$info=$sql->fetch(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404</h1>';
  return;
  }

if($user_type!=1)
{header('Location:identity_error.php');}

if(isset($_POST['action'])){
  if(($_POST['action'])=='return'){

    $sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_code`=:book_code;');
    $sql->bindValue(':book_code',$_POST['book_code']);
    $sql->execute();
    $books=$sql->fetch(PDO::FETCH_ASSOC);

    $sql=$pdo->prepare('UPDATE BMS_books_history SET `return_time`=:return_time WHERE `book_code`=:book_code;');
    $sql->bindValue(':return_time',date('Y-m-d H:i:s',time()));
    $sql->bindValue(':book_code',$_POST['book_code']);
    $sql->execute();

    
    $sql=$pdo->prepare('UPDATE BMS_books SET `book_status`=:book_status,`user_id`=NULL,`lent_time`=NULL WHERE `book_code`=:book_code');
    $sql->bindValue(':book_status',1);
    $sql->bindValue(':book_code',$_POST['book_code']);
    $sql->execute();

    $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_status`=:book_status WHERE `book_code_index`=:book_code_index');
    $sql->bindValue(':book_status',1);
    $sql->bindValue(':book_code_index',$_POST['book_code']);
    $sql->execute();
  

    // $apply_return_time = date('Y-m-d',strtotime('next month'));
    $return_time=date('Y-m-d H:i:s',time());
    $sql=$pdo->prepare('UPDATE BMS_books_user_history SET `return_time`=:return_time WHERE `book_code`=:book_code');
    $sql->bindValue(':return_time',$return_time);
    $sql->bindValue(':book_code',$_POST['book_code']);
    $sql->execute();
    $flag=1;
  }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <a href="index.php?id=<?php echo $id ?>">返回主页面</a>
</head>
<body>
      <div>
      <form action="administrator_return.php" method="post">
      <input id="book_return" type="text" class="inputs" name="book_code" placeholder="图书编号"></dd>
      <button class="register" type="submit" name="action" value="return">归还图书</button>
      <a href="index.php?id=<?php echo $id ?>">返回首页</a>
      <script>
          var flag=0;
          flag=<?php echo $flag ?>;
          if(flag==1){
          alert("归还成功");
          }
          </script>
</body>
</html>