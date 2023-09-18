$('document').ready(function () {
	var pathName 	= new URL(window.location.href).pathname;
	var host 		= new URL(window.location.href).hostname;
		res 		= pathName.split('/');
		host 		='http://'+host;

	$('#idCategorie').change(function () {
		$('#select-sousCategorie').attr('disabled', false);
		sousCategorieAjax(host, $('#idCategorie').val());
	} );
	//$('.btn-sm').load(host+"/App/Backend/Modules/Evaluation/Views/modalAjout.phtml");
	$('.btn-sm').click(function () {
		if (res[res.length-1] != 'categorie') {
			if ($('#filter-element').val() != 0 && $('#filter-element').val() != 'undefined') {
				$('#ajout-idParent').val($('#filter-element :selected').val());
				sousCategorieAjax(host, $('#filter-element').val());
				if (($('#filter-element-sousCategorie option:selected').val())) {
					value = $('#filter-element-sousCategorie').val();
					$('select option[value="'+value+'"]').attr("selected",true);
				}
			} else {
				if ($('#idCategorie').val() == "" || $('#idCategorie').val() == "undefined") {
					$('#select-sousCategorie').attr('disabled', true);
				} 
				else {
					sousCategorieAjax(host, $('#filter-element').val());
				}
			}
		}
	});
	if (res[res.length-1] == 'sousCategorie') {
		$("#filter-element").change(function(){
		  	$("#liste").load(host+"/manage/entreprise/evaluation/getSousCategorie", {
				idParent : $("#filter-element").val()
			},
			function() {

			});
		});
		$("#liste").load(host+"/manage/entreprise/evaluation/getSousCategorie", {
			idParent : $("#filter-element").val()
		});
	} else if (res[res.length-1] == 'questionnaire') {
		$("#filter-element").change(function(){
		  	$("#liste").load(host+"/manage/entreprise/evaluation/getQuestion", {
					idParent : $("#filter-element").val()
				},
				function() {
					var idCategorie = $('#filter-element').val();
					if ($("#filter-element").val() == "" || $("#filter-element").val() == 0) {
						$('#filter-element-sousCategorie').attr('disabled',true);
					}
					else {
						$('#filter-element-sousCategorie').attr('disabled',false);
						$('.modal-body, #idCategorie').val(idCategorie);
						$('.modal-body, #modif-select-idCategorie').val(idCategorie);
						sousCategorieAjax(host, idCategorie);
					}
				}
			);
			// Récuperer la liste de la sous-catégorie
			$.ajax({
				url : host+"/manage/entreprise/evaluation/getSousCategoriePublique",
				data : "idParent=" + $("#filter-element").val(),
				dataType : "json",
				success : function(data)
				{
					$('#filter-element-sousCategorie').empty();
					$('#filter-element-sousCategorie').append('<option value="" selected>Selectionnez une sous-categorie</option>');
					$.each(data, function(i,donnees) {
						$.each(donnees, function(key,value) {
						    if (key == "idCategorie") {
						    	optionValue = value;
						    } else if (key == "libelle") {
						    	optionText = value;
						    }
						});
						$('#filter-element-sousCategorie').append('<option value="' + optionValue + '">' + optionText + '</option>');
					}); 
				}
			});
		});
		$("#filter-element-sousCategorie").change(function(){
	  		$("#liste").load(host+"/manage/entreprise/evaluation/getQuestion", {
				idParent : $("#filter-element").val() ,
				idCategorie : $("#filter-element-sousCategorie").val()
			}, 
			function() {
			});
		});
		$("#liste").load(host+"/manage/entreprise/evaluation/getQuestion", {
			idParent : $("#filter-element").val() ,
			idCategorie : $("#filter-element-sousCategorie").val()
		});
	}
});
$('#annuler').click(function(){
	window.location.reload();
});
$(function() {
	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
});
// Récuperer la liste de la sous-catégorie
function sousCategorieAjax(host, id) {
	$.ajax({
		url : host+"/manage/entreprise/evaluation/getSousCategoriePublique",
		data : "idParent=" + id,
		dataType : "json",
		success : function(data)
		{
			$('#select-sousCategorie').empty();
			$('#select-sousCategorie').append('<option value="" selected>Selectionnez une sous-categorie</option>');
			$.each(data, function(i,donnees) {
				$.each(donnees, function(key,value) {
			    if (key == "idCategorie") {
			    	optionValueSousCategorie = value;
			    	result = value;
			    } else if (key == "libelle") {
			    	optionTextSousCategorie = value;
			    }
				});
				$('#select-sousCategorie').append('<option value="' + optionValueSousCategorie + '">' + optionTextSousCategorie + '</option>');
			});
		},
		error: function (error) {
	    console.log('error; ' + eval(error));
		}
	});
}