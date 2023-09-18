// Évènement lorsqu'on clique sur le bouton de validation
// submitBtn.addEventListener("click", () => {
//   const posteExstant = document.getElementById("option1");
//   const nouveauPoste = document.getElementById("option2");
//   const HOST         = window.location;
//   console.log(HOST);

//   // Redirection en fonction de l'option choisie
//   if (posteExstant.checked) {
//     window.location.href = HOST.origin + "/manage/create-offre";
//   } else if (nouveauPoste.checked) {
//     window.location.href = "#";
//   }
// });

// Évènement lorsqu'on clique sur le bouton de validation
$(window).ready(function(){
  $("#submit-btn").click(function(){
    const posteExstant = $("#option1");
    const nouveauPoste = $("#option2");
    const HOST = window.location;
    console.log(HOST);
    if (posteExstant.prop("checked")) {
      window.location.href = HOST.origin + "/manage/create-offre";
    } else if (nouveauPoste.prop("checked")) {
      window.location.href = HOST.origin + "/manage/create-newOffre";
    }
  });
});

  // // Redirection en fonction de l'option choisie
