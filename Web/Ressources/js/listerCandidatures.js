$(document).ready(function() {
  // Écouter l'événement de changement de valeur de l'élément select
  $('#select-offre').change(function() {
    var selectedValue = $(this).val();
    var value         = selectedValue.split(', ');
    var url = location+"&idOffre="+value[0]+"&poste="+value[1];
    if (selectedValue == "0") {
    	let host = window.origin;
    	let url1 = host+"/manage/candidatures_entreprise";
    	window.location.href = url1;
    } else {
    	window.location.href = url;
    }
  });
});