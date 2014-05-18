<?php

spl_autoload_register(function ($class) {
    include 'Units/' . $class . '.php';
});

$con= new mysqli("120.147.187.76","Deakin","Deakin12!","CPlanner");

$unitsAlreadyInTable = array();

echo "<h2>Units that are currently in the table are: ";


if (isset($_POST['units'])) {
	$units = str_replace(' ', '', strtoupper($_POST['units']));
	$unitsAlreadyInTable = explode (",", $units);
	echo implode(", ", $unitsAlreadyInTable);
} else {
	echo "none";
}
echo "</h2>";
echo "<form action='index.php' method='POST'>
	Insert units (split by comma): <input type='text' name='units' autofocus autocomplete='off'/>
 <input type='submit'/>
</form>";

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
	foreach (glob('Units/*.php') as $filename)
	{
	$unit_code = substr($filename, 6, -4);
		$sql = $con->query("SELECT `Unit_Title`, `Credit_Points`,`EFTSL_Value` FROM `units` WHERE `Unit_Code` = \"".$unit_code."\";");
		if ($sql->num_rows > 0) {
		$row = $sql->fetch_assoc();
	$obj = new $unit_code($row['Unit_Title'], $unit_code, $row['Credit_Points'], $row['EFTSL_Value']);
	echo "<h1>";
	echo $obj->getUnitCode() . " - ";
	echo $obj->getUnitTitle();
	echo "</h1>";
	
	echo "<table border='1' style='border-collapse:collapse;'>
		<tr>
			<td>Credit Points</td>
			<td>" . $obj->getCreditPoints() . "</td>
		</tr>
		<tr>
			<td>EFTSL</td>
			<td>" . $obj->getEFTSL() . "</td>
		</tr>
		<tr>
			<td>Pre-requisites</td>
			<td>" . $obj->getPrerequisites() . "</td>
		</tr>
		<tr>
			<td>Co-requisites</td>
			<td>" . $obj->getCorequisites() . "</td>
		</tr>
		<tr>
			<td>Incompatibles</td>
			<td>" . $obj->getIncompatibilities() . "</td>
		</tr>
		<tr>
			<td>Valid</td>
			<td>";
		if ($obj->validateRequirements($unitsAlreadyInTable)) {
			echo "<p style='color:#33CC33;'>true</p>";
		} else {
			echo "<p style='color:#FF3300;'>false</p>";
		}
		echo "</td>
		</tr>
	</table>";
}
}
}
$con->close();
?>