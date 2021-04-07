<?php
session_start();

/* ------ 1を持っていない人はログインへ戻される----- */
if($_SESSION['login']!= 1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
/* ------------------- end ------------------- */


unset($_SESSION['cart']);

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>購入完了画面</title>
    </head>
    <body>
        <h1>【購入完了画面】</h1>
        <p>購入完了しました。</p>
        <p>ご購入ありがとうございました。</p>
        <a href="product_list.php"><input type="button" value="商品一覧画面に戻る">
    </body>
</html>