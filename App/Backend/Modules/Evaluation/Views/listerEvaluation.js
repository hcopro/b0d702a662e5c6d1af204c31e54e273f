$(document).ready(function(){
	// Remplir la formulaire ajoutée
	$('.btn-sm').click(function () {
		random = getRandom();
		$('.btn-ajout').attr('id', 'btn-ajout'+random);
		$('.btn-ajoutDim').attr('id', 'btn-ajoutDim'+random);
		$('.btn-delete').attr( 'id','btn-delete'+random);
		$('.btn-deleteDim').attr( 'id','btn-deleteDim'+random);
		$('.btn-ajout').attr('onclick','addRow("'+random+'", "ajout")');
		$('.btn-ajoutDim').attr('onclick','addDimension("'+random+'", "ajout")');
		$('.btn-delete',).attr('onclick','removeRow("'+random+'")');
		$('.btn-deleteDim',).attr('onclick','removeRowDimension("'+random+'")');
		$('#modal-p-3-row').attr('id', 'modal-p-3-row'+random);
		getCategorieAjax(0, 'ajout-', '');
		$('.filter-option-inner-inner').html("Choisir question");
		$('.btn.btn-light').css('color','#3e475e');
	});
	$('.btn.btn-md.btn-danger').click(function () {
		window.location.reload();
	});
});
/**
 * Récupère les ID traits de personnalités de la sélection à DOM
 * @param  
 * @return null
 */
function getIdCategory(self = null) {
	var id = [];
	rad = $(self).attr('id').split('idCategorie')[1];
	getQuestionAjax(host,$(self).val(),rad);
	$('.idCategorie').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=id_categorie]').attr('value', id.join(','));
}
/**
 * Récupère les questions de la sélection à DOM
 * @param  self Signifie (this) la ligne a été sélectionnée
 * @param  modal Le modal peut-être le modal d'ajout ou le modal de modification
 * @return null
 */
function getIdQuestions(self = null, modal) {
	var resp = [], indx , indice = -1;
	$.each($('.row.'+modal+' .selectpicker'), function (key, value) {
		console.log($($('.row.'+modal)[key]).children().eq(0).children().find('.modal-input.idParent'));
		if ($($('.row.'+modal)[key]).children().eq(0).children().find('.modal-input.idParent').length > 0) {
			indice++;
			indx = 0
			resp[indice] = ['parent:' + $($('.row.'+modal)[key]).children().eq(0).children().find('.modal-input.idParent').val()];
		}
		indx++
		resp[indice][indx] = ['category:' + $($('.row.'+modal)[key]).children().eq(1).children().find('.form-control.modal-input.idCategorie option:selected').val()];
		$.each($(value).val(), function (k, v) {
			resp[indice][indx][k+1] = v;
		});
	});
	$('#hidden-ajout-idQuestion-'+modal).attr('value',resp);
}
/**
 * Récupère les ID parents du trait de personnalité de la sélection à DOM
 * @param  string motif String motif de l'action
 * @param  int random Valeur aléatoire
 * @return null
 */
function getIdParent(motif, random = '') {
	var id = [];
	$('.idParent').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=parent]').attr('value', id.join(','));
}
/**
 * Récupère la valeur aléatoire
 * @param  int random Valeur aléatoire
 * @return null
 */
function addElementHtml(random, motif) {
	nextRandom 	= getRandom(); // Ajouter une ligne sur le DOM
	$('.p-3-addModal').append('<hr id="ligne'+nextRandom+'">');
	element 	= $('.p-3-addModal:eq(-1)').children('.modalCreate')[$('.p-3-addModal:eq(-1)').children('.modalCreate').length-1]; // Récupère la dernière ligne d'ajout
	selector 	= $(element).attr('id');
	$('.p-3-addModal').append($('#'+selector).clone());
	// Changer les attributs d'ID d'éléments
	$('#btn-ajout'+random,element).attr('id','btn-ajout'+nextRandom);
	$('#btn-delete'+random,element).attr('id','btn-delete'+nextRandom);	
	$('#btn-ajout'+nextRandom,element).attr('onclick','addRow("'+nextRandom+'", "'+motif+'")');
	$('#btn-delete'+nextRandom,element).attr('onclick','removeRow("'+nextRandom+'")');
	$('#'+motif+'-select-idParent',element).attr('onclick','getIdParent("ajout-","'+nextRandom+'")');
	$('#'+motif+'-select-idCategorie',element).attr('id',''+motif+'-select-idCategorie'+nextRandom);
	$(element).attr('id','modal-p-3-row'+nextRandom);
	$('.selectpicker',element).attr('id','idQuestion'+nextRandom);
	$('#btn-ajoutDim'+random,element).attr('id','btn-ajoutDim'+nextRandom);
	$('#btn-deleteDim'+random,element).attr('id','btn-deleteDim'+nextRandom);
	$('#btn-ajoutDim'+nextRandom,element).attr('onclick','addDimension("'+nextRandom+'", '+motif+')');
	$('#btn-deleteDim'+nextRandom,element).attr('onclick','removeRowDimension("'+nextRandom+'")');
	$('#modal-p-3-row'+nextRandom).find('.modal-input.idParent').attr('id', 'ajout-select-idParent'+nextRandom);
}
/**
 * Récupère la valeur aléatoire
 * @param  
 * @return int Valeur entier
 */
