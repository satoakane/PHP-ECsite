<?php

//クラス化
class Validate{

    //関数
    function checkData(){
        //メールとパスワードが空欄たっだ時エラー文を出す

        if(empty($_POST['email'])){
            global $error;
            $error['email']= "メールアドレスを記入してください<br>";
        }

        if(empty($_POST['password'])){
            $error['password']= "パスワードを記入してください<br>";
        
        }
    // var_dump($error);exit;
        return $error;   
    }


    //関数
    function v(){
        //メールとパスワードが空欄たっだ時エラー文を出す

        if(empty($_POST['name'])){
            global $error;
            $error['name']= "氏名を記入してください<br>";
        }

        if(empty($_POST['address'])){
            global $error;
            $error['address']= "住所を記入してください<br>";
        }

        if(empty($_POST['email'])){
            global $error;
            $error['email']= "メールアドレスを記入してください<br>";
        }

        if(empty($_POST['password'])){
            $error['password']= "パスワードを記入してください<br>";
        
        }
    // var_dump($error);exit;
        return $error;   
    }

        //関数
    function reviewCheck(){
        //メールとパスワードが空欄たっだ時エラー文を出す

        if(empty($_POST['nickname'])){
            global $error;
            $error['nickname']= "ニックネームを記入してください<br>";
        }

        if(empty($_POST['review_comment'])){
            global $error;
            $error['review_comment']= "レビュー内容を記入してください<br>";
        
        }
    // var_dump($error);exit;
        return $error;   
    }
}

?>