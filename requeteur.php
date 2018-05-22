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
$bd = $user;

try {
	$BD_JDM = new PDO('mysql:host=localhost;dbname='.$bd, $user, $mdp);
	$BD_JDM->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}

if (isset($_GET["cmd"])){
    $cmd = $_GET["cmd"];
}
else if (isset($_POST["cmd"])){
    $cmd = $_POST["cmd"];
} else {
	$cmd = "";
}
$recup = false;
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
        $pseudo = strval(urldecode($_GET["pseudo"]));
        $fa = strval(urldecode($_GET["fa"]));
        $question = true;

        updateLastFaFw($fa,$n1,$pseudo,$question);

        if (is_relation_exist($n1,$n2,$t)){
            echo "<result>true</result>";
            $table = "relationuser";
            $select = "nbr_recept_neg,nbr_recept_pos,w";
            $where = "n1_s='".$n1."' AND n2_s='".$n2."' AND t=".$t;

            $result = select_one($table, $select, $where);
            if ($result["w"]>0){
                echo "<rel_neg>false</rel_neg>";
            }
            else{
                echo "<rel_neg>true</rel_neg>";
            }
        }
        else {
            echo "<result>false</result>";
        }
        break;
    case "get_user_adresse":

        $pseudo = strval(urldecode($_GET["pseudo"]));
        echo "<result>".getUserAdresse($pseudo)."</result>";

        break;
    case "get_user_last_fa_fw":

        $question = intval(urldecode($_GET["question"]));
        $pseudo = strval(urldecode($_GET["pseudo"]));
        echo "<result>".getUserLastFaFw($pseudo,$question)."</result>";

        break;
    case "insert_user":
        $pseudo = strval($_POST["pseudo"]);
        $adresse = strval($_POST["adresse"]);

        $table = "user";
        $attributs = "pseudo,adresse";
        $values = "'".$pseudo."'".",".$BD_JDM->quote($adresse)."";

        if (!is_user_exist($pseudo)){
            insert($table, $attributs, $values);
        }
        else{
            $set = "pseudo ='".$pseudo."',adresse =".$BD_JDM->quote($adresse)."";
            $idUser = getUserId($pseudo);
            $where = "id=".$idUser;
            update($table, $set, $where);
        }

        break;
    case "insert_rel":

        $question = false;
        $fa = strval(urldecode($_GET["fa"]));
        $n1 = strval(urldecode($_GET["n1"]));
        $n2 = strval(urldecode($_GET["n2"]));
        $t = strval(urldecode($_GET["t"]));
        $w = strval(urldecode($_GET["w"]));
        $pseudo = strval(urldecode($_GET["pseudo"]));
        $table = "relationuser";
        $rel_neg = intval(urldecode($_GET["rel_neg"]));
        updateLastFaFw($fa,$n1,$pseudo,$question);
        if (!is_relation_exist($n1,$n2,$t)){
            $attributs = "n1,n2,n1_s,n2_s,t,w,user_id,nbr_recept";
            $n1_id = getWordId($n1);
            $n2_id = getWordId($n2);

            $values = $n1_id.",".$n2_id.",'".$n1."','".$n2."',".$t.",".$w.",(SELECT id FROM user WHERE pseudo='".$pseudo."')";


            $w_mod = 0;
            if ($rel_neg){
                $w_mod = -10;
                $attributs .="_neg";

                $values = $n1_id.",".$n2_id.",'".$n1."','".$n2."',".$t.",".$w_mod.",(SELECT id FROM user WHERE pseudo='".$pseudo."'),1";
            }
            else{
                $w_mod = 10;
                $attributs .="_pos";
                $values = $n1_id.",".$n2_id.",'".$n1."','".$n2."',".$t.",".$w_mod.",(SELECT id FROM user WHERE pseudo='".$pseudo."'),1";
            }

            insert($table, $attributs, $values);
        }
        else {
            $select = "rid,nbr_recept_neg,nbr_recept_pos,w";
            $where = "n1_s='".$n1."' AND n2_s='".$n2."' AND t=".$t;

            $result = select_one($table, $select, $where);
            increment_nbr_recept($result["rid"],$result["nbr_recept_pos"],$result["nbr_recept_neg"],$result["w"]);
        }

        break;
	  case "makeInference":
	    $n1 = strval(urldecode($_GET["n1"]));
	    $n2 = strval(urldecode($_GET["n2"]));
	    $t = strval(urldecode($_GET["t"]));
	    afficheInf($n1, $n2, $t);
    	break;

}

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


