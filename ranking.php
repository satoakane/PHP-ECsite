<?php
session_start();
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



/* --------------- db接続START --------------- */
$db->db_access();
/*参考になったHP 
https://kisagai.com/2012/06/30/mysql%E3%81%A7%E8%A4%87%E6%95%B0%E3%81%AE%E3%83%86%E3%83%BC%E3%83%96%E3%83%AB%E3%82%92%E9%9B%86%E8%A8%88%E3%81%97%E3%81%A4%E3%81%A4%E7%B5%90%E5%90%88%E3%81%99%E3%82%8B%E6%96%B9%E6%B3%95/ */

// order_detailとproductsからデータを取得
$get_products_info = $db->prepare("SELECT * FROM products LEFT JOIN (SELECT product_id,sum(quantity) as products_num FROM order_detail group by product_id) as num on id=product_id  order by products_num desc");
$get_products_info->execute();
$mysql = $get_products_info->fetchAll(PDO::FETCH_ASSOC);
/* --------------- db接続END --------------- */

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

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>売上ランキング</title>
    </head>
    <body>
        <h2>【売上ランキング】</h2>
        <table border='1'>
            <tr><td>順位</td><td>商品名</td><td>商品画像</td><td>紹介文</td><td>価格</td></tr>
            
            <?php $n=0; foreach($mysql as $row):?>
                <tr>
                    <td><?php $n++; echo $n; ?></td> <!-- 順位表示 -->
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
    </body>
    <a href="product_list.php"><input type="button" value="戻る">
</html>