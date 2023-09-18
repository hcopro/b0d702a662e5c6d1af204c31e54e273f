$(document).ready(function(){
	// Remplir la formulaire ajoutée
	$('.btn-sm').click(function () {
		random = getRandom();
		$('.btn-ajout').attr('id', 'btn-ajout'+random);
		$('.btn-delete').attr( 'id','btn-delete'+random);
		$('.btn-ajout',).attr('onclick','addRow("'+random+'")');
		$('.btn-delete',).attr('onclick','removeRow("'+random+'")');
		$('#modal-p-3-row').attr('id', 'modal-p-3-row'+random);
		getCategorieAjax(0, 'ajout-', '');
	});
});
/**
 * Récupère les ID catégories de la sélection à DOM
 * @param  
 * @return null
 */
function getIdCategory() {
	var id=[]; 
	$('.idCategorie').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=id_categorie]').attr('value', id.join(','));
}
/**
 * Récupère les ID parents de la catégorie de la sélection à DOM
 * @param  string motif String motif de l'action
 * @param  int random Valeur aléatoire
 * @return null
 */
function getIdParent(motif, random = '') {
	getSousCategorieAjax(host, $('#'+motif+'select-idCategorie'+random).val(), motif+'select-idSousCategorie'+random, null);
	var id=[];
	$('.idParent').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=id_parent]').attr('value', id.join(','));
}
/**
 * Récupère la valeur aléatoire
 * @param  int random Valeur aléatoire
 * @return null
 */
function addElementHtml(random) {
	// Ajouter une ligne sur le DOM
	nextRandom = getRandom();
	$('.p-3-addModal').append('<hr id="ligne'+nextRandom+'">');
	// Récupère la dernière ligne d'ajout
	element = $('.p-3-addModal:eq(-1)').children('.modalCreate')[$('.p-3-addModal:eq(-1)').children('.modalCreate').length-1];
	selector = $(element).attr('id');
	$('.p-3-addModal').append($('#'+selector).clone());
	// Changer les attributs d'ID d'éléments
	$('#btn-ajout'+random,element).attr('id','btn-ajout'+nextRandom);
	$('#btn-delete'+random,element).attr('id','btn-delete'+nextRandom);
	$('#btn-ajout'+nextRandom,element).attr('onclick','addRow("'+nextRandom+'")');
	$('#btn-delete'+nextRandom,element).attr('onclick','removeRow("'+nextRandom+'")');
	$('#ajout-select-idCategorie',element).attr('onclick','getIdParent("ajout-","'+nextRandom+'")');
	$('#ajout-select-idCategorie',element).attr('id','ajout-select-idCategorie'+nextRandom);
	$('#ajout-select-idSousCategorie',element).attr('id','ajout-select-idSousCategorie'+nextRandom);
	$(element).attr('id','modal-p-3-row'+nextRandom);
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
	$('#modal-p-3-row'+random).remove();
	$('#ligne'+random).remove();
	getIdParent('ajout-', random);
	getIdCategory();
}
/**
 * Ajouter la ligne à l'ajout
 * @param  int random Valeur aléatoire
 * @return null
 */
function addRow(random) {
	addElementHtml(random);
}
/**
 * Modifier une ligne de l'évaluation
 * @param int id 
 * @param string parent
 * @param string category
 * @param html self 
 * @return null
 */
