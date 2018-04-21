<html>
 <head>
   <title>Webservice BDD Ter 16</title>
</head>
<body>
<font color="blue">Webservice BDD Ter 16</font>

<?php
error_reporting(E_ALL | E_STRICT);

include ("../../.sqlpass.php");

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

    $result = $select->setFetchMode(PDO::FETCH_ASSOC);
    foreach($select->fetchAll()) as $k=>$v) {
        echo $v;
    }
    echo '</result>';
    $db = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


?>

</body>
</html>
