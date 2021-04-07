<?php
session_start();
// var_dump($_SESSION);
require_once "db.php";
$db = new Db();

require_once "function.php";
$validation = new Validate();


/* ------------ 1を持っていない人はログインへ戻される---------- */
if($_SESSION['login']!= 1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
/* ------------------------- end ------------------------- */

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


/* ----------- 商品情報取り出し start----------- */
//usersテーブルからメールアドレスのデータを呼び出す
$get_product_info = 'SELECT * FROM products WHERE id = :id';
//取得実行を変数に代入
$st = $db->prepare($get_product_info);
//入力されたemailを変数に代入する
$product_id=$_SESSION['row_id'];
//bindを使用し、:emailの部分に変数$input_emailに代入されている値を当てはめる
$st->bindParam(':id',$product_id,PDO::PARAM_STR);
//実行
$st->execute();
//取得
$db_product_result = $st->fetch(PDO::FETCH_ASSOC);
/* ----------- 商品情報取り出し end ----------- */


/* ------------------登録ボタン押されたらSTART------------------ */
if(isset($_POST['register'])){
    //バリデーションの実行
    $validation->reviewCheck();

    //ニックネームとレビューが空欄でなければ
    if(!empty($_POST["nickname"] and $_POST["comment"])){
        
        //POSTをSESSIONに代入
        $_SESSION['nickname'] = $_POST['nickname'];
        $_SESSION['comment']= $_POST["comment"];

        //変数に代入
        $nickname=$_SESSION['nickname'];
        $comment=$_SESSION['comment'];

        //userテーブルからidを取得し変数に代入
        $user_id = $db_result['id'];
        //productsテーブルからidを取得し変数に代入
        $product_id=$db_product_result['id'];
        
        //INSERT文を変数に格納
        $sql = "INSERT INTO review(id, user_id, product_id, nickname, comment)VALUES(NULL, :user_id, :product_id, :nickname, :comment)";
        $stmt1 = $db->prepare($sql);
        $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt1->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmt1->bindParam(':nickname', $nickname, PDO::PARAM_STR);
        $stmt1->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt1->execute();
        
        header('Location: product_detail.php');
    }
}
/* ------------------登録ボタン押されたら END------------------ */

?>

<!DOCTYPE html>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>商品口コミ登録画面</title>
        <h2>【商品口コミ登録画面】</h2>
    </head>

    <body>
        <form action='' method='post'>     
            <input type="hidden" name="nickname" value="<?php echo $_POST['nickname'];?>">
            <input type="hidden" name="comment" value="<?php echo $_POST['comment']; ?>"           
            
            <p>ニックネーム：<input type="text" name="nickname"></p>
            <font color="red"><?php echo $error['nickname']; ?></font>

            <p>レビュー内容：<P>
            <textarea name="comment" rows="10" cols="40" placeholder="こちらにご記入ください"></textarea>
            <p><font color="red"><?php echo $error['review_comment']; ?></font><br></p>

            <p><input type="submit" name="register" value="登録">
            <a href="product_detail.php"><input type="button" value="戻る"></a></p>
        </form>
    </body>
</html>