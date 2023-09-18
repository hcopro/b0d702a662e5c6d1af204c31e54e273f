$('#annuler').click(function(){
	//redirect vers la page précédante
	location.assign(document.referrer);
});
$('document').ready(function () {
  	$("li#tab1").addClass('is-active');
	var pathName = new URL(window.location.href).pathname;
  	// Remplir les champs input
  	if (pathName.split('/')[3] = 'beforeUpdate-evaluation') {
  		var valuePoint 	= [];
  		var valueNote 	= [];
  		$('.input-point').each(function(key, val){
  			valuePoint.push($(this).attr('value'));
	       	$(this).val($(this).attr('value'));
	   	});
	  	$('.input-note').each(function(keyTwo, vals){
  			valueNote.push($(this).val());
	   	});
       	$('input:hidden[name=point]').attr('value', valuePoint.join(','));
       	$('input:hidden[name=note]').attr('value', valueNote.join(','));
  	}
  	// Récupère les notes de la sélection à DOM
  	$('.input-note').change(function () {
  		var id=[]; 
		$('.input-note').each(function(key, val){ 
			var select = $(this).val();
			id.push(select);
		});
		$('input:hidden[name=note]').attr('value', id.join(','));
  	});
});
/**
 * Récupère les points de la sélection à DOM
 * @param  
 * @return null
 */
function getPoint() {
	var id=[];
	$('.input-point').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=point]').attr('value', id.join(','));
}
