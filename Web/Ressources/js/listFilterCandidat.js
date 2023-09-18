	const FILTER_GROUP_ALL     		= 1;
	const FILTER_GROUP_POSTE   		= 2;
	const FILTER_GROUP_CANDIDAT 	= 3;
	const FILTER_GROUP_OFFRE 		= 4;
	const FILTER_GROUP_DOMAIN 		= 5;
	const FILTER_GROUP_SUB_DOMAIN 	= 6;
	const MAX_INTERVAL         		= 3;
	const TODAY      				= 1;
	const TOMORROW   				= 2;
	const YESTERDAY  				= 3;
	const THIS_WEEK  				= 4;
	const NEXT_WEEK  				= 5;
	const LAST_WEEK  				= 6;
	const THIS_MONTH 				= 7;
	const NEXT_MONTH 				= 8;
	const LAST_MONTH 				= 9;
	const FILTER_STATUS_ALL			= 0;
	const FILTER_STATUS_WAIT		= 1;
	const FILTER_STATUS_VALID		= 2;
	const FILTER_STATUS_REFUSE		= 3;
	const FILTER_STATUS_REJECT		= 4;
	const FILTER_STATUS_SEND		= 5;

	var hostname 					= new URL(window.location.href).hostname;
	const HOST						= 'http://' + hostname + '/';
		$('document').ready(function(){
			if ($('#filter-group').val() == FILTER_GROUP_ALL) {
				$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
				groupe    : $('#filter-group').val(),
				id        : $('#filter-element').val(),
				periode   : $('#periode').val(),
				debut     : $('#debut').val(),
				fin       : $('#fin').val(),
				statut    : $('#filter-status').val()
			}, function() {
				$('.option-tout').removeClass('invisible');
					$('.option-domaine').addClass('invisible');
					$('.option-subDomaine').addClass('invisible');
					$('.option-offre').addClass('invisible');
					$('.option-poste').addClass('invisible');
					$('.option-candidat').addClass('invisible');
					$('#filter-element').val("");
					$('#filter-element').attr("disabled", true);
			});
			}
			$('.carousel').carousel({
				interval: false,
			});
			$('.carousel').carousel('pause');
		});
		$('#filter-group').change(function(){
			if ($('#filter-group').val() == FILTER_GROUP_ALL) {
				$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
				groupe    : $('#filter-group').val(),
				id        : $('#filter-element').val(),
				periode   : $('#periode').val(),
				debut     : $('#debut').val(),
				fin       : $('#fin').val(),
				statut    : $('#filter-status').val()
			}, function() {
				$('.option-tout').removeClass('invisible');
					$('.option-domaine').addClass('invisible');
					$('.option-subDomaine').addClass('invisible');
					$('.option-offre').addClass('invisible');
					$('.option-poste').addClass('invisible');
					$('.option-candidat').addClass('invisible');
					$('#filter-element').val("");
					$('#filter-element').attr("disabled", true);
			});
			} else if ($('#filter-group').val() == FILTER_GROUP_OFFRE) {
				$('.option-tout').addClass('invisible');
				$('.option-domaine').addClass('invisible');
				$('.option-subDomaine').addClass('invisible');
				$('.option-offre').removeClass('invisible');
				$('.option-poste').addClass('invisible');
				$('.option-candidat').addClass('invisible');
				$('#filter-element').attr("disabled", false);
				$('#filter-element').val("");
			} else if ($('#filter-group').val() == FILTER_GROUP_POSTE) {
				$('.option-tout').addClass('invisible');
				$('.option-domaine').addClass('invisible');
				$('.option-subDomaine').addClass('invisible');
					$('.option-offre').addClass('invisible');
				$('.option-poste').removeClass('invisible');
				$('.option-candidat').addClass('invisible');
				$('#filter-element').attr("disabled", false);
				$('#filter-element').val("");
			} else if ($('#filter-group').val() == FILTER_GROUP_CANDIDAT) {
				$('.option-tout').addClass('invisible');
				$('.option-domaine').addClass('invisible');
				$('.option-subDomaine').addClass('invisible');
					$('.option-offre').addClass('invisible');
				$('.option-poste').addClass('invisible');
				$('.option-candidat').removeClass('invisible');
				$('#filter-element').attr("disabled", false);
				$('#filter-element').val("");
			} else if ($('#filter-group').val() == FILTER_GROUP_DOMAIN) {
				$('.option-tout').addClass('invisible');
				$('.option-domaine').removeClass('invisible');
				$('.option-subDomaine').addClass('invisible');
					$('.option-offre').addClass('invisible');
				$('.option-poste').addClass('invisible');
				$('.option-candidat').addClass('invisible');
				$('#filter-element').attr("disabled", false);
				$('#filter-element').val("");
			} else if ($('#filter-group').val() == FILTER_GROUP_SUB_DOMAIN) {
				$('.option-tout').addClass('invisible');
				$('.option-domaine').addClass('invisible');
				$('.option-subDomaine').removeClass('invisible');
					$('.option-offre').addClass('invisible');
				$('.option-poste').addClass('invisible');
				$('.option-candidat').addClass('invisible');
				$('#filter-element').attr("disabled", false);
				$('#filter-element').val("");
			}
		});

		$('#filter-element').change(function() {
			$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
			groupe    : $('#filter-group').val(),
			id        : $('#filter-element').val(),
			periode   : $('#periode').val(),
			debut     : $('#debut').val(),
			fin       : $('#fin').val(),
			statut    : $('#filter-status').val()
		});
		});

		$('#periode').change(function() {
		$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
			groupe    : $('#filter-group').val(),
			id        : $('#filter-element').val(),
			periode   : $('#periode').val(),
			statut    : $('#filter-status').val()
		}, function() {
			$("#debut").val(null);
			$("#fin").val(null);
			$('#selection-month').val("");
		});
	});

	$('#debut').change(function() {
		$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
			groupe    : $('#filter-group').val(),
			id        : $('#filter-element').val(),
			debut     : $('#debut').val(),
			fin       : $('#fin').val(),
			statut    : $('#filter-status').val()
		}, function() {
			$("#periode").val("");
			$('#selection-month').val("");
			$('#fin').val("");
			var stringDate = $('#debut').datepicker('getDate');
			var date       = new Date(stringDate.getFullYear(), stringDate.getMonth() + MAX_INTERVAL, stringDate.getDate());
		    $('#fin').datepicker('option', 'maxDate', date);
		});
	});

	$('#fin').change(function() {
		$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
			groupe    : $('#filter-group').val(),
			id        : $('#filter-element').val(),
			debut     : $('#debut').val(),
			fin       : $('#fin').val(),
			statut    : $('#filter-status').val()
		}, function() {
			$("#periode").val("");
			$('#selection-month').val("");
		});
	});

	$('#selection-month').change(function() {
		$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
			groupe    : $('#filter-group').val(),
			id        : $('#filter-element').val(),
			mois      : $('#selection-month').val(),
			statut    : $('#filter-status').val()
		}, function() {
			$("#periode").val("");
			$('#debut').val(null);
			$('#fin').val(null);
		});
	});

	$('#filter-status').change(function() {
		$("#liste-candidature").load(HOST + "manage/candidatures_entreprise", {
			groupe    : $('#filter-group').val(),
			id        : $('#filter-element').val(),
			debut     : $('#debut').val(),
			fin       : $('#fin').val(),
			periode   : $('#periode').val(),
			mois      : $('#selection-month').val(),
			statut    : $('#filter-status').val()
		});
	});

$(window).on('load', function(){
	tippy('.fa-eye', {
		content:"Voir détails"
	});
	tippy('.fa-check', {
		content:"Accepter"
	});
	tippy('.fa-times', {
		content:"Refuser"
	});
	tippy('.fa-trash', {
		content:"Archiver"
	});
	$('document').ready(function () {
		$('.archivedModal').click(function () {
			$('#action-archive').attr('href',$(this).attr('data-url'));
		});
	});
	$('.carousel-inner>.carousel-item>.form-control').css('display', 'unset'); // Ajuster la carousel inline de la datepicker fin
});