function getRandom () {
	return Math.random().toFixed(3).split('.')[1];
}
/**
 * Suppprimer la ligne à l'ajout
 * @param  int random Valeur aléatoire 
 * @return null
 */
function removeRow(random) {
	tmp = $('.addTrait');
	$('#modal-p-3-row'+random).remove();
	$('#ligne'+random).remove();
	$('.row.modalCreate')[$('.row.modalCreate').length - 1].append(tmp[0]);
	getIdParent('ajout-', random);
	getIdCategory();
	rad = $($('.row.modalCreate')[$('.row.modalCreate').length - 1]).attr('id').split('row')[1];
	$('.row.modalCreate').find('.btn-delete').attr('onclick','removeRow("'+rad+'")');
}
/**
 * Ajouter la ligne intégrale avec trait de personnalité à l'ajout
 * @param  int random Valeur aléatoire
 * @return null
 */
function addRow(random, motif) {
	addElementHtml(random, motif);
	//Recupérer le champ trait s'il n'y en a pas
	if ($('#modal-p-3-row'+random).children().eq(0).children().length == 0) {
		$('#modal-p-3-row'+random).children().eq(0).append($($('.row.modalCreate').eq(0).children()[0]).html());
		$('#modal-p-3-row'+random).find('.idParent').attr('onclick','getIdParent("'+motif+'-","'+random+'")');
		$('#modal-p-3-row'+random).find('.idParent').attr('id',''+motif+'-select-idParent'+random);
	}
	$('.addTrait')[($('.addTrait').length) - 2].remove();
	$($('#modal-p-3-row'+random).find('.selectpicker')).find('option').remove();
	$($('#modal-p-3-row'+random).find('.selectpicker')).selectpicker('refresh');
	$('#modal-p-3-row'+random).find('.dropdown-toggle').eq(1).remove();
	$('.btn.btn-light').css('color','#3e475e');
	$('#modal-p-3-row'+random).find('.filter-option-inner-inner').html('Choisir une question');
	$('#btn-delete'+random).removeAttr('hidden');
}
/**
 * Ajouter la ligne partielle sans trait de personnalité à l'ajout
 * @param  int random Valeur aléatoire
 * @return null
 */
function addDimension(random, motif) {
	addElementHtml(random, motif);
	$('#modal-p-3-row'+random).children().eq(0).children().remove(); // Enlever le champ du trait
	$('.addTrait')[($('.addTrait').length) - 2].remove(); // Vider le contenu du select option
	$($('#modal-p-3-row'+random).find('.selectpicker')).find('option').remove();
	$($('#modal-p-3-row'+random).find('.selectpicker')).selectpicker('refresh'); // Actualiser le selectpicker
	$('#modal-p-3-row'+random).find('.dropdown-toggle').eq(1).remove(); // Enlever le doublon du select apparu
	$('.btn.btn-light').css('color','#3e475e');
	$('#modal-p-3-row'+random).find('.filter-option-inner-inner').html('Choisir une question');
	$('#ligne'+random).remove(); // Enlever la ligne
	$('#btn-deleteDim'+random).removeAttr('hidden');
}
/**
 * Suppprimer la ligne partielle sans trait de personnalité à l'ajout
 * @param  int random Valeur aléatoire 
 * @return null
 */
