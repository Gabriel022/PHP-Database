<?php
include 'connection.php';
//showing some data and how this will work
echo '<div style="float:right">';
include 'search.php';
echo '</div>';
$sql = $dbh->prepare("select * from rec_articles");
$sql->execute();
while ($row = $sql->fetch()){
	$id = $row[0];
	$title = $row[1];
	$article = $row[2];

	$sentencearray = explode(".",$article);
	for ($i=0;$i<3;$i++){
		$articleshort .= $sentencearray[$i].'. ';
	}

	$articleshort .= ' <a href="article.php?articleid='.$id.'">Read More</a>';

	echo '<h3>'.$title.'</h3>';
	echo '<img src ="images/'.$id.'.jpg" alt="'.$title.'" height="100"/><br>'; 
	echo $articleshort.'<br><br><br>';
	unset($articleshort);
}

?>
<a href="admin/index.php">Go To Admin page</a>