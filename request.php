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

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo '<result>';


    $select = $db->prepare("SELECT * FROM :table where id =:id");

    $table = urldecode($_GET['table']);
    $id = intval(urldecode($_GET['id']));
    $select->bindValue('table',$table,PDO::PARAM_STR);
    $select->bindValue('id',$id,PDO::PARAM_STR);
    $select->execute();

    $result = $select->fetchAll();
    print_r($result);
    echo '</result>';
    $db = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


?>

</body>
</html>
