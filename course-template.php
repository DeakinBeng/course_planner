<?php
	session_start();
	
	include_once("header.php");
	
	// Store the previous page post data in Session (Fetch Campus Information)
	if(isset($_POST["studyMode"]))
	{
		$_SESSION['studyMode'] = @$_POST["studyMode"];
		$sql = $con->query("SELECT * from campus where Campus_ID = '" . $_SESSION['studyMode'] . "'"); 
		if ($sql->num_rows > 0) { 
			$row = $sql->fetch_assoc();
			$_SESSION['campus_name'] = $row['Name'];
		} 
	}
	
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
		
	if(@$_SESSION['major_sequence'] == '')
		header("Location: index.php");
	
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
		
		<br/>
		<div class="left">
			<table>
				<?php
					$search_results = 0;
					// Search and List the units as per search text
					/*$query = "SELECT A.Unit_Code, A.Unit_Title, B.Core from units A LEFT OUTER JOIN major_units B on A.Unit_Code = B.Unit_Code 
					WHERE  (B.Core=0 AND B.Major_ID = '". @$_SESSION['major_selection'] ."' AND A.Unit_Code like '%" . @$_POST['searchCourse'] . "%') 
					OR (B.Core=0 AND B.Major_ID <> '". @$_SESSION['major_selection'] ."' AND A.Unit_Code like '%" . @$_POST['searchCourse'] . "%') 
					Group by A.Unit_Code, A.Unit_Title";*/
					$query = "SELECT A.Unit_Code, A.Unit_Title, B.Core from units A LEFT OUTER JOIN major_units B on A.Unit_Code = B.Unit_Code 
					WHERE A.Unit_Code like '%" . @$_POST['searchCourse'] . "%' AND
					A.Unit_Code NOT IN (". @$_SESSION['added_units'] .") Group by A.Unit_Code, A.Unit_Title";
					$sql = $con->query($query); 
					if ($sql->num_rows > 0) { 
						$search_results = $sql->num_rows;
						while($row = $sql->fetch_assoc()) {
							echo '<tr><td><div id="'. $row["Unit_Code"] . '" class="item">'. $row['Unit_Code'] . ' - ' . $row['Unit_Title'] . '</div></td>
								<td><a title="'. $row["Unit_Code"] . '" href="#" onClick="javascript:newWindow('.$row["Unit_Code"].');" class="view-detail">View Detail</a></td></tr>';
						}
					}
					else
						echo '<tr><td><div>Searched unit is not found or already in Core units.</div></td></tr>';
				?>
			</table>
		</div>
		<p><?php echo $search_results . " Search Results"; ?></p>
		<p>Course Status: <span id="course-status">Incomplete</span></p>
	</div>
	
	<div class="page-main">
		<div class="course-info">
			<!-- Display information from previous post data stored in Session -->
			COURSE CODE : <?php echo @$_SESSION['course_selection']; ?><br/>
			COURSE NAME : <?php echo @$_SESSION['course_name']; ?><br/>
			MAJOR SEQUENCE : <?php echo @$_SESSION['major_sequence']; ?><br/>
			COMMENCING : <?php echo @$_SESSION['commencing_year']; ?><br/>
			CAMPUS : <?php echo @$_SESSION['campus_name']; ?><br/>
			VALID : <span id='valid'>TRUE</span>
		</div>
	
		<p><strong>Click and drag a Unit in the Course Planner Template</strong></p>
		<div class="right">
			<?php 
				// Check if selected major has Satey Induction Program unit and display Note
				$sql = $con->query("SELECT * FROM major_units where Unit_Code = 'SIT010' and Major_ID = '" . $_SESSION['major_selection'] . "'"); 
				if ($sql->num_rows > 0) { 
					$row = $sql->fetch_assoc();
					echo '<p class="note">Note: Need to Complete SIT010 Safety Induction Program (0 credit-point compulsory unit)</p>';
				}
			?>
			<table id="template">
			<?php
			$added_units = "''";
			// 3 Years Loop for Trimester 1 and Trimester 2
			for($i=1; $i<=3; $i++)
			{
			?>
				<tr>
					<td rowspan="2" class="Y<?php echo $i; ?>">Y<?php echo $i; ?></td>
					<td class="T1">T1</td>
					<?php 
					// Get Unit information for Trimester 1
					$query = "SELECT A.Unit_Code, B.Unit_Title, A.Core, C.Trimester1, C.Trimester2, A.Year FROM `major_units` A 
						LEFT OUTER JOIN units B on A.Unit_Code = B.Unit_Code
						LEFT OUTER JOIN availabilities C on A.Unit_Code = C.Unit_Code
						Where C.Trimester1 = 1 AND A.Core = 1 AND A.Unit_Code NOT IN (". $added_units .")
						AND A.Major_ID = '" . $_SESSION['major_selection'] . "' AND C.Campus_ID = '". $_SESSION['studyMode'] . "' Order by A.Unit_Code, A.Core desc";
					$sql = $con->query($query);
					if ($sql->num_rows > 0) { 
						$max_count = 1;
						while($row = $sql->fetch_assoc()) {
							// Fetch Level/Year from the Unit Code
							$unit_year = substr($row["Unit_Code"], 3, 1);
							if($unit_year == $i)
							{
								// Maximum 4 column/units per Trimester
								if($max_count <= 4)
								{
									// Check if Core Unit and not allowing Drag and Drop
									if($row["Core"] == '1') {
										echo '<td class="core"><div id="'. $row["Unit_Code"] . '">'. $row["Unit_Code"]. ' - ' . $row["Unit_Title"] . '</div></td>';
										if($added_units == '')
											$added_units .= "'".$row["Unit_Code"]."'";
										else
											$added_units .= ', ' . "'".$row["Unit_Code"]."'";
									}
								}
								$max_count++;
							}
						}
						while($max_count <= 4)
						{
							// Add Empty Column for electives
							echo '<td class="drop"></td>';
							$max_count++;
						}
					}
					?>
				</tr>
				<tr>
					<td class="T1">T2</td>
					<?php 
					// Get Unit Information for Trimester 2
					$query = "SELECT A.Unit_Code, B.Unit_Title, A.Core, C.Trimester1, C.Trimester2, A.Year FROM `major_units` A 
						LEFT OUTER JOIN units B on A.Unit_Code = B.Unit_Code
						LEFT OUTER JOIN availabilities C on A.Unit_Code = C.Unit_Code
						Where C.Trimester2 = 1 AND A.Core = 1 AND A.Unit_Code NOT IN (". $added_units .")
						AND A.Major_ID = '" . $_SESSION['major_selection'] . "' AND C.Campus_ID = '". $_SESSION['studyMode'] . "' Order by A.Unit_Code, A.Core desc";
					$sql = $con->query($query);
					if ($sql->num_rows > 0) { 
						$max_count = 1;
						while($row = $sql->fetch_assoc()) {
							// Fetch Level/Year from the Unit Code
							$unit_year = substr($row["Unit_Code"], 3, 1);
							if($unit_year == $i)
							{
								// Maximum 4 column/units per Trimester
								if($max_count <= 4)
								{
									// Check if Core Unit and not allowing Drag and Drop
									if($row["Core"] == '1') {
										echo '<td class="core"><div id="'. $row["Unit_Code"] . '">'. $row["Unit_Code"]. ' - ' . $row["Unit_Title"] . '</div></td>';
										if($added_units == '')
											$added_units .= "'".$row["Unit_Code"]."'";
										else
											$added_units .= ', ' . "'".$row["Unit_Code"]."'";
									}
								}
								$max_count++;
							}
						}
						while($max_count <= 4)
						{
							// Add Empty Column for electives
							echo '<td class="drop"></td>';
							$max_count++;
						}
					}
					?>
				</tr>
			<?php
			}
			?>
			</table>
			<!-- Store core units that are already added in the template -->
			<?php $_SESSION['added_units'] = $added_units; ?>
		</div>
		<img class="bin" src="images/trash5.png" />
		<br/>
		<p>Click and drag a unit in trash to remove from Planner.</p>
		<div class="button-right">
			<input type="button" id="btnSave" name="btnSave" value="SAVE" />
			<input type="button" id="btnPrint" name="btnPrint" value="PRINT" />
		</div>
	</div>
	
