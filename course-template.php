<?php
	session_start();
	
	include_once("header.php");
	
	// Store the previous page post data in Session
	if(isset($_POST["studyMode"]))
		$_SESSION['studyMode'] = @$_POST["studyMode"];
	
	// Fetch Course Information
	if(isset($_POST["courseSelection"]))
	{
		$_SESSION['course_selection'] = @$_POST["courseSelection"];
		$sql = $con->query("SELECT * from courses where Course_Code = '" . $_SESSION['course_selection'] . "'"); 
		if ($sql->num_rows > 0) { 
			$row = $sql->fetch_assoc();
			$_SESSION['course_name'] = $row['Course_Title'];
		} 
	}
	
	// Fetch Major Sequence Information
	if(isset($_POST["majorSelection"]))
	{
		$_SESSION['major_selection'] = @$_POST["majorSelection"];
		$sql = $con->query("SELECT * from major_sequences where Major_ID = '" . $_SESSION['major_selection'] . "'"); 
		if ($sql->num_rows > 0) { 
			$row = $sql->fetch_assoc();
			$_SESSION['major_sequence'] = $row['Major_Title'];
		}
	}
	
	// Check month greater than March for Next commencing year
	if(date('n') >= 3) 
		$_SESSION['commencing_year'] = date('Y', strtotime('+1 year'));
	else
		$_SESSION['commencing_year'] = date('Y');
	
?>

<div class="wrapper">
	<div class="left-sidebar">
		<div class="search-unit">
			<p><strong>Search for a unit</strong></p>
			Enter a unit code
			<form method="post" id="frmSearch" name="frmSearch" action="">
				<!-- Load the searched value -->
				<input type="text" id="searchCourse" name="searchCourse" value="<?php echo @$_POST['searchCourse']; ?>" />
				<input type="submit" id="btnSearch" name="btnSearch" value="Search" />
			</form>
		</div>
	</div>
	
	<div class="page-main">
		<div class="course-info">
			<!-- Display information from previous post data stored in Session -->
			COURSE CODE : <?php echo @$_SESSION['course_selection']; ?><br/>
			COURSE NAME : <?php echo @$_SESSION['course_name']; ?><br/>
			MAJOR SEQUENCE : <?php echo @$_SESSION['major_sequence']; ?><br/>
			COMMENCING : <?php echo @$_SESSION['commencing_year']; ?>
		</div>
	</div>

	<div class="left-sidebar">
		<br/>
		<div class="left">
			<table>
				<?php 
					// Search and List the units as per search text
					$sql = $con->query("SELECT * from units where Unit_Code like '%" . @$_POST['searchCourse'] . "%'"); 
					if ($sql->num_rows > 0) { 
						while($row = $sql->fetch_assoc()) {
							echo '<tr><td><div class="item">'. $row['Unit_Code'] . ' - ' . $row['Unit_Title'] . '</div></td></tr>';
						}
					}
					else
						echo '<tr><td><div>Searched unit is not found.</div></td></tr>';
				?>
			</table>
		</div>
	</div>
	
	<div class="page-main">
		<p><strong>Click and drag a Unit in the Course Planner Template</strong></p>
		<div class="right">
			<table>
				<tr>
					<td rowspan="2">Y1</td>
					<td>T1</td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
				</tr>
				<tr>
					<td>T2</td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
				</tr>
				<tr>
					<td rowspan="2">Y2</td>
					<td>T1</td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
				</tr>
				<tr>
					<td>T2</td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
				</tr>
				<tr>
					<td rowspan="2">Y3</td>
					<td>T1</td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					
				</tr>
				<tr>
					<td>T2</td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
					<td class="drop"></td>
				</tr>
			</table>
		</div>
	</div>
	
</div>

<script>
	$(function(){
		$('.left .item').draggable({
			revert:true,
			proxy:'clone'
		});
		$('.right td.drop').droppable({
			onDragEnter:function(){
				$(this).addClass('over');
			},
			onDragLeave:function(){
				$(this).removeClass('over');
			},
			onDrop:function(e,source){
				$(this).removeClass('over');
				if ($(source).hasClass('assigned')){
					$(this).append(source);
				} else {
					var c = $(source).clone().addClass('assigned');
					$(this).empty().append(c);
					c.draggable({
						revert:true
					});
				}
			}
		});
		$('.left').droppable({
			accept:'.assigned',
			onDragEnter:function(e,source){
				$(source).addClass('trash');
			},
			onDragLeave:function(e,source){
				$(source).removeClass('trash');
			},
			onDrop:function(e,source){
				$(source).remove();
			}
		});
	});
</script>

<?php
	include_once("footer.php");
?>