<?php
	include_once("database.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Course Planner</title>
</head>
<body>
	<form action="post" id="frmSearch" name="frmSearch">
    	<h1>Campus Or online Selection</h1>
		<select id="studyMode">
        	<?php 
				$sql = $con->query("SELECT * from campus"); 
				if ($sql->num_rows > 0) { 
					while($row = $sql->fetch_assoc()) { 
						echo '<option value='. $row['Campus_ID'] . '>'. $row['Name'] . '</option>';
					}
				} 
			?>            
        </select>
        
        <h1>Course Selection</h1>
        <select id="courseSelection">
        	<?php 
				$sql = $con->query("SELECT * from courses"); 
				if ($sql->num_rows > 0) { 
					while($row = $sql->fetch_assoc()) { 
						echo '<option value='. $row['Course_Code'] . '>'. $row['Course_Title'] . '</option>';
					}
				} 
			?>
                   
        </select>
        
        <h1>Major Selection</h1>
        <select id="majorSelection">
        	<option value="ComputerScience">Computer Science and Software Development</option>
        	<option value="Multimedia">Multimedia</option>            
        </select>
        <br/><br/>
        <button type="submit" id="btnSubmit" name="btnSubmit">Generate Plan</button>
    </form>
</body>
</html>