function ajax(el, meth, url, status){
	var hr = new XMLHttpRequest();
		var vars = serialize(el,'true','true',meth,url);
		if(meth == "GET"){
			hr.open(meth, url+vars, true);
			hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		}else{
			hr.open(meth, url, true);
			hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		}
			hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200){ 
				document.getElementById(status).innerHTML = hr.responseText;
			}
		}
		if(meth == "POST"){
			hr.send(vars);
		}
		else{
			hr.send();
		}
}