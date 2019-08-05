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
?>

<!DOCTYPE html>
  <html>

  <head>
    <meta charset="UTF-8">
    <title>注册</title>
    <!-- <link href="register.css" rel="stylesheet"> -->
　　
  </head>
  <body>
      <form  name="myform" id="myform" method="POST" action="register1.php" onsubmit="check()">
        <h1 class="bold">注册账户</h1>
        <dl>
          <dt>你的Email：</dt>
          <dd><input id="email" type="text" class="inputs" onblur="checkEmail()" name="user_email" placeholder="邮箱"/></dd>
          <span id="email1"></span>
        </dl>
        <dl>
          <dt>你的用户名：</dt>
          <dd><input id="username" type="text" class="inputs" onblur="checkUsername()" name="user_name" placeholder="用户名"/></dd>
          <span id="username1"></span>
        </dl>
        <dl>
            <dt>你的密码：</dt>
            <dd><input id="password" type="password" class="password" onblur="checkPassword()" name="user_password" placeholder="密码"/></dd>
            <span id="password1"></span>
        </dl>
        <dl>
            <dt>再次确认密码：</dt>
            <dd><input id="repassword" type="password" class="repassword" onblur="checkRepassword()" name="user_repassword" placeholder="确认密码"/></dd>
            <span id="repassword1"></span>
        </dl>
        <dl>
            <dt>管理员验证密码：</dt>
            <dd>
              <input id="admin_password" type="password" class="password" 
                  	onblur="checkAdpassword()" name="admin_password"  placeholder="管理员密码（用户不填）"/>
            </dd>
            <span id="admin_password1"></span>
        </dl>
        <button class="register" type="submit" name="action" value="register">注册账户</button>
      </form>
  <script>
    console.log("asf")
 function checkUsername(){
  console.log("asdsad")
   var name=document.getElementById("username").value.trim();/*去除字符串两端的空白字符*/
   var name1=/^[^@#]{3,16}$/;
     if(!name1.test(name)){      
         document.getElementById("username1").innerHTML=
          "用户名为3~16个字符，且不能包含”@”和”#”字符?";
     }
     else{
         document.getElementById("username1").innerHTML="";
         return true;
     }
    }

function checkPassword(){
  var password=document.getElementById("password").value;
  var password1=/^[0-9A-Za-z]\w{7,16}$/;
  if(!password1.test(password)){
    document.getElementById("password1").innerHTML="密码长度必须在8个字符到16个字符之间";    
  }
  else{
      document.getElementById("password1").innerHTML="";
      return true;
  }

}

function checkRepassword(){
  var repassword=document.getElementById("repassword").value;
  var password=document.getElementById("password").value;
  if(repassword!=password){
      document.getElementById("repassword1").innerHTML="两次输入的密码不一致";      
  }
  else{
      document.getElementById("repassword1").innerHTML="";
      return true;
  }
}

function checkEmail(){
  var email=document.getElementById("email").value.trim();
  var checkemail=/^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
  if(!checkemail.test(email)){
    document.getElementById("email1").innerHTML="输入的邮箱格式不正确";
    
  }
  else{
    document.getElementById("email1").innerHTML="";
      return true;
  }
}

function checkAdpassword(){
  var adpassword=document.getElementById("admin_password").value;
  if((adpassword!="999999")&&(adpassword!="")){
    document.getElementById("admin_password1").innerHTML="管理员验证密码不正确";
  }
  else{
    document.getElementById("admin_password1").innerHTML="";
  }
}


          var judge=0, type=0;
          judge=<?php echo $judge; ?>;
          type=<?php echo $user_type;?>;          
          if (judge == 1) {
              alert("恭喜，注册成功！");
              if(type == 1){alert("您为管理员身份");}
              judge=0;
              let temp = <?php echo $info["id"] ?? null;?>
              window.location.href="index.php?id=<?php echo $info["id"];?>"; 
          }
          if((judge==2)){
              alert("该用户名已经被注册");
              judge=0;
          }
          if((judge==3)){
              alert("抱歉，注册失败");
              judge=0;
          }
        </script>
  </body>
</html>