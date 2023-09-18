$('input[type="file"]').change(function(){
	if (this.files && this.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e)
		{
			$("#image").attr("src", e.target.result);
		};
		reader.readAsDataURL(this.files[0]);
	}
});

$(document).on("click", ".cancel", function () {
    var url  = $(this).data('url');
    $('.modal-body #text-confirmation').text('Voulez-vous vraiment annuler votre inscription ?');
    document.getElementById('action-cancel').href = url;
});


$(document).ready(function(){
	var dateFr = { altField: "#datepicker",
                 closeText: 'Fermer',
                 prevText: 'Précédent',
                 nextText: 'Suivant',
                 currentText: 'Aujourd\'hui',
                 monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                 monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
                 dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                 dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                 dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                 weekHeader: 'Sem.',
                 duration: "fast",
                 dateFormat: "dd/mm/yy"};
	jQuery('.datepicker').datepicker(
	  	dateFr
	);
});

$("#submit").click(function(){
  if ($('#select-country').val() != "" && $('#input-phone').val() != "") {
    $('#contact').val($('#select-country').val() + "/" + $('#input-phone').val());
  } 

  var dateAr = $(".datepicker").val().split('/');
  $("#dateNaiss").val(dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0]);
});

