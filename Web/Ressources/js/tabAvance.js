$("document").ready(function(){
  var tab1 = "/manage/entreprise/avance";
  var tab2 = "/manage/entreprise/parametreAvance";
  var tab3 = "/manage/entreprise/demandeAvance";
  var tab4 = "/manage/entreprise/demandeAvanceQuinzaine"
  var pathname = new URL(window.location.href).pathname;
  if (pathname == tab1) {
     $("li#tab1").addClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
     $("li#tab4").removeClass('is-active');
  } else if (pathname == tab2) {
     $("li#tab2").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
     $("li#tab4").removeClass('is-active');
  } else if (pathname == tab3) {
     $("li#tab3").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab4").removeClass('is-active');
  } else if (pathname == tab4) {
     $("li#tab4").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
     $("li#tab3").removeClass('is-active');
  }
});