function removeRowDimension(random) {
	tmp = $('.addTrait');
	$('#modal-p-3-row'+random).remove();
	$('#ligne'+random).remove();
	$('.row.modalCreate')[$('.row.modalCreate').length - 1].append(tmp[0]);
	getIdParent('ajout-', random);
	getIdCategory();
	rad = $($('.row.modalCreate')[$('.row.modalCreate').length - 1]).attr('id').split('row')[1];
	$('.row.modalCreate').find('.btn-delete').attr('onclick','removeRow("'+rad+'")');
}
/**
 * Insérer la valeur d'input type hidden à la modification
 * @param 
 * @return null
 */
function setInputHiddenQuestion (modal) {
	var resp = [], indx , indice = -1;
	$.each($('.row.'+modal+' .selectpicker'), function (key, value) {
		if ($($('.row.'+modal)[key]).children().eq(0).children().find('.modal-input.idParent').length > 0) {
			indice++;
			indx = 0
			resp[indice] = ['parent:' + $($('.row.'+modal)[key]).children().eq(0).children().find('.modal-input.idParent').val()];
		}
		indx++
		resp[indice][indx] = ['category:' + $($('.row.'+modal)[key]).children().eq(1).children().find('.form-control.modal-input.idCategorie option:selected').val()];
		$.each($(value).val(), function (k, v) {
			resp[indice][indx][k+1] = v;
		});
	});
	$('#hidden-ajout-idQuestion-'+modal).attr('value',resp);
}
/**
 * Suppprimer la ligne partielle sans trait de personnalité à la modification
 * @param  int random Valeur aléatoire 
 * @return null
 */
function removeRowDimensionModif(random) {
	if ($('#modal-card-p-3-modif-row'+random).find('.row.addTrait').length == 1) {
		$($('.row.addTrait')[0]).find('.btn-ajout').attr('onclick','addRowModif("'+$($('.row-modif')[$('.row-modif').length-2]).attr('id').split('row')[1]+'")')
		$($('.row-modif')[$('.row-modif').length -2]).append($($('.row.addTrait')[0]).clone());
	}
	$('#modal-card-p-3-modif-row'+random).remove();
	setInputHiddenQuestion("row-modif");
}
/**
 * Modifier une ligne intégrale avec trait de personnalité de l'évaluation
 * @param int id 
 * @param string parent
 * @param string category
 * @param html self 
 * @return null
 */
function updateEvaluation(id, parent, category,self) {
	$('#modif-idEvaluation').attr('value',id);
	$('#date').attr('name','date_modif');
	// Récupérer la valeur du poste
	$.each($('#modif-select-idPoste option') ,function (k, v) {
		if ($(v).html().trim().toLowerCase() === $(self).parent().parent().find('.sorting_1').html().trim().toLowerCase()) {
			$('#modif-select-idPoste option[value=' + $(v).val() + ']').attr('selected', 'selected');
		}
	});
	getCategorieAjax(0, 'modif-', '');
	$('#modif-evaluation').attr('value', $($(self).parent().parent().children()[1]).html().trim());
	getEvaluationAjax(id);
	// Effacer le doublon d'affichage sur les selectpicker
	setTimeout(function () {
		$('.selectpicker').parent().parent().find('button:eq(1)').remove();
		$('.btn.dropdown-toggle').css('color','rgb(62, 71, 94)');
		setInputHiddenQuestion("row-modif");
	},1500);
	setTimeout(function () {
		$('#exampleModalLabelModif').empty();
		$('#exampleModalLabelModif').append("Modifier un modèle d'évaluations");
		$('#submitModif').empty();
		$('#submitModif').append("Enregistrer");
	} ,300);
}
/**
 * Complète les champs du formulaire de la modification
 * @param  
 * @return null
 */
function fillFildsUpdate() {
	$.each($('#hidden-modif-idParent').val().split(','),function (key, val) {
		if (val) {
			res=$('.card-p-3-modif').children('div.row')[key];
			res = $(res).children().find('.idParent');
			res = $(res).attr('id');
			$('#'+res+' option[value='+val+']').attr('selected','selected');
			getCategorieAjax(0, 'modif-', '');
		}
	} );


/*	setTimeout(function () {
		$.each($('#hidden-modif-idParent').val().split(','),function (key, val) {
			if (val) {
				if (key == 1) {
					getCategorieAjax(0, 'modif-', '');
					setTimeout(function () {
						$('#modif-select-idParent option[value='+val+']').attr('selected','selected');
					} ,1500);
				} else {
					res=$('.card-p-3-modif').children('div.row')[key];
					res = $(res).children().find('.idParent');
					res = $(res).attr('id');
					$('#'+res+' option[value='+val+']').attr('selected','selected');
				}
			}
		} );
	} ,1000);*/
	/*setTimeout(function () {
		$.each($('#hidden-modif-idCategorie').val().split(','), function (keyTwo, value) {
			if (value) {
				res=$('.card-p-3-modif').children('div.row')[keyTwo];
				te = $(res).children().find('.idCategorie');
				te = $(te).attr('id');
				$('#'+te+' option[value='+value+']').attr('selected','selected');
			}
		} );
	} ,2000);*/
}
/**
 * Création une évalution employées
 * @param int id 
 * @param string parent
 * @param string category
 * @param html self
 * @return null
 */
