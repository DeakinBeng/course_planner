<?php 

include_once("database.php");

$array = $_GET['array'];
$array = str_replace("(,", "'", $array);
$array = str_replace(",", "','", $array);
$array = str_replace(")", "'", $array);

$query = "SELECT A.Unit_Code, A.Unit_Title from units A LEFT OUTER JOIN major_units B on A.Unit_Code = B.Unit_Code WHERE A.Unit_Code like '%" . $_GET['input'] . "%' AND A.Unit_Code NOT IN (". $array .") Group by SUBSTRING(A.Unit_Code, 4, 3), A.Unit_Title";
$sql = $con->query($query);

$rows = array();
	 
if ($sql->num_rows > 0) { 
	while($rec = $sql->fetch_assoc()) { 
		$rows[] = $rec;
	}
}
//mysql_close($con);
 
echo json_encode($rows);
?>