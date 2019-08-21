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
$sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=:user_name');
$sql->bindValue(':user_name',$user_name);
$sql->execute();
$info=$sql->fetch(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404 can not find the information.</h1>';
  return;
}



$books=[-1];
if(isset($_POST['action'])){
      echo 554;
      
      $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index` LIKE :search OR `book_name` LIKE :search;');
      $sql->bindValue(':search','%'.$_POST['search'].'%');      
      $sql->execute();
      $books=$sql->fetchall(PDO::FETCH_ASSOC);
      $judge=1;      

     if(empty($books)) echo '<h1 style="position:absolute;top:400px;left:600px;">对不起，未能找到相关书籍。</h1>';
 
    else $judge=0;
}
  $books=json_encode($books);
  echo $books;
  $temp=$id;
  echo "<script>window.location.href='../html/library.html?id=$temp'</script>";
?>

