// Évènement lorsqu'on clique sur le bouton de validation
submitBtn.addEventListener("click", () => {
  const posteExstant = document.getElementById("option1");
  const nouveauPoste = document.getElementById("option2");
  const HOST         = window.location;
  console.log(HOST);

  // Redirection en fonction de l'option choisie
  if (posteExstant.checked) {
    window.location.href = HOST.origin"/manage/create-offre";
  } else if (nouveauPoste.checked) {
    window.location.href = "#";
  }
});