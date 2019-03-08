$(document).ready(function(){

	'use strict';


	$('.message a').click(function(){
   		$('form').animate({height: "toggle", opacity: "toggle"}, "slow");
	});


	/*********Form Validation************/

	$('.login-page button').click(function(){
		if($(this).siblings(".mail").val() != ""){
			var result = /\S+@\S+\.\S+/.test($(this).siblings(".mail").val());
			if (result == false) {
				$(this).siblings("span").css("display", "inline-block");
				$(this).siblings(".mail").css("border-color", "red");
			}else{
				$(this).siblings("span").css("display", "none");
				$(this).siblings(".mail").css("border-color", "white");
				alert("Entered");
				window.location.href = "form_data.html";
			}
		}
	});

	$('.login-page button').click(function(){
		var pass1 = $("#pass1");
		var pass2 = $("#cpass");

		if(pass1.val() != pass2.val()){
			$(this).siblings("#pass").css("display","inline-block");
		}else{
			$(this).siblings("#pass").css("display","none");
		}
	});

	$(".addI").click(function(){
		$(".delIns").hide(200);
		$(".addIns").toggle(200);
	});
	
	$(".delI").click(function(){
		$(".addIns").hide(200);
		$(".delIns").toggle(200);
	});

	$(".addC").click(function(){
		$(".delCourse").hide(200);
		$(".addCourse").toggle(200);
	});
	
	$(".delC").click(function(){
		$(".addCourse").hide(200);
		$(".delCourse").toggle(200);
	});

});

function showLoad(){
	xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("load").querySelector('.container-fluid').innerHTML = this.responseText;

		}
	};
	xmlhttp.open("GET", "http://localhost/Course_Allocation/showInsLoad.php", true);
	xmlhttp.send();
	
}


function changeTable(item){
	xmlhttp = new XMLHttpRequest();

	var semester = item.innerHTML;
	var degree = window.localStorage.getItem("degree");

	xmlhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200) {
			var id = item.getAttribute("href");
			document.getElementById(id.substr(1)).querySelector('.container-fluid').innerHTML = this.responseText;

		}
	};
	xmlhttp.open("GET", "http://localhost/Course_Allocation/showOutput.php?semester="+semester+"&degree="+degree, true);
	xmlhttp.send();
}


function clearfiles(){
	console.log("Closed");
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", "http://localhost/Course_Allocation/deleteAllocation.php", true);
	xmlhttp.send();
}

function onshow(){
	xmlhttp = new XMLHttpRequest();
	var semester = document.getElementById("1").innerHTML;
	var degree = window.localStorage.getItem("degree");
	xmlhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200){
			var firsttab = document.getElementById("1").getAttribute("href");
			document.getElementById(firsttab.substr(1)).querySelector('.container-fluid').innerHTML = this.responseText;
		}
	}
	
	xmlhttp.open("GET", "http://localhost/Course_Allocation/showOutput.php?semester="+semester+"&degree="+degree, true);
	xmlhttp.send();
}


function ongenerate(){

	var season = document.getElementById("season");
	var degree = document.getElementById("degree");

	if (degree.value != 'cs' && degree.value != 'se') {
		alert("Please select a valid Degree program");
	}
	else if(season.value != 'spring' && season.value != 'fall'){
		alert("Please select a Season");
	}
	else{
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET", "http://localhost/Course_Allocation/allocate.php", true);
		xmlhttp.send();
		document.location.href = "results.html";
	}
}

function setDegree(){
	degree = document.getElementById("degree").value;
	window.localStorage.setItem("degree",degree);
}