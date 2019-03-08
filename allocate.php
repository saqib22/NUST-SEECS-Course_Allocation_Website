<?php

	$servername = 'localhost';
	$username = 'root';
	$password = '193764825s';
	$dbname = "fall_allocation";
	$instructors = [];
	$preferences = [];
	$courses_bscs= [];
	$courses_bese = [];
	$sec_names = ['A','B','C'];
	$course_bscs_credits = array();
	$course_bese_credits = array();
	$load = array();
	$csAssignedSecs = array();
	$seAssignedSecs = array();

	$conn = new mysqli($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection failed" . $conn->connect_error);
	}
	
	$sql = "SELECT * FROM instructor";
	$results = $conn->query($sql);

	if ($results->num_rows > 0) {
    	while($row = $results->fetch_assoc()) {
     	   array_push($instructors, $row["name"].'-'.$row["preferences"]);
    	}
	} 
	
	$sql = "SELECT * FROM bscs_fall_courses";
	$results = $conn->query($sql);
	if ($results->num_rows > 0) {
		while ($row = $results->fetch_assoc()) {
			array_push($courses_bscs, $row["name"]);
			$course_bscs_credits[$row["name"]] = $row["credit_hrs"];
		}
	}

	$sql = "SELECT * FROM bese_fall_courses";
	$results = $conn->query($sql);
	if ($results->num_rows > 0) {
		while ($row = $results->fetch_assoc()) {
			array_push($courses_bese, $row["name"]);
			$course_bese_credits[$row["name"]] = $row["credit_hrs"];
		}
	}

	$shuffled = myshuffle($instructors);
	$ins = $shuffled[0];
	$preferences = $shuffled[1];
	

	for ($i=0; $i < count($instructors) ; $i++) {
		$instructor = $ins[$i]; 
		$load[$instructor] = 0;
	}

	for ($i=0; $i < count($courses_bscs) ; $i++) { 
		$course = $courses_bscs[$i];
		$csAssignedSecs[$course] = 3;
	}

	for ($i=0; $i < count($courses_bese) ; $i++) {
		$course = $courses_bese[$i]; 
		$seAssignedSecs[$course] = 2;
	}
	

	$cs_sec = 2;
	$se_sec = 1;
	$sec = 0;
	$cs_allocation = [];
	$se_allocation = [];
	$unassigned_cs = [];
	$unassigned_se = [];

	while (1) {
		if($sec <= $cs_sec){
			$a = allocate($courses_bscs, $ins, $preferences, $sec_names[$sec], $course_bscs_credits, $load, $cs_allocation,'cs');
			$cs_allocation = $a[0];
			$load = $a[1];
		}
		if($sec <= $se_sec){
			$a = allocate($courses_bese, $ins, $preferences, $sec_names[$sec], $course_bese_credits, $load, $se_allocation,'se');
			$se_allocation = $a[0];
			$load = $a[1];
		}
		$sec++;

		if($sec > $cs_sec && $sec > $se_sec){
			break;
		}
		$shuffled = myshuffle($instructors);
		$ins = $shuffled[0];
		$preferences = $shuffled[1];
	}

	//var_dump($load);
	//var_dump($cs_allocation);
	//var_dump($se_allocation);


	function myshuffle($all_ins){
		$pre = [];
		shuffle($all_ins);
		for ($i=0; $i < count($all_ins) ; $i++) { 
			$pieces = explode('-', $all_ins[$i]);
			$all_ins[$i] = $pieces[0];
			$pre[$i] = $pieces[1];
		}
		return array($all_ins, $pre);
	}


	function allocate($courses, $instructors, $preferences, $section, $credits, $load, $prev_allocation, $degree){
		$pref_num = 0;
		$allocated = [];
		$next_courses = $courses;
		$prefs = [];
		$t_names = [];

		while(1) {

			$next_preferences = get_next_preferences($preferences,$instructors, $pref_num);
			$prefs = $next_preferences[0];
			$t_names = $next_preferences[1];
			
			if (empty($prefs)) {
				break;
			}
			
			$allocation = allocate_on_pref($next_courses, $t_names, $prefs, $section, $credits, $load, $prev_allocation,$degree);
			
			array_push($allocated, $allocation[0]);
			$next_courses = $allocation[1];
			$prev_allocation = array_merge($prev_allocation,$allocation[0]);
			$load = $allocation[2];
			$pref_num++;
			$prefs = [];
			$t_names = [];
			
			
		}
		return array($prev_allocation,$load);
	}


	function get_next_preferences($completeList,$instructors, $rank){
		$pre = [];
		$ins = [];
		for ($i=0; $i < count($completeList) ; $i++) { 
			$p = explode(',', $completeList[$i]);
			if (isset($p[$rank])) {
				array_push($pre, $p[$rank]);
				array_push($ins, $instructors[$i]);
			}
		}
		return array($pre,$ins);
	}


	function allocate_on_pref($courses, $ins, $preferences, $section, $credits, $load, $prev_allocation, $degree){
		$assigned = $prev_allocation;
		global $unassigned_cs;
		global $unassigned_se;

		if ($degree == 'cs') {
			$unassigned = $unassigned_cs;
		}else{
			$unassigned = $unassigned_se;
		}

		
		$load = $load;
		$alloted_this_sec = [];

		for ($i=0; $i < count($courses); $i++) { 
			$course = $courses[$i];
			$alloted_this_sec[$course] = false;
		}

		for ($i=0; $i < count($courses) ; $i++) { 
			$course = $courses[$i];
			for ($j=0; $j < count($ins) ; $j++) { 
				$instructor = $ins[$j];
			

					if($preferences[$j] == $course && $load[$instructor] <= 10 && $alloted_this_sec[$course] == false ){
						$load[$instructor] += $credits[$course];
						if (isset($assigned[$course])) {
							$assigned[$course] = $assigned[$course] . $instructor . " (".$section.") ". ", ";
							$alloted_this_sec[$course] = true;
						}else{
							$assigned[$course] = $instructor." (" .$section.") ". ", ";
							$alloted_this_sec[$course] = true;
						}
					}
				}
				if (!isset($assigned[$course])) { 
					array_push($unassigned, $course);
					$unassigned = array_unique($unassigned); 
				}	
			}
			if ($degree == 'cs') {
				$unassigned_cs = array_values($unassigned);
				return array($assigned, $unassigned_cs, $load);
			}else{
				$unassigned_se = array_values($unassigned);
				return array($assigned, $unassigned_se, $load);
			}
			
		
	}

	file_put_contents('cs_alloc.json', json_encode($cs_allocation));
	file_put_contents("se_alloc.json", json_encode($se_allocation));
	file_put_contents("instructor_load.json", json_encode($load));


?>