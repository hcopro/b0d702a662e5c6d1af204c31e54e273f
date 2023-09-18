$("document").ready(function(){
	/* @changelog 06/12/2021 [EVOL] (Lansky) Changer la structure du menu entreprise en onjet de trableau */
	var menus = {
		item1 : [
			"/manage/interlocuteurs"
		],
		item2 : [
			"/manage/entretiens"
		],
		item3 : [
			"/manage/niveaux_entretiens"
		],
		item4 : [
			"/manage/candidatures_entreprise"
		],
		item5 : [
			"/manage/offres",
			"/manage/offre_candidatures"
		],
		item6 : [
			"/manage/tableau_de_bord-offre",
			"/manage/tableau_de_bord-entretien",
			"/manage/tableau_de_bord-interlocuteur"
		],
		item7 : [
			"/manage/tests"
		],
		item8 : [
			"/manage/resultats"
		],
		item9 : [
			"/manage/entreprise/contrat",
			"/manage/entreprise/templateContrat",
			"/manage/entreprise/parametreContrat"
		],
		item10 : [
			"/manage/employes",
			"/manage/employe",
			"/manage/update-employe",
			"/manage/edit-compte_banque"
		],
		item11 : [
			"/manage/entreprise_postes",
			"/manage/create-entreprise_poste",
			"/manage/entreprise_poste",
			"/manage/update-entreprise_poste"
		],
		item12 : [
			"/manage/entreprise_services",
			"/manage/entreprise_service"
		],
		item13 : [
			"/manage/entreprise/formation",
			"/manage/entreprise/catalogueFormation",
			"/manage/entreprise/offreFormation"
		],
		item14 : [
			"/manage/entreprise/formateur",
			"/manage/entreprise/formationFormateur"
		],
		item15 : [
			"/manage/entreprise/pointage/dashboard",
			"/manage/entreprise/retard/dashboard"
		],
		item16 : [
			"/manage/entreprise/tacheRealisee",
			"/manage/entreprise/suivi/dashboard",
			"/manage/tracking",
			"/manage/tracking/currentTask"

		],
		item17 : [
			"/manage/entreprise/permission/dashboard",
			"/manage/entreprise/repos/dashboard",
			"/manage/entreprise/jourFerie",
			"/manage/entreprise/parametre/permission"
		],
		item18 : [
			"/manage/entreprise/interim",
			"/manage/entreprise/evaluationInterim"
		],
		item19 : [
			"/manage/entreprise/conge",
			"/manage/parametre/conge",
			"/manage/historiqueConge",
			"/manage/entreprise/validation"
		],
		item20 : [
			"/manage/entreprise/planning"
		],
		item21 : [
			"/manage/entreprise/avance",
			"/manage/entreprise/parametreAvance",
			"/manage/entreprise/detailAvance",
			"/manage/entreprise/historiqueAvance",
			"/manage/entreprise/demandeAvance",
			"/manage/entreprise/demandeAvanceQuinzaine"
		],
		item22 : [
			"/manage/entreprise/paie",
			"/manage/entreprise/avantagePaie",
			"/manage/entreprise/parametrePaie",
			"/manage/entreprise/detailFichePaie",
			"/manage/entreprise/parametreFichePaie",
			"/manage/entreprise/detailFichePaie"
		],
		/* @changelog 29/09/2021 [EVOL] (Lansky) Ajout de la fonctionnalité d'évaluation */
		item23 : [
			"/manage/entreprise/evaluation",
			"/manage/entreprise/detail-evaluation",
			"/manage/entreprise/evaluation/archive",
			"/manage/entreprise/evaluation/affiche_graph"
		],
		item24 : [
			"/manage/entreprise/evaluation_modele",
			"/manage/entreprise/detail-evaluation_modele"
		],
		item25 : [
			"/manage/entreprise/evaluation/categorie",
			"/manage/entreprise/evaluation/questionnaire",
			"/manage/entreprise/evaluation/categorie_detail"
		],
		item26 : [
			"/manage/barometre_list",
			"/manage/barometre",
			"/manage/linear_answers_barometer"
		]
	};
	var	pathname = new URL(window.location.href).pathname;
	$.each($('.lien-menu').parent(), function(key, val) {
	  	inc = key + 1;
		// chercher la correspondance d'item
		$.map(menus, function(el, indx) {
			if (indx == $.trim('item'+inc)) {
				item = el;
				return false;
			}		
		});
		activer(pathname, item, $(val).attr('id'), $(val).parent().parent().attr('id').split('collapseMenu')[1]);
	});
	collapseTitle();
});
function activer(pathname, item, idItem, parent)
{
	if ($.inArray(pathname, item) >= 0) {
	    $(".item").removeClass('item-active');
	    $("#titre").removeClass();
		$("#titre").addClass("fa fas fa-caret-right");
		for (var i = 1; i <= parent.length; i++) {
		    $("#"+idItem).addClass('item-active');
		    $("#"+idItem + " .lien-menu").css('color', 'white');
		    $("#collapseMenu" + parent.substring(0, i)).collapse('show');
		    $('#titre-' + parent.substring(0, i)).removeClass();
		    $('#titre-' + parent.substring(0, i)).addClass("fa fas fa-caret-down");
	    }
	}
}

/* @changelog 29/10/2021 [FIX] (Lansky) Affichage du menu collapse caché */
function collapseTitle() {
  	setTimeout(function () {
	  	if ($('#titre-3').attr('class') == 'fa fas fa-caret-down') {
			$('#collapseMenu3').addClass("show");
			$('#btn-titre-3').attr("aria-expanded", "true");
	  	} else {
			$('#collapseMenu3').removeClass("show");
			$('#btn-titre-3').attr("aria-expanded", "false");
	  	}
  	}, 360);
}
/**
 * Collapsed the menu 
 * @param self tags élements this
 * @return null
*/
function changeCollapsed(self) {
	$("#collapseMenu"+$(self).attr('id').split('-')[2]).on('shown.bs.collapse', function () {
	   	$(self).children('i').removeClass();	
		$(self).children('i').addClass("fa fas fa-caret-down");
	});
	$("#collapseMenu"+$(self).attr('id').split('-')[2]).on('hidden.bs.collapse', function () {
	   	$(self).children('i').removeClass();
		$(self).children('i').addClass("fa fas fa-caret-right");
	});
}