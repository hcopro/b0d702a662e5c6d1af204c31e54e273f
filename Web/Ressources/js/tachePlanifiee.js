$("#submit").click(function(){
	var script = "";
	if ($("#minute").val() != "") {
		script += $("#minute").val() + " ";
	} else {
		script += "* ";
	}
	if ($("#heure").val() != "") {
		script += $("#heure").val() + " ";
	} else {
		script += "* ";
	}
	if ($("#jour-mois").val() != "") {
		script += $("#jour-mois").val() + " ";
	} else {
		script += "* ";
	}
	if ($("#mois").val() != "") {
		script += $("#mois").val() + " ";
	} else {
		script += "* ";
	}
	if ($("#jour-semaine").val() != "") {
		script += $("#jour-semaine").val();
	} else {
		script += "*";
	}
	$("#codePeriode").val(script);
});
tippy('.fa-trash', {
	content: "supprimer"
});
tippy('.fa-play', {
	content: "activer cette tâche"
});
tippy('.fa-stop', {
	content: "désactiver cette tâche"
});
tippy('.tache-activee', {
	content: "activée"
});
tippy('.tache-desactivee', {
	content: "désactivée"
});
tippy('.fa-info-circle', {
	content: "voir les détails"
});
tippy('.nouvelle-tache', {
	content: "Planifier une nouvelle tâche"
});
$('#annuler').click(function(){
	window.location.reload();
});
$(function() {
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
});