// to do: there should be a button that adds input fields #adder
	// would like said input button to be hidden
	// said button should add a list item to the grocery list.
	// this should have the properties of other li input items: 
	// readonly, and be removed by a jQuery function.  
// below needs more comments 
$(':header').addClass('headline');
var addInput = $('#adder');
addInput.hide();
$('#add-button').on('click', function() {
	addButton.show();	
}

addInput.on('blur', function() {
	// addInput already a jquery object
	// create a new Jquery list item
	var initValue = this.attr('value'); 		// get the value in the form
	var $listItem = $('<li><input type="text" value="' + initValue + '" /></li>');
	var lastField = $('li').not('#submit-button').last();
	lastField.after($listItem);
//	this.parent();
/*	
	var listNode = document.createElement('li');
	var inputNode = document.createElement('input');
	inputNode.
	ListItem.
*/
}

// $('li input').hide().fadeIn(700);
/* $('li:not("#submit-button")').hide().fadeIn(1500); 
$('li').not('#submit-button').hide().fadeIn(1500);
*/
/*$('li').not('button').on('click', function() {
	$(this).remove();
}
$('#add-button').on('click' , function() {
	$('#adder').show();
}
$('#adder').on('blur', function () {
	var inputField = $(this);
	var listItem = $(this).parent();

	var inputText =	'<li id="four"><input type="text" /></li>'
}
$function
$('#add-button').on('click', function() {
	$(this).parent().append
});
*/
