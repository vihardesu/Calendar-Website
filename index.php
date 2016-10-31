<!DOCTYPE html>
<?php 
session_start();
?>
<html>
<head>
	<title>Calendar</title>
	<!-- for popup box source:http://www.w3schools.com/jquerymobile/tryit.asp?filename=tryjqmob_forms_popup -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://classes.engineering.wustl.edu/cse330/content/calendar.min.js"></script>
</head>
<body>
<button data-role="none" class="ogbtns" id="prev_month_btn">Prev Month</button>
<button data-role="none" class="ogbtns" id="next_month_btn">Next Month</button>
<!-- For login -->
<div id="loginBox">
Login:
<input data-role="none" type="text" id="username" placeholder="Username">
<input data-role="none" type="password" id="password" placeholder="Password">
<button data-role="none" class="ogbtns" id="loginbtn">Login</button>
</div>

<div id="logoutBox">
	<button data-role="none" class="ogbtns" id="logoutbtn">Logout</button>
</div>
<br>
<!-- For Register -->
<div id="regBox">
Register:
<input data-role="none" type="text" id="newUsername" placeholder="Username">
<input data-role="none" type="password" id="newPass" placeholder="Password">
<button data-role="none" class="ogbtns" id="regbtn">Register</button>
</div>
<span id='loginMessage'></span>
<span id="loggedAs"></span>

<br><br>
<div id="currentDate"></div>
<table id="fullCalendar">
	<tr>
		<th>Sun</th>
		<th>Mon</th>
		<th>Tue</th>
		<th>Wed</th>
		<th>Thu</th>
		<th>Fri</th>
		<th>Sat</th>
	</tr>
	<tr id="week0"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr id="week1"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr id="week2"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr id="week3"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr id="week4"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr id="week5"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr id="week6"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>
<button data-role="none" class="ogbtns" id="todaybtn">Today</button>
<br>
Go To Date:
<input data-role="none" type="date" id="jumpToDate">
<button data-role="none" class="ogbtns" id="jumpTobtn">Go</button></div>
<div id="eventResponses">

 <div data-role="main" class="ui-content">
    <a href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all ui-icon-check ui-btn-icon-left">Create Event</a>

    <div data-role="popup" id="myPopup" class="ui-content" style="min-width:250px;">
      
        <div>
          <h3>Create an Event</h3>
          <input type="text" id="eventTitle" placeholder="Title">
          <input type="date" id="eventDate">
          <input type="time" id="eventTime">
         <!-- Create group event:
          <input type="text" id="others" placeholder="Other Username"> -->
          <input type="submit" data-inline="true" id="submitbtn" value="Submit">
        </div>
      
    </div>
  </div>

  <div id="displayedEvents"><h4>This day's events</h4></div>
  <div id="editEventBox">
  	<h2>Edit Event:</h2>
  	<input data-role="none" type="text" placeholder="title" id="editTitle"><br>
  	<input data-role="none" type="date" id="editDate"><br>
  	<input data-role="none" type="time" id="editTime">
  	<button data-role="none" id="editSubmit">Edit</button>
  </div>
<input type="hidden" name="token" value=<?php echo $_SESSION['token'];?> />
<script type="text/javascript">
$("#editEventBox").hide();
//for Login and reg //---------------------------------------------------------------------------
	function loginAjax(event){
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;
		var token = $("input[name=csrf]").val();
		//make url-encoded string for passing post data
		
		var dataString = "username="+encodeURIComponent(username)+"&password="+encodeURIComponent(password)+"&token="+encodeURIComponent(token);

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "login_ajax.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.addEventListener("load", function(event){
			var jsonData = JSON.parse(event.target.responseText);
			if(jsonData.success){
				alert("you're logged in as "+username);
				clearInputs();
				getUser();
			}
			else{
				alert(jsonData.message);
				getUser();
			}
		}, false);
		xhr.send(dataString);
		
	}

	function regAjax(event){
		var newUsername = document.getElementById("newUsername").value;
		var newPass = document.getElementById("newPass").value;
		var token = $("input[name=csrf]").val();
		var dataString = "newUsername="+encodeURIComponent(newUsername)+"&newPass="+encodeURIComponent(newPass)+"&token="+encodeURIComponent(token);

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "reg_ajax.php", true);
		
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.addEventListener("load", function(event){
			var jsonData = JSON.parse(event.target.responseText);
			
			if(jsonData.success){
				alert("Congrats, registered! Now login, silly");
				clearInputs();
			}
			else{
				alert(jsonData.message);
			}
		}, false);
		xhr.send(dataString);
	}
	document.getElementById("loginbtn").addEventListener("click", loginAjax, false);
	document.getElementById("regbtn").addEventListener("click", regAjax, false);

	function clearInputs(){
		var cInputs = document.getElementsByTagName("input");
		for(var i=0; i<cInputs.length; i++){
			cInputs[i].value = "";
		}
	}

	function logoutAjax(event){
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "logout_ajax.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.addEventListener("load", function(event){
			var jsonData = JSON.parse(event.target.responseText);
			if(jsonData.success){
				getUser();
			}
			else{
				alert("I'm gonna pout");
			}
		}, false);
		xhr.send(null);
	}
	document.getElementById("logoutbtn").addEventListener("click", logoutAjax, false);