function updateEvaluation(id , parent, category,self) {
	$('#formModif').attr('action',"update-evaluation_model");
	$('#modif-idEvaluation').attr('value',id);
	$('#date').attr('name','date_modif');
	$('#modif-evaluation').attr('value', $(self).parent().parent().find('.sorting_1').html().trim());
	if ($('#row-evaluee').length > 0 && $('#row-evaluateur').length > 0) {
		$('#row-evaluee').remove();
		$('#row-evaluateur').remove();
		$('hr').remove();
	}
	if (!$('#hidden-modif-idParent').val() || $('#hidden-modif-idParent').val() !== parent) {
		cleanFildsBeforeShown();
		$('#hidden-modif-idParent').attr('value',parent);
		$('#hidden-modif-idCategorie').attr('value', category);
		random = getRandom();
		$('#modal-card-p-3-modif-row').attr('id', 'modal-card-p-3-modif-row'+random);
		$.each(parent.split(','), function(key,value) {
			if (key > 0 && value && key < parent.split(',').length-1) {
				modifElementHtml(random);
			}
		});
		fillFildsUpdate();
	} else {
		// Enlever les champs ne sont plus utiles
		if ($('.card-p-3-modif:eq()').children('.form-group.hidden').length > 4) {
			$.each($('.card-p-3-modif:eq()').children('.form-group.hidden'), function (key, value){
				if (key > 3) {
					$(value).remove();
				}
			});
		}
	}
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
	setTimeout(function () {
		$.each($('#hidden-modif-idParent').val().split(','),function (key, val) {
			if (val) {
				if (key == 1) {
					getCategorieAjax(0, 'modif-', '');
					getSousCategorieAjax(host, val, 'modif-select-idSousCategorie','','');
					setTimeout(function () {
						$('#modif-select-idCategorie option[value='+val+']').attr('selected','selected');
					} ,1500);
				} else {
					res=$('.card-p-3-modif').children('div.row')[key];
					res = $(res).children('div.col-md-5').find('.idParent');
					res = $(res).attr('id');
					$('#'+res+' option[value='+val+']').attr('selected','selected');
					getSousCategorieAjax(host, val, 'modif-select-idSousCategorie'+res.split('Categorie')[1],'','');
				}
			}
		} );
	} ,1000);
	setTimeout(function () {
		$.each($('#hidden-modif-idCategorie').val().split(','), function (keyTwo, value) {
			if (value) {
				res=$('.card-p-3-modif').children('div.row')[keyTwo];
				te = $(res).children('div.col-md-5').find('.idCategorie');
				te = $(te).attr('id');
				$('#'+te+' option[value='+value+']').attr('selected','selected');
			}
		} );
	} ,2000);
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
	$('#date').attr('name','date_creation');
	$('#formModif').attr('action',"create-evaluation");
	$('.card-p-3-modif').append('<div class="form-group hidden"> '+
									'<input class="form-control" id="hidden-modif-idEvaluateur" name="id_evaluateur" type="text" value=""> ' +
									'<p class="help-block text-danger"></p> ' +
								'</div> ' +
								'<div class="form-group hidden"> ' +
									'<input class="form-control" id="hidden-modif-idEvaluee" name="id_evaluee" type="text" value=""> ' +
									'<p class="help-block text-danger"></p> ' +
								'</div>'
	);
	$('.card-p-3-modif').append('<hr>');
	$('.card-p-3-modif').append(htmlEvaluee());
	$('.card-p-3-modif').append(htmlEvaluateur());
	$('.card-p-3-modif').append('<hr>');
	setTimeout(function () {
		$('#exampleModalLabelModif').empty();
		$('#exampleModalLabelModif').append("Créer une évaluation à l'employé");
		$('#submitModif').empty();
		$('#submitModif').append("Envoyer");
	} ,300);
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
	html +=			'<div class="col-md-7">';
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
					id = valueTwo;
				} else if (keyTwo == 'prenom') {
					prenom = valueTwo;
				} else if (keyTwo == 'nom') {
					nom = valueTwo;
				} else if (keyTwo == 'chefHierarchique') {
					superieur = valueTwo;
				} else if (keyTwo == 'email') {
					dests = id + ':' + valueTwo;
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
	$('#idEvaluateur').append('<option value="" selected>Selectionnez l\'évaluateur</option>');
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
	var id=[]; 
	$('.idEvaluateur').each(function(key, val){ 
		var select = $(this).val(); 
		id.push(select); 
	});
	$('input:hidden[name=id_evaluateur]').attr('value', id.join(','));
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
function modifElementHtml(random) {
	// Ajouter une ligne sur le DOM
	nextRandom = getRandom();
	$('.card-p-3-modif').append($('#modal-card-p-3-modif-row'+random).clone());
	// Récupère la dernière ligne de modif
	element = $('.card-p-3-modif:eq(-1)').children('.row-modif')[$('.card-p-3-modif:eq(-1)').children('.row-modif').length-1];
	// Changer les attributs d'ID d'éléments
	$('.btn-modif',element).attr('id','btn-modif'+nextRandom);
	$('.btn-delete',element).attr('id','btn-delete'+nextRandom);
	$('.btn-modif',element).attr('onclick','addRowModif("'+nextRandom+'")');
	$('.btn-delete',element).attr('onclick','removeRowModif(this)');
	$('#modif-select-idCategorie',element).attr('onclick','getIdParent("modif-","'+nextRandom+'")');
	$('#modif-select-idCategorie',element).attr('id','modif-select-idCategorie'+nextRandom);
	$('#modif-select-idSousCategorie',element).attr('id','modif-select-idSousCategorie'+nextRandom);
	$(element).attr('id','modal-card-p-3-modif-row'+nextRandom);
	getCategorieAjax(0, 'modif-', nextRandom);
}
/**
 * Effacer une ligne lors de la modification
 * @param  
 * @return null
 */
function removeRowModif(self) {
	$(self).parent().parent().parent().remove();
	var id=[];
	$('.idParent').each(function(key, val){ 
		var select = $(this).val();
		id.push(select);
	});
	$('input:hidden[name=id_parent]').attr('value', id.join(','));
	getIdCategory();
}
/**
 * Ajouter une ligne lors de la modification
 * @param  
 * @return null
 */
function addRowModif(random) {
	modifElementHtml(random);
	element = $('.card-p-3-modif:eq(-1)').children('.row-modif')[$('.card-p-3-modif:eq(-1)').children('.row-modif').length-1];
	nextRandom = $(element).attr('id').split('row')[1];
	$('#modif-select-idCategorie'+random,element).attr('onclick','getIdParent("modif-","'+nextRandom+'")');
	$('#modif-select-idCategorie'+random,element).attr('id','modif-select-idCategorie'+nextRandom);
	$('#modif-select-idSousCategorie'+random,element).attr('id','modif-select-idSousCategorie'+nextRandom);
	idTmp = $('#modif-select-idCategorie option:selected').val();
	getCategorieAjax(0, 'modif-', '');
	if (idTmp > 0) {
		setTimeout(function () {
			$('#modif-select-idCategorie').val(idTmp);
		},1500);
	}
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