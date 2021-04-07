<?php
session_start();
// var_dump($_SESSION);
//DB呼び出し
require_once "db.php";
//DBインスタンス作成
$db = new Db();

/* ------ 1を持っていない人はログインへ戻される----- */
if($_SESSION['login']!= 1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
/* ------------------- end ------------------- */


/* --------- ログアウトボタンが押されたら --------*/
if(isset($_POST['logout'])){
    session_destroy();
    header('Location:login.php');
}
/* ------------------- end ------------------- */


/* --------------- db接続 --------------- */
$db->db_access();
// productsからデータを取得
$get_products_info = $db->prepare("SELECT * FROM products");
$get_products_info->execute();
$mysql = $get_products_info->fetchAll(PDO::FETCH_ASSOC);


//usersテーブルからメールアドレスのデータを呼び出す
$get_users_info = 'SELECT * FROM users WHERE email = :email';
//取得実行を変数に代入
$stmt = $db->prepare($get_users_info);
//入力されたemailを変数に代入する
$input_email=$_SESSION['email'];
//bindを使用し、:emailの部分に変数$input_emailに代入されている値を当てはめる
$stmt->bindParam(':email',$input_email,PDO::PARAM_STR);
//実行
$stmt->execute();
//取得
$db_result = $stmt->fetch(PDO::FETCH_ASSOC);
/* --------------- db接続 --------------- */

/* --------------- 詳細ボタン押されたら start --------------- */
if(isset($_POST['detail'])){
    $_SESSION["row_id"] = $_POST["row_id"];
    $_SESSION["row_name"] = $_POST["row_name"];
    $_SESSION["row_image"] = $_POST["row_image"];
    $_SESSION["row_introduction"] = $_POST["row_introduction"];
    $_SESSION["row_price"] = $_POST["row_price"];
    header('location: product_detail.php');
}
/* --------------- 詳細ボタン押されたら end --------------- */

/* --------------- ランキングボタン押されたら start --------------- */
if(isset($_POST['ranking'])){

    header('location: ranking.php');
}
/* --------------- ランキングボタン押されたら end --------------- */

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>商品一覧画面</title>
    </head>
    <body>
        <h2>【商品一覧画面】</h2>

        <h3>ユーザー名:<?php echo $db_result['name']." さん";?></h3>

        <!------------------- 新着情報記載START ------------------->
        <div class="new">
        <?php foreach($mysql as $row): ?>
            <?php $end = end($mysql); ?>
            <?php if($row == $end):?>
                <h4><font color="red">新着商品↓</font>
                <form action="" method="post">
                    <input type="submit" class="button02" value="<?php echo $row['name'];?>" name="detail">
                    <!-- <a href="product_detail.php"> <?php //echo $row['name']; ?><a> -->
                    <input type="hidden" name="detail" value="詳細">
                    <input type="hidden" name="row_id" value="<?php echo $row['id']?>">
                    <input type="hidden" name="row_name" value="<?php echo $row['name']; ?>">
                    <input type="hidden" name="row_image" value="<?php echo $row['image']; ?>">
                    <input type="hidden" name="row_introduction" value="<?php echo $row['introduction']; ?>">
                    <input type="hidden" name="row_price" value="<?php echo $row['price']; ?>">
                <style>
                    .button02{
                        display:block;
                        width: 100px;
                        height:30px;
                        color: red;
                        text-decoration:none;
                        text-align: center;
                        background-color:#FDF5E6; /*ボタン色*/
                        border-radius: 30px; /*角丸*/
                        -webkit-transition: all 0.5s;
                        transition: all 0.5s;
                    }
                </style>
                </form>
            <?php endif;?>
        <?php endforeach;?> 
        </div>
        <!------------------- 新着情報記載END ------------------->


        <!------------------- ランキングSTART ------------------->    
        <form action="" method="post">
            <input type="submit" value="売上ランキング" name="ranking">
        </form>
        <!------------------- ランキングEND ------------------->  

        <br>
        <table border='1'>
            <tr><td>ID</td><td>商品名</td><td>商品画像</td><td>紹介文</td><td>価格</td></tr>
            <?php foreach($mysql as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><img src="img/<?php echo $row['image']; ?>" width="100px" height="75px"></td>
                    <td><?php echo $row['introduction']; ?></td>
                    <td><?php echo $row['price']; ?>円</td>
                    <td>
                    <form action="" method="post">
                        <input type="submit" value="詳細" name="detail">
                        <input type="hidden" name="row_id" value="<?php echo $row['id']?>">
                        <input type="hidden" name="row_name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="row_image" value="<?php echo $row['image']; ?>">
                        <input type="hidden" name="row_introduction" value="<?php echo $row['introduction']; ?>">
                        <input type="hidden" name="row_price" value="<?php echo $row['price']; ?>">
                    </form> 
                    </td>
                </tr>
            <?php endforeach;?> 
        </table><br>

        <form action="" method="post">
            <input type="submit" value="ログアウト" name="logout">
            <a href="cart.php"><input type="button" value="カートの中身を確認する"></a>
        </form>

    </body>
</html>