function createEvaluationUser(id , parent, category,self) {
	updateEvaluation(id , parent, category,self);
	$('#date').attr('name','dateCreation');
	//$('#formModif').attr('action',"create-evaluation");
	$('.card-p-3-modif').append('<div class="form-group hidden"> '+
									'<input class="form-control" id="hidden-modif-idEvaluateur" name="idEvaluateur" type="text" value=""> ' +
									'<p class="help-block text-danger"></p> ' +
								'</div> ' +
								'<div class="form-group hidden"> ' +
									'<input class="form-control" id="hidden-modif-idEvaluee" name="idEvaluee" type="text" value=""> ' +
									'<p class="help-block text-danger"></p> ' +
								'</div>'
	);
	$('.card-p-3-modif').append('<hr>');
	$('.card-p-3-modif').append(htmlEvaluee());
	$('.card-p-3-modif').append(htmlEvaluateur());
	$('.card-p-3-modif').append('<hr>');
    $('.row.row-modif').children().remove();
    $('.row.row-modif').append('<div class="row"> ' +
							              	'<div class="col-md-2" style="margin: 17px"> ' +
				  								'<span class="titre">Poste : </span> ' +
			  								'</div> ' +
			  								'<div class="col-md-7" style="padding-right: 138px"> ' +
				  								'<div class="form-group"> ' +
				  									'<i class="fa fa-tag icon-input" aria-hidden="true" style="color: #3a434f;margin-left: 4%;margin-top: 4%;"></i> ' +
								                  	'<select  class="form-control span3 focus_activated modal-input idPoste" style="width: 375px" name="idEntreprisePoste"' +
								                  	'id="ajout-select-idPoste" required="required" data-validation-required-message="Veuillez choisir un poste" disabled="disabled" > ' +
								                    	'<option class="text-center" value=""> '+$(self).parent().parent().find('.sorting_1').html().trim()+' </option> ' +
								                  	'</select> ' +
								                '</div> ' +
				  							'</div> ' +
						              	'</div> ');
    $('.card.p-3.card-p-3-modif').find('#modif-evaluation').val($($(self).parent().parent().find('td')[1]).html().trim());

	setTimeout(function () {
		$('#exampleModalLabelModif').empty();
		$('#exampleModalLabelModif').append("Créer une évaluation à l'employé");
		$('#submitModif').empty();
		$('#submitModif').append("Envoyer");
		$('#submitModif').attr('disabled','true');
		$('#submitModif').attr('data-target','#confirmationModal');
	    $('#submitModif').attr('data-toggle',"modal");
	    $('#submitModif').attr('type',"button");
	    $('#submitModif').attr('onclick',"getAllFormHidden(this)");
	} ,300);
}
/**
 * Récupérer les inputs hidden dans la form html vers la confirmation
 * @param html self
 * @return null
 */
function getAllFormHidden(self) {
	var res = $('.card-p-3-modif').get(0);
   	$('#formLibelle').val($('#modif-evaluation').val());
   	$.each(res.childNodes,function(key, value) {
   		if($(value).attr('class') == 'form-group hidden') {
   			$('#form-hidden').append(value);
   		} 
   	});
}
/**
 * Création une ligne d'évalution pour évaluer un employé
 * @param 
 * @return html
 */
