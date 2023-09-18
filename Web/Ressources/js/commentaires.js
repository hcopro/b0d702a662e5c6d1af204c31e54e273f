$(document).ready(function() {
  	$('#editComment').click(function() {
	    console.log("aaaaaaaa");
	    let card = $(this).closest('.card');
	    let child = $(card).children()[0];
	    console.log($('input[type="hidden"]#idCommentaireEdit', card).val());
	    console.log($('input[type="hidden"]#idCommentaireEdit', card).val());
	    $("#idCommentaire").attr('value', $('input[type="hidden"]#idCommentaireEdit', card).val());
	    $("#addComment").text($('input[type="hidden"]#idCommentaireEdit', card).val());
  	});
});