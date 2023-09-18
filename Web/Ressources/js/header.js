$(document).ready(function(){
	var divAlert = document.getElementById("alert");
	if (typeof(divAlert) != "undefined" && divAlert != null) {
		setTimeout(function(){
			$('#alert').hide();
		}, 5000);
	} 	
	// functionFiltre();
	$(".customPagination").click(function(){
		keepHeight();
	});
	keepHeight();
});

/**@changelog Lansky [EVOL] 07/06/2022 Ajouter un titre d'un onglet */
$(window).on('load', function(){
	if (!$('title').text().trim()) {
		if ($('.list-title.section-heading').text().trim()) {
			$('title').text($('.list-title.section-heading').text().trim());
		} else if ($('#titre').text().trim()) {
			$('title').text($('#titre').text().trim());

		}
	}
	/**@changelog Lansky [EVOL] 31/08/2022 Flouter les menus qui n'ont pas aux utilisateurs */
	var urlOrigin = new URL(window.location).origin;
	$.each($('.lien-menu'), function() {
	 	currentLink = $(this).attr('href').slice(urlOrigin.length + 1);
	 	if (currentLink.slice(0,3) === '/#/') {
	 		$(this).css('opacity', 0.3);
	 	}
	});
});

$(function () { 
	var validationSelection = $('.form-group:visible').find('input,select,textarea');
	if ($(validationSelection).not("[type=submit]").length > 0) {
		$(validationSelection).not("[type=submit]").jqBootstrapValidation(); 
	}
});

function keepHeight() {
	var fenetre = $(window).height(),
		html			=	$('body').height(),
		headerHeight	=	$('nav').height(),
		footerHeight	=	$('footer').height();

	if (fenetre > html) {
		$('.page-section').css('min-height', fenetre - (headerHeight + footerHeight) );
	}
} 

function notifier(type, message) {
	$('#alert').addClass('alert-' + type);
	$('#alert').html(message);
	$('#alert').show();
	setTimeout(function(){
		$('#alert').hide();
	}, 5000);
}