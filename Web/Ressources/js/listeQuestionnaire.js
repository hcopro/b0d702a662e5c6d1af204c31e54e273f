	function gererForm(idQuestion, idCategorie, libQuestion) {
		idSouscategorie = idCategorie;
		pathname = new URL(window.location.href).pathname;		
		$('#idQuestion').val(idQuestion);
    	$('#questionnaire').val(libQuestion);
		if ($('#filter-element').val() == 0) {
			getCategorieAjax(idCategorie);
		} else {
			if (pathname.split('_')[1] == 'detail') {
				$('#modif-idQuestion').val(idQuestion);
    			$('#modif-libelle').val(libQuestion);
				$('#modif-select-idCategorie').attr('disabled', true);
			} else {
				$('#modif-select-idCategorie').append('<option value="' + $('#filter-element :selected').val() + '">'+
				$('#filter-element :selected').text()+'</option>');
				$('select option[value="'+$('#filter-element').val()+'"]').attr("selected", true);
				$('#modif-select-idCategorie').attr('disabled', true);
			}
		}
	}
	var host 		= new URL(window.location.href).hostname;
		host 		='http://'+host;
	var idSouscategorie = null;
	// Remplir la formulaire modifiée
	$('.btn-warning-filter').click(function () {
		$('document').ready(function () {
			if ($('#modif-select-idCategorie :selected').val()) {
				getSousCategorieAjax(host, $('#modif-select-idCategorie :selected').val(), 'modif-', idSouscategorie);
			}
			$('#modif-select-idCategorie').change(function () {
				getSousCategorieAjax(host, $('#modif-select-idCategorie').val(), 'modif-');
			} );
		});
	});
	// Remplir la formulaire ajoutée
	$('.btn-sm').click(function () {
		if ($('#filter-element').val() != 0) {
			$('#ajout-select-idCategorie').val($('#filter-element').val());
			$('select option[value="'+$('#filter-element :selected').val()+'"]').attr("selected", true);
			getSousCategorieAjax(host, $('#filter-element :selected').val(), 'ajout-');
		}
		$('document').ready(function () {
			$('#ajout-select-idCategorie').change(function () {
				$('#ajou-select-idSousCategorie').empty();
				getSousCategorieAjax(host, $('#ajout-select-idCategorie').val(), 'ajout-', null, 'yes');
			} );
		});
	});
	// Récuperer la liste de la sous-catégorie
	function getSousCategorieAjax (host, id, attribut, idSouscategorie = null, change = null) {
		var result = 0;
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getSousCategoriePublique",
			data : "idParent=" + id,
			dataType : "json",
			success : function(data)
			{
				$('#'+attribut+'select-idSousCategorie').empty();
				if(change == 'yes') {
					$('#'+attribut+'select-idSousCategorie').append('<option value="" selected>Selectionnez une sous-categorie</option>');
				} else {
					if ($('#filter-element-sousCategorie').val()) {
						if ($('#filter-element-sousCategorie option:selected').val() > 0) {
							$('#'+attribut+'select-idSousCategorie').val($('#filter-element-sousCategorie :selected').val());
						}
					} else {
						$('#'+attribut+'select-idSousCategorie').append('<option value="" selected>Selectionnez une sous-categorie</option>');
					}
				}
				$.each(data, function(i,donnees) {
					$.each(donnees, function(key,value) {
					    if (key == "idCategorie") {
					    	optionValueSousCategorie = value;
					    	result = value;
					    } else if (key == "libelle") {
					    	optionTextSousCategorie = value;
					    }
					});
					$('#'+attribut+'select-idSousCategorie').append('<option value="' + optionValueSousCategorie + '">' + optionTextSousCategorie + '</option>');
				});
				if (idSouscategorie) {
					setTimeout(function() {
						$('#modif-select-idSousCategorie').val(idSouscategorie);
					},200);
				}
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
		return result;
	}
	// Récuperer la catégorie
	function getCategorieAjax(id) {
		var result = 0;
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getCategorie",
			data : "idCategorie=" + id,
			dataType : "json",
			success : function(data)
			{
				$.each(data, function(key,value) {
				    if (key == "idCategorie") {
				    	$('#modif-select-idCategorie').val(value);
					    getSousCategorieAjax(host, value, 'modif-');
				    	result = value;
				    } else if (key == "libelle") {
				    	optionText = value;
				    }
				});
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
		return result;
	}