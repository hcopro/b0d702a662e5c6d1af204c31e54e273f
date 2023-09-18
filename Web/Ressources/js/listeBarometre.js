/**
 * @Author Lansky 17/03/2022
 * */
	const FILTER_GROUP_ALL     		= 1;
	const FILTER_GROUP_POSTE   		= 2;
	const FILTER_GROUP_EMPLOYE 		= 3;
	const FILTER_GROUP_SERVICE 		= 4;
	const MAX_INTERVAL         		= 3;
	const TODAY      				= 1;
	const TOMORROW   				= 2;
	const YESTERDAY  				= 3;
	const THIS_WEEK  				= 4;
	const NEXT_WEEK  				= 5;
	const LAST_WEEK  				= 6;
	const THIS_MONTH 				= 7;
	const NEXT_MONTH 				= 8;
	const LAST_MONTH 				= 9;
	const FILTER_STATUS_ALL			= 0;
	const FILTER_STATUS_REPLY		= 1;
	const FILTER_STATUS_NO_REPLY	= 2;

	var hostname 					= new URL(window.location.href).hostname;
	var pathName 					= new URL(window.location.href).pathname;
	const HOST						= 'http://' + hostname + '/';
				$('document').ready(function(){
			var listenEvent = false;
			if ($('#filter-group').val() == FILTER_GROUP_ALL) {
				$("#" + listId).load(HOST + "manage/" + loadUrl, {
					groupe    	: $('#filter-group').val(),
					periode   	: $('#periode').val(),
					debut     	: $('#debut').val(),
					fin       	: $('#fin').val(),
					mois      	: $('#selection-month').val(),
					etat      	: $('#filter-etat').val(),
					dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
				}, function() {
					$('.option-tout').removeClass('invisible');
					$('.option-service').addClass('invisible');
					$('.option-poste').addClass('invisible');
					$('.option-employe').addClass('invisible');
				});
			}
			$('.carousel').carousel({
				interval: false,
			});
			$('.carousel').carousel('pause');
		});
		$('#filter-group').change(function(){
			$("#" + listId).load(HOST + "manage/" + loadUrl, {
				groupe    	: $('#filter-group').val(),
				periode   	: $('#periode').val(),
				debut     	: $('#debut').val(),
				fin       	: $('#fin').val(),
				mois      	: $('#selection-month').val(),
				etat      	: $('#filter-etat').val(),
				dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
			}, function() {
			});
		});
		$('#filter-last-date-receive').change(function(){
			$("#" + listId).load(HOST + "manage/" + loadUrl, {
				groupe    	: $('#filter-group').val(),
				periode   	: $('#periode').val(),
				debut     	: $('#debut').val(),
				fin       	: $('#fin').val(),
				mois      	: $('#selection-month').val(),
				etat      	: $('#filter-etat').val(),
				dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
			}, function() {
			});
		});


		$('#periode').change(function() {
			$("#" + listId).load(HOST + "manage/" + loadUrl, {
				groupe    	: $('#filter-group').val(),
				periode   	: $('#periode').val(),
				etat      	: $('#filter-etat').val(),
				dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
			}, function() {
				$("#debut").val(null);
				$("#fin").val(null);
				$('#selection-month').val("");
			});
		});

	$('#debut').change(function() {
		$("#" + listId).load(HOST + "manage/" + loadUrl, {
			groupe    	: $('#filter-group').val(),
			debut     	: $('#debut').val(),
			fin       	: $('#fin').val(),
			etat      	: $('#filter-etat').val(),
			dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
		}, function() {
			$("#periode").val("");
			$('#selection-month').val("");
			$('#fin').val("");
			var stringDate = $('#debut').datepicker('getDate');
			var date       = new Date(stringDate.getFullYear(), stringDate.getMonth() + MAX_INTERVAL, stringDate.getDate());
		    $('#fin').datepicker('option', 'maxDate', date);
		});
	});

	$('#fin').change(function() {
		$("#" + listId).load(HOST + "manage/" + loadUrl, {
			groupe    	: $('#filter-group').val(),
			debut     	: $('#debut').val(),
			fin       	: $('#fin').val(),
			etat      	: $('#filter-etat').val(),
			dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
		}, function() {
			$("#periode").val("");
			$('#selection-month').val("");
		});
	});

	$('#selection-month').change(function() {
		$("#" + listId).load(HOST + "manage/" + loadUrl, {
			groupe    	: $('#filter-group').val(),
			mois      	: $('#selection-month').val(),
			etat      	: $('#filter-etat').val(),
			dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null
		}, function() {
			$("#periode").val("");
			$('#debut').val(null);
			$('#fin').val(null);
		});
	});

	$('#filter-etat').change(function() {
		$("#" + listId).load(HOST + "manage/" + loadUrl, {
			groupe    	: $('#filter-group').val(),
			debut     	: $('#debut').val(),
			fin       	: $('#fin').val(),
			periode   	: $('#periode').val(),
			mois      	: $('#selection-month').val(),
			etat      	: $('#filter-etat').val(),
			dateReceive	: $('#filter-last-date-receive').length > 0 ? $('#filter-last-date-receive').val() : null	
		});
	});

