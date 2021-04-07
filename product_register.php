<?php
session_start();

// $dir = ‘img’;
// mkdir($dir, 0777); // ←値は何でも良い
// chmod($dir, 0777);

//送信が押されたら
if(isset($_POST['submit'])){
    
    $tempfile = $_FILES['product_image']['tmp_name'];//フルパス
    
    //アップロード画像の移動先
    $filemove = '/Applications/MAMP/htdocs/ec_site/img/'.$_FILES['product_image']['name'];
    
    //move_uploaded_file関数を使って、アップロードした画像を指定した場所に移動させる
    move_uploaded_file($tempfile , $filemove );
    
    //もし全ての項目が空欄でなければ
    if(!empty($_POST["product_name"]) and !empty($_FILES["product_image"]['name']) and !empty($_POST["introduction"]) and !empty($_POST["product_price"])){
        $_SESSION["product_name"] = $_POST["product_name"];
        $_SESSION["product_image"] = $_FILES["product_image"]['name'];
        $_SESSION["introduction"] = $_POST["introduction"];
        $_SESSION["product_price"] = $_POST["product_price"];
        
        $_SESSION['login']=1;
        header('Location:product_confirm.php');
    }
}

?>

<!doctype HTML>
    <head>
        <html lang="ja">
        <meta charset="utf-8">
        <title>商品登録画面</title>
        <h2>【商品登録画面】</h2>
    </head>
    
    <body>
        <form action="" method='post' enctype="multipart/form-data">
            商品名：<input type="text" name="product_name" value="<?php if(!empty($_POST["product_name"])){echo $_POST["product_name"];} ?>">
            <?php if(empty($_POST["product_name"]) and isset($_POST["product_name"])){echo "商品名が入力されていません。";} ?><br>
            <br>

            商品画像：<input type="file" name="product_image">
            <?php if(empty($_POST["product_image"]) and isset($_POST["product_image"])){echo "画像が選択されていません。";} ?><br>
            <br>

            紹介文：<br><textarea rows="5" cols="45" name="introduction" ><?php if(!empty($_POST["introduction"])){echo $_POST["introduction"];} ?></textarea>
            <?php if(empty($_POST["introduction"]) and isset($_POST["introduction"])){echo "紹介文が入力されていません。";} ?><br>
            <br>

            価格：<input type="text" name="product_price" value="<?php if(!empty($_POST["product_price"])){echo $_POST["product_price"];} ?>">円
            <?php if(empty($_POST["product_price"]) and isset($_POST["product_price"])){echo "価格が入力されていません。";} ?><br>
            <br>
            <input type='submit' name='submit' value='送信'>
        </form>
    </body>
</html>