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

$cmd = strval(urldecode($_GET["cmd"]));
$table = strval(urldecode($_GET["from"]));


$recup = false;
function select($table, $select, $where){
	global $BD_JDM;

	 $rqt="SELECT ".$select." FROM ".$table." WHERE ".$where;
	 $query = $BD_JDM->prepare($rqt);
	 $query->execute();
	 return $query->fetchAll();
}

function insert($table, $field, $values){
    global $BD_JDM;
	$rqt="INSERT INTO ".$table." (".$field.") VALUES (".$values.");";
	echo "</br>".$rqt."</br>";
	$result = $BD_JDM->query($rqt);
    return $result;
}

function update($table, $set, $where){
    global $BD_JDM;
	$rqt="UPDATE ".$table." SET ".$set." WHERE ".$where.";";
	echo "</br>".$rqt."</br>";
	$result = $BD_JDM->query($rqt);
    return $result;
}

function select_one($table, $select, $where){
	global $BD_JDM;

    if ($where != ""){
        $where = " WHERE ".$where;
    }
	 $rqt="SELECT ".$select." FROM ".$table.$where;
     echo $rqt;
	 $query = $BD_JDM->prepare($rqt);
	 $query->execute();

     $result = $query->fetch();
	 return $result;
}


function increment_nbr_recept($rid, $nbr_recept){
    global $BD_JDM;
    $rqt="UPDATE relationuser SET nbr_recept=".($nbr_recept+1)." WHERE rid=".$rid.";";

    $query = $BD_JDM->prepare($rqt);
    $query->execute();
    return $result;
}


function afficheResultat($tab){
    echo "nbr_resultat = ".sizeof($tab);
	$strucAffich = "<resultat>";

	for($i = 0; $i < sizeof($tab); $i++){
		$strucAffich .= "<id =".$i.">";
		for($j = 0; $j < sizeof($tab[$i])/2; $j++){

			$strucAffich .="<res>".$tab[$i][$j]."</res>";
		}
		$strucAffich .="</id>";
	}
	$strucAffich .="</resultat>";
	echo $strucAffich;
}

function active_debug($user_pseudo){
    global $BD_JDM;
    $rqt="UPDATE user SET debug=1 WHERE pseudo='".$user_pseudo."';";

    $query = $BD_JDM->prepare($rqt);
    $query->execute();
}
function desactive_debug($user_pseudo){
    global $BD_JDM;
    $rqt="UPDATE user SET debug=0 WHERE pseudo='".$user_pseudo."';";

    $query = $BD_JDM->prepare($rqt);
    $query->execute();
}
function is_user_exist ($pseudo){
    $table = "user";
    $select = "id";
    $where = "pseudo='".$pseudo."'";

    $result = select_one($table, $select, $where);

    if ($result["id"] == NULL){
        return false;
    }
    else {
        return true;
    }
}

function getUserId ($pseudo){
    $table = "user";
    $select = "id";
    $where = "pseudo='".$pseudo."'";

    $result = select_one($table, $select, $where);

    return $result["id"];
}


function is_relation_exist ($n1,$n2,$t){
    $table = "relationuser";
    $select = "rid,nbr_recept";
    $where = "n1_s='".$n1."' AND n2_s='".$n2."' AND t=".$t;

    $result = select_one($table, $select, $where);

    if ($result["rid"] == NULL){
        return false;
    }
    else {
        return true;
    }
}

function is_in_debug($pseudo){
    $table = "user";
    $select = "debug";
    $where = "pseudo='".$pseudo."'";

    $result = select_one($table, $select, $where);

    if ($result["debug"] == 0){
        return false;
    }
    else {
        return true;
    }
}