</div>

<script>
	// Display the unit detail information in a new window
	function newWindow(unit) {
		//alert($(unit).attr("id"));
		//window.open("cinfoviewer.php/?code="+unit.innerHTML, "", "menubar=0, resizable=0,dependent=0,status=0,width=660,height=700,left=10,top=10"); 
		window.open("cinfoviewer.php/?code="+$(unit).attr("id"), "", "menubar=0, resizable=0,dependent=0,status=0,width=660,height=700,left=10,top=10"); 

		return false;
	} 
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
				//item_dropped();
				var arr= [];
				var row = 0;
				var col = 0;
				var oddrow = true;
				var count = 0;
				var curObj = $(this);
				var dropLocX;
				$(".right table tr").each(function() {
					arr[row] = [];
					$(this).children().each(function() {
						if (((oddrow && col > 1 && col <= 5) || (!oddrow && col > 0 && col <= 4)) && $(this).text().length > 0) {
							arr[row][oddrow ? col - 2 : col - 1] = $(this).text().substring(0, 6);
							count++;
						}
						if (curObj.is($(this))) {
							dropLocX = row;
						}
						col++;
					});
					col = 0;
					row++;
					oddrow = !oddrow;
				});
				 $.ajax({
					   type: "POST",
					   url: "validation/validate.php",
					   data: {table : JSON.stringify(arr), unit_code : $(source).attr("id"), row : dropLocX},
					   success: function( msg ){
						if (msg == "valid") {
							$("#valid").text("TRUE");
						} else {
							$("#valid").text("FALSE");
						}
					   },
					   error: function()
					   {
						 alert("Something went wrong!");
					   }
				});
				$(this).removeClass('over');
				if ($(source).hasClass('assigned')){
					if($(this).has('.item.assigned').length == 0) {
						$(this).append(source);
					}
				} else {
					if($("#template").find("div#" + $(source).attr("id")).length == 0) {
						var c = $(source).clone().addClass('assigned');
						$(this).empty().append(c);
						c.draggable({
							revert:true
						});
					}
					else
						alert('Unit already exists in the template.');
				}
			}
				
		});
		function item_dropped() {
			alert('test');
		}
		$('.bin').droppable({
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