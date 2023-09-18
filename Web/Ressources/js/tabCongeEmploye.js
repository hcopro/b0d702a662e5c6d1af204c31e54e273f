$("document").ready(function(){
   var tab1       = "/manage/employe/conge";
   var tab2       = "/manage/employe/validation";
   var tab3       = "/manage/employe/historiqueConge";
   // var tab4       = ["", "/manage/employe/congeCollaborateur", "/manage/historiqueConge"];
   var tab4       = "/manage/employe/congeCollaborateur";
   var tab5       = "/manage/collaborater/planning";
   var pathname   = new URL(window.location.href).pathname;
   if (pathname == tab1) {
      $("li#tab1").addClass('is-active');
      $("li#tab2").removeClass('is-active');
      $("li#tab3").removeClass('is-active');
      $("li#tab4").removeClass('is-active');
      $("li#tab5").removeClass('is-active');
   } else if (pathname == tab2) {
      $("li#tab2").addClass('is-active');
      $("li#tab1").removeClass('is-active');
      $("li#tab3").removeClass('is-active');
      $("li#tab4").removeClass('is-active');
      $("li#tab5").removeClass('is-active');
   } else if (pathname == tab3) {
      $("li#tab3").addClass('is-active');
      $("li#tab1").removeClass('is-active');
      $("li#tab2").removeClass('is-active');
      $("li#tab4").removeClass('is-active');
      $("li#tab5").removeClass('is-active');
   } else if (pathname == tab4) {
      $("li#tab4").addClass('is-active');
      $("li#tab1").removeClass('is-active');
      $("li#tab2").removeClass('is-active');
      $("li#tab3").removeClass('is-active');
      $("li#tab5").removeClass('is-active');
   } else if (pathname == tab5) {
      $("li#tab5").addClass('is-active');
      $("li#tab1").removeClass('is-active');
      $("li#tab2").removeClass('is-active');
      $("li#tab3").removeClass('is-active');
      $("li#tab4").removeClass('is-active');
   } /*else if ($.inArray(pathname, tab4)) {
     $("li#tab4").addClass('is-active');
     $("li#tab1").removeClass('is-active');
     $("li#tab2").removeClass('is-active');
    $("li#tab3").removeClass('is-active');
  }*/ // À MODIDIFIER LE DOUBLON TAB4 
});