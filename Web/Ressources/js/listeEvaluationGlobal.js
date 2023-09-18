var host 			= new URL(window.location.href).hostname;
var idSouscategorie = null;
	host 			='http://'+host;
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
	// Récuperer la liste de la sous-catégorie
	function getSousCategorieAjax (host, id, attribut, idSouscategorie = null, change = null) {
		var result = 0;
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getSousCategoriePublique",
			data : "idParent=" + id,
			dataType : "json",
			success : function(data)
			{
				$('#'+attribut).empty();
				$('#'+attribut).append('<option value="" selected>Selectionnez une sous-categorie</option>');
				if (data.length == 0) {
					$('#'+attribut).append('<option value="">Aucune</option>');
				} else {
					if (data.length >= 2) {
						$('#'+attribut).append('<option value="Tout">Tout</option>');
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
					$('#'+attribut).append('<option value="' + optionValueSousCategorie + '">' + optionTextSousCategorie + '</option>');
				});
				if (idSouscategorie) {
					setTimeout(function() {
						$('#'+attribut).val(idSouscategorie);
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
	function getCategorieAjax(id, motif, random = null) {
		var result = 0;
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getCategorie",
			data : "idCategorie=" + id,
			dataType : "json",
			success : function(data)
			{
				if (id > 0) {
					$.each(data, function(key,value) {
					    if (key == "idCategorie") {
					    	$('#'+motif+'-select-idCategorie').val(value);
						    getSousCategorieAjax(host, value, motif+'-select-idSousCategorie'+random);
					    	result = value;
					    } else if (key == "libelle") {
					    	optionText = value;
					    }
					});
				} else {
					$('#'+motif+'select-idCategorie').empty();
					$('#'+motif+'select-idCategorie').append('<option value="" selected>Selectionnez une categorie</option>');
					$.each(data, function(key,value) {
						$.each(value, function(indice,donnee) {
						    if (indice == "idCategorie") {
						    	optionValueCategorie = donnee;
						    } else if (indice == "libelle") {
						    	optionTextCategorie = donnee;
						    }
						});
						$('#'+motif+'select-idCategorie'+random).append('<option value="' + optionValueCategorie + '">' + optionTextCategorie + '</option>');
					});
				}
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
		return result;
	}