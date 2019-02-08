/*
	For Ajax Live Search on Subtitle Field
 */




/*
	Script to Copy title function with clickFunction
 */

function copyFunction( elId ) {
	// Make the display block to copy the text field 
	document.getElementById("clickTitle-" + elId).style.display = 'block';

	// Copy the text inside the input field
	var copyText = document.getElementById( "clickTitle-" + elId );
	copyText.select();
	document.execCommand("copy");

	// Make the display none to hide the input field
	document.getElementById("clickTitle-" + elId).style.display = 'none';

	alert("Title Copied: " + copyText.value);

	return false;
}

 function checkedFunction( elId ){
 	var checkbox = document.getElementById('topic_checkbox_value-' + elId);
 	checkbox.checked = true;
}

