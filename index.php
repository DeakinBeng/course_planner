<?php
	include_once("database.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Course Planner</title>

<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">

$(function() {

      $('#courseSelection').change(function(){
		//alert("load-course-major.php?course_code=" + $('#courseSelection').val());
		load_course_major('load-course-major.php?course_code=' + $('#courseSelection').val());
    });

});
/*function to load a list*/
function load_course_major(url)
{
    $("#majorSelection").empty();
    $.getJSON(url,null,function(data)
    {
        $.each(data, function(i,obj)
        {
            $("#majorSelection").append($('<option></option>').val(obj['Major_ID']).html(obj['Major_Title']));
        });
    });
}
</script>

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
        <select id="majorSelection" width="100">
        	<option value="---">---</option>          
        </select>
        <br/><br/>
        <button type="submit" id="btnSubmit" name="btnSubmit">Generate Plan</button>
    </form>
</body>
</html>