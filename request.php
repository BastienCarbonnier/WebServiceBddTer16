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

    // la requete
	$query = $db->prepare("SELECT relation FROM :table
							   WHERE id= :id");
    $table = urldecode($_GET['table']);
    $id = intval(urldecode($_GET['id']));
	$query->bindParam(":table", $table);
	$query->bindParam(":id", $id);

	// execution
	$query->execute();

	// Récupération de la première ligne
	$result2 = $query->fetch();



    print_r($result2);
    echo '</result>';
    $db = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


?>

</body>
</html>
