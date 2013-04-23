/////////////// Checkbox handling ////////////////////
//////////////Check all checkboxes //////////////////
function checkAll(){
	var boxes = document.getElementsByTagName('input');
	var allBox = document.getElementById('all');
	for(i=0;i<boxes.length;i++){
			if(allBox.checked == true && boxes[i].type == 'checkbox'){
			boxes[i].checked = true;
		}else{
			if(boxes[i].type == 'checkbox' && boxes[i].type != 'radio'){
			boxes[i].checked = false;
			}
		}
	}
}
///// Gather checked boxes values ////////////////////
function boxValues(){
	var selected = new Array();
	var boxes = document.getElementsByTagName('input');
	for(i=0;i<boxes.length;i++){
		if(boxes[i].checked == true && boxes[i].type == "checkbox" && boxes[i].id != 'all'){
			var val = boxes[i].value;
			selected.push(val);
			var newString = selected.toString();
		}
	}/// end for
	return newString;
}/////////////// End checkbox handling ///////////////////////////
///// Serialize form data for sending to the server /////////////
function serialize(el,radios,checkboxes,meth,url){
	var boxVal = "";
	var output = "";
	var target = document.getElementById(el);
	var inputs = target.getElementsByTagName('input');
	var selects = target.getElementsByTagName('select');
	var textareas = target.getElementsByTagName('textarea');
	for(i=0;i<inputs.length;i++){
		////// If there are radio buttons in the form //////////
		if(radios == 'true'){
			if(inputs[i].type == 'radio' && inputs[i].checked == true){
				var radIds = inputs[i].id;
				var radVals = inputs[i].value;
				output += radIds+'='+radVals+'&';
			}
		}////// End if there are radio buttons in the form //////////
			var inputIds = inputs[i].id;
			var inputVals = inputs[i].value;
				if(inputs[i].type != 'radio' && inputs[i].type != 'checkbox'){
					output += inputIds+'='+inputVals+'&';
		}
		/////// If there are checkboxes in the form //////////////////
		if(checkboxes == 'true'){
			if(inputs[i].checked == true && inputs[i].type == "checkbox" && inputs[i].name != 'all'){
				var bxVal = boxValues();
				}
			}
		}
		/////////// If there are select tags in the form ////////////
		if(selects.length > 0){
			for(i=0;i<selects.length;i++){
				var selectIds = selects[i].id;
				var selectVals = selects[i].value;
				output += selectIds+'='+selectVals+'&';
			}
		}
		///////// If there are textareas in the form //////////////
		if(textareas.length > 0){
			for(i=0;i<textareas.length;i++){
				var taIds = textareas[i].id;
				var taVals = textareas[i].value;
				output += taIds+'='+taVals+'&';
			}
		}
		//////// Create JSON string to send to PHP if there are checkboxes selected //////////
		if(typeof(bxVal) != "undefined" && typeof(bxVal) == "string"){
			var makeArr = bxVal.split(",");
			boxVal = JSON.stringify(makeArr);
		}
		////// Format string for GET method /////////////////////////	
		if(meth == "GET"){
			var string = "?"+output.slice(0,-1)+"&";
			return string.slice(0,-1);
		}////// End format string for GET method //////////////////
			else{
		//// If no checkboxes selected or no checkboxes are in the form /////////////
				if(checkboxes == 'false'){
					return output.slice(0,-1);
				}
					else{
					return "cbs="+boxVal+"&"+output.slice(0,-1);
				}
		}
}