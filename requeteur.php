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

} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}

$rqt = strval(urldecode($_GET["rqt"]));
$table = strval(urldecode($_GET["from"]));


$recup = false;
function selection($table, $select, $where){
	echo "<br/>";
	global $BD_JDM;

	 $rqt="SELECT ".$select." FROM ".$table." WHERE ".$where;
     echo $rqt;
	 $query = $BD_JDM->prepare($rqt);
	 $query->execute();
	 return $query->fetchAll();
}

function insertion($table, $field, $values){
    global $BD_JDM;
	$rqt="INSERT INTO ".$table." (".$field.") VALUES (".$values.");";
	echo "</br>".$rqt."</br>";
	$result = $BD_JDM->query($rqt);
    return $result;
}

function select_one($table, $select, $where){
	echo "<br/>";
	global $BD_JDM;

	 $rqt="SELECT ".$select." FROM ".$table." WHERE ".$where;
     echo $rqt;
	 $query = $BD_JDM->prepare($rqt);
	 $query->execute();

     $result = $query->fetch();
     print_r($result);
	 return $result[0];
}
/*
UPDATE relationuser
SET nbr_recept = (Select nbr_recept FROM relationuser WHERE rid=6)+1
WHERE rid=6;
*/
function increment_nbr_recept($rid, $nbr_recept){
    global $BD_JDM;


	$rqt="UPDATE relationuser SET nbr_recept=".($nbr_recept+1)." WHERE rid=".$rid.";";
	echo "</br>".$rqt."</br>";

    $query = $BD_JDM->prepare($rqt);
    $query->execute();
    return $result;
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
/*

Activer mode debug
UPDATE user
SET debug = 1
WHERE pseudo ='Bastien Carbonnier';

desactiver Mode debug
UPDATE user
SET debug = 0
WHERE pseudo ='Bastien Carbonnier';

Inserer affirmation
INSERT INTO relationuser (n1,n2,t,user_id)
VALUES (1452,1628,6,(SELECT id FROM user WHERE pseudo='Bastien Carbonnier'));

https://2018hlin601ter16.proj.info-ufr.univ-montp2.fr/WebServiceBddTer16/requeteur.php?rqt=insert_rel&n1=123&n2=345&t=78&pseudo=Bastien%20Carbonnier

 */



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
    case "insert_rel":

        $n1 = strval(urldecode($_GET["n1"]));
        $n2 = strval(urldecode($_GET["n2"]));
        $t = strval(urldecode($_GET["t"]));
        $pseudo = strval(urldecode($_GET["pseudo"]));
        $table = "relationuser";
        $select = "rid,nbr_recept";
        $where = "n1=".$n1." AND n2=".$n2." AND t=".$t;

        $result = select_one($table, $select, $where);

        if ($result["rid"] == NULL){
            $attributs = "n1,n2,t,user_id";

            $values = $n1.",".$n2.",".$t.",(SELECT id FROM user WHERE pseudo='".$pseudo."')";

            insertion($table, $attributs, $values);
        }
        else {
            echo "<br/>";
            print_r($result);
            increment_nbr_recept($result["rid"],$result["nbr_recept"]);
        }

        /*

        */
        break;

}


?>


</body>
</html>
