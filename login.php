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
$flag=3;
$info['id']=-5;
if(isset($_POST['action'])){
  if($_POST['action']==='login'){
    if($_POST['user_name']!=""&&$_POST['user_password']!=""){  
      $user_name=$_POST['user_name'];
      $user_password=$_POST['user_password'];
      $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name');
      $sql->bindValue(':user_name',$user_name);
      $sql->execute();
      $info=$sql->fetch(PDO::FETCH_ASSOC);
      $pdo = null;
      
      if($info === false&&$user_name!="") {
        $flag=3;
      }
      else {
        $real_user_password=$info['user_password'];
      
      if($real_user_password==$user_password) {
        $flag=1;
        session_start();
        $_SESSION['user_name']=$user_name;
        $_SESSION['user_id']=$info['id'];
        $_SESSION['user_type']=$info['user_type'];
      }
      else {
        $flag=2;
      }

     }
  }
}
}

if($flag==1){
  $temp = $info['id'];
  echo "<script>alert('登录成功!');window.location.href='index.html?id=$temp'</script>"; 
}
else if($flag==2){
  echo "<script>alert('密码或用户名错误');window.location.href='login.html'</script>";
}
else if($flag==3) {
  echo "<script>alert('用户不存在');window.location.href='login.html'</script>";
}
