					const FILTER_GROUP_ALL     = 1;
        			const FILTER_GROUP_SERVICE = 2;
        			const FILTER_GROUP_POSTE   = 3;
        			const FILTER_GROUP_EMPLOYE = 4;
        			const TODAY      = 1;
					const TOMORROW   = 2;
					const YESTERDAY  = 3;
					const THIS_WEEK  = 4;
					const NEXT_WEEK  = 5;
					const LAST_WEEK  = 6;
					const THIS_MONTH = 7;
					const NEXT_MONTH = 8;
					const LAST_MONTH = 9;

	      			$('document').ready(function(){
	      				if ($('#filter-group').val() == FILTER_GROUP_ALL) {
	      					$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
								groupe    : $('#filter-group').val(),
								id        : $('#filter-element').val(),
								periode   : $('#periode').val(),
								debut     : $('#debut').val(),
								fin       : $('#fin').val(),
								type      : $('#selection-type').val()
							}, function() {
								$('.option-tout').removeClass('invisible');
		      					$('.option-service').addClass('invisible');
		      					$('.option-poste').addClass('invisible');
		      					$('.option-employe').addClass('invisible');
		      					$('#filter-element').val("");
		      					$('#filter-element').attr("disabled", true);
							});
	      				}
	      				$('.carousel').carousel({
  							interval: false,
						});
	      			});
	      			$('#filter-group').change(function(){
	      				if ($('#filter-group').val() == FILTER_GROUP_ALL) {
	      					$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
								groupe    : $('#filter-group').val(),
								id        : $('#filter-element').val(),
								periode   : $('#periode').val(),
								debut     : $('#debut').val(),
								fin       : $('#fin').val(),
								type      : $('#selection-type').val()
							}, function() {
								$('.option-tout').removeClass('invisible');
		      					$('.option-service').addClass('invisible');
		      					$('.option-poste').addClass('invisible');
		      					$('.option-employe').addClass('invisible');
		      					$('#filter-element').val("");
		      					$('#filter-element').attr("disabled", true);
							});
	      				} else if ($('#filter-group').val() == FILTER_GROUP_SERVICE) {
	      					$('.option-tout').addClass('invisible');
	      					$('.option-service').removeClass('invisible');
	      					$('.option-poste').addClass('invisible');
	      					$('.option-employe').addClass('invisible');
	      					$('#filter-element').attr("disabled", false);
	      					$('#filter-element').val("");
	      				} else if ($('#filter-group').val() == FILTER_GROUP_POSTE) {
	      					$('.option-tout').addClass('invisible');
	      					$('.option-service').addClass('invisible');
	      					$('.option-poste').removeClass('invisible');
	      					$('.option-employe').addClass('invisible');
	      					$('#filter-element').attr("disabled", false);
	      					$('#filter-element').val("");
	      				} else if ($('#filter-group').val() == FILTER_GROUP_EMPLOYE) {
	      					$('.option-tout').addClass('invisible');
	      					$('.option-service').addClass('invisible');
	      					$('.option-poste').addClass('invisible');
	      					$('.option-employe').removeClass('invisible');
	      					$('#filter-element').attr("disabled", false);
	      					$('#filter-element').val("");
	      				}
	      			});
	      			$('#filter-element').change(function() {
	      				$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
							groupe    : $('#filter-group').val(),
							id        : $('#filter-element').val(),
							periode   : $('#periode').val(),
							debut     : $('#debut').val(),
							fin       : $('#fin').val(),
							type      : $('#selection-type').val()
						});
	      			});
	      			$('#periode').change(function() {
						$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
							groupe    : $('#filter-group').val(),
							id        : $('#filter-element').val(),
							periode   : $('#periode').val(),
							type      : $('#selection-type').val()
						}, function() {
							$("#debut").val(null);
							$("#fin").val(null);
							$('#selection-month').val("");
						});
					});
					$('#debut').change(function() {
						$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
							groupe    : $('#filter-group').val(),
							id        : $('#filter-element').val(),
							debut     : $('#debut').val(),
							fin       : $('#fin').val(),
							type      : $('#selection-type').val()
						}, function() {
							$("#periode").val("");
							$('#selection-month').val("");
						});
					});
					$('#fin').change(function() {
						$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
							groupe    : $('#filter-group').val(),
							id        : $('#filter-element').val(),
							debut     : $('#debut').val(),
							fin       : $('#fin').val(),
							type      : $('#selection-type').val()
						}, function() {
							$("#periode").val("");
							$('#selection-month').val("");
						});
					});
					$('#selection-month').change(function() {
						$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
							groupe    : $('#filter-group').val(),
							id        : $('#filter-element').val(),
							mois      : $('#selection-month').val(),
							type      : $('#selection-type').val()
						}, function() {
							$("#periode").val("");
							$('#debut').val(null);
							$('#fin').val(null);
						});
					});
					$('#selection-type').change(function() {
						$("#liste-pointage").load("<?= HOST . 'manage/pointages'?>", {
							groupe    : $('#filter-group').val(),
							id        : $('#filter-element').val(),
							debut     : $('#debut').val(),
							fin       : $('#fin').val(),
							periode   : $('#periode').val(),
							mois      : $('#selection-month').val(),
							type      : $('#selection-type').val()
						});
					});