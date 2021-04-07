
<?php

session_start();
/*-------------- db接続START --------------*/
require_once "db.php";
$db = new Db();
/*--------------- db接続END ---------------*/


/* ------------ 1を持っていない人はログインへ戻される---------- */
if($_SESSION['login']!= 1){
    echo "ログインしてください。<br>";
    echo "<a href=","login.php",">ログイン画面へ</a>";
    exit;
}
/* ------------------------- end ------------------------- */


/* ----------- レビュー情報取り出し start----------- */
//db接続実行
$db->db_access();
$get_review_info = $db->prepare("SELECT * FROM review");
$get_review_info->execute();
$db_review_result = $get_review_info->fetchAll(PDO::FETCH_ASSOC);
/* ----------- レビュー情報取り出し end----------- */


/* ----------- ユーザー情報取り出し start----------- */
$get_users_info = 'SELECT * FROM users WHERE email = :email';
$stmt = $db->prepare($get_users_info);
$input_email=$_SESSION['email'];
$stmt->bindParam(':email',$input_email,PDO::PARAM_STR);
$stmt->execute();
$db_result = $stmt->fetch(PDO::FETCH_ASSOC);
/* ----------- ユーザー情報取り出し end----------- */


/* ----------- 商品情報取り出し start----------- */
$get_product_info = 'SELECT * FROM products WHERE id = :id';
$st = $db->prepare($get_product_info);
$product_id=$_SESSION['row_id'];
$st->bindParam(':id',$product_id,PDO::PARAM_STR);
$st->execute();
$db_product_result = $st->fetch(PDO::FETCH_ASSOC);
/* ----------- 商品情報取り出し end ----------- */


/* ------------- カートに追加するが押された時 start ------------ */
if(isset($_POST['addCart'])){
    //数量が空欄でなければ
    if(!empty($_POST['quantity'])){
        
        //変数に代入
        $id = $db_product_result['id'];
        $name = $db_product_result['name'];
        $image = $db_product_result['image'];
        $introduction = $db_product_result['introduction'];
        $price = $db_product_result['price'];
        $quantity = $_POST['quantity'];

        //カートに配列としてデータを格納
        $_SESSION['cart'][$name]=[
                        'id' => $id,
                        'image' => $image,
                        'introduction' => $introduction,
                        'price' => $price,
                        'quantity' => $quantity];
        header('Location: product_list.php');
    }
}
/* --------------- カートに追加するが押された時 end -------------- */


/* --------- 最終課題/レビュー投稿ボタン押された時 start ---------- */
if(isset($_POST['review'])){
    header('Location: review.php');
}
/* ----------- 最終課題/レビュー投稿ボタン押された時 end ---------- */


/* ----------- delete押された時の処理 start ---------- */
if(isset($_POST['delete'])){
    $sql = "DELETE FROM review WHERE id = :delete_id";
    $stmt2 = $db->prepare($sql);
    $stmt2->bindParam(':delete_id', $_POST['review_id']);
    $stmt2->execute();
    header('Location: product_detail.php');
}
/* ------------ delete押された時の処理 end ------------ */


/* ----------- いいね数表示 start ---------- */
    //userテーブルからidを取得し変数に代入
    $user_id = $db_result['id'];
    //productsテーブルからidを取得し変数に代入
    $product_id=$_SESSION['row_id'];
    // $product_id=$db_product_result['id'];
    $sql1 = 'SELECT * FROM good WHERE product_id = :product_id'; 
    $stmt3 = $db->prepare($sql1);
    $stmt3->bindParam(':product_id',$product_id ,PDO::PARAM_STR);
    $stmt3->execute();
    $db_good_result = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    $count_good=count($db_good_result);
/* ------------ いいね数表示 end ------------ */
    

/* ----------- いいね押された時の処理 start ---------- */

