
    <?php
        session_start();

    /* ------ 1を持っていない人は登録画面へ戻される start----- */
    if($_SESSION['login']!= 1){
        echo "商品登録画面からご登録ください。<br>";
        echo "<a href=","product_register.php",">商品登録画面へ</a>";
        exit;
    }
    /* ---------------------- end ---------------------- */


    /* ------------------- DB接続 start ------------------ */
        //DB呼び出し
        require_once "db.php";
        //DBのインスタンス作成
        $db = new Db();
        //db接続実行（関数呼び出している）
        $db->db_access();
        
            //SESSIONデータを受け取る
            $productname=$_SESSION["product_name"];
            $productimage=$_SESSION["product_image"];
            $productintro=$_SESSION["introduction"];
            $productprice=$_SESSION["product_price"];
        
            //プレイスホルダーを設置                                                    プレイスホルダー＝空箱
            $sql = "INSERT INTO products(id, name, image, introduction, price)VALUES(NULL, :productname, :productimage, :productintro, :productprice)";
            $stmt = $db->prepare($sql);
            //プレイスホルダーに値を代入する
            $stmt->bindParam(':productname', $productname, PDO::PARAM_STR);
            $stmt->bindParam(':productimage', $productimage, PDO::PARAM_STR);
            $stmt->bindParam(':productintro', $productintro, PDO::PARAM_STR);
            $stmt->bindParam(':productprice', $productprice, PDO::PARAM_STR);
            $stmt->execute();
    /* -------------------- DB接続 end -------------------- */
    
            unset($_SESSION['login']);
    ?>

<!DOCTYPE html>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>商品登録完了画面</title>
        
    </head>
    <body>
        <h2>【商品登録完了画面】</h2>
        <form action='product_register.php' method='post'>
            <p>登録完了しました。</p>
            <br>
            <input type='submit' name='submit' value='登録画面に戻る'>
        </form>
    </body>
</html>