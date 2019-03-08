<?php

	$conn = new mysqli("localhost", "root", "193764825s", "fall_allocation");
	if($conn->connect_error){
		die("Connection failed" . $conn->connect_error);
	}

	$name = $_GET["course_name"];
	$degree = $_GET["removing_degree"];

	if ($degree == 'cs') {
		
			$sql = "delete from bscs_fall_courses where name='"."$name"."'";
			if ($conn->query($sql) === TRUE) {
			    echo "Record deleted successfully";
		}
	}
	elseif($degree == 'se'){

		$sql = "delete from bese_fall_courses where name='"."$name"."'";

		if ($conn->query($sql) === TRUE) {
		    echo "Record deleted successfully";
		}
	}


?>