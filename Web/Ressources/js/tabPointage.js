$("document").ready(function(){
  var tab1 = "/manage/entreprise/permission/dashboard";
  var tab5 = "/manage/entreprise/repos/dashboard";
  var tab2 = "/manage/entreprise/pointage/dashboard";
  var tab3 = "/manage/entreprise/parametre/permission";
  var tab4 = "/manage/entreprise/jourFerie";
  var tab10 = "/manage/entreprise/retard/dashboard";
  var pathname = new URL(window.location.href).pathname;
  if (pathname == tab1) {
    $("li#tab1").addClass('is-active');
    $("li#tab2").removeClass('is-active');
    $("li#tab3").removeClass('is-active');
    $("li#tab4").removeClass('is-active');
    $("li#tab5").removeClass('is-active');
    $("li#tab10").removeClass('is-active');
  } else if (pathname == tab2) {
     $("li#tab2").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
     $("li#tab4").removeClass('is-active');
     $("li#tab5").removeClass('is-active');
     $("li#tab10").removeClass('is-active');
  } else if (pathname == tab3) {
     $("li#tab3").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab4").removeClass('is-active');
     $("li#tab5").removeClass('is-active');
     $("li#tab10").removeClass('is-active');
  } else if (pathname == tab4) {
     $("li#tab4").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
     $("li#tab5").removeClass('is-active');
     $("li#tab10").removeClass('is-active');
  } else if (pathname == tab5) {
     $("li#tab5").addClass('is-active');
     $("li#tab4").removeClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
     $("li#tab10").removeClass('is-active');
  } else if (pathname == tab10) {
     $("li#tab10").addClass('is-active');
     $("li#tab4").removeClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
     $("li#tab5").removeClass('is-active');
  }
});