$(window).on('load', function(){
	tippy('.fa-eye', {
		content:"Voir détails"
	});
	tippy('.fa-check', {
		content:"Accepter"
	});
	tippy('.fa-trash', {
		content:"Archiver"
	});	
	$('document').ready(function () {
		$('.archivedModal').click(function () {
			$('#action-archive').attr('href',$(this).attr('data-url'));
		});
		$('.confirmationModal').click(function () {
			$('#action-archive').attr('href',$(this).attr('data-url'));
		});
		if (pathName == '/manage/barometre') {
			$('.btn.send').click(function() {
				$('.btn.btn-light').css('color','#3e475e');
				$('#action-envoie').attr('href',$(this).attr('href'));
			});
			$('#action-envoie').click(function() {
				if ($('.modal-input.select-employe.selectpicker').val().length == 0) {
					$(".msg-empty").html("* Veuillez sélectionner au moins un employé").addClass("error-msg");
				} else {
					$('#confirmationModal').modal('show');
					$('#modalSendBarometre').removeClass('show');
					$('p#text-confirmation').text('Voulez-vous vraiment envoyer ce baromètre ?');
					$('#action-confirmation').attr('href',$(this).attr('href')+'&listIdEmploye='+$('.modal-input.select-employe.selectpicker').val().join());
				}
			});
		} else if (pathName == '/manage/employe/barometre') {
			$('.verify').click(function() {
				$('#confirmationModal').modal('show');
				$('#textModal').modal('hide');
				$('p#text-confirmation').html("Voulez-vous vraiment répondre ce baromètre ? <br/> Il n'y a pas de retour en arrière lors de démarrage!!!");
				$('.btn.deney-modal').text('Pas maintenant');
			});
			$('#action-confirmation').click(function(){
				$('#confirmationModal').modal('hide');
				$('#responseBarometerModal').modal('show');
				$('#action-responseBarometer').attr('hidden',true);
				$('#responseBarometerModal').unbind('click');
				$.ajax({
					url : HOST + "/manage/getBarometer",
					data : "idBarometre=" + $('.verify').attr('id'),
					dataType : "json",
					success : function(data)
					{
						var indx = 0;
						$.each(data.contents, function(index, classify) {
							indx 			= index;
							indRmq 			= 0;
							dNone 			= index == 0 ? '' : 'd-none';
							let classGroupe = typeof(classify.class) === 'undefined' ? (typeof(classify.periode) === 'undefined' ? '<div></div>' : periodText(classify.periode)) : periodText(classify.class);
							let divClass 	= classGroupe === "undefined" ? '' : '<div class="row text-center" style="background: linear-gradient(to bottom, #F29430 0%, #F26F30 100%);align-items: center;">' +
																		'<span class="label label-info" style="color:#ffffff; font-size: 21px; font-style: italic; font-family: monospace;"> '+classGroupe+'</span>' +
																	'</div>';
							$("#text-responseBarometer").append('<div class="form-groupe period text-center ' + dNone + '" id="period-' + index + '">' +
																	divClass +
																'</div>');
							/*$("#text-responseBarometer").append('<div class="form-groupe period text-center ' + dNone + '" id="period-' + index + '">' +
																	'<div class="row text-center" style="background: linear-gradient(to bottom, #F29430 0%, #F26F30 100%);align-items: center;">' +
																		'<span class="label label-info" style="color:#ffffff; font-size: 21px; font-style: italic; font-family: monospace;"> '+classGroupe+'</span>' +
																	'</div>' +
																'</div>');*/
							$.each(classify.questions, function(indexPeriod, questions) {
								++indRmq;
								var dblock = indexPeriod == 0 && !dNone.trim() ? 'd-block' : '';
							  	html = ' <div class="quiz ' + dblock +  '" id="quiz-' + index+'-'+ indexPeriod  + ' " style="text-align-last: left; margin-left: 10%;"> ' +
							  				'<label> <strong>' +questions.question.replace(/\n/g, '<br>&emsp;').replace(/\t/g, '&emsp;')+ '</strong></label> ';
							  	$.each(questions.choise, function(indexChoix, valChoix) {
							  		html +='<div class="form-check text-center" style="margin-left: 8%;">' +
												'<input class="form-check-input" type="radio" onclick="getChecked(this)" name="flexRadio' + index+'-'+ indexPeriod  + '" id="flexRadio-'+ index +'-' +indexPeriod +'-' + indexChoix +'">' +
												'<label class="form-check-label" for="flexRadio-'+ index +'' +indexPeriod +'' + indexChoix +'">'+valChoix+'</label>' +
											'</div>';
							  	})
							  	html+= ' </div>';
							  	$("#period-" + index).append(html);
							  	html = '';
							});
							var myObject = {'id': 1, 'name': 'Paresh', 'city': 'Rajkot'};
     
						    if(classify.hasOwnProperty('remarque')){
						       if (classify.remarque.trim()) {
									html =  ' <div class="quiz" id="quiz-' + index+'-'+indRmq+'"> ' +
					  							'<label> ' +
					  								'<strong>' + classify.remarque + '</strong>'+
			  									'</label>' +
			  									'<textarea name="remarque'+index+'-'+indRmq+'" class="form-control focus_activated input-remarque" rows="3"></textarea>' +
			  									'<button class="btn-info suivant" onclick="btnSuiv(this)" style="margin-left: 670px;">Suivant&nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></button>' +
			  								'</div>';
								  	$("#period-" + index).append(html);
								  	html = '';
								}
						    }
						});
						indx += 1;
						$("#text-responseBarometer").append('<div class="form-groupe period text-center  d-none" id="period-' + indx + '">' +
																	'<span class="label label-info" style=" font-size: 29px; font-style: italic; font-family: monospace;"> '+
																		'Suggestions et/ou demandes particulières' +
																	'</span>' +
																'</div>');
						$("#period-" + indx).append(' <div class="quiz" id="quiz-' + indx+'"> ' +
							  							'<label> ' +
							  								'<strong>' +
							  									'Partage nous ici tes idées, tes besoins ou un problème particulier que tu rencontres en ce moment ' +
							  									'et qui te contrarie particulièrement. Ton avis compte énormément pour nous.' +
						  									'</strong>'+
					  									'</label>' +
					  									'<textarea name="suggestion" class="form-control focus_activated input-suggestion" rows="5"></textarea>'
					  								);
						$('.quiz').css('display','none');
					},
					error: function (error) {
					    console.log('error: ' + eval(error));
					}
				});
			});
		} else if (pathName == '/manage/linear_answers_barometer') {
			$('#exportCsv').click(function() {
				var url = $(this).parent().attr('action');
				var addUrl = '?groupe=' + $('#filter-group').val() +
					'&periode=' 	+ $('#periode').val() +
					'&debut=' 		+ $('#debut').val() +
					'&fin=' 		+ $('#fin').val() +
					'&mois=' 		+ $('#selection-month').val() +
					'&etat=' 		+ $('#filter-etat').val() +
					'&dateReceive=' + ($.trim($('#filter-last-date-receive').val()) ? $('#filter-last-date-receive').val() : '');
				url += addUrl;
				$(this).parent().attr('action',url);
			});
		}
	});
});
$('.deney-modal').click(function(){
	window.location.reload();
});