function increment_nbr_recept($rid, $nbr_recept_pos,$nbr_recept_neg,$w){
	global $BD_JDM;
	$rel_neg = intval($_GET["rel_neg"]);
	$w_mod = 0;
	if ($rel_neg){
		$w_mod = $w-10;
		$rqt="UPDATE relationuser SET nbr_recept_neg=".($nbr_recept_neg+1).",w=".($w_mod)." WHERE rid=".$rid.";";
	}
	else{
		$w_mod = $w+10;
		$rqt="UPDATE relationuser SET nbr_recept_pos=".($nbr_recept_pos+1).",w=".($w_mod)." WHERE rid=".$rid.";";
	}
	
	
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

function is_fa_fw_exist ($pseudo){
	$table = "itemuser";
	$select = "id";
	$idUser = getUserId($pseudo);
	$where = "user_id=".$idUser;
	
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
	$select = "rid,nbr_recept_pos,nbr_recept_neg";
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
	$r_fw_id = select_one("node", "eid", "n='".$n."'");
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

function getUserAdresse($pseudo){
	$adresse = select_one("user", "adresse", "pseudo='".$pseudo."'");
	return $adresse["adresse"];
}
function getUserLastFaFw($pseudo,$question){
	if ($question){
		$champ = "last_fa_ques,last_fw_ques";
	}
	else{
		$champ = "last_fa_aff,last_fw_aff";
	}
	
	$idUser = getUserId($pseudo);
	$where = "user_id=".$idUser;
	
	$last = select_one("itemuser", $champ,$where);
	
	if ($question){
		return "<fa>".$last["last_fa_ques"]."</fa><fw>".$last["last_fw_ques"]."</fw>";
	}
	else{
		return "<fa>".$last["last_fa_aff"]."</fa><fw>".$last["last_fw_aff"]."</fw>";
	}
	
}

function updateLastFaFw($fa,$fw,$pseudo,$question){
	
	$table = "itemuser";
	$idUser = getUserId($pseudo);
	
	if (is_fa_fw_exist ($pseudo)){
		if ($question){
			$set = "last_fw_ques ='".$fw."',last_fa_ques ='".$fa."'";
		}
		else{
			$set = "last_fw_aff ='".$fw."',last_fa_aff ='".$fa."'";
		}
		
		
		$where = "user_id=".$idUser;
		
		update($table, $set, $where);
	}
	else{
		if ($question){
			$attributs = "user_id,last_fa_ques,last_fw_ques";
		}
		else{
			$attributs = "user_id,last_fa_aff,last_fw_aff";
		}
		
		$values = $idUser.",'".$fa."','".$fw."'";
		
		insert($table, $attributs, $values);
		
	}
}

function afficheInf($n1, $n2, $t){
	global $BD_JDM;
	$query = "SELECT t1.n2, t1.w as w1, t2.w as w2 FROM relation as t1 JOIN relation as t2 ON t1.n2 = t2.n1 WHERE t1.t = 6 AND t2.t = :t AND t1.n1 = :n1 AND t2.n2 = :n2";
	$rqt = $BD_JDM->prepare($query);
	
	$rqt->bindParam(":t",$t);
	$rqt->bindParam(":n1",$n1);
	$rqt->bindParam(":n2",$n2);
	
	$rqt->execute();
	$result = $rqt->fetch();
	// $result : tableau contenant : $result["n2"], $result["w1"], $result["w2"] 
	// respectivement le mot intermédiaire, la poid de la premier relation et le poid de la seconde
	
	// Actuellment affiche un truc du style : 3171 6 104993 0 2503675
	// pour la recherche de l'inférence n1 t1 n2
	// On a n1 t2 n3 t1 n2
	echo $n1 . " 6 " . $result["n2"] . " " . $t . " " . $n2;
}
?>


</body>
</html>
