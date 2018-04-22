<html>
 <head>
   <title>Webservice BDD Ter 16</title>
</head>
<body>
<font color="blue">Webservice BDD Ter 16</font>

<?php
error_reporting(E_ALL | E_STRICT);
echo "Partie 1";

include_once "fonction.php";
echo "partie 2";
include ("../../.sqlpass.php");
echo "partie 3";
try {
	$db = new PDO('mysql:host=localhost;dbname='.$user, $user, $mdp);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "partie 4";

$rqt = urldecode($_GET["rqt"]);
$table = strval(urldecode($_GET["from"]));
echo $rqt;
echo "<br/>".$table;
$recup = false;
switch($rqt){
	case "select":
		$select = strval(urldecode($_GET["select"]));
		$where = strval(urldecode($_GET["where"]));
		$recup = selection($table, $select, $where);
		afficheTableau($recup);
		break;
	case "insert":
		$values = strval(urldecode($_GET["values"]));
		$field = strval(urldecode($_GET["field"]));
		insertion($table, $field, $values);
		break;
}


function selection($table, $select, $where){
	echo "<br/>";
	global $db;

	 $rqt="SELECT ".$select." FROM ".$table." WHERE ".$where;
	 $query = $db->prepare($rqt);
	 $query->execute();
	 return $query->fetchAll();


	/* Ne marche pas
 	$query = $db->prepare("SELECT :selection FROM :table WHERE :clause ");
	$query->bindValue(":selection", $select);
	$query->bindValue(":table", $table);
	$query->bindValue(":clause", $where);
	$query->execute();
	*/


}


function insertion($table, $field, $values){
	global $db;

	$rqt="INSERT INTO ".$table." (".$field.") VALUES (".$values.")";
	echo "</br>".$rqt."</br>";
	$query = $db->prepare($rqt);
	$query->execute();
}

} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}
?>


</body>
</html>