function htmlEvaluee() {
	html  = '';
	html += '<div class="row" id="row-evaluee">';
	html +=			'<div class="col-md-3">';
	html +=				'<span class="titre">Employé à évaluer: </span>';
	html +=			'</div>';
	html +=			'<div class="col-md-7" style="padding-right: 39px; padding-left: 7px;">';
	html +=				'<div class="form-group">';
	html +=					'<i class="fa fa-user icon-input" aria-hidden="true" style="color: #3a434f;"></i>';
	html +=					'<select class="form-control font-weight-bold" id="idEvaluee" required="required"';
	html +=					'data-validation-required-message="Veuillez selectionner un employé" style="width: 100%; max-width: 100%;';
	html +=					'font-size: 0.8em!important;" onclick="getSuperieurHierarchique(this)" >';
	html +=						'<option value="">Selectionnnez l\'employé</option>';
								$.each(employes, function (key, value) {
									var id = 0;
									var nom = '';
									var prenom = '';
									$.each(value, function (keyTwo, valueTwo) {
										if (keyTwo == 'idEmploye') {
											id = valueTwo;
										} else if (keyTwo == 'prenom') {
											prenom = valueTwo;
										} else if (keyTwo == 'nom') {
											nom = valueTwo;
										}
									});
	html +=							'<option value="' + id + '">' + nom + '&nbsp;' + prenom + '</option>';
								});
	html +=					'</select>';
	html +=					'<p class="help-block text-danger"></p>';
	html +=				'</div>';
	html +=			'</div> ';
	html +=		'</div>';
	return html;
}
/**
 * Chercher tous les supérieurs hiérarchiques d'employé évaluée sélectionner
 * @param  int idSup
 * @return null
 */
function supHierarchique(idSup) {
	var dests = '';
	$.each(employes, function (key, value) {
		var id = 0;
		var superieur = 0;
		var nom = '';
		var prenom = '';
		if (value['idEmploye'] == idSup) {
			$.each(value, function (keyTwo, valueTwo) {
				if (keyTwo == 'idEmploye') {
					id 			= valueTwo;
				} else if (keyTwo == 'prenom') {
					prenom 		= valueTwo;
				} else if (keyTwo == 'nom') {
					nom 		= valueTwo;
				} else if (keyTwo == 'chefHierarchique') {
					superieur 	= valueTwo;
				} else if (keyTwo == 'email') {
					dests 		= id + ':' + valueTwo;
				}
			});
			$('#idEvaluateur').append('<option value="' + id+ '">' + nom + '&nbsp;' + prenom + '</option>');
			if (superieur > 0) {
				supHierarchique(superieur);
			}
		}
	});
}
/**
 * Récupère le supérieur hiérarchique d'employé évaluée
 * @param  html self Correspond à this 
 * @return null
 */
function getSuperieurHierarchique(self) {
	$('#hidden-modif-idEvaluee').attr('value', $(self).val());
	$('#idEvaluateur').empty();
	$('#idEvaluateur').append('<option value="" selected>Sélectionnez l\'évaluateur</option>');
	$.each(employes, function (key, value) {
		var superieur = 0;
		if (value['idEmploye'] == $(self).val()) {
			$.each(value, function (keyTwo, valueTwo) {
 				if (keyTwo == 'chefHierarchique') {
					superieur = valueTwo;
				}
			});
			$('#hidden-modif-idEvaluateur').attr('value', superieur);
			supHierarchique(superieur);
		}
	});
	if ($('#idEvaluateur option').length < 3 ) {
		$('.btn-deleteLines').attr('disabled', true);
		$('.btn-addLines').attr('disabled', true);
	} else {
		$('.btn-deleteLines').attr('disabled', false);
		$('.btn-addLines').attr('disabled', false);
	}
}
/**
 * Récupère les ID évaluateurs lors de la sélection à DOM
 * @param  
 * @return null
 */
function getIdEvaluateur() {
	var id = []; 
	$('.idEvaluateur').each(function(key, val){ 
		var select = $(this).val(); 
		id.push(select); 
	});
	$('input:hidden[name=idEvaluateur]').attr('value', id.join(','));
	if ($('#hidden-modif-idEvaluee').val() != "") {
		$('#submitModif').prop("disabled", false);
	}
}
/**
 * Création une ligne d'évalution pour les évaluateurs
 * @param 
 * @return html
 */
