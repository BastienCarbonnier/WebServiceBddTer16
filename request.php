<html>
 <head>
   <title>Webservice BDD Ter 16</title>
</head>
<body>
<font color="blue">Webservice BDD Ter 16</font>

<?php
error_reporting(E_ALL | E_STRICT);
include ("../../.sqlpass.php");

$cmd = urldecode($_GET['cmd']);
$champ = urldecode($_GET['champ']);
$from = urldecode($_GET['from']);

$query = $cmd." ".$champ." FROM ".$from;

if (isset($_GET['where'])){
    $where = urldecode($_GET['where']);
    $query .=" WHERE ".$where.";";
}
else{
    $query .= ";";
}
echo "<p>".$query."</p>";
try {
    $dbh = new PDO('mysql:host=localhost;dbname='.$user, $user, $mdp);
    echo '<result>';
    foreach($dbh->query($query) as $row) {
        print_r($row);
    }
    echo '</result>';
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


?>

</body>
</html>
