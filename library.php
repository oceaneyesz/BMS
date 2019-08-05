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



$books=[-1];
if(isset($_POST['action'])){
    if($_POST['action']==='search'){
      
      $sql=$pdo->prepare('SELECT * FROM BMS_books_index WHERE `book_code_index` LIKE :search OR `book_name` LIKE :search;');
      $sql->bindValue(':search','%'.$_POST['search'].'%');      
      $sql->execute();
      $books=$sql->fetchall(PDO::FETCH_ASSOC);
      $judge=1;      

    }    
    else $judge=0;
}


?>



<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<a href="index.php?id=<?php echo $id ?>">返回主页面</a>
</head>
<body>
<script>var secSelect=document.getElementById("sec").value;</script>
<table width=100%  class="table table-striped" style="overflow:hidden;white-space: nowrap;">
<?php if(empty($books)) echo '<h1 style="position:absolute;top:400px;left:600px;">对不起，未能找到相关书籍。</h1>'?>
<tr>
    <td>图书编号</td>
    <td>图书名称</td>
    <td>图书作者</td>
    <td>图书状态</td>
    <td>借阅热度</td>
    <td>图书好评率</td>
    
       
</tr>
<?php foreach ($books as $value) {?>
  <tr>
      <td><?php echo urldecode($value['book_code_index']);?></td>
      <td><?php echo urldecode($value['book_name']);?></td>
      <td><?php echo $value['book_author']; ?></td>
      <td><?php echo $value['book_status'];?></td>      
      <td><?php echo $value['book_popular'];?></td>
      <td><?php echo urldecode($value['book_eval']); ?></td>
      <td><a href="evaluate.php" >图书评价</a></td>             
      

      
  </tr>
  
  <?php }?>
  <form action="library.php" method="POST">
  <select id="sec" name="search">
  <option>请选择图书种类</option><option value ="B">哲学宗教B</option><option value ="D">政治法律D</option><option value ="E">军事经济E</option><option value ="G">文教科体G</option>
  <option value ="H">语言文字文学H</option><option value ="J">艺术J</option><option value ="K">历史地理天文K</option><option value ="O">数理科学及化学O</option><option value ="Q">生物科学Q</option>
  <option value ="R">医药卫生R</option><option value ="S">农业工业技术S</option>
  </select>
  <input type="text" name="search" placeholder="输入图书编号或名称" />
  <button id="search" type="submit" name="action" value="search"> 搜索 </button>
  </form>

  <a href="books_ranking_list.php"><button type="button">图书借阅排行榜</button></a>
  <a href="users_ranking_list.php"><button type="button">个人借阅排行榜</button></a>
  <a href="all_books.php"><button id="all" type="button" name="action" value="all">全部馆藏</button></a>
  <table width=100%  class="table table-striped" style="overflow:hidden;white-space: nowrap;">
  
</body>
</html>