function htmlEvaluateur() {
	html = '<div class="row" id="row-evaluateur"> '+
				'<div class="row col-md-12"> '+ 
					'<div class="col-md-3" style="font-size: 1.1em;"> '+
						'<span class="titre">Évaluateurs: </span> '+
					'</div> '+
					'<div class="col-md-7"> '+
						'<div class="form-group"> '+
							'<i class="fa fa-user icon-input" aria-hidden="true" style="color: #3a434f;"></i> '+
							'<select class="form-control font-weight-bold idEvaluateur" onclick="getIdEvaluateur()" id="idEvaluateur" required="required" '+
							'data-validation-required-message="Veuillez selectionner un employé" '+
							'style="width: 100%; max-width: 100%; font-size: 0.8em!important;"> '+
								'<option value="">Selectionnnez l\'évaluateur</option> ';
								$.each(employes, function (key, value) {
									var id = 0;
									var nom = '';
									var prenom = '';
									$.each(value, function (keyTwo, valueTwo) {
										if (keyTwo == 'idEmploye') {
											id = valueTwo;
										} else if (keyTwo == 'prenom') {
											prenom = valueTwo;
										} else if (keyTwo == 'nom') {
											nom = valueTwo;
										}
									});
	html +=							'<option value="' + id + '">' + nom + '&nbsp;' + prenom + '</option>';
								});
	html +=					'</select> '+
							'<p class="help-block text-danger"></p> '+
						'</div> '+
					'</div> '+
					'<div class="col-md-2" style="padding-top: 1px;"> '+
						'<div class="form-group"> '+
							'<button type="button" class="btn btn-addLines" disabled="true" onclick="addRowAjoutEvaluation(this)" style="background: #6e9e89;" > '+
								'<i class="fa fa-plus" aria-hidden="false"></i> '+
							'</button> '+
				  			'<button type="button" class="btn btn-deleteLines" disabled="true" id="times_close" onclick="removeRowAjoutEvaluation(this)" style="background: #d62037;"> '+
								'<i class="fas fa-times" ></i>  '+
				  			'</button> '+
				  		'</div> '+
					'</div> '+
				'</div> '+
			'</div> ';
	return html;
}
/**
 * Génère l'affichage de la modification
 * @param  int random Valeur aléatoire
 * @return null
 */
function modifElementHtml(random, partial = null) {
	// Ajouter une ligne sur le DOM
	nextRandom 	= getRandom();
	if (partial) {
		$('#modal-card-p-3-modif-row'+random).clone().insertAfter($('#modal-card-p-3-modif-row'+random));
		// $('.btn-modif',element).attr('onclick','addRowModif("'+nextRandom+'","partial")');
	} else {
		$('.card-p-3-modif').append($('#modal-card-p-3-modif-row'+random).clone());
		// Récupère la dernière ligne de modif
		element 	= $('.card-p-3-modif:eq(-1)').children('.row-modif')[$('.card-p-3-modif:eq(-1)').children('.row-modif').length-1];
		// Changer les attributs d'ID d'éléments
		$('.btn-modif',element).attr('id','btn-modif'+nextRandom);
		$('.btn-modif',element).attr('onclick','addRowModif("'+nextRandom+'")');
		$('.btn-delete',element).attr('id','btn-delete'+nextRandom);
		$('.btn-delete',element).attr('onclick','removeRowModif(this)');
		$('#modif-select-idParent',element).attr('onclick','getIdParent("modif-","'+nextRandom+'")');
		$('#modif-select-idParent',element).attr('id','modif-select-idParent'+nextRandom);
		$('#modif-select-idCategorie',element).attr('id','modif-select-idCategorie'+nextRandom);
		$(element).attr('id','modal-card-p-3-modif-row'+nextRandom);
	}
}
/**
 * Effacer une ligne lors de la modification
 * @param  
 * @return null
 */
function removeRowModif(self) {
	$(self).parent().parent().parent().remove();
	var id = [];
	$('.idParent').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=parent]').attr('value', id.join(','));
	getIdCategory();
}
/**
 * Ajouter une ligne lors de la modification
 * @param  
 * @return null
 */
