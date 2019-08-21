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
    header("Location:../html/login.html");
else{
  $user_name=$_SESSION['user_name'];
  $user_type=$_SESSION['user_type'];
  $id=$_SESSION['user_id'];
}
echo json_decode($user_name,true);
$sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=:user_name');
$sql->bindValue(':user_name',$user_name);
$sql->execute();
$info=$sql->fetch(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404 can not find the information.</h1>';
  return;
}

$num1=0;$num2=0;
$sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_status`=:book_status AND `user_id`=:user_id');
$sql->bindValue(':book_status',0);
$sql->bindValue(':user_id',$id);
$sql->execute();
$res=$sql->fetchall(PDO::FETCH_ASSOC);

foreach($res as $books){
  $num1++;
  }
$sql=$pdo->prepare('SELECT * FROM BMS_books WHERE `book_status`=:book_status AND `user_id`=:user_id');
$sql->bindValue(':book_status',2);
$sql->bindValue(':user_id',$id);
$sql->execute();
$res=$sql->fetchall(PDO::FETCH_ASSOC);
foreach($res as $books){
  $num2++;
  }


  if(isset($_POST['action'])){
    if($_POST['action']==='signout'){
        session_destroy();
        $flag=1;
    }

    if ($flag==1){  
        echo "<script>alert('您已经成功退出登录！');window.location.href='../html/login.html'</script>";
        
    }
  }

?>