function getWordId($n){
    echo "debut getWordId\n";
    $r_fw_id = select_one("node", "eid", "n='".$n."'");
    echo "après select one\n";
    if ($r_fw_id["eid"] == NULL){
        $r_min= select_one("node", "MIN(eid) min","");
        $min = intval($r_min["min"])-1;
        if ($min >= 0){
            $min=-1;
        }
        insert("node", "eid,n,t,w",$min.",'".$n."',1,0");
        return $min;
    }
    else {
        return $r_fw_id["eid"];
    }
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

https://2018hlin601ter16.proj.info-ufr.univ-montp2.fr/WebServiceBddTer16/requeteur.php?cmd=desactive_debug&pseudo=Bastien%20Carbonnier

Inserer affirmation
INSERT INTO relationuser (n1,n2,t,user_id)
VALUES (1452,1628,6,(SELECT id FROM user WHERE pseudo='Bastien Carbonnier'));

https://2018hlin601ter16.proj.info-ufr.univ-montp2.fr/WebServiceBddTer16/requeteur.php?cmd=insert_rel&n1=123&n2=345&t=78&pseudo=Bastien%20Carbonnier
https://2018hlin601ter16.proj.info-ufr.univ-montp2.fr/WebServiceBddTer16/requeteur.php?cmd=insert_rel&n1=123&n2=345&t=6&pseudo=Bastien%20Carbonnier

insert user :
https://2018hlin601ter16.proj.info-ufr.univ-montp2.fr/WebServiceBddTer16/requeteur.php?cmd=insert_user&pseudo=Thomas






 */

switch($cmd){
    /*
	case "select":
		$select = strval(urldecode($_GET["select"]));
		$where = strval(urldecode($_GET["where"]));
		$recup = select($table, $select, $where);

		afficheResultat($recup);
		break;

	case "insert":
		$values = strval(urldecode($_GET["values"]));
		$field = strval(urldecode($_GET["field"]));
		insert($table, $field, $values);
		break;
    */
    case "active_debug":
        $user_pseudo = strval(urldecode($_GET["pseudo"]));
        active_debug($user_pseudo);
        break;
    case "desactive_debug":
        $user_pseudo = strval(urldecode($_GET["pseudo"]));
        desactive_debug($user_pseudo);
        break;
    case "is_in_debug":
        $pseudo = strval(urldecode($_GET["pseudo"]));
        if (is_in_debug($pseudo)){
            echo "<result>true</result>";
        }
        else{
            echo "<result>false</result>";
        }
        break;
    case "user_exist":
        $pseudo = strval(urldecode($_GET["pseudo"]));
        if (is_user_exist($pseudo)){
            echo "<result>true</result>";
        }
        else {
            echo "<result>false</result>";
        }
        break;
    case "relation_exist":
        $n1 = strval(urldecode($_GET["n1"]));
        $n2 = strval(urldecode($_GET["n2"]));
        $t = strval(urldecode($_GET["t"]));


        if (is_relation_exist($n1,$n2,$t)){
            echo "<result>true</result>";
        }
        else {
            echo "<result>false</result>";
        }
        break;
    case "insert_user":
        $pseudo = strval(urldecode($_GET["pseudo"]));
        $idBot = strval(urldecode($_GET["idBot"]));
        $session = strval(urldecode($_GET["session"]));

        $table = "user";
        $attributs = "pseudo,idBot,session";
        $values = "'".$pseudo."'".",'".$idBot."','".$session."'";

        if (!is_user_exist($pseudo)){
            insert($table, $attributs, $values);
        }
        else{
            $set = "pseudo ='".$pseudo."',idBot ='".$idBot."','".$session."'";
            $idUser = getUserId($pseudo);
            $where = "id=".$idUser;
            update($table, $set, $where);
        }

        break;
    case "insert_rel":

        $n1 = strval(urldecode($_GET["n1"]));
        $n2 = strval(urldecode($_GET["n2"]));
        $t = strval(urldecode($_GET["t"]));
        $pseudo = strval(urldecode($_GET["pseudo"]));
        $table = "relationuser";

        if (!is_relation_exist($n1,$n2,$t)){
            $attributs = "n1,n2,n1_s,n2_s,t,user_id";

            $n1_id = getWordId($n1);

            $n2_id = getWordId($n2);

            $values = $n1_id.",".$n2_id.",'".$n1."','".$n2."',".$t.",(SELECT id FROM user WHERE pseudo='".$pseudo."')";

            insert($table, $attributs, $values);
        }
        else {
            increment_nbr_recept($result["rid"],$result["nbr_recept"]);
        }

        break;

}


?>


</body>
</html>
