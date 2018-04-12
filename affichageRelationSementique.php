<?php

	include_once 'head.php';
	include_once 'fonction.php';
	
	$tab = array(6,9,10,15,16,17,24,31,32,34,37,38,39,40,49,50,53,55,56,57,58,62,63,64,67,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,119,121,150,151,152,155,156,13,3,42);
	
	echo "<table border=1>";
	for($i = 0; $i < sizeof($tab); $i++){
		$query = "SELECT * FROM listerelation WHERE id=".$tab[$i];		
		$relation = $BD_JDM->query($query)->fetch();
		
		echo "<tr><td>".$relation["id"]."</td><td>"
				.$relation["relation"]."</td><td>"
				.$relation["definition"]."</td></tr>";
	}
	echo "</table>";
	
?>