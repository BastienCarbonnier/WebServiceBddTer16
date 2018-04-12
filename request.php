<html>
 <head>
   <title>Webservice BDD Ter 16</title>
</head>
<body>
<font color="blue">Webservice BDD Ter 16</font>


<?php
    error_reporting(E_ALL | E_STRICT);
    include ("../../.sqlpass.php");

    $db= new mysqli("localhost",$user,$mdp,$user);

//Test 34256272
    $cmd = urldecode($_GET['cmd']);
    $champ = urldecode($_GET['champ']);
    $from = urldecode($_GET['from']);

    $query = $cmd." ".$champ." FROM ".$from;

    if (isset($_GET['where'])){
        $where = urldecode($_GET['where']);
        $query .=" WHERE ".$where.";";
    }
    else{
        $query .= ";";
    }
	echo "<p>".$query."</p>";



    if ( $result = $db->query($query) ){
        echo '<result>';
	while ($row = $result->fetch_array(MYSQLI_ASSOC)){

        echo '<pre>';
	print_r($row);
	echo '</pre>';
	}
        echo '</result>';
        $result->free();
        $db->close();
    }
    else{
        echo "<result>NULL</result>" ;
    }

?>

</body>
</html>
