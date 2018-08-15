<?php
try {
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード';
    $pdo=new PDO($dsn,$user,$password,array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION//エラー投げる
        ));
    
//テーブルを作成する。
    $sql_ct="CREATE TABLE IF NOT EXISTS tbmission4_vp"
        ." ("
        . "id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,"
        . "PRIMARY KEY(id),"
        . "name char(32),"
        . "comment TEXT,"
        . "time TEXT,"
        . "pass TEXT"
        .");";
    $stmt=$pdo->query($sql_ct);
//テーブル作成確認
    $sql_st='SHOW TABLES';
    $result=$pdo->query($sql_st);
    foreach($result as $row){
        echo $row[0];
        echo '<br>';
    }
unset($row);
    echo '<hr>';
//SQLコマンド確認
    $sql_c='SHOW CREATE TABLE tbmission4_vp';
    $result=$pdo->query($sql_c);
    foreach($result as $row){
        print_r($row);
    }
    unset($row);
echo "<hr>";
var_dump($result);
echo "<hr>";

//投稿時間の取得
$time = date("Y/n/j Ah:i");	
$name=$_POST['name'];
$comment=$_POST['comment'];
$password=$_POST['password'];
//テーブルへの入力
if(!empty($comment)){

$sql_it=$pdo->prepare("INSERT INTO tbmission4_vp (name,comment,time,pass) VALUES(:name,:comment,:time,:pass)");
//PDOインスタンスのprepareメソッドが入る。
//パラメータとして:nameと:commentを準備
$sql_it->bindParam(':name', $name, PDO::PARAM_STR);
$sql_it->bindParam(':comment', $comment, PDO::PARAM_STR);

$sql_it->bindParam(':time', $time, PDO::PARAM_STR);
$sql_it->bindParam(':pass', $password, PDO::PARAM_STR);
$sql_it->execute();
}

//削除機能の実装
$delete=$_POST['delete'];
$pass_d=$_POST['pass_del'];
$dnum=false;
if(!empty($delete)&&(!empty($pass_d))){
    $sql_d='SELECT*FROM tbmission4_vp';
    $result=$pdo->query($sql_d);
    foreach ($result as $row){
        echo $row['id']."<br>";
        echo $delete."<br>";
                    if($row[0]==$delete){
                        echo "68Check";
                        if($pass_d==$row['pass']){
                            //echo "70Check";
                           $dnum=true;
                        }
                    }
                }
    unset($row);
    
    if($dnum){
    $sql_del="DELETE FROM tbmission4_vp WHERE id=$delete";
    $result=$pdo->query($sql_del);
    }else{
        echo "パスワードが違います"."<br>";
    }
}    

//編集機能の実装
$editnum=$_POST['editnum'];
$fe_name=$_POST['fe_name'];
$fe_comment=$_POST['fe_comment'];
$pass_c=$_POST['pass_ch'];
$editnum=$_POST['editnum'];
$cnum=false;
if(!empty($editnum)&&!empty($pass_c)){
    $sql_c='SELECT*FROM tbmission4_vp';
    $result=$pdo->query($sql_c);
    foreach ($result as $row){
        //echo $row['id']."<br>";
        //echo $delete."<br>";
                    if($row[0]==$editnum){
                        echo "68Check";
                        if($pass_c==$row['pass']){
                            echo "70Check";
                           $cnum=true;
                        }
                    }
                }
    if($cnum){
    $sql_edit1="UPDATE tbmission4_vp set name='$fe_name',comment='$fe_comment' where id=$editnum";
    $result1=$pdo->query($sql_edit1);
    }else{
        echo "パスワードが違います"."<br>";
    }
}
//出力
$sql_disp='SELECT*FROM tbmission4_vp ORDER BY id';
$results=$pdo->query($sql_disp);
foreach($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['time'].',';
    echo $row['pass'].'<br>';
}
unset($row);

    
}catch(PDOException $e){
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}
?>
<html lang="ja">
	<meta charset="utf-8"/>
	<head>
		<title>入力フォーム</title>
	</head>
		<body>
			<form action="mission_4-1tech.php" method="POST">
				<p><input type="text" name="name"  placeholder="名前"></p>
				<p><input type="comment" name="comment"  placeholder="コメント"></p>
                <p><input type="text" name="password" value="パスワード"></p>
				<p><input type="submit" value="送信する"></p>
			</form>
            
            <form action="mission_4-1tech.php" method="POST">
                <p><input type="text" name="editnum" placeholder="編集番号"></p>
                
                <p><input type="text" name="fe_name" placeholder="名前"></p>
                
                <p><input type="text" name="fe_comment" placeholder="コメント"></p>
                
                <p><input type="text" name="pass_ch" placeholder="パスワード"></p>
				<p><input type="submit" value="送信する"></p>
			</form>

			<form action="mission_4-1tech.php" method="POST">
				<p><input type="text" name="delete" placeholder="削除番号"></p>
                <p><input type="text" name="pass_del" value="パスワード"></p>
    	 		<p><input type="submit" name="del" value="削除"></p>
			</form>

		</body>
</html>
