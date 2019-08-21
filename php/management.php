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
    header("Location:login.html");
else{
  $user_name=$_SESSION['user_name'];
  $user_type=$_SESSION['user_type'];
  $id=$_SESSION['user_id'];
}
if($user_type!=1){
  header('Location:identity_error.php');}
$sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
$sql->bindValue(':user_name',$user_name);
$sql->execute();
$info=$sql->fetch(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404</h1>';
  return;
  }

    $sql='SELECT * FROM BMS_books WHERE `book_status`=2;';
    $res=$pdo->query($sql);
    $num=$res->rowCount();

?>

