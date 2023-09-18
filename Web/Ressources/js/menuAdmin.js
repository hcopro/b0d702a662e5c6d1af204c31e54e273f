$("document").ready(function(){
	var item1 = [
		"/manage/superadmins"
	];
	var item2 = [
		"/manage/activeEntreprises",
		"/manage/inactiveEntreprises"
	]
	var item3 = [
		"/manage/activeCandidats"
	];
	var item4 = [
		"/manage/emails_contacts"
	];
	var item5 = [
		"/manage/archiveEntreprises"
	];
	var item6 = [
		"/manage/archiveCandidats"
	];
	var item7 = [
		"/manage/historiques"
	];
	var item8 = [
		"/manage/domaines"
	];
	var item9 = [
		"/manage/personnalites"
	];
	var item10 = [
		"/manage/services",
	];
	var item11 = [
		"/manage/postes"
	];
	var item12 = [
		"/manage/contrats"
	];
	var item13 = [
		"/manage/niveaux_experiences"
	];
	var item14 = [
		"/manage/niveaux_etudes"
	];
	var item15 = [
		"/manage/banques"
	];
	var item16 = [
		"/manage/categorieProfessionnelles"
	];
	var item17 = [
		"/manage/jourFeries"
	];
	var item18 = [
		"/manage/permissions"
	];
	var item19 = [
		"/manage/tachePlanifiees" 
	];
	var item20 = [
		"/manage/admin/templateContrat" 
	];
	var pathname = new URL(window.location.href).pathname;
	activer(pathname, item1, 1);
	activer(pathname, item2, 2);
	activer(pathname, item3, 3);
	activer(pathname, item4, 4);
	activer(pathname, item5, 5);
	activer(pathname, item6, 6);
	activer(pathname, item7, 7);
	activer(pathname, item8, 8);
	activer(pathname, item9, 9);
	activer(pathname, item10, 10);
	activer(pathname, item11, 11);
	activer(pathname, item12, 12);
	activer(pathname, item13, 13);
	activer(pathname, item14, 14);
	activer(pathname, item15, 15);
	activer(pathname, item16, 16);
	activer(pathname, item17, 17);
	activer(pathname, item18, 18);
	activer(pathname, item19, 19);
	activer(pathname, item20, 20);
});
function activer(pathname, item, index)
{
	if ($.inArray(pathname, item) >= 0) {
	    $(".item").removeClass('item-active');
	    $("#item" + index).addClass('item-active');
	    $("#item" + index + " .lien-menu").css('color', 'white');
	    $("#item" + index).parent().parent().collapse('show');
	    if (index >= 1 && index <= 7) {
			$("#titre-1").removeClass();
			$("#titre-1").addClass("fa fas fa-caret-down");
			$("#titre-2").removeClass();
			$("#titre-2").addClass("fa fas fa-caret-right");
			$("#titre-3").removeClass();
			$("#titre-3").addClass("fa fas fa-caret-right");
		} else if (index >= 8 && index <= 14) {
			$("#titre-1").removeClass();
			$("#titre-1").addClass("fa fas fa-caret-right");
			$("#titre-2").removeClass();
			$("#titre-2").addClass("fa fas fa-caret-down");
			$("#titre-3").removeClass();
			$("#titre-3").addClass("fa fas fa-caret-right");
		} else if (index >= 15 && index <= 20) {
			$("#titre-1").removeClass();
			$("#titre-1").addClass("fa fas fa-caret-right");
			$("#titre-2").removeClass();
			$("#titre-2").addClass("fa fas fa-caret-right");
			$("#titre-3").removeClass();
			$("#titre-3").addClass("fa fas fa-caret-down");
		}
	}
}

$('#btn-titre-1').click(function(){
	$('#collapseMenu1').on('shown.bs.collapse', function () {
	   	$("#titre-1").removeClass();
		$("#titre-1").addClass("fa fas fa-caret-down");
	});
	$('#collapseMenu1').on('hidden.bs.collapse', function () {
	   	$("#titre-1").removeClass();
		$("#titre-1").addClass("fa fas fa-caret-right");
	});
});

$('#btn-titre-2').click(function(){
	$('#collapseMenu2').on('shown.bs.collapse', function () {
	   	$("#titre-2").removeClass();
		$("#titre-2").addClass("fa fas fa-caret-down");
	});
	$('#collapseMenu2').on('hidden.bs.collapse', function () {
	   	$("#titre-2").removeClass();
		$("#titre-2").addClass("fa fas fa-caret-right");
	});
});

$('#btn-titre-3').click(function(){
	$('#collapseMenu3').on('shown.bs.collapse', function () {
	   	$("#titre-3").removeClass();
		$("#titre-3").addClass("fa fas fa-caret-down");
	});
	$('#collapseMenu3').on('hidden.bs.collapse', function () {
	   	$("#titre-3").removeClass();
		$("#titre-3").addClass("fa fas fa-caret-right");
	});
});
