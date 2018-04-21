<?php
error_reporting(E_ALL | E_STRICT);

include_once 'fonction.php';


include ("../../.sqlpass.php");

try {
	$db = new PDO('mysql:host=localhost;dbname='.$user, $user, $mdp);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}

var_dump($_GET);


$rqt = strval(urldecode($_GET["rqt"]));
$table = strval(urldecode($_GET["from"]));

$recup = false;
switch($rqt){
	case "select": 
		$select = strval(urldecode($_GET["select"]));
		$where = strval(urldecode($_GET["where"]));
		$recup = selection($table, $select, $where);
		afficheTableau($recup);
	case "insert": ; break;
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
?>