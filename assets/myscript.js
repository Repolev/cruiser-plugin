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

// Automatically check the hidden checkbox with button
 function checkedFunction( elId ){
 	var checkbox = document.getElementById('topic_checkbox_value-' + elId);
 	checkbox.checked = true;
}


// Javascript and jQuery with Ajax to Pick the topics outside of the page
function pickFunction(elId){
	var pickButton = document.getElementById("pickButton-" + elId);
	pickButton.classList.remove('btn-info');
	pickButton.classList.add('btn-outline-primary');
	pickButton.innerHTML = 'Picked';
	var post_id = elId;
	jQuery(document).ready( function(){         
        jQuery.ajax({
            url  : ajaxurl,
            type : 'post',
            data : {
                action : 'update_post_topic_writer',
                post_id : post_id
            },
            success : function( response ) {
                console.log(response);
            }
        });          
	});
	return false;
}