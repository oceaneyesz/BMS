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
$flag=-5;
if(isset($_POST['action'])){
  if($_POST['action']==='delete'){
    $book_code=$_POST['book_code'];
    $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index`=:book_code_index');
    $sql->bindValue(':book_code_index',$book_code);
    $sql->execute();      
    $info=$sql->fetchall(PDO::FETCH_ASSOC);

    if($info!=0){
    $sql=$pdo->prepare('DELETE FROM BMS_books WHERE `book_code`= :book_code;');
    $sql->bindValue(':book_code',$book_code);
    $sql->execute();

    $sql=$pdo->prepare('DELETE FROM BMS_books_index WHERE `book_code_index`= :book_code_index');
    $sql->bindValue(':book_code_index',$book_code);
    $sql->execute();
    $flag=1;
    }
    else{
      $flag=0;
    }
  }


  $flag 
  if($flag==1){
    echo "<script>alert('删除成功');window.location.href='../html/add_book.html'</script>";
  
  }
  else if($flag==-1){    
    echo "<script>alert('该图书不存在');window.location.href='../html/add_book.html'</script>";
  else{
    
    echo "<script>alert('删除失败或该图书不存在');window.location.href='../html/add_book.html'</script>";
    
  }
  $flag=-5;

}

?>


