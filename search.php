<?php
include 'connection.php';
$qry = $_POST['qry'];
if (isset($qry)){
	$searchsql =  $dbh->prepare("select count(id) as numresults from rec_articles where articletitle like ? or articledescription like ?");
	$term = "%$qry%";
	$searchsql->bindValue(1,$term,PDO::PARAM_STR);
	$searchsql->bindValue(2,$term,PDO::PARAM_STR);
    $searchsql->execute();
  	$searchrow = $searchsql->fetch();
     echo 'Your search for <strong>'.$qry.'</strong> generated ('.$searchrow['numresults'].') results.<br>';


     $displaysql =  $dbh->prepare("select * from rec_articles where articletitle like ? or articledescription like ?");
	$displaysql->bindValue(1,$term,PDO::PARAM_STR);
	$displaysql->bindValue(2,$term,PDO::PARAM_STR);
    $displaysql->execute();

  
    while ($displayrow = $displaysql->fetch()){
	$id = $displayrow[0];
	$title = $displayrow[1];
	$recipe = $displayrow[2];

	$sentencearray = explode(".",$recipe);
	for ($i=0;$i<2;$i++){
		$articleshort .= $sentencearray[$i].'. ';
	}

	$articleshort .= ' <a href="article.php?articleid='.$id.'">Read More</a>';

	echo '<h3>'.$title.'</h3>';
	echo '<img src ="images/'.$id.'.jpg" alt="'.$title.'" height="50"/><br>'; 
	echo $articleshort.'<br><br><br>';
	unset($articleshort);
  }


}



?>


<form action="search.php" method="post">
<input type="text" name="qry" placeholder="Find a recipe">
<input type="submit" name="submit" value="Search">
</form>