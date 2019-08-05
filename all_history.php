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
$sql=$pdo->prepare('SELECT * FROM BMS_users');
$sql->execute();
$info=$sql->fetch(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404 can not find the information.</h1>';
  return;
}

$sql=$pdo->prepare('SELECT * FROM BMS_books_history ORDER BY lent_time DESC');
$sql->execute();
$books=$sql->fetchall(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
</head>
<body>
<table width=100%  class="table table-striped" style="overflow:hidden;white-space: nowrap;">
            <tr>
              
                <td>图书编码</td>
                <td>借阅时间</td>
                <td>归还时间</td>
                <td>借阅用户</td>   
            </tr>
<?php foreach ($books as $value) {?>
            <tr>                
                <td><?php echo $value['book_code']; ?></td>
                <td><?php echo urldecode($value['lent_time']); ?></td>
                <td><?php echo urldecode($value['return_time']); ?></td>
                <td><?php echo $value['user_id']; ?></td>
            </tr>
<?php }?>
</body>
</html>