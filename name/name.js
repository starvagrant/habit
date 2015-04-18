var name = $( "input" ).attr( "name");
console.log(name);
$( "input" ).attr("name" , function( arr ) {
	return "food-entry" + arr;
});	
var newName = $( "input" ).attr( "name");
console.log(newName);

