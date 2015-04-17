
var $groceryList = $('ul#grocery-list');
var $addButton = $('button#add-button');
var $addInput = $('input#adder');
//console.log($groceryList);
//console.log($addButton);
//console.log($addInput);

$groceryList.on('click', 'li', function(){	//	remove an element after it is clicked	
	$(this).remove();
//	console.log('clicked');
});

console.log('yoyoyo');
$addInput.hide();
$addInput.on('blur', function(){
	var value = $(this).val();
	console.log(value);
	var listInput = '<li><input type="text" value="' + value + '" "readonly="readonly" class="default" /></li>';
	console.log(listInput);
	$groceryList.append(listInput);
});
$addButton.on('click', function(e) { e.preventDefault(); $addInput.fadeIn(1000); }); // 
