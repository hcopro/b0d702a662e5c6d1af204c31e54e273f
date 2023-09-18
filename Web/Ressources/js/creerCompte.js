$('#submit').click(function(){
	/** 
	 *@changelog Lansky [EVOL] 08/09/2022 Cacher les autres champs
	 * */
	if (!$("#nomCreateur").val().trim() || !$("#mailCreateur").val().trim() || !$("#contactRapide").val().trim()) {
		if (!$("#nomCreateur").val().trim()) {
			$("#nomCreateur-message").html("<ul><li>Veuillez entrer votre nom *</li><ul>");
		}
		if (!$("#mailCreateur").val().trim()) {
			$("#mailCreateur-message").html("<ul><li>Veuillez entrer votre e-mail *</li><ul>");
		}
		if (!$("#contactRapide").val().trim()) {
			$("#contactRapide-message").html("<ul><li>Veuillez renseigner votre contact *</li><ul>");
		}
		return false;
	} else {
		if ($('#login').parent().hasClass('d-none')) { // L'élément parent de $('#login') a la classe 'd-none'
			$('#login').parent().removeClass('d-none');
			$('#motDePasse').parent().removeClass('d-none');
			$('#confirmation').parent().removeClass('d-none');
			$('#nomCreateur').parent().addClass('d-none');
			$('#mailCreateur').parent().addClass('d-none');
			$('#contactRapide').parent().addClass('d-none');
			return false;
		} else {
			if (!$("#motDePasse").val().trim() || !$("#confirmation").val().trim() || $("#motDePasse").val() != $("#confirmation").val()) {
				$("#match-message").html("<ul><li>Confirmation incorrecte *</li><ul>");
				return false;
			}
		}
	}
});

/** 
 *@changelog Lansky [EVOL] 21/09/2022 Ajuster 
 *
*/
var pathName = new URL(window.location).pathname;
$(document).ready(function(){
    if($('button[name="upload"]').length > 0) {
	    tippy('button[name="upload"]', {
			content: "Télécharger l'exemplaire"
		});
	}
	if (pathName == "/manage/employes") {
		$('input[type="file"]').click(function () {
			$('button[name="upload"]').removeClass('hidden');
			$('button[name="upload"]').css('margin-left', '-30%');
			$('button[name="upload"]').css('margin-bottom', '-9%');
			$('button[name="download"]').addClass('hidden');
		});
	}
	/** 
	 *@changelog 2023-05-19 Lansky [EVOL] Ajout fonctionnalité voir mot de passe saisi
	 *
	*/
	$('.eye-icon').click(function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        let changeType = $(this).parent().find('input');
        if ($(changeType).attr('type') == 'text') {
        	$(changeType).attr('type','password');
        } else if ($(changeType).attr('type') == 'password') {
        	$(changeType).attr('type','text');
        }
    });
});
