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
    $db = new PDO('mysql:host=localhost;dbname='.$user, $user, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo '<result>';

    $select = $db->prepare("SELECT * FROM :table WHERE id = :id");
    $select->bindParam(':table', $table);
    $select->bindParam(':id', $id);
    $table = urldecode($_GET['table']);
    $id = urldecode($_GET['id']);
    $select->execute();

    echo '</result>';
    $db = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


?>

</body>
</html>
