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
if (($_SESSION['user_name'])==''&&$_SESSION['user_email']=='')
    header("Location:login.php");
else{
  $user_name=$_SESSION['user_name'];
  $user_type=$_SESSION['user_type'];
  $id=$_SESSION['user_id'];
}
$sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=:user_name');
$sql->bindValue(':user_name',$user_name);
$sql->execute();
$info=$sql->fetchall(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404 can not find the information.</h1>';
  return;
}

$sql=$pdo->prepare('SELECT * FROM BMS_books_user_history WHERE `user_id`=:user_id ORDER BY lent_time DESC');
$sql->bindValue(':user_id',$id);
$sql->execute();
$books=$sql->fetchall(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <a href="index.php?id=<?php echo $id ?>">返回主页面</a>
</head>
<body>
<table width=100%  class="table table-striped" style="overflow:hidden;white-space: nowrap;">
            <tr>
                
                
                <td>图书编码</td>
                <td>借阅时间</td>
                <td>归还时间</td>   
            </tr>
<?php foreach ($books as $value) {?>
            <tr>
                
                
                <td><?php echo $value['book_code']; ?></td>
                <td><?php echo urldecode($value['lent_time']); ?></td>
                <td><?php if($value['return_time']==""){echo '--';}else echo urldecode($value['return_time']); ?></td>

            </tr>
<?php }?>
</body>
</html>