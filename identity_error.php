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

$sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name;');
$sql->bindValue(':user_name',$user_name);
$sql->execute();
$info=$sql->fetch(PDO::FETCH_ASSOC);
if($info === false) {
  echo '<h1>404</h1>';
  return;
  }
  
?>
<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">

    </head>

    <style>

        span{

            font-size: 200%;

        }

    </style>

    <script>

        var t1=setTimeout("document.getElementById('note').innerHTML='2秒后跳转到用户主页'",1000);

        var t2=setTimeout("document.getElementById('note').innerHTML='1秒后跳转到用户主页'",2000);

        var t3=setTimeout("document.getElementById('note').innerHTML='0秒后跳转到用户主页';window.location.href='index.php?id=<?php echo $id?>';",3000);

    </script>

    <body>

            <div style="background-color: white;position: relative;margin: 0 auto;top:40%;width: 600px;height: 100px;border-radius:  1.5em 0.5em 1.5em 0.5em">

                <span>对不起，你没有权限访问此页面！</span><br>

                <span id="note" style="color: red">3秒后跳转到用户主页</span>

            </div>

        </div>

    </body>

</html>