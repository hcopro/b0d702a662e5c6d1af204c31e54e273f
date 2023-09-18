/* @changelog 29/09/2021 [EVOL] (Lansky) Ajout de la fonctionnalité d'évaluation */
$("document").ready(function(){
  	var tab1 		= "/manage/entreprise/evaluation/categorie_detail",
		tab2 		= "/manage/entreprise/evaluation/categorie",
  		tab4 		= "/manage/entreprise/evaluation/questionnaire",
  		tab6 		= "/manage/entreprise/evaluation",
  		tab7 		= "/manage/entreprise/evaluation_modele",
  		tab8 		= "/manage/entreprise/evaluation/archive",
  		tab9 		= "/manage/employe/evaluation_valider",
  		tab10 		= "/manage/employe/evaluation",
  		tab11 		= "/manage/employe/evaluation/archive",
  		tab12 		= "/manage/employe/barometre",
  		tab13 		= "/manage/barometre",
  		tab14 		= "/manage/barometre_list",
  		pathname 	= new URL(window.location.href).pathname;
  	if (pathname == tab2) {
	  	$("li#tab1").removeClass('is-active');
	  	$("li#tab2").addClass('is-active');
	  	$("li#tab4").removeClass('is-active');
  	} else if (pathname == tab4) {
	  	$("li#tab4").addClass('is-active');
	  	$("li#tab1").removeClass('is-active');
	  	$("li#tab2").removeClass('is-active');
  	} else if (pathname == tab1) {
	  	$("li#tab1").addClass('is-active');
	  	$("li#tab2").removeClass('is-active');
	  	$("li#tab4").removeClass('is-active');
  	} else if (pathname == tab6) {
	  	$("li#tab6").addClass('is-active');
	  	$("li#tab7").removeClass('is-active');
	  	$("li#tab8").removeClass('is-active');
  	} else if (pathname == tab7) {
	  	$("li#tab7").addClass('is-active');
	  	$("li#tab6").removeClass('is-active');
	  	$("li#tab8").removeClass('is-active');
  	} else if (pathname == tab8) {
	  	$("li#tab8").addClass('is-active');
	  	$("li#tab6").removeClass('is-active');
	  	$("li#tab7").removeClass('is-active');
  	} else if (pathname == tab9) {
		$("li#tab9").addClass('is-active');
		$("li#tab10").removeClass('is-active');
	  	$("li#tab11").removeClass('is-active');
  	} else if (pathname == tab10) {
		$("li#tab10").addClass('is-active');
		$("li#tab9").removeClass('is-active');
	  	$("li#tab11").removeClass('is-active');
  	} else if (pathname == tab11) {
		$("li#tab11").addClass('is-active');
	  	$("li#tab9").removeClass('is-active');
		$("li#tab10").removeClass('is-active');
  	} else if (pathname == tab12) {
  		if (new URL(window.location.href).search == '?reply=YES') {
  			$("li#tab6").addClass('is-active');
		  	$("li#tab5").removeClass('is-active');
  		} else {
			$("li#tab5").addClass('is-active');
		  	$("li#tab6").removeClass('is-active');
  		}
  	} else if (pathname == tab13) {
		$("li#tab6").addClass('is-active');
	  	$("li#tab5").removeClass('is-active');
  	}  else if (pathname == tab14) {
		$("li#tab5").addClass('is-active');
	  	$("li#tab6").removeClass('is-active');
  	}
});