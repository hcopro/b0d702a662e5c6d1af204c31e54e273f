$(document).ready(function(){
  var a = "/manage/edit-candidat";
  var b = "/manage/update-password";
  var c = "/manage/edit-pseudo";
  var d = "/manage/edit-personnalite_candidat";
  var e = "/manage/candidat/dashboard";
  var f = "/manage/formations";
  var g = "/manage/diplomes";
  var h = "/manage/experiences";
  var pathname = new URL(window.location.href).pathname;
  if (pathname == a){
    $("li#a").addClass('is-active');
    $("li#b").removeClass('is-active');
    $("li#c").removeClass('is-active');
    $("li#d").removeClass('is-active');
  }
  else if (pathname == b){
     $("li#b").addClass('is-active');
     $("li#a").removeClass('is-active');
     $("li#c").removeClass('is-active');
     $("li#d").removeClass('is-active');
  }
  else if (pathname == c){
     $("li#c").addClass('is-active');
     $("li#a").removeClass('is-active');
     $("li#b").removeClass('is-active');
     $("li#d").removeClass('is-active');
  }
  else if (pathname == d){
     $("li#d").addClass('is-active');
     $("li#a").removeClass('is-active');
     $("li#b").removeClass('is-active');
     $("li#c").removeClass('is-active');
  }
  else if (pathname == e){
     $("li#e").addClass('is-active');
     $("li#h").removeClass('is-active');
     $("li#f").removeClass('is-active');
     $("li#g").removeClass('is-active');
  }
  else if (pathname == f){
     $("li#f").addClass('is-active');
     $("li#e").removeClass('is-active');
     $("li#g").removeClass('is-active');
     $("li#h").removeClass('is-active');
  }
  else if (pathname == g){
     $("li#g").addClass('is-active');
     $("li#f").removeClass('is-active');
     $("li#e").removeClass('is-active');
     $("li#h").removeClass('is-active');
  }
  else if (pathname == h){
     $("li#h").addClass('is-active');
     $("li#e").removeClass('is-active');
     $("li#f").removeClass('is-active');
     $("li#g").removeClass('is-active');
  }
});