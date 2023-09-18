$(document).ready(function(){
  var a = "/manage/tableau_de_bord-offre";
  var b = "/manage/tableau_de_bord-entretien";
  var c = "/manage/tableau_de_bord-interlocuteur";
  var pathname = new URL(window.location.href).pathname;
  
  if (pathname == a){
    $("li#a").addClass('is-active');
    $("li#b").removeClass('is-active');
    $("li#c").removeClass('is-active');
  }
  else if (pathname == b){
     $("li#b").addClass('is-active');
     $("li#a").removeClass('is-active');
     $("li#c").removeClass('is-active');
  }
  else if (pathname == c){
     $("li#c").addClass('is-active');
     $("li#a").removeClass('is-active');
     $("li#b").removeClass('is-active');
  }
});