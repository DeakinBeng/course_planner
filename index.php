<?php
	include_once("header.php");
?>

<div class="wrapper">
	<form method="post" id="frmSearch" name="frmSearch" action="course-template.php">
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
        <select id="majorSelection" width="100">
        	<option value="---">---</option>          
        </select>
		<br/>
        <button type="submit" id="btnSubmit" name="btnSubmit" />Generate Plan</button>
    </form>
</div>

<?php
	include_once("footer.php");
?>