if(isset($_POST['good'])){
    
        //goodテーブルからuser_idを呼び出す
        $sql1 = 'SELECT * FROM good WHERE product_id = :product_id AND user_id = :login_user_id'; 
        $stmt3 = $db->prepare($sql1);
        $stmt3->bindParam(':product_id',$product_id ,PDO::PARAM_STR);
        $stmt3->bindParam(':login_user_id',$user_id ,PDO::PARAM_STR);
        $stmt3->execute();
        $db_good_result = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    // var_dump($db_good_result);
    //ユーザーと商品が一致しなかったら

        if(!empty($db_good_result)){
        // echo"一度押されています";exit;
        $sql3 = "DELETE FROM good WHERE id = :login_user_id";
        $stmt5 = $db->prepare($sql3);
        $stmt5->bindParam(':login_user_id', $_POST['good']);
        $stmt5->execute();
        header('Location: product_detail.php');
    }else{
        // echo"いいね";exit;
        $sql2 = "INSERT INTO good(id, user_id, product_id)VALUES(NULL, :user_id, :product_id)";
        $stmt4 = $db->prepare($sql2);
        $stmt4->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt4->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmt4->execute();
        header('Location: product_detail.php');
    }
    // }
}
/* ------------ good押された時の処理 end ------------ */

?>

<!DOCTYPE html>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>商品詳細画面</title>
        <h2>【商品詳細画面】</h2>
    </head>
    
    <body>
        <form action='' method='post'>
            <p>商品名：<?php echo $db_product_result['name']; ?></p>
            <p>商品画像：<br><img src="img/<?php echo $db_product_result['image'];?>" width="280px" height="250px">
            <p>紹介文：<?php echo $db_product_result['introduction']; ?></p>
            <p>価格：<?php echo $db_product_result['price']; ?>円</p>

            <p>追加する数量：<input type="text" name="quantity">個
            <p><font color="red"><?php if(empty($_POST["quantity"]) and isset($_POST["quantity"])){echo "数量が入力されていません。";} ?></font></p>
            <br>
            <input type="submit" name="addCart" value="カートに追加する">
            <INPUT type="button" onclick="location.href='product_list.php'" value="商品一覧へ戻る">
        </form>
        <br><br>

        <!---------------------- いいね機能START ---------------------->
        <form action="" method="post">
            <input type="hidden" name="good" value="<?php echo $_POST["good"];?>">
            <?php ?>
            <input type="submit" class="button02" name="good" value="いいね♡<?php echo $count_good;?>">
            
            <style>
                .button02{
                    display:block;
                    width: 100px;
                    height:40px;
                    color: black;
                    text-decoration:none;
                    text-align: center;
                    background-color: #FFDDFF; /*ボタン色*/
                    border-radius: 30px; /*角丸*/
                    -webkit-transition: all 0.5s;
                    transition: all 0.5s;
                }
            </style>


        </form>
        <!----------------------- いいね機能END ----------------------->


        <!----------------------- 口コミ★レビューSTART ----------------------->
        <form action='' method='post'>
                <h3>口コミ★レビュー</h3>
                <input type="submit" name="review" value="口コミ投稿する"><br>
        </form>
        <!-- レビュー表示 -->
        <?php foreach($db_review_result as $review):?>
            <!-- 商品毎にレビュー表示 -->
            <?php if($db_product_result['id'] === $review['product_id']){?>
                <form action="" method="post">
                    <tr>
                        ＊--------------------------------------------<br>
                        <?php echo "ニックネーム：".$review['nickname'];?><br>
                        <?php echo "口コミ：".$review['comment'];?><br>
                        <?php 
                        //ユーザーとレビュー投稿したユーザーが一致した場合削除ボタンを表示させる
                        if($db_result['id'] !== $review['user_id']){  
                            echo '<input type="button" value="Accept" style="visibility:hidden" id="accept">';
                        }else{
                            echo '<input type="submit" name="delete" value="削除">';?> 
                            <input type="hidden" name="review_id" value="<?php echo $review['id']?>">
                            <input type="hidden" name="review_user" value="<?php echo $review['user_id']; ?>">
                            <input type="hidden" name="review_product" value="<?php echo $review['product_id']; ?>">
                            <input type="hidden" name="review_nickname" value="<?php echo $review['nickname']; ?>">
                            <input type="hidden" name="review_comment" value="<?php echo $review['comment']; ?>">
                        <?php }?>              
                        <br>--------------------------------------------＊<br>
                    </tr>
                </form>
            <?php }?>
        <?php endforeach; ?>
        <!----------------------- 口コミ★レビューEND ----------------------->
    </body>
</html>