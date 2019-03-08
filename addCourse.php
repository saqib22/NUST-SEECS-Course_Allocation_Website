<?php
	
	$code = $_GET["code"];
	$name =  $_GET["name"];
	$degree = $_GET["deg"];
	$semester = $_GET["semester"];
	$credits = $_GET["credits"];

	var_dump($_GET);

	$conn = new mysqli("localhost", "root", "193764825s", "fall_allocation");
	if($conn->connect_error){
		die("Connection failed" . $conn->connect_error);
	}

	if ($degree == 'BSCS') {
		$query = "Insert Into bscs_fall_courses Values('"."$code"."','"."$name"."','"."Fall"."',"."$credits".",'"."$degree"."',"."$semester".")";
		if ($conn->query($query) == TRUE)
    		echo "New record created successfully";
	}elseif ($degree == 'BESE') {
		$query = "Insert Into bese_fall_courses Values('"."$code"."','"."$name"."','"."Fall"."',"."$credits".",'"."$degree"."',"."$semester".")";
		if ($conn->query($query) == TRUE)
    		echo "New record created successfully";
	}



?>