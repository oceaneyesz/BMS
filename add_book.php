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
        $sql=$pdo->prepare('INSERT INTO BMS_books_index(`book_code_index`,`book_name`,`book_author`,`book_status`)
          VALUES(:book_code_index,:book_name,:book_author,:book_status);');
        $sql->bindValue(':book_code_index',$_POST['book_code']);
        $sql->bindValue(':book_name',$_POST['book_name']);
        $sql->bindValue(':book_author',$_POST['book_author']);
        $sql->bindValue(':book_status',1);
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
      <form action="add_book.php" method="post">
      <input  type="text" class="inputs" name="book_code" placeholder="图书编号"></dd>
      <input  type="text" class="inputs" name="book_name" placeholder="图书名称"></dd>
      <input  type="text" class="inputs" name="book_author" placeholder="图书作者"></dd>
      <input  type="text" class="inputs" name="book_type" placeholder="图书种类"></dd>
      <button class="register" type="submit" name="action" value="add">添加图书</button>
      <script>
          var flag=0;
          flag=<?php echo $flag ?>;
          if(flag==1){
          alert("添加成功");
          }
          else if(flag==-1){
            alert("该图书已存在");
          }
          else{
            alert("添加失败或该图书已存在");
          }
          flag=0;
          </script>
          </form>
</body>
</html>