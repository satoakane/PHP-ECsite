
<?php
session_start();

/* ------ 1を持っていない人は登録画面へ戻される----- */
if($_SESSION['login']!= 1){
    echo "商品登録画面からご登録ください。<br>";
    echo "<a href=","product_register.php",">商品登録画面へ</a>";
    exit;
}
/* ------------------- end ------------------- */

?>

<!DOCTYPE html>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>商品確認画面</title>
        <h2>【商品確認画面】</h2>
    </head>
    
    <body>
        <p>商品名：<?php echo $_SESSION['product_name']; ?></p>
        <p>商品画像：<br>
        <img src="img/<?php echo $_SESSION['product_image'];?>" width="150px" height="200px"><p>
        <p>紹介文：<?php echo $_SESSION['introduction']; ?></p>
        <p>価格：<?php echo $_SESSION['product_price']; ?>円</p>

        <form action='product_complete.php' method='post'>
            <button type="button" onclick=history.back()>戻る</button>
            <input type='submit' name='submit' value='送信'>
        </form>

    </body>
</html>