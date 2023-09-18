	function gererForm(idQuestion, idCategorie, libQuestion) {
		pathname = new URL(window.location.href).pathname;		
		$('#idQuestion').val(idQuestion);
    	$('#questionnaire').val(libQuestion);
    	getQuestionAjax(host,idQuestion,idCategorie ,'');
		if ($('#filter-element').val() == 0) {
				$('#modif-select-idCategorie').attr('disabled', false);
				$('#modif-select-idCategorie').val(idCategorie);
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
	// Remplir la formulaire ajoutée
	$('.btn-sm').click(function () {
		if ($('#filter-element').val() != 0) {
			$('#ajout-select-idCategorie').val($('#filter-element').val());
			$('select option[value="'+$('#filter-element :selected').val()+'"]').attr("selected", true);
		}
	});
	// Récuperer le trait de personnalité
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
	// Récuperer une question
	function getQuestionAjax(host,id, idCat, motif = '') {
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getQuestionAjax",
			data : {
				idQuestion	: id,
				idCategorie	: idCat
			},
			dataType : "json",
			success : function(data)
			{
				// Afficher les champs interprétation
				$.each(data['interpretation'], function (k, v) {
					$('textarea[name="' + k + '"]').val(v);
				});
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
	}