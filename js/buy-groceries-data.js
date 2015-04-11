$(':header').addClass('headline');

$('li#submit-button').hide().fadeIn(1500);
/* $('li:not("#submit-button")').hide().fadeIn(1500); 
$('li').not('#submit-button').hide().fadeIn(1500);
*/

$('li').not('#submit-button').on('click', function() {
  $(this).remove();
});
