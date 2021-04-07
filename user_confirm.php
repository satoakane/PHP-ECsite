
<?php
session_start();
//前ページでログイン条件クリアしたら、1を持ってlist.phpに飛ぶよう指示
//1を保持している人は一覧に移動できるが、1を持っていない人はログインへ戻される
if($_SESSION['login']!=1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
?>

<!DOCTYPE html>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>ユーザー確認画面</title>
        <h2>【確認画面】</h2>
    </head>
    
    <body>
        <p>氏名：<?php echo $_SESSION['name']; ?></p>
        <p>住所：<?php echo $_SESSION['address']; ?></p>
        <p>email：<?php echo $_SESSION['email']; ?></p>
        <p>パスワード：<?php echo $_SESSION['password']; ?></p>

        <form action='user_complete.php' method='post'>
            <button type="button" onclick=history.back()>戻る</button>
            <input type='submit' name='submit' value='送信'>
        </form>

    </body>
</html>