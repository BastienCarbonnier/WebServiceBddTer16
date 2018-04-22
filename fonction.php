<?php
/* Fonctions */
function recupMot($idmot){
  global $db;
  $query = "SELECT n FROM node WHERE eid=".$idmot;
  return $db->query($query)->fetch()[0];
}

function recupRelation($idRelation){
  global $db;
  $query = "SELECT relation FROM listerelation WHERE id=".$idRelation;
  return $db->query($query)->fetch()[0];
}

function affichage($idMot1, $idRelation, $idMot2){
   echo recupMot($idMot1)
        ." ". recupRelation($idRelation)
        ." ". recupMot($idMot2)." <br/>";
}

function affichagePoid($idMot1, $idRelation, $idMot2, $poid){
   echo recupMot($idMot1)
        ." ". recupRelation($idRelation)
        ." ". recupMot($idMot2)
        ." ". $poid ." <br/>";
}

function affichagePoidTableau($idMot1, $idRelation, $idMot2, $poid){
  echo "<tr><td>".recupMot($idMot1)
    ."</td><td>". recupRelation($idRelation)
    ."</td><td>". recupMot($idMot2)
    ."</td><td>". $poid ." </td></tr>";
}

/*
 * Affiche les inférences stocké dans la BD
 * @param ordre : true alors inférences affiché dans l'ordre
 * 							 décroissant des poids
 * 				  Sinon affiché comme stocké dans la BD
 *
 */
function affichageInfere($ordre=false){
  /* variable de connexion globale */
  global $db;
  global $BD_heberg;

  // Affichage trié ou non
  if($ordre){
    $query = "SELECT * FROM `relationinfere` ORDER BY `relationinfere`.`w` DESC";
  } else {
    $query = "SELECT * FROM `relationinfere`"/* ORDER BY `relationinfere`.`w` DESC"*/;
  }

  $allRelation = ($BD_heberg->query($query)->fetchAll());

  echo "<table border=1>";
  echo "<tr><th>Mot1</th><th>Relation</th><th>Mot2</th><th>Poid</th></tr>";
  for($i = 0; $i < sizeof($allRelation); $i++){
    affichagePoidTableau($allRelation[$i]["n1"]
                  ,$allRelation[$i]["t"]
                  ,$allRelation[$i]["n2"]
                  ,$allRelation[$i]["w"]);
  }
  echo "</table>";

}

function maxRid(){
  /* variable de connexion globale */
  global $db;
  global $BD_heberg;

  if(isset($_SESSION["maxrid"])){
    $_SESSION["maxrid"] = $_SESSION["maxrid"];
  } else {
    // recherche du rid max (pour pk)
    $query="SELECT MAX(rid) FROM relation";
    $maxRID = ($db->query($query)->fetch())[0];

    $query="SELECT MAX(rid) FROM relationinfere";
    $maxRIDinf = ($BD_heberg->query($query)->fetch())[0];

    $_SESSION["maxrid"] = max($maxRIDinf, $maxRID);
  }
  return $_SESSION["maxrid"];
}

/*
* Permet d'inserer le contenu de $_SESSION["insertion"]
*/
function inserer(){


  if(isset($_SESSION["insertion"])){
  	/* variable de connexion globale */
  	global $db;
  	global $BD_heberg;
    for($i = 0; $i < sizeof($_SESSION["insertion"]);$i++) {
      $query = $BD_heberg->prepare("INSERT INTO relationinfere (rid, n1, n2, t, w)
                                    VALUES (:rid, :n1, :n2, :t, :w)");

      $query->bindParam(":rid", $_SESSION["insertion"][$i]["rid"]);
      $query->bindParam(":n1", $_SESSION["insertion"][$i]["n1"]);
      $query->bindParam(":n2", $_SESSION["insertion"][$i]["n2"]);
      $query->bindParam(":t", $_SESSION["insertion"][$i]["t"]);
      $query->bindParam(":w", $_SESSION["insertion"][$i]["w"]);
      $query->execute();
    }
  } // else
}

function relationPresente($n1, $t, $n2){
  if($n1 == $n2){
    return true;
  }
  for($i = 0; $i < sizeof($_SESSION["insertion"]);$i++) {
    if($_SESSION["insertion"][$i]["n1"] == $n1
       && $_SESSION["insertion"][$i]["t"] == $t
       && $_SESSION["insertion"][$i]["n2"] == $n2){
         return true;
    } // else
  }
  return false;
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
 ?>
