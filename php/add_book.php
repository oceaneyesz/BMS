<?php
ini_set('date.timezone','Asia/Shanghai');
session_start();
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

if($_SESSION['user_name']==''&&$_SESSION['user_email']=='')
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
  if($_POST['action']==='add'){
    $repeat_code=$_POST['book_code'];
    $sql="SELECT * FROM BMS_books WHERE `book_code`='".$repeat_code."'";
    $book_repeat=$pdo->query($sql);
    $rowCount=$book_repeat->rowCount();
    if($rowCount!=0){$flag=-4;}
    else{
      $sql="SELECT * FROM BMS_books_index WHERE `book_code_index`='".$_POST['book_code']."'";      
      $add_index=$pdo->query($sql);
      $fetch_num=$add_index->fetch(PDO::FETCH_ASSOC);
      $index_row=$add_index->rowCount();
      if($index_row==0){
        $sql=$pdo->prepare('INSERT INTO BMS_books_index(`book_code_index`,`book_name`,`book_author`,`book_status`,`book_type`)
          VALUES(:book_code_index,:book_name,:book_author,:book_status,:book_type);');
        $sql->bindValue(':book_code_index',$_POST['book_code']);
        $sql->bindValue(':book_name',$_POST['book_name']);
        $sql->bindValue(':book_author',$_POST['book_author']);
        $sql->bindValue(':book_status',1);
        $sql->bindValue(':book_type',substr($_POST['book_code'],0,1);
        $sql->execute();
        $flag=1;

        $sql=$pdo->prepare('INSERT INTO BMS_books(`book_code`,`book_status`) VALUES(:book_code,:book_status);');
        $sql->bindValue(':book_code',$_POST['book_code']);
        $sql->bindValue(':book_status',1);
        $sql->execute();
      }
      else{
        $flag=-1;
      }

    }
  }
  if($flag==1){    
      echo "<script>alert('添加成功');window.location.href='../html/add_book.html'</script>";
    }
    else if($flag==-1){      
      echo "<script>alert('该图书已存在');window.location.href='../html/add_book.html'</script>";
    }
    else{      
      echo "<script>alert('添加失败或该图书已存在');window.location.href='../html/add_book.html'</script>";
    }
    $flag=-5;
   
}




?>


