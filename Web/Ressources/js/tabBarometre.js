/* @changelog 26/01/2022 [EVOL] (Lansky) Ajout le tableau active au baromètre */
$("document").ready(function(){
  	var tab1 		= "/manage/employe/barometre",
  		tab2 		= "/manage/barometre",
  		tab3 		= "/manage/barometre_list",
  		tab4 		= "/manage/linear_answers_barometer",
  		pathname 	= new URL(window.location.href).pathname;
  	if (pathname == tab1) {
  		if (new URL(window.location.href).search == '?reply=YES') {
  			$("li#tab6").addClass('is-active');
		  	$("li#tab5").removeClass('is-active');
		  	$("li#tab7").removeClass('is-active');
  		} else {
			$("li#tab7").addClass('is-active');
		  	$("li#tab6").removeClass('is-active');
		  	$("li#tab5").removeClass('is-active');
  		}
  	} else if (pathname == tab2) {
		$("li#tab6").addClass('is-active');
	  	$("li#tab5").removeClass('is-active');
		$("li#tab7").removeClass('is-active');
		$("li#tab8").removeClass('is-active');
  	}  else if (pathname == tab3) {
		$("li#tab5").addClass('is-active');
	  	$("li#tab6").removeClass('is-active');
	  	$("li#tab7").removeClass('is-active');
		$("li#tab8").removeClass('is-active');
  	}  else if (pathname == tab4) {
		$("li#tab8").addClass('is-active');
		$("li#tab5").removeClass('is-active');
	  	$("li#tab6").removeClass('is-active');
	  	$("li#tab7").removeClass('is-active');
  	}
});