// Converting JS object to an array
function objectToArray (myObj) {
    var array = $.map(myObj, function(value, index){
        return [index + ':' + value];
    });
}

function finished () {
	var res = [];
	$.each($('.form-check-input:checked'), function(index, val) {
	  	res[index] = $(val).attr('id');
	});
	let rmq = '';
	$.each($('.quiz').find('.input-remarque'), function() {
	  	let key = $(this).attr('name').split('-')[0].split('remarque')[1];
	  	rmq += key+':'+$(this).val()+','; 
	});
	$('#action-responseBarometer').attr('href','answer-barometre?idBarometre=' + $('.verify').attr('id') + '&myResponse=' + res.join() + '&suggestion=' + $('textarea.input-suggestion').val() + '&remarque=' + rmq);
	$('#text-responseBarometer').append('<p>Merci pour votre réponse !!! </br> Cliquez sur le bouton <italic>"Valider ma réponse"</italic> </p>');
	$('#action-responseBarometer').removeAttr('hidden');
	$('.modal-footer').children('#btn-ok').remove();
	$('.form-groupe.period').last().addClass('d-none');
	$('.form-groupe.period').last().addClass('d-none');
	$('.form-groupe.period').last().children('.quiz').removeClass('d-block');
}

function getChecked (self) {
	setTimeout(function () {
		$(self).closest('.quiz').removeClass('d-block');
		if ($(self).closest('.form-groupe.period').children('').last().attr('id').trim() === $(self).closest('.quiz').attr('id').trim()) {
			$(self).closest('.form-groupe.period').addClass('d-none');
			if ($(self).closest('.form-groupe.period').next().length == 1) {
				$(self).closest('.form-groupe.period').next().removeClass('d-none');
				$(self).closest('.form-groupe.period').next().children('.quiz').first().addClass('d-block');
			}
			if ($(self).closest('.form-groupe.period').next().attr('id') === $('.form-groupe.period').last().attr('id')) {
				setTimeout($('.modal-footer').append('<button class="btn btn-success" onclick="finished()" id="btn-ok">Ok</button>'),500) ;
			}
		} else {
			$(self).closest('.quiz').next().addClass('d-block');
		}
	} , 500);
}

