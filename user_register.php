<?php
session_start();
//バリデーションの関数化・クラス
require_once "function.php";
//バリデーション
$validation = new Validate();

if(isset($_POST['submit'])){

    //バリデーションの実行
    $validation->v();

    if(!empty($_POST["name"] and $_POST["address"] and $_POST["email"] and $_POST["password"])){
        $_SESSION=$_POST;
        $_SESSION['login']=1;
        header('Location:user_confirm.php');
    }
}
?>

<!doctype HTML>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>ユーザー登録画面</title>
        <h2>【登録画面】</h2>
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    </head>
    
    <body>
        <form action="" method='post'>
            <p>氏名：<input type="text" name="name"></p>
            <font color="red"><?php echo $error['name']; ?></font>

            <p>住所:<br>            
            <!-- ▼郵便番号入力フィールド(7桁) -->
            〒<input type="text" name="zip11" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','address','address');"><br>
            <!-- ▼住所入力フィールド(都道府県+以降の住所) -->
            都道府県+以降の住所:
            <input type="text" name="address" size="60">
            <font color="red"><?php echo $error['address']; ?></font></p>

            <p>Email：<input type="text" name="email"></p>
            <font color="red"><?php echo $error['email']; ?></font>

            パスワード：<input type="text" name="password">
            <font color="red"><?php echo $error['password']; ?></font><br>
            <br>
            <input type='submit' name='submit' value='登録'>
            <a href="login.php"><input type="button" value="ログイン画面">
        </form>
    </body>
</html>