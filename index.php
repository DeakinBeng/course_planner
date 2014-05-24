<?php
	session_start();
	
	include_once("header.php");
?>

<script type="text/javascript">

		$(function() {
			$( "#majorSelection" ).prop( "disabled", true );
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
					$('#majorSelection').prop('disabled', false);
				});
			});
		}
		
		function forwardCourse()
		{
			if($("#majorSelection").val() == "" || $("#majorSelection").val() == null) {
				alert("Select Course and Major to proceed.");
				return false;
			}
			else
				return true;
		}
</script>

<div class="wrapper">
	<form method="post" id="frmSearch" name="frmSearch" onsubmit="return forwardCourse()" action="course-template.php">
    	<h1>Campus Or online Selection</h1>
		<select id="studyMode" name="studyMode">
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
        <select id="courseSelection" name="courseSelection">
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
        <select id="majorSelection" name="majorSelection">
        	<option value=""></option>          
        </select>
		<br/>
        <input type="submit" id="btnSubmit" name="btnSubmit" value="Generate Plan" />
    </form>
</div>

<?php
	include_once("footer.php");
?>