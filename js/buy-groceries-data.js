// to do: there should be a button that adds input fields #adder
	// would like said input button to be hidden
	// said button should add a list item to the grocery list.
	// this should have the properties of other li input items: 
	// readonly, and be removed by a jQuery function.  
// below needs more comments 
/*
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
	
}
*/
var $groceryList = $('ul#grocery-list');
var $addButton = $('button#add-button');
var $addInput = $('input#adder');
//console.log($groceryList);
//console.log($addButton);
//console.log($addInput);

$groceryList.on('click', 'li', function(){	//	remove an element after it is clicked	
//	if($(this).hasClass('food-item')) 	$(this).remove();
//	$(this).remove();
	console.log('clicked');
//	var yoyoyo = $(this);
//	var yoyoyo = this;
//	console.log(yoyoyo);
//	console.log($(this));
/*
	var listItemClicked = $(this);
	if (listItemClicked.hasClass('food-item')){
		console.log('food idiot');
	} else {
		console.log('not food idiot');
	}
*/
});

console.log('yoyoyo');
$addInput.hide();
$addInput.on('blur', function(){
	var listInput = '<li><input type="text" disabled="disabled" class="default" /></li>';
	$grocery-list.append(listInput);
});
$addButton.on('click', function(e) { e.preventDefault(); $addInput.fadeIn(1000); }); // 


/*
$('li').not('button').on('click', function() {
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
//$addButton.on('click', function(e) { e.preventDefault(); console.log($addInput); }); // source of problem looks like variable of out scope}
//$addInput.fadeIn(1000);
/*
$addInput.on('blur', function () {		// doesn't work
	console.log( this.val() );
});
*/
/*
$addInput.on('click', function () {
//	console.log( 'yoyoyo' );
//	console.log( this ); // element
//	console.log( $(this) ); // jQuery object
	console.log( $(this).val() ); // form value
});
*/

//$('li input').hide().fadeIn(1500);	// works
//$('button').hide().fadeIn(1000);	// works
//$('li button#submit-button').hide().fadeIn(1000); 	// works, note: child element will not show if parent is hidden
