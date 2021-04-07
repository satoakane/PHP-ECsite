<?php
session_start();

//DB呼び出し
require_once "db.php";
//DBのインスタンス作成
$db = new Db();

//$_SESSION['cart']でカート情報を取得　（空の場合も配列で返す）
// $cart = isset($_SESSION['cart'])? $_SESSION['cart']:[];
$cart = $_SESSION['cart'];
// var_dump($cart);exit;

/* ------ 1を持っていない人はログインへ戻される----- */
if($_SESSION['login']!= 1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
/* ------------------- end ------------------- */


/* ----------- 合計金額計算 start----------- */
$total_calc = 0;
foreach($cart as $name => $product){
$total_calc += $product['price'] * $product['quantity'];
}
/* ------------- 合計金額計算 end------------- */


/* ----------- ユーザー情報取り出し start----------- */
//db接続実行（関数呼び出している）
$db->db_access();
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
/* ----------- ユーザー情報取り出し end ----------- */


/* ----------- 購入ボタンが押された時の処理 start----------- */
if(isset($_POST['buy'])){

    if(!empty($_POST["address"])){

        $user_id = $db_result['id'];
        $order_total = $total_calc;
        $order_address = $_POST['address'];

        try{
            // トランザクション開始
            $db->beginTransaction();
        
            /* --------- ordersテーブルの処理 start --------- */
            $order_sql = "INSERT INTO orders(order_id, user_id, total, address)VALUES(NULL, :user_id, :order_total, :order_address)";
            $order_stmt = $db->prepare($order_sql);
            $order_stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $order_stmt->bindParam(':order_total', $order_total, PDO::PARAM_STR);
            $order_stmt->bindParam(':order_address', $order_address, PDO::PARAM_STR);
            $order_stmt->execute();
            /* --------- ordersテーブルの処理 end --------- */
            

            /* ----- order_detailテーブルの処理 start ----- */
            //ordersテーブルのid取得（最後に挿入された行のIDの値を返す）
            $order_id = $db->lastInsertId();
        
            foreach($cart as $name => $product){
                $quantity = $product['quantity'];
                $product_id = $product['id'];

                $order_detail_sql = "INSERT INTO order_detail(order_detail_id, order_id, product_id, quantity)VALUES(NULL, :order_id, :product_id, :quantity)";
                $order_detail_stmt = $db->prepare($order_detail_sql);
                // var_dump($order_detail_stmt);exit;

                $order_detail_stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
                $order_detail_stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
                $order_detail_stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
                $order_detail_stmt->execute();
            }
            /* ----- order_detailテーブルの処理 end ----- */

            // コミット(処理成功)
            $db->commit();
        }catch(PDOException $e){
            // ロールバック(処理失敗:一連の処理を取り消す)
            $db->rollBack();
            echo "失敗:" .$e->getMessage()."\n";
            exit;
        }
    }
        
        header('Location:mail_test.php');
}
/* ----------- 購入ボタンが押された時の処理 end ----------- */



?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>注文確認画面</title>
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    </head>

    <body>
        <h1>【注文確認画面】</h1>
        <table border='1'>
            <tr>
                <td>商品名</td>
                <td>画像</td>
                <td>紹介文</td>
                <td>価格</td>
                <td>数量</td>
                <td>計</td>
            </tr>
            <?php foreach($cart as $name => $product):  ?>
                <tr>
                    <td><?php echo $name;?></td>
                    <td><img src="img/<?php echo $product['image']; ?>" width="100px" height="75px"></td>
                    <td><?php echo $product['introduction'];?></td>
                    <td><?php echo $product['price'];?>円</td>
                    <td><?php echo $product['quantity'];?></td>
                    <td><?php echo $product['price'] * $product['quantity'];?>円</td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p>カート全体の合計金額：<?php echo $total_calc ?>円</p>
        
        <p>【住所（配送先）】
        <form action="" method="post">
        
            <!-- ▼郵便番号入力フィールド(7桁) -->
            郵便番号(7桁):<br>
            〒<input type="text" name="zip11" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','address','address');"><br>
            <!-- ▼住所入力フィールド(都道府県+以降の住所) -->
            都道府県+以降の住所:<br>
            <input type="text" name="address" size="60">

            <p>支払方法：銀行振込</p>
        
            <input type="submit" value="購入する" name="buy">
            <a href="cart.php"><input type="button" value="戻る">
        </form>

    </body>
</html>