$(document).ready(function(){
	$("#autre").click(function(){
		var text = document.createElement("input");
	    text.setAttribute("type", "text");
	    text.setAttribute("name", "autreQualite");		    
	    text.setAttribute("id", "autreQualite");
	    text.setAttribute("class", "form-control focus_activated");
	    text.setAttribute("placeholder", "Votre personnalité *");
	    text.setAttribute("data-validation-regex-regex", "^[a-zA-Z|éèêëôöîïâàùç |'-]*");
	    text.setAttribute("data-validation-regex-message", "Caractère non valide");

		var paragraphe = document.createElement("p");
	    paragraphe.setAttribute("class", "help-block text-danger");

	    document.getElementById("block-personnalite").append(text);
	    document.getElementById("block-personnalite").append(paragraphe);
	});

	$("#submit").click(function(){
		var checkbox   = document.getElementsByName('qualite');
		var input      = document.getElementsByName('autreQualite');
		var perso      = ""; 
		var autrePerso = "";
		for (var i = 0; i < checkbox.length; i++) {
			var check = checkbox[i].checked;
			if (check) {
				perso += checkbox[i].value + "_";
			}
		}
		for (var i = 0; i < input.length; i++) {
			if (input[i].value != "") {
				autrePerso += (input[i].value).charAt(0).toUpperCase() + (input[i].value).slice(1) + "_";
			}
		}
		$("#autrePersonnalite").val(autrePerso);
		var tabPersonnalite    = ( perso + autrePerso).split("_");   
	  	var newTabPersonnalite = tabPersonnalite.filter(function(elem, index, self) {
	      	return index === self.indexOf(elem);
	  	});

		$("#personnalite").val(newTabPersonnalite.toString().replace(/\,/g, '_'));
	});
});