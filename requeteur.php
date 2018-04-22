<html>
 <head>
   <title>Webservice BDD Ter 16</title>
</head>
<body>
<font color="blue">Webservice BDD Ter 16</font>

<?php
error_reporting(E_ALL | E_STRICT);

//include_once "fonction.php";

include ("../../.sqlpass.php");
try {
	$BD_JDM = new PDO('mysql:host=localhost;dbname='.$user, $user, $mdp);
	$BD_JDM->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$rqt = strval(urldecode($_GET["rqt"]));
$table = strval(urldecode($_GET["from"]));


$recup = false;
switch($rqt){
	case "select":
		$select = strval(urldecode($_GET["select"]));
		$where = strval(urldecode($_GET["where"]));
		echo "<br/>".$select;
		echo "<br/>".$where;
		$recup = selection($table, $select, $where);
		echo "<br/>".$recup;
		print_r($recup);
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
	global $BD_JDM;

	 $rqt="SELECT ".$select." FROM ".$table." WHERE ".$where;
	 $query = $BD_JDM->prepare($rqt);
	 $query->execute();
	 return $query->fetchAll();


	/* Ne marche pas
 	$query = $BD_JDM->prepare("SELECT :selection FROM :table WHERE :clause ");
	$query->bindValue(":selection", $select);
	$query->bindValue(":table", $table);
	$query->bindValue(":clause", $where);
	$query->execute();
	*/


}


function insertion($table, $field, $values){
	global $BD_JDM;

	$rqt="INSERT INTO ".$table." (".$field.") VALUES (".$values.")";
	echo "</br>".$rqt."</br>";
	$query = $BD_JDM->prepare($rqt);
	$query->execute();
}

function afficheTableau($tab){
	$strucAffich = "<table border = 1>";
	for($i = 0; $i < sizeof($tab); $i++){
		$strucAffich .= "<tr>";
		for($j = 0; $j < sizeof($tab[$i])/2; $j++){

			$strucAffich .="<td>".$tab[$i][$j]."</td>";
		}
		$strucAffich .="</tr>";
	}
	$strucAffich .="</table>";
	echo $strucAffich;
}


} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}


?>


</body>
</html>