function periodText(classify) { 
	var text = '';
	if ($.isNumeric(classify)) {
		switch (parseInt(classify)) {
			case 1: 
				text = "aujourd'hui" ;
			break ;

			case 2: 
				text = "demain";
			break ;

			case 3: 
				text = "hier";
			break ;

			case 4: 
				text = "cette semaine";
			break ;

			case 5: 
				text = "la semaine prochaine";
			break ;

			case 6: 
				text = "la semaine passée";
			break ;

			case 7: 
				text = "ce mois";
			break ;

			case 8: 
				text = "le mois prochain";
			break ;

			case 9: 
				text = "le mois dernier";
			break ;
		}
	} else {
		text = classify;
	}
	return text;
}

function addRowChoise(motif , self) {
	$(self).closest('.choise-answer').append($(self).closest('.add-choise').clone());
	var closestChoise = $(self).closest('.choise-answer').find('.add-choise');
	$($(closestChoise[closestChoise.length -2])).find('.bouton').remove(); // Effacer le bouton avant dernière colonne
	$(closestChoise[closestChoise.length -1]).css('margin-left',(closestChoise.length - 1)*21 +'%');
	$(closestChoise[closestChoise.length -1]).css('margin-top', '-63px');
	$('.add-choise').css('margin-bottom', '');
	$('.add-choise').closest('.row').css('margin-bottom', '-40px');
	$(closestChoise).find('input.modal-input-choise').last().val(''); // Effacer le text
	$($($('.add-choise')[$('.add-choise').length -1])[0]).find('.hidden').removeClass('hidden'); // Afficher le bouton caché
}

