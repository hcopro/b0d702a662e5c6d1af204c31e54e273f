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
    jQuery( ".datepicker" ).datepicker(
      	dateFr
    );
	$("#autre").click(function(){
		var text = document.createElement("input");
	    text.setAttribute("type", "text");
	    text.setAttribute("name", "autreQualite");
	    text.setAttribute("class", "form-control focus_activated");
	    text.setAttribute("placeholder", "Saisir une personnalité");

	    document.getElementById("block-personnalite").appendChild(text);
	});

	$("#submit").click(function(){

		var date = new Date();
		var dateNow = date.getFullYear() + "-" + String(date.getMonth() + 1).padStart(2, '0') + "-" + String(date.getDate()).padStart(2, '0');
		var pathname = new URL(window.location.href).pathname;

		if (pathname == "/manage/create-offre" || pathname == "/manage/update-offre" || pathname == "/manage/create-newOffre") {
			if (pathname == "/manage/create-newOffre") {
				var mission = "";
				var missions = "";
				$("#table-mission > tbody > tr > td").each(function() {
					mission += $(this).text() + "/";
				});
				var tabMission = mission.split("///");
				for (var i = 0; i < tabMission.length; i++) {
					if (tabMission[i] != "") {
						missions += tabMission[i] + "_";
					}
				}
				$("input[name='input_mission']").val(missions);
			}
			var dateLimite = $("#datepickerLimite").val().split('/');
			$("#dateLimite").val(dateLimite[2] + '-' + dateLimite[1] + '-' + dateLimite[0]);
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
			
			if ($('#dateLimite').val() < dateNow) {
				$("#dateLimite-message").html("<ul><li>Date non valide </li></ul>");
				return false;
			}

			if ($('#block-sousDomaine:visible').length != 0) {
				if ($('#idDomaine').val() == "") {
					$("#idDomaine-message").html("<ul><li>Veuillez choisir un domaine</li><ul>");
					return false;
				} 
				if ($('#nomSousDomaine').val() == "") {
					$("#nomSousDomaine-message").html("<ul><li>Veuillez séléctionner ou entrer un sous domaine</li><ul>");
					return false;
				}
			}
			if ($('#block-domaine:visible').length != 0) {
				if ($('#nomDomaine').val() == "") {
					$("#nomDomaine-message").html("<ul><li>Veuillez séléctionner ou entrer un domaine</li><ul>");
					return false;
				}
			}
			if ($('#block-contrat:visible').length != 0) {
				if ($('#designation').val() == "") {
					$("#designation-message").html("<ul><li>Veuillez séléctionner ou entrer un contrat</li><ul>");
					return false;
				}
			}/*
			if ($('#block-niveauExperience:visible').length != 0) {
				if ($('#niveauExperience').val() == "" || $('#niveauExperience').val() == "0") {
					$("#niveauExperience-message").html("<ul><li>Veuillez séléctionner ou entrer un niveau d'expérience</li><ul>");
					return false;
				}
			}
			if ($('#block-niveauEtude:visible').length != 0) {
				if ($('#niveauEtude').val() == "" || $('#niveauEtude').val() == "0") {
					$("#niveauEtude-message").html("<ul><li>Veuillez séléctionner ou entrer votre niveau</li><ul>");
					return false;
				}
			}*/

		} else if (pathname == "/manage/create-formation" || pathname == "/manage/create-experience" || pathname == "/manage/update-formation" || pathname == "/manage/update-experience") {
			var dateDebut = $("#datepickerDebut").val().split('/');
			$("#dateDebut").val(dateDebut[2] + '-' + dateDebut[1] + '-' + dateDebut[0]);
			var dateFin = $("#datepickerFin").val().split('/');
			$("#dateFin").val(dateFin[2] + '-' + dateFin[1] + '-' + dateFin[0]);	
			if ($('#block-sousDomaine:visible').length != 0) {
				if ($('#idDomaine').val() == "") {
					$("#idDomaine-message").html("<ul><li>Veuillez choisir un domaine *</li><ul>");
					return false;
				} 
				if ($('#nomSousDomaine').val() == "") {
					$("#nomSousDomaine-message").html("<ul><li>Veuillez séléctionner ou entrer un sous domaine *</li><ul>");
					return false;
				}
			}
			if ($('#block-domaine:visible').length != 0) {
				if ($('#nomDomaine').val() == "") {
					$("#nomDomaine-message").html("<ul><li>Veuillez séléctionner ou entrer un domaine *</li><ul>");
					return false;
				}
			}
			if ($('#dateFin').val() < $('#dateDebut').val()) {
				$("#dateFin-message").html("<ul><li>Date fin doit être inférieur à la date début</li><ul>");
				return false;
			}
			if (dateNow < $('#dateFin').val()) {
				$("#dateFin-message").html("<ul><li>Date fin non valide *</li></ul>");
				return false;
			}
		} else if (pathname == "/manage/create-diplome" || pathname == "/manage/update-diplome") {
			var dateObtention = $("#datepickerObtention").val().split('/');
			$("#dateObtention").val(dateObtention[2] + '-' + dateObtention[1] + '-' + dateObtention[0]);
			if ($('#block-domaine:visible').length != 0) {
				if ($('#nomDomaine').val() == "") {
					$("#nomDomaine-message").html("<ul><li>Veuillez séléctionner ou entrer un domaine *</li><ul>");
					return false;
				}
			}
			if ($('#block-niveauEtude:visible').length != 0) {
				if ($('#niveauEtude').val() == "") {
					$("#niveauEtude-message").html("<ul><li>Veuillez séléctionner ou entrer votre niveau *</li><ul>");
					return false;
				}
			}
			if (dateNow < $('#dateObtention').val()) {
				$("#dateObtention-message").html("<ul><li>Date non valide *</li></ul>");
				return false;
			}
		} else if (pathname == "/manage/create-entretien" || pathname == "/manage/edit-entretien" || pathname == "/manage/next_level-entretien") {
			var date = $("#datepickerDate").val().split('/');
			$("#date").val(date[2] + '-' + date[1] + '-' + date[0]);
			if (dateNow > $('#date').val()) {
				$("#date-message").html("<ul><li>Date non valide</li></ul>");
				return false;
			}
		} else if (pathname == "/manage/create-entreprise_poste" || pathname == "/manage/update-entreprise_poste" || pathname == "/manage/next_level-entretien") {
			var mission = "";
			var missions = "";
			$("#table-mission > tbody > tr > td").each(function(){
				let newText = addNewLinerText($(this).text())
				mission += newText + "/";
			});
			var tabMission = mission.split("///");
			for (var i = 0; i < tabMission.length; i++) {
				if (tabMission[i] != "") {
					missions += tabMission[i] + "_";
				}
			}
			$("input[name='input_mission']").val(missions);
			if ($('#block-niveauEtude:visible').length != 0) {
				if ($('#niveauEtude').val() == "") {
					$("#niveauEtude-message").html("<ul><li>Veuillez séléctionner ou entrer un niveau d'étude</li><ul>");
					return false;
				}
			}
			if ($('#block-anneeExperienceInterne:visible').length != 0) {
				if ($('#niveauExperienceInterne').val() == "" || $('#niveauExperienceInterne').val() == "0") {
					$("#niveauExperienceInterne-message").html("<ul><li>Veuillez séléctionner ou entrer un niveau d'expérience</li><ul>");
					return false;
				}
			}
			if ($('#block-anneeExperienceExterne:visible').length != 0) {
				if ($('#niveauExperienceExterne').val() == "" || $('#niveauExperienceExterne').val() == "0") {
					$("#niveauExperienceExterne-message").html("<ul><li>Veuillez séléctionner ou entrer un niveau d'expérience</li><ul>");
					return false;
				}
			}
		} 
	});
	
	$("#nomService").click(function(){
		if ($("#nomService").val() != "") {
			$("#service").val($("#nomService").val());
			$("#nomService").val("");
		}
	});

	$("#nomPoste").click(function(){
		if ($("#nomPoste").val() != "") {
			$("#poste").val($("#nomPoste").val());
			$("#nomPoste").val("");
		}
	});

	$("#idSousDomaine").click(function(){
		if ($("#idSousDomaine").val() == "autre") {
			$('#block-sousDomaine').show();		
		} else {
			$('#block-sousDomaine').hide();
		}
	});

	$("#idDomaine").click(function(){
		if ($("#idDomaine").val() == "autre") {
			$('#block-domaine').show();				
		} else {
			$('#block-domaine').hide();	
		}
	});		

	$("#idNiveauEtude").click(function(){
		if ($("#idNiveauEtude").val() == "autre") {
			$('#block-niveauEtude').show();					
		} else {
			$('#block-niveauEtude').hide();	
		}
	});

	$("#idNiveauExperience").click(function(){
		if ($("#idNiveauExperience").val() == "autre") {
			$('#block-niveauExperience').show();					
		} else {
			$('#block-niveauExperience').hide();
		}
	});

	$("#idContrat").click(function(){
		if ($("#idContrat").val() == "autre") {
			$('#block-contrat').show();					
		} else {
			$('#block-contrat').hide();
		}
	});

	$("#idDomaine").change(function(){
		$('#idDomaine-message').html('');
	});

	$("#nomSousDomaine").click(function(){
		$('#nomSousDomaine-message').html('');
	});

	$("#nomDomaine").click(function(){
		$('#nomDomaine-message').html('');
	});

	$("#niveauEtude").click(function(){
		$('#niveauEtude-message').html('');
	});

	$("#designation").click(function(){
		$('#designation-message').html('');
	});

	$("#niveauExperience").click(function(){
		$('#niveauExperience-message').html('');
	});

	$("#poste").click(function(){
		$('#poste-message').html('');
	});

	$("#emails").hover(function(){
		$("#help-emails").toggle();
	});

	$("#interlocuteurs").hover(function(){
		$("#help-interlocuteurs").toggle();
	});

	$("#niveauExperienceInterne").click(function(){
		$("#niveauExperienceInterne-message").html("");
	});

	$("#niveauExperienceExterne").click(function(){
		$("#niveauExperienceExterne-message").html("");
	});

	$("#anneeExperienceInterne").click(function(){
		if ($("#anneeExperienceInterne").val() == "autre") {
			$('#block-anneeExperienceInterne').show();					
		} else {
			$('#block-anneeExperienceInterne').hide();
		}
	});

	$("#anneeExperienceExterne").click(function(){
		if ($("#anneeExperienceExterne").val() == "autre") {
			$('#block-anneeExperienceExterne').show();					
		} else {
			$('#block-anneeExperienceExterne').hide();
		}
	});
	
	$("#PhotoOffre").change(function(){
		console.log("fileToUpload == ready");
	    if (this.files && this.files[0]) {
	        console.log("Condition == succesfull");
	        var reader = new FileReader();
	        reader.onload = function(e)
	        {
	          $('img[name="photoCouvertureOffre"].couverture-offre').attr("src", e.target.result);
	        };
	        reader.readAsDataURL(this.files[0]);
	      }
	});
});

/**
 * Get the value of the text
 * 
 * @param text
 * 
 * @return text
 *   
*/
function addNewLinerText(text) {
	// Diviser le texte en un tableau de mots
	let wordsArray	= text.split(' ');
	let response 	= [];
	// Itérer sur chaque mot et vérifier les caractères de nouvelle ligne
	for (let i = 0; i < wordsArray.length; i++) {
		response[i] = wordsArray[i];
		// Vérifier si le mot contient un caractère de nouvelle ligne
		if (response[i].indexOf('\n') !== -1) {
			// response[i] += '\n';
			// response[i] += '<br>';
			response[i] = response[i].replace(/\n/g, "&#10;");
		}
	} 
  	return response.join(' ');
}