function addRowModif(random, partial = null) {
	modifElementHtml(random, partial);
	var element;
	var tmp;
	if (partial) {
		$.map($('.p-3.card-p-3-modif').children(), function(item, index) {
		    if ($(item).attr('id') == 'modal-card-p-3-modif-row'+random) {
				element = item;
				// tmp = index;
		    }
		});
		nextRandom = getRandom();
		// $($('.p-3.card-p-3-modif').children()[tmp]).attr('id','modal-card-p-3-modif-row'+nextRandom);  // cibler l'index de la ligne
		$(element).attr('id','modal-card-p-3-modif-row'+nextRandom);  // cibler l'index de la ligne
		$('.btn.btn-ajoutDimModif',element).attr('onclick','addRowModif("'+nextRandom+'", "partial")');
		$($('#modal-card-p-3-modif-row'+nextRandom).children()[0]).children().remove(); // Effacer le contenu du trait de personnalité (s'il y en a)
	} else {
		element = $('.card-p-3-modif:eq(-1)').children('.row-modif')[$('.card-p-3-modif:eq(-1)').children('.row-modif').length-1];
		nextRandom 	= $(element).attr('id').split('row')[1];
		$('#modif-select-idParent'+nextRandom+' option[value=""]').attr('selected','selected'); // Rien à sélectionner pour trait de personnalité
		$('#modif-select-idParent'+random,element).attr('onclick','getIdParent("modif-","'+nextRandom+'")');
		$('#modif-select-idParent'+random,element).attr('id','modif-select-idParent'+nextRandom);
		$('.row.addTrait:eq(0)').remove();
		$('#btn-ajout'+random, element).attr('onclick', 'addRowModif("'+nextRandom+'")');
		$('#btn-ajout'+random, element).attr('id', 'btn-ajout'+nextRandom);
	}
	$('#modif-select-idCategorie'+random,element).attr('id','modif-select-idCategorie'+nextRandom);
	$('.btn.btn-deleteDimModif', element).attr('onclick', 'removeRowDimensionModif("'+nextRandom+'")');
	$('.selectpicker',element).attr('id','idQuestion'+nextRandom);
	$('#idQuestion'+nextRandom+' option').remove();
	$('#idQuestion'+nextRandom).selectpicker('refresh');
	$($('.selectpicker',element).parent().parent().find('button')[1]).remove(); // Enlever le doublon du selectpicker
	$('.btn.dropdown-toggle',element).css('color','rgb(62, 71, 94)'); // couleur du dropdown selectpicker 
	getCategorieAjax(0, 'modif-', nextRandom);
}
/**
 * Effacer une ligne lors de l'ajout d'évaluer employé
 * @param  html self Correspond à this
 * @return null
 */
function removeRowAjoutEvaluation(self) {
	$(self).parent().parent().parent().remove();
	getIdEvaluateur();
}
/**
 * Ajouter une ligne lors de l'ajout d'évaluer employé
 * @param  html self Correspond à this
 * @return null
 */
function addRowAjoutEvaluation(self) {
	html 	=	'<div class="row col-md-12"> '+
					'<div class="col-md-3"> </div> '+
					'<div class="col-md-7"> ';
	html 	+= 			$(self).parent().parent().parent().find('.col-md-7').html();
	html 	+= 		'</div> '+
					'<div class="col-md-2" style="padding-top: 1px;"> '+
						'<div class="form-group"> '+
							'<button type="button" class="btn btn-modif" onclick="addRowAjoutEvaluation(this)" style="background: #6e9e89;" > '+
								'<i class="fa fa-plus" aria-hidden="false"></i> '+
							'</button> '+
				  			'<button type="button" class="btn btn-delete" onclick="removeRowAjoutEvaluation(this)" style="background: #d62037;"> '+
								'<i class="fas fa-times" id="times_close" ></i>  '+
				  			'</button> '+
		  				'</div> '+
	  				'</div> '+
				'</div> ';
	$('#row-evaluateur').append(html);
}
/**
 * Effacer les champs auparavant
 * @param  
 * @return null
 */
function cleanFildsBeforeShown() {
	test = $('.card-p-3-modif:eq()').children('.row');
	if (test.length > 2) {
		$.each(test, function(key, value){ 
			if(key > 1) {  
				value.remove();
			}
			// Changer le nom du parent par défault
			if (key == 1) {
				$(value).attr('id','modal-card-p-3-modif-row');
			}
		});
	}
}
// Récuperer la liste de la dimension
/*	function getSousCategorieAjax (id, motif, random = null) {
		$.ajax({
			url : host+"/manage/entreprise/evaluation/getCategorie",
			data : "idCategorie=" + id,
			dataType : "json",
			success : function(data)
			{
				if (id > 0) {
					$.each(data, function(key,value) {
					    if (key == "idCategorie") {
						    getQuestionAjax(host, value, motif+'-select-idSousCategorie'+random);
					    	result = value;
					    } else if (key == "libelle") {
					    	optionText = value;
					    }
					});
				} else {
					$('#'+motif+'select-idSousCategorie').empty();
					$('#'+motif+'select-idSousCategorie').append('<option value="" selected>Sélectionnez une dimension</option>');
					$.each(data, function(key,value) {
						$.each(value, function(indice,donnee) {
						    if (indice == "idCategorie") {
						    	optionValueCategorie = donnee;
						    } else if (indice == "libelle") {
						    	optionTextCategorie = donnee;
						    }
						});
						$('#'+motif+'select-idSousCategorie'+random).append('<option value="' + optionValueCategorie + '">' + optionTextCategorie + '</option>');
					});
				}
			},
			error: function (error) {
			    console.log('error; ' + eval(error));
			}
		});
	}*/




