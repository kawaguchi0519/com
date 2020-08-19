<?php
    //データベース接続//
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    if(!empty($_POST["editnumber"])){
        $sql = 'SELECT * FROM tbtest WHERE id='.$_POST["editnumber"].' AND password="'.$_POST["editpass"].'"';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        $row = $results[0];
        
    }
       ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>Web掲示板</h1>
    <form action="" method="post">
        <input type="txt" name="name" placeholder="名前"
         value= <?php
                if(!empty($_POST["editnumber"])){
                echo $row['name'];}
        ?>><br>
        <input type="txt" name="comment" placeholder="コメント"
         value= <?php
                if(!empty($_POST["editnumber"])){
                echo $row['comment'];}     
        ?>><br>
        <input type="password" name="pass" placeholder="パスワード" ><br>
        <input type="hidden" name="changenumber" 
         value= <?php
                if(!empty($_POST["editpass"])){
                    echo $_POST["editnumber"];
                }
       ?> >
        <!--編集モード指定時のセキュリティを作成-->
        <input type="submit" name="submit"><br>
        
        <input type="txt" name="deletenumber" placeholder="削除対象番号"><br>
        <input type="password" name="deletepass" placeholder="パスワード" >
        <input type="submit" name="deletesubmit" value="削除"><br><br>
        
        <input type="txt" name="editnumber" placeholder="編集対象番号"><br>
        <input type="password" name="editpass" placeholder="パスワード" >
        <input type="submit" name="editsubmit" value="編集">
    </form>
    <?php

        

        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "password TEXT"
        .");";
        $stmt = $pdo->query($sql);//statement.query≒prepareユーザーの入力情報を含むか否かで区別。
        
     
        
        //編集機能
        if($_POST["submit"]){           
            if(empty($_POST["changenumber"])){    
                if(empty($_POST["name"])){
                    echo "名前を入力してください<br>";
                }elseif(empty($_POST["comment"])){
                    echo "コメントを入力してください<br>";
                }elseif(empty($_POST["pass"])){
                    echo "送信パスワードを入力してください<br>";
                }else{
                    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment,:date, :password)");
                    $sql -> bindParam(':name', $name,PDO::PARAM_STR);
                    //bindParam：割り当てる
                    $sql -> bindParam(':comment', $comment,PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date,PDO::PARAM_STR);
                    $sql -> bindParam(':password', $pass,PDO::PARAM_STR);
	                $name=$_POST["name"];
                    $comment=$_POST["comment"];
                    $date=date("Y/m/d H:i:s");
                    $pass=$_POST["pass"];
	                $sql -> execute();//実行する
                            echo "投稿されました<br>";
                }
            
            }else{
                   
                    
                if(!empty($_POST["pass"])){
                  
                
                      
                        $edit_pass=$_POST["pass"];
                        $id = $_POST["changenumber"]; 
                        $name = $_POST["name"];
                        $comment = $_POST["comment"]; 
                        $date=date("Y/m/d H:i:s");
                        
                       
                        $sql = 'UPDATE tbtest SET name=:name,comment=:comment ,date=:date WHERE id=:id and password=:password';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->bindParam(':password', $edit_pass, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                            
                            
                            echo "編集されました<br>";
                   
                }
            }
        }

        //削除機能
        if($_POST["deletesubmit"]){
            if(empty($_POST["deletenumber"]) && empty($_POST["deletepass"])){
                            echo "削除したい番号を入力してください<br>";
            }
            elseif(!empty($_POST["deletenumber"]) && empty($_POST["deletepass"])){
                    echo "削除パスワードを入力してください<br>";
            }
            elseif(!empty($_POST["deletenumber"]) && !empty($_POST["deletepass"])){
                    
                    $delete=$_POST["deletenumber"];
                    $del_pass=$_POST["deletepass"];
                   
                    $id = $_POST["deletenumber"];
                    $sql = 'delete from tbtest where id=:id and password=:password';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':password', $del_pass, PDO::PARAM_STR);
                    $stmt->execute();
                        echo "削除が実行されました<br>";
                    
                    }
           
        }
        
        if($_POST["editsubmit"]){
            
            if(empty($_POST["editnumber"])){
                echo "編集したい番号を入力してください<br>";
            }elseif(empty($_POST["editpass"])){
                echo "編集パスワードを入力してください<br>";
            }elseif(!empty($_POST["editnumber"]) && !empty($_POST["editpass"])){
                $edit=$_POST["editnumber"];
               //パスワードあってるときはhtmlの編集モードスイッチに指定番号を表示
                    
               
            }
        }
        

        //ブラウザへの表示機能
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        echo "<hr>";
        }
        
    ?>
</body>
</html>
