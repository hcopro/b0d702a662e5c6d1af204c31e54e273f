$("document").ready(function(){
  var update1 = "/manage/update-employe";
  var update2 = "/manage/edit-compte_banque";
  var update3 = "/manage/update-password";
  var update4 = "/manage/edit-pseudo";
  var update5 = "/manage/update-contratEmploye";
  var update6 = "/manage/show-historiqueContrat";
  var pathname = new URL(window.location.href).pathname;
  if (pathname == update1) {
    $("li#update1").addClass('is-active');
    $("li#update2").removeClass('is-active');
    $("li#update3").removeClass('is-active');
    $("li#update4").removeClass('is-active');
    $("li#update5").removeClass('is-active');
  }
  else if (pathname == update2) {
     $("li#update2").addClass('is-active');
     $("li#update1").removeClass('is-active');
     $("li#update3").removeClass('is-active');
     $("li#update4").removeClass('is-active');
     $("li#update5").removeClass('is-active');
  }
  else if (pathname == update3) {
     $("li#update3").addClass('is-active');
     $("li#update1").removeClass('is-active');
     $("li#update2").removeClass('is-active');
     $("li#update4").removeClass('is-active');
     $("li#update5").removeClass('is-active');
  }
  else if (pathname == update4) {
     $("li#update4").addClass('is-active');
     $("li#update1").removeClass('is-active');
     $("li#update2").removeClass('is-active');
     $("li#update3").removeClass('is-active');
     $("li#update5").removeClass('is-active');
  }
  else if (pathname == update5) {
     $("li#update5").addClass('is-active');
     $("li#update1").removeClass('is-active');
     $("li#update2").removeClass('is-active');
     $("li#update3").removeClass('is-active');
     $("li#update4").removeClass('is-active');
  }
  else if (pathname == update6) {
    $("li#update5").addClass('is-active');
     $("li#update1").removeClass('is-active');
     $("li#update2").removeClass('is-active');
     $("li#update3").removeClass('is-active');
     $("li#update4").removeClass('is-active');
  }
});