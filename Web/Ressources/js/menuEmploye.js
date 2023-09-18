$("document").ready(function(){
	/* @changelog 06/12/2021 [EVOL] (Lansky) Changer la structure du menu employé en onjet de tableau */
	var menus = {
		item1 : [
			"/manage/employe/detailAvance",
			"/manage/employe/demandeAvance",
			"/manage/employe/demandeAvanceQuinzaine",
			"/manage/employe/historiqueAvance"
		],
		item2 : [
			"/manage/employe/detailFichePaie"
		],
		item3 : [
			"/manage/show-monContrat",
			"/manage/show-monHistoriqueContrat"
		],
		item4 : [
			"/manage/employe/formationDisponible",
			"/manage/employe/validationFormation",
			"/manage/employe/formationAssistee",
			"/manage/employe/suggestionFormation"
		],
		item5 : [
			"/manage/employe/conge",
			"/manage/employe/validation",
			"/manage/employe/historiqueConge",
			"/manage/employe/congeCollaborateur",
			"/manage/collaborater/planning"
		],
		item6 : [
			"/manage/employe/pointage/dashboard"
		],
		item7 : [
			"/manage/shift/dashboard"
		],
		item8 : [
			"/manage/employe/suivi/dashboard",
			"/manage/employe/interim",
			"/manage/tracking",
			"/manage/tracking/currentTask",
			"/manage/entreprise_postes"
		],
		item9 : [
			"/manage/employe/evaluation_valider",
			"/manage/employe/valide-evaluation_valider"
		],
		item10 : [
			"/manage/employe/evaluation",
			"/manage/employe/detail-evaluation"
		],
		item11 : [
			"/manage/employe/evaluation/archive"
		],
		item12 : [
			"/manage/employe/barometre",	
			"/manage/barometre_list",
			"/manage/linear_answers_barometer"
		],
		item13 : [
			"/manage/candidatures_entreprise",	
			"/manage/suiviCandidature"
		],
		item14 : [
			"/manage/offres",	
			"/manage/tableau_de_bord-offre"
		],
		item15 : [
			"/manage/tests",	
			"/manage/resultats"
		]
	};
	var pathname = new URL(window.location.href).pathname;
	var hostName = new URL(window.location.href).hostname;
	$.each($('.lien-menu').parent(), function(key, val) {
		let currentUrl = $(this).children().attr('href').replace(new URL(window.location.href).origin,'');
			currentUrl = currentUrl.indexOf('?') > -1 ? currentUrl.substring(0,currentUrl.indexOf('?')) : currentUrl;
		// chercher la correspondance d'item
		$.map(menus, function(el, indx) {
			if ($.inArray(currentUrl, el) > -1) {
				item = el;
				return false;
			}		
		});
		activer(pathname, item, $(val).attr('id'), $(val).parent().parent().attr('id').split('collapseMenu')[1]);
	});
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
	    if ($('#collapseMenu2').attr('class') == 'm-0 p-0 collapse in') {
		    setTimeout(function(){$("#collapseMenu" + parent.substring(0, i)).collapse('show')} , 2000);
	    }
	}
	collapseTitle();
}
/**
 * Collapsed the menu 
 * @param self tags élements this
 * @return null
*/
function changeCollapsed(self) {
	$("#collapseMenu"+$(self).attr('id').split('-')[2]).on('shown.bs.collapse', function () {
	   	$("#titre-"+$(self).attr('id').split('-')[2]).removeClass();
		$("#titre-"+$(self).attr('id').split('-')[2]).addClass("fa fas fa-caret-down");
	});
	$("#collapseMenu"+$(self).attr('id').split('-')[2]).on('hidden.bs.collapse', function () {
	   	$("#titre-"+$(self).attr('id').split('-')[2]).removeClass();
		$("#titre-"+$(self).attr('id').split('-')[2]).addClass("fa fas fa-caret-right");
	});
}

/* @changelog 2021-10-29 [FIX] (Lansky) Affichage du menu collapse caché */
function collapseTitle() {
  	setTimeout(function () {
	  	if ($('#titre-2').attr('class') == 'fa fas fa-caret-down') {
			$('#collapseMenu2').addClass("show");
			$('#btn-titre-2').attr("aria-expanded", "true");
	  	} else {
			$('#collapseMenu2').removeClass("show");
			$('#btn-titre-2').attr("aria-expanded", "false");
	  	}
  	}, 300);
}