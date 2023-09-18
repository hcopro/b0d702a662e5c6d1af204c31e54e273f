$(document).ready(function(){
	var menuCache = true;

	$('#barre-menu').click(function() {
	  if (menuCache) {
	    $('.menu-gauche').show();
	    $('.contenu-droite').removeClass('col-md-12');
	    $('.contenu-droite').addClass('col-md-10');
	    menuCache = false;
	  } else {
	  	$('.menu-gauche').hide();
	    $('.contenu-droite').removeClass('col-md-10');
	    $('.contenu-droite').addClass('col-md-12');
	    menuCache = true;
	  }
	});
	$(".body-humanexus").scroll(function() {
      console.log("Le div a été défilé !");
    });
});