//---------------------------------------------------------------------------
	//to display user
	function getUser(event){
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "getUsername.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.addEventListener("load", function(event){
		var jData = JSON.parse(event.target.responseText);
		if(jData.success){
			document.getElementById("loggedAs").textContent = "Logged in as: "+jData.username;
		}
		else if(jData.success === false){
			document.getElementById("loggedAs").textContent = "Not Logged In!";
		}
	}, false);
		xhr.send(null);
	}
	document.addEventListener("DOMContentLoaded", getUser, false);
//---------------------------------------------------------------------------
	// For our purposes, we can keep the current month in a variable in the global scope
	var currentMonth = new Month(2016, 9); // October 2016
	var defaultMonth = new Month(2016, 9); // to return to default
	//-----------------------------------------
	updateCalendar();
	//-----------------------------------------
	//display the month and year at the top
	var strMonth = "";
	monthToString(currentMonth.month);
	document.getElementById("currentDate").textContent = strMonth+" "+currentMonth.year;
	//------------------------------------------------------- Prev, Next, and Today buttons
	// Change the month when the "next" button is pressed
	document.getElementById("next_month_btn").addEventListener("click", function(event){
		currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
		updateCalendar(); // Whenever the month is updated, we'll need to re-render the calendar in HTML
		monthToString(currentMonth.month);
		document.getElementById("currentDate").textContent = strMonth+" "+currentMonth.year;
		
		//alert("The new month is "+currentMonth.month+" "+currentMonth.year);
	}, false);
	//Change stuff when "prev" button is pressed
	document.getElementById("prev_month_btn").addEventListener("click", function(event){
		currentMonth = currentMonth.prevMonth();
		updateCalendar();
		monthToString(currentMonth.month);
		document.getElementById("currentDate").textContent = strMonth+" "+currentMonth.year;
		
		//alert("The new month is "+currentMonth.month+" "+currentMonth.year);
	});

	document.getElementById("todaybtn").addEventListener("click", function(event){
		currentMonth = defaultMonth;
		updateCalendar();
		monthToString(currentMonth.month);
		document.getElementById("currentDate").textContent = strMonth+" "+currentMonth.year;

	}, false);

	document.getElementById("jumpTobtn").addEventListener("click", function(event){
		var jumpdate = document.getElementById("jumpToDate").value;
		var d = new Date(jumpdate);
		var jumpmonth = parseInt(d.getMonth());
		var jumpyear = parseInt(d.getFullYear());
		currentMonth = new Month(jumpyear, jumpmonth);

		updateCalendar();
		monthToString(currentMonth.month);
		document.getElementById("currentDate").textContent = strMonth+" "+currentMonth.year;
		document.getElementById("jumpToDate").value = "";
	}, false);

	//---------------------------------------------------------------------------

function updateCalendar(){
	refreshCalendar();
	var weeks = currentMonth.getWeeks();
	var counter = 0;
  
	for(var j=0; j<weeks.length; j++){
		
		var idtracker = "week"+counter;
		var days = weeks[j].getDates();
		// days contains normal JavaScript Date objects.
		for(var i=0; i<7; i++){
			var info = (days[i].getFullYear().toString()+"-"+addZeros(days[i].getMonth()+1)+"-"+addZeros(days[i].getDate())).toString();
			// You can see console.log() output in your JavaScript debugging tool, like Firebug,
			// WebWit Inspector, or Dragonfly.
			document.getElementById(idtracker).innerHTML += "<td class='date' "+info.toString()+" id="+info+">"+days[i].getDate()+"<span id=events"+info+"></span></td>";

			//change background color of different months' days
			if(days[i].getMonth() < currentMonth.month || days[i].getMonth() > currentMonth.month){
				document.getElementById(info).style.backgroundColor="#cbd9ef";
			}
		}
		counter++;
	}
  
}
function addZeros(a){
	if(a<10){
		return "0"+a.toString();
	}
	else{
		return a.toString();
	}
}

