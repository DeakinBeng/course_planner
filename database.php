<?php 
$con= new mysqli("deakincourseplanner.com","Deakin","Deakin12!","CPlanner"); 
if (mysqli_connect_errno()) 
{ 
	echo "Failed to connect to MySQL: " . mysqli_connect_error(); 
} else { 
	$sql = $con->query("SELECT `Unit_Title`, `Credit_Points`,`EFTSL_Value` FROM `units` WHERE `Unit_Code` = \"SIT192\";"); 
	if ($sql->num_rows > 0) { 
		$row = $sql->fetch_assoc(); 
		echo "Unit code: SIT192"; echo "<br />Unit title: " . $row['Unit_Title']; 
		echo "<br />Credit points: " . $row['Credit_Points']; 
		echo "<br />EFTSL value: " . $row['EFTSL_Value']; 
	} 
} 

?>