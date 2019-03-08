<!DOCTYPE html>
<html>
<head>
	<style>
		table {
			margin-top: 20px;
		    width: 100%;
		    border-collapse: collapse;
		}
		table, td, th {
		    border: 1px solid black;
		    padding: 5px;
		}
		th {
			text-align: left;
		}
		thead{
			background-color: #dddddd;
		}
	</style>
</head>
</html>


<?php //header('Access-Control-Allow-Origin: *');

	$semester = $_GET["semester"];
	$degree = $_GET["degree"];
	

	if ($degree == 'cs') {
		$json = file_get_contents('cs_alloc.json');
		$allocation = json_decode($json, true);
	}
	elseif ($degree == 'se') {
		$json = file_get_contents("se_alloc.json");
		$allocation = json_decode($json, true);
	}


	$courses = [];
	$course_codes = array();
	$course_semester = array();
	$credit_hrs = array();
	$s_no = 1;
	
	$conn = new mysqli("localhost", "root", "193764825s", "fall_allocation");
	if($conn->connect_error){
		die("Connection failed" . $conn->connect_error);
	}

	if ($degree == 'cs') {
		$sql = "SELECT * FROM bscs_fall_courses";
		$results = $conn->query($sql);
		if ($results->num_rows > 0) {
			while ($row = $results->fetch_assoc()) {
				array_push($courses, $row["name"]);
				$course_codes[$row["name"]] = $row["code"];
				$course_semester[$row["name"]] = $row["semester"];
				$credits_hrs[$row["name"]] = $row["credit_hrs"];
			}
		}
	}
	elseif($degree == 'se'){
		$sql = "SELECT * FROM bese_fall_courses";
		$results = $conn->query($sql);
		if ($results->num_rows > 0) {
			while ($row = $results->fetch_assoc()) {
				array_push($courses, $row["name"]);
				$course_codes[$row["name"]] = $row["code"];
				$course_semester[$row["name"]] = $row["semester"];
				$credits_hrs[$row["name"]] = $row["credit_hrs"];
			}
		}
	}

	echo "<table cellspacing='10' >";
	echo "<thead>";
	echo "<th>S/no</th>";
	echo "<th>Course Code</th>";
	echo "<th>Course Name</th>";
	echo "<th>Instructors</th>";
	echo "</thead>";

	for ($i=0; $i < count($courses); $i++) { 
		echo "<tr>";
		$course = $courses[$i];
		
		if ($course_semester[$course] == $semester) {

			echo "<td>". $s_no . "</td>";
			echo "<td>" .$course_codes[$course]."</td>	" ;
			echo "<td>". $courses[$i] . "</td>";
			if (isset($allocation[$course])) {
				echo "<td>" . $allocation[$course] . "</td>";
			}else{
				echo "<td>N/A</td>";
			}
			$s_no++;
		}
	echo "</tr>";
	}
	echo "</table>";


?>