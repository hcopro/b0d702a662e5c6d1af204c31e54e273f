$(document).ready(function(){
  var a = "/manage/edit-superadmin";
  var b = "/manage/update-password";
  var c = "/manage/edit-pseudo"; 

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