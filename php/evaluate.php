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
$_SESSION['book_code']=123000;
$flag=-5;
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
$_SESSION['book_code']=$_GET['book_code'];
if (isset($_POST['action'])){
  if($_POST['action']==='eval'){


$sql=$pdo->prepare('INSERT INTO BMS_books_evaluate(`user_id`,`user_name`,`book_code`,`book_user_eval` VALUES (:user_id,:user_name,:book_code,:book_user_eval);');
$sql->bindValue(':user_id',$id);
$sql->bindValue(':user_name',$user_name);
$sql->bindValue(':book_code',$_SESSION['book_code']);
$sql->bindValue(':book_user_eval',urlencode($_POST['evaluate']));
$sql->execute();
$flag=1;
  
}

$sql=$pdo->prepare('SELECT * FROM BMS_books_evaluate WHERE `book_code`=:book_code');
$sql->bindValue(':book_code',$_SESSION['book_code']);
$sql->execute();
$books=$sql->fetchall(PDO::FETCH_ASSOC);
$num1=0;$num2=0;
foreach ($books as $value){
  if(($value['book_user_eval'])=="1"){$num1++;}
  $num2++;
}
if($num2!==0){
$book_eval=round($num1/$num2,2);
$sql=$pdo->prepare('UPDATE BMS_books_index SET `book_eval`=:book_eval WHERE `book_code`=:book_code');
$sql->bindValue(':book_eval',$book_eval);
$sql->bindValue(':book_code',$_SESSION['book_code']);
$sql->execute();
}


if($flag==1){
  $temp=$id;  
  echo "<script>alert('评价成功！');window.location.href='../html/library.php'</script>";
}

}
?>