// Récuperer la liste de la dimension ainsi que les/la question(s) sélectionnée(s)
function getEvaluationAjax(idEval) {
	$.ajax({
		url : host+"/manage/entreprise/evaluation/getEvaluationAjax",
		data : "idEvaluation=" + idEval,
		dataType : "json",
		success : function(data)
		{
			console.log(data);
				random 			= getRandom();
			cleanFildsBeforeShown();
			$('#modal-card-p-3-modif-row').attr('id', 'modal-card-p-3-modif-row'+random);
			var indice = -1;
			$.each(data, function (k,v) {
				if (k > 0) {
					modifElementHtml(random);
				}
				$('.modal-input.idParent:eq('+k+') option[value='+v.parent.libelle+']').attr('selected', 'selected'); // champs parent
				$('#hidden-modif-idParent').attr('value',$('#hidden-modif-idParent').val()+','+v.parent.libelle);
				$.each(v.category, function (ke,val) {
					indice++;
					if (ke > 0 ) {
						modifElementHtml($($('.row.row-modif:eq('+ (ke - 1) +')')[0]).attr('id').split('row')[1]); // Ajout une ligne
						$($($('.row.row-modif:eq('+ indice +')')[0]).children()[0]).children().remove(); // Supprimer le contenu du trait
					}
					var currentRandum = $('.selectpicker').eq(indice).parent().parent().parent().attr('id').split('row')[1];
					// Assigner nouvelle ID à la dimension
					if ($($('.modal-input.idCategorie')[indice]).attr('id') != 'modif-select-idCategorie'+currentRandum) {
						$($('.modal-input.idCategorie')[indice]).attr('id', 'modif-select-idCategorie'+currentRandum);
					}
					$('.modal-input.idCategorie option[value='+val.sousCategories.idCategorie+']').eq(indice).attr('selected','selected'); // Affecter une valeur à la dimension
					getQuestionAjax(host,val.sousCategories.idCategorie, currentRandum);
					$('.selectpicker').eq(indice).attr('id','idQuestion'+currentRandum); // Nouvelle ID question actuelle
					setTimeout(function () {
						$.each(val.questionnaires, function (key,value) {
							$('#idQuestion'+currentRandum+' option[value='+value.idQuestion+']').attr('selected','selected');
						});
						$('#idQuestion'+currentRandum).selectpicker('refresh');
					} ,1000);
					$('#modal-card-p-3-modif-row'+currentRandum).find('.btn.btn-deleteDimModif').attr('onclick','removeRowDimensionModif('+currentRandum+')');
					$('#modal-card-p-3-modif-row'+currentRandum).find('.btn.btn-ajoutDimModif').attr('onclick','addRowModif("'+currentRandum+'", "partial")');
				});
				// Effacer le  bouton d'ajoutr un trait au-dessus
				if ($('.row.addTrait').length  > 1)  {
					$('.row.addTrait').eq(0).remove();
				}
			});

			$('.btn.btn-deleteDimModif').removeAttr('hidden');
			var tmpRand = $('.row.row-modif:eq('+indice+')').attr('id').split('row')[1];
			// Enlever le paramètre "partial" dans addRowModif sur on click
			$($('.btn.btn-ajoutDimModif')[indice]).attr('onclick', 'addRowModif("'+tmpRand+'")');
			console.log(tmpRand)
			$('.row.row-modif:eq('+indice+')').append($($('.row.addTrait')[0]).clone());
			$($('.row.addTrait')[0]).find('.btn.btn-ajout').attr('id','btn-ajout'+tmpRand);
			$($('.row.addTrait')[0]).find('.btn.btn-ajout').attr('onclick','addRowModif("'+tmpRand+'")');
			$($('.row.addTrait')[0]).find('.btn.btn-delete').attr('onclick','removeRow("'+tmpRand+'", "modif")');
			/* TSY BOLA OPERATIONNELLE ITY
				$('.row.row-modif:eq('+indice+')').find('.btn-ajout').attr('onclick', 'addRow("'+tmpRand+'", "ajout")');
				$('.row.row-modif:eq('+indice+')').find('.btn-ajout').attr('id', 'btn-ajout'+tmpRand);
			*/
		},
		error: function (error) {
		    console.log('error; ' + eval(error));
		}
	});
}