$("document").ready(function(){
	var item1 = [
		"/manage/candidatures"
	];
	var item2 = [
		"/manage/suggest_offres"
	];
	var pathname = new URL(window.location.href).pathname;
	activer(pathname, item1, 1);
	activer(pathname, item2, 2);
});
function activer(pathname, item, index)
{
	if ($.inArray(pathname, item) >= 0) {
	    $(".item").removeClass('item-active');
	    $("#item" + index).addClass('item-active');
	    $("#item" + index + " .lien-menu").css('color', 'white');
	    $("#item" + index).parent().parent().collapse('show');
	}
}