//refresh calendar so update can thrown in new dates
function refreshCalendar(){
	for(var i=0; i<7; i++){
		var resetId = "week"+i;
		document.getElementById(resetId).innerHTML = "";
	}
}
//---------------------------------------------------------------------------
//click on a div to add an event, display events
$(document).ready(function(){
		$(document).on("click", ".date",  function(event){
			document.getElementById("displayedEvents").innerHTML = "<h4>This day's events</h4>";
			var token = $("input[name=csrf]").val();

			var tdId = event.target.id;
			var dataString = "tdId="+encodeURIComponent(tdId)+"&token="+encodeURIComponent(token);

			var xhr = new XMLHttpRequest();
			xhr.open("POST", "displayEvents_ajax.php", true);
			
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			xhr.addEventListener("load", function(event){
				var jsonData = JSON.parse(event.target.responseText);
				var count = Object.keys(jsonData).length-1;
					for(var i=0; i<count; i++){
						if(jsonData.success){
							var eventId = jsonData[i].eId;
							var editId = "edit"+jsonData[i].eId;
							var deleteId = "delete"+jsonData[i].eId;
							document.getElementById("displayedEvents").innerHTML += "<div id=de"+eventId+"><h2>"+jsonData[i].title+"</h2> at "+jsonData[i].time+" <button onclick=editEvent("+eventId+") id="+editId+">Edit</button><button onclick=deleteEvent("+eventId+") id="+deleteId+">Delete</button></div>";
							
							
						}
					}
			}, false);
			xhr.send(dataString);
		});
});
function editEvent(id){
  var thisId = id;
	var xhr = new XMLHttpRequest();
	$("#editEventBox").show();
	document.getElementById("editSubmit").addEventListener("click", function(){
		var newTitle = document.getElementById("editTitle").value;
		var newDate = document.getElementById("editDate").value;
		var token = $("input[name=csrf]").val();
		var newTime = document.getElementById("editTime").value;
		var dataString = "id="+encodeURIComponent(thisId)+"&newTitle="+encodeURIComponent(newTitle)+"&newDate="+encodeURIComponent(newDate)+"&newTime="+encodeURIComponent(newTime)+"&token="+encodeURIComponent(token);
		xhr.open("POST", "editEvent_ajax.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.addEventListener("load", function(event){
			
			var jsonData = JSON.parse(event.target.responseText);
			if(jsonData.success){
				var elementId = "de"+id;
				document.getElementById(elementId).innerHTML = "";
				$("#editEventBox").hide();
			}
			else{
				alert(jsonData.message);
			}
		});
		xhr.send(dataString);
	}, false);
}
function deleteEvent(id){
	var xhr = new XMLHttpRequest();
	var token = $("input[name=csrf]").val();
		var dataString = "id="+encodeURIComponent(id)+"&token="+encodeURIComponent(token);
		xhr.open("POST", "deleteEvent_ajax.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.addEventListener("load", function(event){
			
			var jsonData = JSON.parse(event.target.responseText);
			if(jsonData.success){
				var elementId = "de"+id;
				document.getElementById(elementId).innerHTML = "";
			}
		});
		xhr.send(dataString);
}


//-----------------------------------------------------------------
//Create Events
function uploadEvent(event){
		var date = document.getElementById("eventDate").value;
		var title = document.getElementById("eventTitle").value;
		var time = document.getElementById("eventTime").value;
		//var others = document.getElementById("others").value;
		var token = $("input[name=csrf]").val();
		//alert(others);
		//make url-encoded string for passing post data
		
		var dataString = "date="+encodeURIComponent(date)+"&title="+encodeURIComponent(title)+"&time="+encodeURIComponent(time)+"&token="+encodeURIComponent(token);

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "uploadEvent_ajax.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		xhr.addEventListener("load", function(event){

			var jsonData = JSON.parse(event.target.responseText);
			// if(jsonData.groupSuccess){
			// 	alert(jsonData.message);
			// }
			if(jsonData.success){
				alert("Event Successfully Set");
				// alert("Event set for: "+ jsonData.date+ " at: "+jsonData.time);
			}
			else{
				alert("You need to Log in!");
			}
		}, false);
		xhr.send(dataString);
}
document.getElementById("submitbtn").addEventListener("click", uploadEvent, false);
//---------------------------------------
// Display events based on clicked day





//month to string for display
function monthToString(a){
	if(a === 0){
		strMonth = "January";
	}
	else if(a == 1){
		strMonth = "February";
	}
	else if(a == 2){
		strMonth = "March";
	}
	else if(a == 3){
		strMonth = "April";
	}
	else if(a == 4){
		strMonth = "May";
	}
	else if(a == 5){
		strMonth = "June";
	}
	else if(a == 6){
		strMonth = "July";
	}
	else if(a == 7){
		strMonth = "August";
	}
	else if(a == 8){
		strMonth = "September";
	}
	else if(a == 9){
		strMonth = "October";
	}
	else if(a == 10){
		strMonth = "November";
	}
	else if(a == 11){
		strMonth = "December";
	}
}
</script>
</body>
</html>