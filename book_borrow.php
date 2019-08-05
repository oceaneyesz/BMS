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
$judge=-5;
session_start();
if ($_SESSION['user_name']==''&&$_SESSION['user_email']=='')
    header("Location:login.php");
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

if (isset($_POST['action'])){
  if($_POST['action']==='lend'){
      if($_POST['book_code']=="")
      {$judge=0;}
      
      else
      {
        
        $book_code=urlencode($_POST['book_code']);        
        $sql="SELECT * FROM BMS_books WHERE `book_status`=1 AND `book_code`='$book_code'";
        $res_num=$pdo->query($sql);
        $num=0;
        if($res_num){
        $num=$res_num->rowCount();
        }
        if($num!=0){
          $lent_time=date('Y-m-d H:i:s',time());
          $apply_return_time = date('Y-m-d',strtotime("$lent_time + 1 month"));                    
          $sql=$pdo->prepare('INSERT INTO BMS_books_user_history(`lent_time`,`book_code`,`user_id`,`user_name`,`apply_return_time`) VALUES (:lent_time,:book_code,:user_id,:user_name,:apply_return_time,);');
          $sql->bindValue(':lent_time',$lent_time);
          $sql->bindValue(':book_code',$book_code);
          $sql->bindValue(':user_id',$id);
          $sql->bindValue(':user_name',$user_name);
          $sql->bindValue(':apply_return_time',$apply_return_time);
          $sql->execute();      
          $judge=1;

          $sql=$pdo->prepare('UPDATE BMS_books SET `book_status`=0,`lent_time`=:lent_time,`user_id`=:user_id WHERE `book_code`=:book_code;');
          $sql->bindValue(':lent_time',$lent_time);
          $sql->bindValue(':user_id',$id);
          $sql->bindValue(':book_code',$book_code);
          $sql->execute();

          $sql=$pdo->prepare('INSERT INTO BMS_books_history(`book_code`,`lent_time`,`apply_return_time`,`user_id`) VALUES (:book_code,:lent_time,:apply_return_time,:user_id);');
          $sql->bindValue(':book_code',$book_code);
          $sql->bindValue(':lent_time',$lent_time);
          $sql->bindValue(':user_id',$id);
          $sql->bindValue(':apply_return_time',$apply_return_time);
          $sql->execute();
          
          $apply_return_time = date('Y-m-d',strtotime('next month'));
          $book_code=$_POST['book_code'];
          $lent_time=date('Y-m-d H:i:s',time());
          $sql=$pdo->prepare('INSERT INTO BMS_books_user_history(`lent_time`,`book_code`,`user_id`,`apply_return_time`) VALUES (:lent_time,:book_code,:user_id,:apply_return_time);');
          $sql->bindValue(':lent_time',$lent_time);
          $sql->bindValue(':book_code',$book_code);
          $sql->bindValue(':user_id',$id);
          $sql->bindValue(':apply_return_time',$apply_return_time);
          $sql->execute();
          
          $sql="SELECT * FROM BMS_books_history WHERE `book_code`='$book_code'";
          $res_lent_num_sel=$pdo->query($sql);
          $res_lent_num = 0;
          if($res_lent_num_sel){
            $res_lent_num=$res_lent_num_sel->rowCount();
          }
          var_dump($res_lent_num);

          $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_status`=:book_status,`book_lent`=:book_lent WHERE `book_code_index`=:book_code_index;');
          $sql->bindValue(':book_status',0);
          $sql->bindValue(':book_lent',$res_lent_num);
          $sql->bindValue(':book_code_index',$book_code);         
          $sql->execute();

          $month_time=date("Y-m-d",strtotime("-1 month "));
          $sql="SELECT * FROM `BMS_books_history` WHERE `book_code`='$book_code' AND `lent_time`>'$month_time'";
          $res_lent_num_sel2=$pdo->query($sql);
          $res_lent_num2 = 0;
          $popular='warm';
          if($res_lent_num_sel2){
          $res_lent_num2=$res_lent_num_sel2->rowCount();
          }
          if($res_lent_num2>=20){$popular='hot';}
          else if($res_lent_num2<20&&$res_lent_num2>=4){$popular='warm';}
          else {$popular='cold';}
          $sql=$pdo->prepare('UPDATE BMS_books_index SET `book_popular`=:book_popular WHERE `book_code_index`=:book_code_index;');
          $sql->bindValue(':book_popular',$popular);
          $sql->bindValue(':book_code_index',$book_code);
          $sql->execute();
          

          $sql="SELECT * FROM `BMS_books_history` WHERE `user_id`='$id';";
          $user_lent_num=$pdo->query($sql);
          $lent_num=$user_lent_num->rowCount();
          $sql=$pdo->prepare('UPDATE BMS_users SET `user_lent`=:user_lent WHERE `user_name`=:user_name;');
          $sql->bindValue(':user_lent',$lent_num);
          $sql->bindValue(':user_name',$user_name);
          $sql->execute();
        }
        else{$judge=0;}
    }
      //没有排序
  }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>图书借阅</title>
    <a href="index.php?id=<?php echo $id ?>">返回主页面</a>
</head>
<body>
    <dl>
      <form  id="book_code" method="POST" action="book_borrow.php">
      <dt>图书编码：</dt>
      <dd><input type="text" class="inputs" name="book_code" placeholder="图书编码"/></dd>
      <button type="submit" name="action" value="lend">借阅图书</button>
      </form>
    </dl>
    
    <script>
    var judge=0;
    judge=<?php echo $judge; ?>;
    if(judge==1){
      alert("借阅成功");
      window.location.href="index.php?id=<?php echo $info["id"];?>";
    }
    else if(judge==0){
      alert("该图书不存在");
    }
    </script>
    </body>
    </html>