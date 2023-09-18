$(document).ready(function(){
	var dateFr = { altField: "#datepicker",
                      closeText: 'Fermer',
                      prevText: 'Précédent',
                      nextText: 'Suivant',
                      currentText: 'Aujourd\'hui',
                      monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                      monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
                      dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                      dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                      dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                      weekHeader: 'Sem.',
                      duration: "fast",
                      dateFormat: "dd/mm/yy"};
	var uri = window.location.toString();
	if (uri.indexOf("?") > 0) {
	    var clean_uri = uri.substring(0, uri.indexOf("?"));
	    window.history.replaceState({}, document.title, clean_uri);
	}

	$("#search-type").click(function(){
	if ($("#search-type").val() == "month") {
		$('#block-mois').show();
		dateFr.dateFormat = "MM yy";
		jQuery('#mois').datepicker(
	      dateFr
	    );		
	} else {
		$('#block-mois').hide();
	}
	if ($("#search-type").val() == "year") {
		$('#block-annee').show();		
	} else {
		$('#block-annee').hide();
	}
	if ($("#search-type").val() == "twoDates") {
		$('#block-deuxDates').show();
		dateFr.dateFormat = "dd/mm/yy";
		jQuery( "#date1" ).datepicker(
	      dateFr
	    );
	    jQuery( "#date2" ).datepicker(
	      dateFr
	    );		
	} else {
		$('#block-deuxDates').hide();
	}
	});

	$("#link-search-month").click(function(){
		if ($("#mois").val() != "") {
			window.location.href += "?month=" + $("#mois").val();
		} 	
	});

	$("#link-search-year").click(function(){
		if (/^[0-9]{4}$/.test($("#annee").val())) {
			window.location.href += "?year=" + $("#annee").val();
		} else {
			$('#annee-message').html('<ul><li>Année no valide *</li></ul>');
		}
	});

	$("#link-search-twoDates").click(function(){
		if ($('#date1').val() != "" && $('#date2').val() != "" && ($('#date1').val() < $('#date2').val())) {
			window.location.href += "?date1=" + $("#date1").val() + "&date2=" + $("#date2").val();
		} else {
			$('#date-message').html('<ul><li>Veuillez renseigner bien les 2 dates</li></ul>');
		}
	});
});
