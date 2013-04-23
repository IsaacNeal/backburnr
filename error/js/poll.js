function list_nums(){
	var x = new XMLHttpRequest();
		x.onreadystatechange = function(){
		if (x.readyState==4 && x.status==200){
			var nums = x.responseText.split(",");
			document.getElementById("count1").innerHTML = nums[0];
			document.getElementById("count2").innerHTML = nums[1];
			document.getElementById("count3").innerHTML = nums[2];
	}
}
	x.open("GET", "getNums.php?t=" + Math.random(), true);
	x.send();
}
function ajax_post(){
// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
// Create some variables we need to send to our PHP file
	var url = "voteAjax.php";
	var radio = document.getElementsByName("radio");
	var vars = "radio="+fv();
	hr.open("POST", url, true);
// Set content type header information for sending url encoded variables in the request
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
	if(hr.readyState == 4 && hr.status == 200) {
		var return_data = hr.responseText;
		document.getElementById("status").innerHTML = return_data;
		document.getElementById("radio1").checked = false;
		document.getElementById("radio2").checked = false;
		document.getElementById("radio3").checked = false;
	}
}
	hr.send(vars); 
	document.getElementById("status").innerHTML = 'processing...';
		var g = new XMLHttpRequest();
		g.onreadystatechange = function(){
		if (g.readyState==4 && g.status==200){
			var nums = g.responseText.split(",");
			document.getElementById("count1").innerHTML = nums[0];
			document.getElementById("count2").innerHTML = nums[1];
			document.getElementById("count3").innerHTML = nums[2];
	}
}
	g.open("GET", "getNums.php?t=" + Math.random(), true);
	g.send();
		
	function fv () {
	var form = document.getElementById("my_form");
	for ( var i = 0; i < form.length; i++ ) {
	if ( form[i].checked ){
	var val = form[i].value;
	return(val);
	  }
	}
  }
}