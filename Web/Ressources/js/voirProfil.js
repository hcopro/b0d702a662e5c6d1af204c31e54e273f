$(document).ready(function(){
  if (typeof($('#btn-active')) != "undefined" && $('#btn-active') != null) {
    tippy('#btn-active', {
      content: "Cliquer pour publier"
    });
  } 
  if (typeof($('#btn-desactive')) != "undefined" && $('#btn-desactive') != null) {
    tippy('#btn-desactive', {
      content: "Cliquer pour cacher"
    });
  } 
    
  tippy('#pseudo-btn', {
    content: "Cliquer pour modifier le pseudo"
  }); 

  /** 
   *@changelog 2023-05-23 Lansky [EVOL] Ajout fonctionnalité voir mot de passe saisi
   *
  */
  $('.eye-icon').click(function() {
    $(this).toggleClass('fa-eye fa-eye-slash');
    let changeType = $(this).parent().find('input');
    if ($(changeType).attr('type') == 'text') {
      $(changeType).attr('type','password');
    } else if ($(changeType).attr('type') == 'password') {
      $(changeType).attr('type','text');
    }
  });
});