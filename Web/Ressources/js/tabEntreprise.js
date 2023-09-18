$(document).ready(function(){
  var a = "/manage/tableau_de_bord-offre";
  var b = "/manage/tableau_de_bord-entretien";
  var c = "/manage/tableau_de_bord-interlocuteur";
  var d = "/manage/edit-entreprise";
  var e = "/manage/update-password";
  var f = "/manage/edit-pseudo"; 

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

  else if (pathname == d){
     $("li#d").addClass('is-active');
     $("li#e").removeClass('is-active');
     $("li#f").removeClass('is-active');
  }
  else if (pathname == e){
     $("li#e").addClass('is-active');
     $("li#d").removeClass('is-active');
     $("li#f").removeClass('is-active');
  }
  else if (pathname == f){
     $("li#f").addClass('is-active');
     $("li#d").removeClass('is-active');
     $("li#e").removeClass('is-active');
  }

  var currentUrl      = window.location.href.split('&tabs=');
  // var currentTabs   = <?php echo json_encode($tabId) ?>;
  $(".1").addClass('is-active');
  if (currentTabs){
     if(currentTabs.includes(currentUrl[1])) {
          $(".1").removeClass('is-active');
          $("#"+currentUrl[1]).addClass('is-active');
     }
  }
});