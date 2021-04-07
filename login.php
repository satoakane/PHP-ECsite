<?php
session_start();

//バリデーションの呼び出し
require_once "function.php";
//DBの呼び出し
require_once "db.php";
//バリデーションのインスタンス作成
$validation = new Validate();
//DBのインスタンス作成
$db = new Db();

/* ------------- ログインボタンが押された時 start ------------ */

    if(isset($_POST['submit'])){

        //バリデーションの実行
        $validation->checkData();
        
        //メールとパスワードが空欄でなければ
        if(!empty($_POST['email'] && $_POST['password'])){
            $_SESSION = $_POST;

            //db接続実行（関数呼び出している）
            $db->db_access();
            
            //データベースからメールアドレスのデータを呼び出す
            $sql = 'SELECT * FROM users WHERE email = :email'; //設置されたパラメーター
            $stmt = $db->prepare($sql);
            
            //入力されたemailを変数に代入する
            $input_email=$_SESSION['email'];

            //bindを使用し、:emailの部分に変数$input_emailに代入されている値を当てはめる
            $stmt->bindParam(':email',$input_email,PDO::PARAM_STR);

            //実行
            $stmt->execute();

            //取得
            $db_result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //メールアドレスが一致していたら
            if($db_result['email'] == $input_email){
                
                //暗号化されたパスワードの照合をする //password_verify(パスワード,ハッシュ値)
                //ハッシュ化したパスワードは長いので、DBのデータ型（文字数）に気を付ける
                if(password_verify($_SESSION['password'], $db_result['password'])){
                    $_SESSION['login']=1;

                    //一致したら一覧画面に飛ぶ
                    header('Location:product_list.php');
                }else{
                    //一致しない場合、エラー文を出す
                    echo "認証できませんでした。";
                }
            }else{
                //メールアドレスが一致しなければ、falseを返す
                $email_result = "再度ご入力下さい";
                echo $email_result;
            }
        } 
    }
/* --------------- ログインボタンが押された時 end -------------- */
?>

    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="utf-8">
            <title>ログイン画面</title>
        </head>
        <body>
            <h1>【ログイン画面】</h1>
            <form action="" method='post'>
                Email：<input type="text" name="email">
                    <font color="red"><?php echo $error['email']; ?></font><br>
                Password：<input type="text" name="password">
                    <font color="red"><?php echo $error['password']; ?></font><br><br> 
                <input type="submit" name="submit" value="ログイン">
                <a href="user_register.php"><input type="button" value="登録画面">
            </form>
        </body>
    </html>