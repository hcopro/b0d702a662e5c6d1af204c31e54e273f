$(document).ready(function(){
	$(document).on("click", ".cancel", function () {
	    var url  = $(this).data('url');
	    $('.modal-body #text-confirmation').text('Voulez-vous vraiment l\'enregistrement ?');
	    document.getElementById('action-cancel').href = url;
	});
	$('input[type="file"]').change(function(){
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e)
			{
				$("#image").attr("src", e.target.result);
			};
			reader.readAsDataURL(this.files[0]);
		}
	});
	var dateFr = {altField: "#datepicker",
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
	jQuery('.datepicker').datepicker(
	  	dateFr
	);

	$("#autre").click(function(){
		var text = document.createElement("input");
	    text.setAttribute("type", "text"); 
	    text.setAttribute("name", "autreQualite");
	    text.setAttribute("id", "autreQualite");
	    text.setAttribute("class", "form-control focus_activated");
	    text.setAttribute("placeholder", "Votre personnalité *");
	    text.setAttribute("data-validation-regex-regex", "^[a-zA-Z|éèêëôöîïâàùç |'-]*");
	    text.setAttribute("data-validation-regex-message", "Caractère non valide");

		var paragraphe = document.createElement("p");
	    paragraphe.setAttribute("class", "help-block text-danger");

	    document.getElementById("block-personnalite").append(text);
	    document.getElementById("block-personnalite").append(paragraphe);
	});

	var pathname = new URL(window.location.href).pathname;
	if (pathname == "/manage/edit-compte_banque") {
		if ($('#typePaiement').val() == "en espèce" || $('#typePaiement').val() == "") {
			$('#idBanque').attr("disabled", "disabled");
			$('#numeroCompte').attr("disabled", "disabled");
		} else {				
			$('#idBanque').removeAttr("disabled");
			$('#numeroCompte').removeAttr("disabled");
		}
	}
	$('#typePaiement').change(function(){
		if (pathname == "/manage/edit-compte_banque") {
			if ($('#typePaiement').val() == "en espèce"  || $('#typePaiement').val() == "") {
				$('#idBanque').attr("disabled", "disabled");
				$('#numeroCompte').attr("disabled", "disabled");
			} else {				
				$('#idBanque').removeAttr("disabled");
				$('#numeroCompte').removeAttr("disabled");
			}
		} else {
			if ($('#typePaiement').val() == "en espèce"  || $('#typePaiement').val() == "") {
				$('#block-banque').hide();
			} else {
				$('#block-banque').show();
				$('#numeroCompte').keyup(function(){
					var regex = /^([0-9]{5}[\s][0-9]{5}[\s][0-9]{11}[\s][0-9]{2})$/;
				    if($('#numeroCompte').val().match(regex)) {
						$("#numeroCompte-message").html("");
				    } else {
				    	$("#numeroCompte-message").html("<ul><li>23 chiffres \n (00000 00000 00000000000 00)</li><ul>");
				    }
				});
			}
		}		
	}); 

	$("#idBanque").click(function(){
		$('#idBanque-message').html('');
	});
	$("#numeroCompte").click(function(){
		if ($('#numeroCompte').val() == "") {
			$('#numeroCompte-message').html('');
		}
	});

	$('#type').change(function(){
		if ($('#type').val() == "CDI") {
			$('#block-essai').show();
			$('#block-dateDebut').hide();
			$('#block-dateFin').hide();
		} else if ($('#type').val() == "Journalier") {
			$('#block-dateDebut').show();
			$('#block-dateFin').hide();
			$('#block-essai').hide();
		} else {
			$('#block-dateDebut').show();
			$('#block-dateFin').show();
			$('#block-essai').hide();
		}
	});

	$('#essai').click(function(){
		console.log('hello');
	});
	
	/*	$('input[name="addMenu"]').click(function(){
	let resp=[];
		$.each($('input[name="addMenu"]:checked'),function(){ // Récupérer les cases cochées
		 	resp.push($('label[for="'+$(this).attr('id')+'"]').text().trim());
		});
		let autorisationGiven = defaultMenuAutorisation;
		$.each(autorisationGiven.RECRUTEMENT, function(key, value) {
		    autorisationGiven.RECRUTEMENT[key] = $.grep(value, function(item) { // Filtrer les éléments cochés
		        return $.inArray(item.title, resp) !== -1;
		    });
		    if (autorisationGiven.RECRUTEMENT[key].length === 0) {
		        delete autorisationGiven.RECRUTEMENT[key]; // Éliminer les éléments vides
		    }
		});
		let mapping = $.map(autorisationGiven, function(value) {return value;});		
		console.log(autorisationGiven);
		console.log(mapping);

	});*/
	$("#submit").click(function(){
		if (pathname == "/manage/edit-compte_banque") {
			if (!$("#idBanque").prop('disabled') && $('#idBanque').val() == "") {
				$("#idBanque-message").html("<ul><li>Veuillez séléctionner une banque</li><ul>");
				return false;
			}
			if (!$("#numeroCompte").prop('disabled')) {
				var regex = /^([0-9]{5}[\s][0-9]{5}[\s][0-9]{11}[\s][0-9]{2})$/;
			    if(!$('#numeroCompte').val().match(regex) && $('#numeroCompte').val() != "") {
					$("#numeroCompte-message").html("<ul><li>23 chiffres \n (00000 00000 00000000000 00)</li><ul>");
					return false;
			    } else if ($('#numeroCompte').val() == "") {
			    	$("#numeroCompte-message").html("<ul><li>Veuillez entrer un numéro du compte</li><ul>");
			    	return false;
			    }
			}
		} else {
		  	var checkbox   = document.getElementsByName('qualite');
			var input      = document.getElementsByName('autreQualite');
			var perso      = ""; 
			var autrePerso = "";
			for (var i = 0; i < checkbox.length; i++) {
				var check = checkbox[i].checked;
				if (check) {
					perso += checkbox[i].value + "_";
				}
			}
			for (var i = 0; i < input.length; i++) {
				if (input[i].value != "") {
					autrePerso += (input[i].value).charAt(0).toUpperCase() + (input[i].value).slice(1) + "_";
				}
			}
			$("#autrePersonnalite").val(autrePerso);
			var tabPersonnalite    = ( perso + autrePerso).split("_");   
		  	var newTabPersonnalite = tabPersonnalite.filter(function(elem, index, self) {
		      	return index === self.indexOf(elem);
		  	});
			$("#personnalite").val(newTabPersonnalite.toString().replace(/\,/g, '_'));

			if ($('#block-banque:visible').length != 0) {
				if ($('#idBanque').val() == "") {
					$("#idBanque-message").html("<ul><li>Veuillez séléctionner une banque</li><ul>");
					return false;
				}
				if ($('#numeroCompte').val() == "") {
					$("#numeroCompte-message").html("<ul><li>Veuillez entrer un numéro du compte</li><ul>");
					return false;
				}
				if ($('#numeroCompte-message').html() != "") {
					return false;
				}
			}
		}
	    /* @changelog 2023-03-31 [OPTIM] (Lansky) Donner autorisation au menu pour le salarié */
		let resp=[];
		$.each($('input[name="addMenu"]:checked'),function(){ // Récupérer les cases cochées
		 	resp.push($('label[for="'+$(this).attr('id')+'"]').text().trim());
		});
		if (typeof(defaultMenuAutorisation) !== 'undefined') {
			let autorisationGiven = defaultMenuAutorisation;
			$.each(autorisationGiven.RECRUTEMENT, function(key, value) {
			    autorisationGiven.RECRUTEMENT[key] = $.grep(value, function(item) { // Filtrer les éléments cochés
			        return $.inArray(item.title, resp) !== -1;
			    });
			    if (autorisationGiven.RECRUTEMENT[key].length === 0) {
			        delete autorisationGiven.RECRUTEMENT[key]; // Éliminer les éléments vides
			    }
			});
			// let mapping = $.map(autorisationGiven, function(value) {return value;});
			$('#menuAutorised').val(JSON.stringify(autorisationGiven)); // Assigner la variable sérialisée dans l'input menuAutorised
		}
	});

    /* @changelog 2022-05-02 [OPTIM] (Lansky) Ajouter un champ si le salarié est chef et validateur demande de congé[par exemple, etc...] ou non */
    if ($('input[name=isValidator]').length > 0) {
    	$('input[name=isValidator]:checked').val($('input[name=isValidator]:checked').attr('id').slice(-1));
    }
    $('input[name=isValidator]').change(function(){
		$(this).val($(this).attr('id').slice(-1));
	});
    /* @changelog 2023-05-05 [OPTIM] (Lansky) Ajout une fonctionnalité sur un salarié a plus d'un supérieur et le passage de la validation du au chef par service */
    $('.selectpicker').change(function(){
		let id = $(this).attr('id');
		if($('input[name="' + id + '"]').length > 0) {
			$('input[name="' + id + '"]').val('');
			$('input[name="' + id + '"]').val($(this).val().join(','));
		}
	});
	/* @changelog 2023-05-24 [OPTIM] (Lansky) Remplissage automatique le champ salaire en lettre */
	$('.btn.dropdown-toggle').css('color', 'black!important');
    $('#salaire').change(function(){
		$.ajax({
			url : new URL(window.location.href).origin +"/manage/makeNumberToLetter",
			data : "number=" + $(this).val(),
			dataType : "text",
			success : function(string)
			{
				let value = string.replace(/['"]/g, '');
					value = value.charAt(0).toUpperCase() + value.slice(1);
				$('#salaireEnLettre').val(value);
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
	});
});
	
$(window).on('load', $('.selectpicker'), function(event) {
	event.preventDefault();
	// $('.btn.dropdown-toggle').css('color', '!important');
	// $('.btn.dropdown-toggle').css('color', 'rgb(33, 37, 41)');
/*	let id = $(this).attr('id');
	if($('input[name="' + id + '"]').length > 0) {
		$('input[name="' + id + '"]').val('');
		$('input[name="' + id + '"]').val($(this).val().join(','));
	}*/
	/** @changelog 2023-06-05 [FIX] (Lansky) Filtrer les postes par service */
	$('#idEntrepriseService').change(function() {
		$.ajax({
				url : new URL(window.location).origin + "/manage/getPostes",
				data : "idEntrepriseService=" + $("#idEntrepriseService").val(),
			dataType : "json",
			success : function(data)
			{
				$('#idEntreprisePoste').empty();
				$('#idEntreprisePoste').append('<option value="" selected>Selectionnez un poste</option>');
				$.each(data, function(i,donnees) {
					$.each(donnees, function(key,value) {
					    if (key == "idEntreprisePoste") {
					    	optionValue = value;
					    } else if (key == "poste") {
					    	optionText = value;
					    }
					});
					$('#idEntreprisePoste').append('<option value="' + optionValue + '">' + optionText + '</option>');
				}); 
			}
		});
	});
});