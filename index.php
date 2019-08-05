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

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<h1>尊敬的用户<?php echo $user_name?>，欢迎您！</h1>

</head>
<body>
  <h3><?php echo $num1==0?'':'您有'.$num1.'本书未归还'?></h3>
  <h3><?php echo $num2==0?'':'您有'.$num2.'本书逾期'?></h3>
  <div style="display:inline-block;margin-right:100px;">
        <a href="library.php"><button class="btn btn-warning" style="font-size:150%;font-family:Microsoft YaHei;">图书馆</button></a>
        <a href="book_borrow.php"><button class="btn btn-warning" style="font-size:150%;font-family:Microsoft YaHei;">借阅图书</button></a>
        <a href="book_return.php"><button class="btn btn-warning" style="font-size:150%;font-family:Microsoft YaHei;">归还图书</button></a>
        <a href="user_history.php"><button class="btn btn-warning" style="font-size:150%;font-family:Microsoft YaHei;">历史记录</button></a>
        <a href="management.php"><button class="btn btn-warning" style="font-size:150%;font-family:Microsoft YaHei;">管理功能</button></a>
  </div>
  <div class="content">
      <div id="title">2019年7月10日 星期三</div>
      <div id="clock"></div>
    </div>
  <div id="div">
      <img id=obj src ="picture1.jpg" border =0 /> 
  </div>
  <script type="text/javascript">
      function time(){
          var today = new Date();
          var year=today.getFullYear();
          var month=today.getMonth()+1;
          var date=today.getDate();
          var day=today.getDay();
          var week={
            0:"星期日",
            1:"星期一",
            2:"星期二",
            3:"星期三",
            4:"星期四",
            5:"星期五",
            6:"星期六",
            }
            document.getElementById("title").innerHTML=year +"年  "+month+"月  "+date+"日  "+week[day];
            var today=new Date();
            var hh=today.getHours();
            var mm=today.getMinutes();
            var ss=today.getSeconds();
            document.getElementById("clock").innerHTML=hh +":"+mm +":" +ss;
            setTimeout(time,1000);
          }
          time();
         var arr=new Array();
         arr[0]="picture1.jpg";
         arr[1]="picture2.jpg";
         arr[2]="picture3.jpg";
         arr[3]="picture4.jpg";
         arr[4]="picture5.jpg";
         arr[5]="picture6.jpg";
         arr[6]="picture7.jpg";
         var curIndex=0;
         setInterval(function() {
             var obj=document.getElementById("obj");
            
             if(curIndex==arr.length - 1) {
                 curIndex=0;
             }
             else {
                 curIndex +=1;
             }
             obj.src=arr[curIndex];
             console.log(curIndex);
         },5000)
        
    </script>
</body>
</html>
