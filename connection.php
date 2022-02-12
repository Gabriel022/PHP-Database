<?php
session_start();
$dbh = new PDO('mysql:host=localhost;dbname=webd153finalproject', 'root', 'root');
/*
try {
    $dbh = new PDO('mysql:host=localhost;dbname=Webd153','root', 'root');
    foreach($dbh->query('SELECT * from stores') as $row) {
      echo $row[0].' '.$row[1].'<br>';
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
*/
?>
