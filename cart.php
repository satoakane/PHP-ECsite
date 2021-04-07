<?php

/* ----------- delete押された時の処理 start ---------- */
//POSTで送られてきたデータを頭のPHPで受け取る
$delete_name = $_POST['delete_name'];

session_start();
//商品名を指定して、sessionから削除する　、カートの中の削除する商品
if($delete_name != '') unset($_SESSION['cart'][$delete_name]);
/* ------------ delete押された時の処理 end ------------ */

$cart = $_SESSION['cart'];
var_dump($_SESSION['cart']);
/* ---------- 合計金額計算 start ---------- */
$total_calc = 0;
foreach($cart as $name => $product){
$total_calc += $product['price'] * $product['quantity'];
}
/* ------------ 合計金額計算 end ------------ */


/* ------ 1を持っていない人はログインへ戻される----- */
if($_SESSION['login']!= 1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
/* ------------------- end ------------------- */

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>カート一覧画面</title>
    </head>
    <body>
        <h1>【カート一覧画面】</h1>

        <?php
        /* ----------- バリデーション start ---------- */
        if(empty($_SESSION['cart'])){
            echo '<font color="red">※商品が入っておりません。</font>';
        }

        //購入ボタンが押されて
        if(isset($_POST['buy'])){
            header('Location:order_confirm.php');
        }
        /* ------------- バリデーション end ----------- */
        ?>

        
        <table border='1'>
            <tr>
                <td>商品名</td>
                <td>画像</td>
                <td>紹介文</td>
                <td>価格</td>
                <td>数量</td>
            </tr>

            <?php foreach($cart as $name => $product):?>
                    <tr>
                        <td><?php echo $name;?></td>
                        <td><img src="img/<?php echo $product['image']; ?>" width="100px" height="75px"></td>
                        <td><?php echo $product['introduction'];?></td>
                        <td><?php echo $product['price'];?>円</td>
                        <td><?php echo $product['quantity'];?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <button type="submit" class="delete">削除</button>
                                <!-- 送り先は自分自身で、methodはPOST -->
                                <input type="hidden" name="delete_name" value="<?php echo $name; ?>">
                            </form>
                        </td>
                    </tr>
            <?php endforeach; ?>
        </table>

        <p>合計金額：<?php echo $total_calc ?>円</p>

        <form action="" method="post">
            <div id ="accept">
                <?php
                if (empty($_SESSION['cart'])){
                    echo '<input type="button" value="Accept" style="visibility:hidden" id="accept">';
                }else{
                    echo '<input type="submit" name="buy" value="購入する">';
                }
                ?>
            </div>
            <!-- <input type="submit" name="buy" value="購入する"> -->
            <a href="product_list.php"><input type="button" value="戻る">
        </form>
    </body>
</html>