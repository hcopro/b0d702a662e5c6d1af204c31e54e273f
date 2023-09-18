$("#btn_mission").click(function(){
	$("#block-mission").show();
	$(this).remove();
});

$('#btn_validate').click(function(){
	$('#validate-message').html('');
	if ($("#description_mission").val() == "") {
		$('#description-message').html("<ul><li>Veuillez remplir ce champ</li><ul>");
	} else if ($("#niveau_mission").val() == "") {
		$('#niveau-message').html("<ul><li>Veuillez remplir ce champ</li><ul>");
	} else {
		// var line = "<tr><td class='hidden'>" + $('#id_mission').val() + "</td><td>" + $('#description_mission').val().replace(/\n/g, "<br>") + "</td><td>" + $('#niveau_mission').val() + "</td><td><i class='fas fa-edit' onclick='editRow(this)'></i></td><td><i class='fas fa-times' id='times_close' onclick='removeRow(this)'></i></td></tr>";
		var line = "<tr><td class='hidden'>" + $('#id_mission').val() + "</td><td>" + $('#description_mission').val() + "</td><td>" + $('#niveau_mission').val() + "</td><td><i class='fas fa-edit' onclick='editRow(this)'></i></td><td><i class='fas fa-times' id='times_close' onclick='removeRow(this)'></i></td></tr>";
		$('#id_mission').val("");
		$('#description_mission').val("");
		$('#niveau_mission').val("");
		$("#table-mission").append(line);

	}
});

$('#description_mission').click(function(){
	$('#description-message').html("");
});

$('#niveau_mission').click(function(){
	$('#niveau-message').html("");
});

function removeRow(element) {
	$(element).parents("tr").remove();
}

function editRow(element) {
	var text = "";
	$(element).parents("tr").children('td').each(function(){
		text += $(this).text() + "/";
	});
	var tabText = text.split("/");
	if ($('#block-mission:visible').length == 0) {
		$("#block-mission").show();
		$("#btn_mission").remove();
	}
	if ($("#description_mission").val() == "" && $("#niveau_mission").val() == "") {
		$("#id_mission").val(tabText[0]);
		$("#description_mission").val(tabText[1]);
		$("#niveau_mission").val(tabText[2]);
		$(element).parents("tr").remove();
	} else {
		$('#validate-message').html('<ul><li>Veuillez valider avant de modifier une autre</li></ul>');
	}
	
}