function removeRowChoise(self) {
	var swap = $($(self).closest('.bouton')).clone();
	$($(self).closest('.add-choise')).remove();
	$($('.choise-answer .add-choise:last')[0]).append(swap);
	if ($('.add-choise').length == 1) {
		$($('.add-choise')[0]).find('.btn-delete-row-choise').addClass('hidden'); // Cacher le bouton suppre
	}
}

function addRowQuestion(motif , self) {
	$($(self).closest('.add-periode')[0]).children('.add-question').last().before($(self).closest('.add-question').clone());
	lastQuestion = $(self).closest('.add-periode').find('.add-question').last();
	let random 		= getRandom();
	let imgTag 		= $(lastQuestion).find('.row.row-image');
	$('img', imgTag).attr('id', 'image'+random);
	$('button', imgTag).attr('onclick', '$("#img-quest'+random+'").click(); return false;');
	$('input', imgTag).attr('id', 'img-quest'+random);
	$('input', imgTag).attr('name', 'img-quest'+random);
	let inputClass = $('#img-quest'+random).attr('class');
	setTimeout(function () {
		$('#img-quest'+random).attr('class', inputClass+' hidden');
		var uniqueList=$('#img-quest'+random).attr('class').split(' ').filter(function(item,i,allItems){
		    return i==allItems.indexOf(item);
		}).join(' ');
		$('#img-quest'+random).attr('class',uniqueList);
	}, 80);
	$($('.add-question')[$('.add-question').length -2]).find('.add-btn-question').remove(); // Effacer le bouton avant dernière colonne
	$($($('.add-question')[$('.add-question').length -1])[0]).find('.hidden').removeClass('hidden'); // Afficher le bouton caché
	clearQuestion(lastQuestion);
}

function removeRowQuestion(self) {
	var swap = $($(self).closest('.add-btn-question')).clone();
	$($(self).closest('.add-question')).remove();
	$($('.add-question .row-question:last')[0]).append(swap);
	if ($('.add-question').length == 1) {
		$($('.add-question')[0]).find('.btn-delete-row-question').addClass('hidden'); // Cacher le bouton suppre
	}
}

function clearQuestion (quest) {
	$(quest).find('textarea.question').val(''); // Vider la valeur de la question
	// Effacer les champs du choix sauf le premier.
	var i = 0;
	if ($(quest).find('.add-choise').length > 1) {
		for (i = $(quest).find('.add-choise').length - 1; i > 0; i--) {
			removeRowChoise($($(quest).find('.add-choise')[i]).find('.btn.btn-delete-row-choise'));
		}
	}
	$($(quest).find('.add-choise')[i]).find('input.modal-input-choise').val('');
}

function addRowPeriod(motif , self) {
	$('.card.p-3').append($($(self).closest('.add-periode')[0]).clone());
	$($('.add-periode')[$('.add-periode').length -2]).find('.add-btn-period').remove(); // Effacer le bouton avant dernière colonne
	$('.btn-delete-row-period.hidden').removeClass('hidden'); // Afficher le bouton caché
	$('.add-periode').last().find('#periode').val('').change();
	var i = 0;
	if ($('.add-periode').last().find('.add-question').length > 1) {
		for (i = $('.add-periode').last().find('.add-question').length - 1; i > 0; i--) {
			removeRowQuestion($($('.add-periode').last().find('.add-question')[i]).find('.btn.btn-delete-row-question'));
		}
	}
	clearQuestion($('.add-periode').last().find('.add-question')[i]);
	$('textarea.remarque').last().val('');
	let random = getRandom();
	let imgTag = $('.add-periode').last().find('.row.row-image');
	$('img', imgTag).attr('id', 'image'+random);
	$('button', imgTag).attr('onclick', '$("#img-quest'+random+'").click(); return false;');
	$('input', imgTag).attr('id', 'img-quest'+random);
	$('input', imgTag).attr('name', 'img-quest'+random);
	$('#img-quest'+random).attr('class');
	let urlOrigin = new URL(window.location).origin;
	$('#image'+random).val();
	$('#image'+random).attr('src', urlOrigin+'/../Web/Ressources/images/defaultLogo.png');
}

