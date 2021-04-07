
<?php
    session_start();

    /* -------- 1を持っていない人はログインへ戻される ------- */
    if($_SESSION['login']!= 1){
        echo "ログインしてください。<br>";
        echo "<a href=","login.php",">ログイン画面へ</a>";
        exit;
    }
    /* ---------------------- end ---------------------- */


    /* ---------------------db接続 start -------------------- */
    //DB呼び出し
    require_once "db.php";
    //dbインスタンス作成
    $db = new Db();
    //db接続実行（関数呼び出している）
    $db->db_access();

    //SESSIONデータを受け取る
    $username=$_SESSION["name"];
    $useraddress=$_SESSION["address"];
    $useremail=$_SESSION["email"];
    $userpassword=$_SESSION["password"];

    //パスワードの暗号化 password_hash 関数を使って、$hash 変数に代入してます。
    $hash = password_hash($userpassword,PASSWORD_DEFAULT);

    //INSERT文を変数に格納
    $sql = "INSERT INTO users(id, name, address, email, password)VALUES(NULL, :username, :useraddress, :useremail, :userpassword)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':useraddress', $useraddress, PDO::PARAM_STR);
    $stmt->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $stmt->bindParam(':userpassword', $hash, PDO::PARAM_STR);
    $stmt->execute();
    /* ---------------------- db接続 end ----------------------- */


    /* ---------- ログイン画面へのボタンが押されたら start --------- */
    if(isset($_POST['login'])){
        unset($_SESSION['login']);
        header('Location:login.php');
    }
    /* ------------ ログイン画面へのボタンが押されたら end ----------- */

?>

<!DOCTYPE html>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>ユーザー完了画面</title>
        <h2>【完了画面】</h2>
    </head>
    <body>
        <p>登録完了しました。</p>
        <form action='' method='post'>
            <input type='submit' name='login' value='ログイン画面へ'>
        </form>
    </body>
</html>