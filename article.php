<?php
include 'connection.php';
$articleid = $_GET['articleid'];
if (empty($articleid) || !is_numeric($articleid)){
	header ("Location: index.php");
}
/// insert comment here!!!

$ip = $_SERVER['REMOTE_ADDR'];


$submit = $_POST['submit'];
if ($submit == 'Make Comment'){
  $username = $_POST['username'];
  $comment = $_POST['comment'];
  //Check for the ip before insert

$checksql = $dbh->prepare("select COUNT(rec_ipaddress.ipaddress) as numip  from rec_ipaddress,rec_comments 
where rec_ipaddress.ipaddress = ? and rec_ipaddress.commentid = rec_comments.id 
and rec_comments.articleid = ?");
$checksql->bindValue(1,$ip,PDO::PARAM_STR);
$checksql->bindValue(2,$articleid,PDO::PARAM_INT);
$checksql->execute();
$checkrow= $checksql->fetch();
$alreadyposted = $checkrow['numip'];

if ($alreadyposted < 1){
  //insert into users first (username)//done
  //insert into comments second userid,comment,recipeid
  //insert into ipaddress userid, commentid, ipaddress
 $userinsql =  $dbh->prepare("insert into rec_users (username) values (?)");
 $userinsql->bindValue(1,$username,PDO::PARAM_STR);
 $userinsql->execute();
 $userid = $dbh->lastInsertId();


 //now insert into comments table
 $commentinsql = $dbh->prepare("insert into rec_comments (userid,comment,articleid) values (?,?,?)");
 $commentinsql->bindValue(1,$userid,PDO::PARAM_INT);
 $commentinsql->bindValue(2,$comment,PDO::PARAM_STR);
 $commentinsql->bindValue(3,$articleid,PDO::PARAM_INT);
 $commentinsql->execute();
 $lastcommentid = $dbh->lastInsertId();
 //now insert into ipaddress
 $ipinsql = $dbh->prepare("insert into rec_ipaddress (userid,commentid,ipaddress) values (?,?,?)");
 $ipinsql->bindValue(1,$userid,PDO::PARAM_INT);
 $ipinsql->bindValue(2,$lastcommentid,PDO::PARAM_INT);
 $ipinsql->bindValue(3,$ip,PDO::PARAM_STR);
 $ipinsql->execute();
 echo '<br>Comment post successfully<br>';
}
else {
	echo '<br>You have already commented on this recipe from your ip address</br>';
}



}



$sql = $dbh->prepare("select * from rec_articles where id = ?");
$sql->bindValue(1,$articleid,PDO::PARAM_INT);
$sql->execute();
while ($row = $sql->fetch()){
	$id = $row[0];
	$title = $row[1];
	$recipe = $row[2];
	echo '<h1>'.$title.'</h1>';
	echo '<img src ="images/'.$id.'.jpg" alt="'.$title.'" height="400"/><br>'; 
	echo $recipe.' <br><button onclick="window.print();">Print</button><br><br><br>';
        echo '<a href="index.php">Go Back to Home Page</a>';
	echo '<h3>Comments</h3>';
	$innersql = $dbh->prepare("select rec_users.username,rec_comments.comment
		from rec_users,rec_comments
		where rec_comments.userid = rec_users.id 
		and rec_comments.articleid = ? order by rec_comments.id desc");
	$innersql->bindValue(1,$articleid,PDO::PARAM_INT);
	$innersql->execute();
	while ($innerrow = $innersql->fetch()){
		$username = $innerrow[0];
		$comment = $innerrow[1];
		echo '<p>'.$comment.'</p>';
		echo '<p><strong>'.$username.'</strong>';
	}
     //restrict by ip here
    ?>
    <form action = "article.php?<?php echo $_SERVER['QUERY_STRING'];?>" method="post">
    Username: <input type="text" name="username"><br>
    Comment: <textarea name="comment"></textarea>
    <input type="submit" name="submit" value="Make Comment"><br>
    </form>
    <?
  

}
















?>