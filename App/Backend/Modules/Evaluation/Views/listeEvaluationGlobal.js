var host 			= new URL(window.location.href).hostname;
var idSouscategorie = null;
	host 			='http://'+host;
	// Récuperer le trait de personnalité
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
					$('#'+motif+'select-idCategorie'+random).empty();
					$('#'+motif+'select-idCategorie'+random).append('<option value="" selected>Sélectionnez une dimension</option>');
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
	// Récuperer les questions
	function getQuestionAjax(host,id, rand = '') {
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getQuestionAjax",
			data : "idCategorie=" + id,
			dataType : "json",
			success : function(data)
			{
				var optionVal,
					optionText;
				$('.selectpicker#idQuestion'+rand).find('option').remove();
				$('#idQuestion'+rand).parent().parent().find('.filter-option-inner-inner').html("Choisir une question");
				$('#idQuestion'+rand).parent().parent().find('.dropdown-menu.inner.show').find('li').remove();
				$.each(data, function (k, v) {
					optionText = '';
					optionVal = '';
					$.each(v, function (indx, val) {
						if (indx == "idQuestion") {
					    	optionVal = val;
					    } else if (indx == "libelle") {
					    	optionText = val;
					    }
					});
					$('.selectpicker#idQuestion'+rand).append('<option value="' + optionVal+ '">' + optionText + '</option>').selectpicker('refresh');
				});
				if (data) {
					$('#idQuestion'+rand).parent().parent().find('.filter-option-inner-inner').html("Rien a été sélectionné");
				}
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
	}