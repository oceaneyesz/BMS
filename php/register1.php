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
$judge = -5;
$user_type = -5;
$info['id'] =null;
if(isset($_POST['action'])){
  if($_POST['action']==='register'){
      $user_name=$_POST['user_name'];
      $user_email=$_POST['user_email'];
      $user_password=$_POST['user_password'];
      $user_repassword =$_POST['user_password'];
      $user_password =password_hash($_POST['user_password'], PASSWORD_DEFAULT);/*加密*/
      $sql="SELECT * FROM BMS_users WHERE `user_name`='".$_POST['user_name']."'";
      if($_POST['admin_password']=='999999'){
        $user_type=1;
      }
      else{
        $user_type=0;
      }    
    $res=$pdo->query($sql);
    $rowCount=$res->rowCount();
    if ($rowCount==0){
      $sql=$pdo->prepare('INSERT INTO BMS_users(`user_email`,`user_name`,`user_password`,`user_type`,`user_reg_time`) VALUES(:user_email ,:user_name ,:user_password,:user_type,:user_reg_time);');
      $sql->bindValue(':user_email',$_POST['user_email']);
      $sql->bindValue(':user_name',$_POST['user_name']);
      $sql->bindValue(':user_password',$_POST['user_password']);
      $sql->bindValue(':user_type',$user_type);
      $sql->bindValue(':user_reg_time',date('Y-m-d H:i:s',time()));
      $execute_res=$sql->execute();
      if ($execute_res==1){
        $judge=1;
        $_SESSION['user_name']=$user_name;
        $_SESSION['user_email']=$user_email;
        $_SESSION['user_type']=$user_type;
        $sql=$pdo->prepare('SELECT * FROM BMS_users WHERE `user_name`=BINARY :user_name');
        $sql->bindValue(':user_name',$user_name);
        $sql->execute();
        $info=$sql->fetch(PDO::FETCH_ASSOC);
                  if($info === false) {
                          echo '<h1>404</h1>';
                          return;
                      }
                      else {
                          $_SESSION['user_id']=$info['id'];        
                      }
                  }
              else{
                      $judge=3;
                  }
          }
          else{
              $judge=2;
          }
      }
      else $judge=-1;
  }

        
  if ($judge == 1) {
      
      $temp=$info["id"];
      if($type == 1){alert("您为管理员身份");
      echo "<script>alert('恭喜，注册成功,您为管理员身份!');window.location.href='index.php?id=$temp'</script>";
      }
      else{
      echo "<script>alert('恭喜，注册成功！');window.location.href='index.php?id=$temp'</script>";
      }
      $judge=0;
     
  }
  if(($judge==2)){
      echo "<script>alert('该用户名已经被注册');window.location.href='register1.html'</script>";
      $judge=0;
  }
  if(($judge==3)){
    echo "<script>alert('抱歉，注册失败');window.location.href='register1.html'</script>";      
      $judge=0;
  }

?>

