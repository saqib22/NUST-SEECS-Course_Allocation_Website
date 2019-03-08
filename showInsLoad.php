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

<?php

	$json = file_get_contents('instructor_load.json');
	$loads = json_decode($json, true);	
	$s_no = 1;
	
	
	$conn = new mysqli("localhost", "root", "193764825s", "fall_allocation");
	if($conn->connect_error){
		die("Connection failed" . $conn->connect_error);
	}
	
	$instructors = [];

	$sql = "SELECT * FROM instructor";
	$results = $conn->query($sql);

	if ($results->num_rows > 0) {
    	while($row = $results->fetch_assoc()) {
     	   array_push($instructors, $row["name"]);
    	}
	} 
	
	echo "<table cellspacing='10' >";
	echo "<thead>";
	echo "<th>S/no</th>";
	echo "<th>Intructor Name</th>";
	echo "<th>Total Credit Hours Teaching</th>";
	echo "</thead>";
	

	for ($i=0; $i < count($loads); $i++) { 
		echo "<tr>";
		$instructor = $instructors[$i];
		
		echo "<td>". $s_no . "</td>";
		echo "<td>" .$instructor."</td>	" ;
		if (isset($loads[$instructor])) {
			echo "<td>" . $loads[$instructor] . "</td>";
		}else{
			echo "<td>N/A</td>";
		}
		$s_no++;
		
	echo "</tr>";
	}
	





?>