$('document').ready(function () {
	var pathName 	= new URL(window.location.href).pathname;
	var host 		= new URL(window.location.href).hostname;
		res 		= pathName.split('/');
		host 		='http://'+host;
	$('.btn-primary.btn-sm').click(function () {
		// if (pathName == '/manage/barometre') {
		//     console.log("eeeeeeeeeeeeeeeeeeeee");
			
		// } else {
			if (res[res.length-1] != 'categorie') {
				if ($('#filter-element').val() != 0 && $('#filter-element').val() != 'undefined') {
					$('#ajout-idParent').val($('#filter-element :selected').val());
				}
			}
			$('textarea').val(''); // Vider les champs interprétation
		// }
	});
	if (res[res.length-1] == 'questionnaire') {
		$("#filter-element").change(function(){
	  		$("#liste").load(host+"/manage/entreprise/evaluation/getQuestion", {
				idCategorie : $("#filter-element").val()
			}, 
			function() {
			});
		});
		$("#liste").load(host+"/manage/entreprise/evaluation/getQuestion", {
			idCategorie : $("#filter-element").val()
		});
	}
});
$('.annuler').click(function(){
	window.location.reload();
});
$(function() {
	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
});