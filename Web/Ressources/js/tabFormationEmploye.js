$("document").ready(function(){
  var tab1 = "/manage/employe/formationDisponible";
  var tab2 = "/manage/employe/formationAssistee";
  var tab3 = "/manage/employe/validationFormation"
  var pathname = new URL(window.location.href).pathname;
  if (pathname == tab1) {
    $("li#tab1").addClass('is-active');
    $("li#tab2").removeClass('is-active');
    $("li#tab3").removeClass('is-active');
  } else if (pathname == tab2) {
    $("li#tab2").addClass('is-active');
    $("li#tab1").removeClass('is-active');
    $("li#tab3").removeClass('is-active');
  } else if (pathname == tab3) {
    $("li#tab3").addClass('is-active');
    $("li#tab1").removeClass('is-active');
    $("li#tab2").removeClass('is-active');
  }
});