function removeRowPeriod(self) {
	var swap = $($(self).closest('.add-btn-period')).clone();
	$($(self).closest('.add-periode')).remove();
	$($('.add-periode .row-period:last')[0]).append(swap);
	if ($('.add-periode').length == 1) {
		$($('.add-periode')[0]).find('.btn-delete-row-period').addClass('hidden'); // Cacher le bouton suppre
	}
}
/**
 * Récupère les valeurs du champs existantes à DOM
 * @param  self Signifie (this) la ligne a été changée
 * @return null
 */
function getAllContents(self = null) {
	response = []; 
	res='';
	$.each($('.add-periode'), function(indexPeriod, valPeriod) {
		array = []; quest='';
		$.each($('.add-question',valPeriod), function(indexQuestion, valQuestion) {
			choix = [];
			$.each($('.add-choise ', valQuestion), function(indexChoise, valChoise) {
				choix[indexChoise] = $('input.modal-input-choise', valChoise).val();
			});
			quest+=',question:'+$('textarea.question ', valQuestion).val().replace(/\n/g, '<br>&emsp;')+',choise:'+choix.join(',')+',image:'+$('input[type="file"]', valQuestion).val() ;
		});
		let rmq = $('.add-remarque', this);
		    rmq = $('textarea.remarque', rmq).val().trim();
		res += ',class:'+$('#periode', valPeriod).val()+',questions:'+quest+',remarque:'+ rmq
	});
	$('#hidden-add-contents').attr('value', res);
}

$('.modal-text').click(function(){
	let months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
	let month = $($(this).closest('tr').children()[2]).text().trim().split(' ')[1];
		month = (month.charAt(0) == 'A' || month.charAt(0) == 'O' ? "d'" : "de ") + month;
	for (var i = 0; i < months.length; i++) {
		if($('#textModal .modal-body p').html().indexOf(months[i]) > -1) {
			let de = months[i].charAt(0) == 'A' || months[i].charAt(0) == 'O' ? "d'" : "de ";
			$('#textModal .modal-body p').html($('#textModal .modal-body p').html().replace(de + months[i], month));
		}
	}
	$('button.verify').attr('id', $(this).attr('id'));
});

function eventListener()
{
	listenEvent = true;
	return listenEvent;
}
/*
function addScript(self) {
	var image = $(self).parent().find('input[type=file]').attr('id');
console.log(image)
	if (this.files && this.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e)
		{
            $('#'+image).attr("src", e.target.result);
		};
		reader.readAsDataURL(this.files[0]);
	}
}
*/
$('input[type="file"]').on('change',function(){
	var image = !$(this).attr('id').split('img-quest')[1].trim() ? "#image" : "#image"+$(this).attr('id').split('img-quest')[1];
	if (this.files && this.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e)
		{
            $(image).attr("src", e.target.result);
		};
		reader.readAsDataURL(this.files[0]);
	}
});

/**
 * Récupère la valeur aléatoire
 * @param  
 * @return int Valeur entier
 */
function getRandom () {
	return Math.random().toFixed(3).split('.')[1];
}

function btnSuiv(self) {
	getChecked(self);
}

function addOffSet(self) {
	let offset = $('.paginate_button.active').children().text();
	let href = $(self).attr('href');
		href += offset;
	$(self).attr('href', href);
}