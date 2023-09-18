$(document).ready(function(){
	if (new URL(window.location).pathname ==='/manage/candidat/dashboard') {
    window.addEventListener("scroll", function() {
      var element = document.getElementById("candidat-menu");
      var secondNavbar = document.getElementById("second-navbar");
      var elementPosition = element.getBoundingClientRect();

      if (elementPosition.top <= 0) {
        secondNavbar.classList.remove("d-none");
      } else {
        secondNavbar.classList.add("d-none");
      }
    });
    $("#candidat-menu").scroll(function() {
      console.log("Le div a été défilé !");
    });
    
    $('#barre-offre').keyup(function(event){
      var input = $(this);
      var val  = input.val();
      if(val == ''){
        $('#offres-disponible a').show();
        $('#offres-disponible').removeClass('underline');
        return true;
      }
      var regexp = '\\b(.*)';
      for(var i in val){
        regexp += '('+val[i]+')(.*)';
      }
      regexp += '\\b';
      $('#offres-disponible a').show();
      $('#offres-disponible').find('h5').each(function(){
        var span = $(this);
        var resultats = span.text().match(new RegExp(regexp,'i'));
        if(resultats){
          var string = '';
          for(var i in resultats){
            if(i > 0){
              string += resultats[i];
            }
          }
          span.empty().append(string);
        }else{
          span.parent().parent().hide();
        }
      })
    });

    $('#barre-candidature').keyup(function(event){
      var candidature = $(this);
      var value  = candidature.val();
      if(value == ''){
        $('.candidature').show();
        return true;
      }
      var search = '\\b(.*)';
      for(var i in value){
        search += '('+value[i]+')(.*)';
      }
      search += '\\b';
      $('.candidature').show();
      $('.candidature').find('h5.poste-candidature').each(function(){
        var div = $(this);
        var resultat = div.text().match(new RegExp(search,'i'));
        if(resultat){
          var strings = '';
          for(var i in resultat){
            if(i > 0){
              strings += resultat[i];
            }
          }
          div.empty().append(strings);
        }else{
          div.parent().parent().hide();
        }
      })
    });

    $("#addExperience").click(function(){
      $('#dateFinExperienceDiv').hide();
      $('#stautExperience').click(function(){
        if ($('#stautExperience').val() == "termine") {
          $('#dateFinExperienceDiv').show();
          $('#dateFinExperienceModal').attr('required', 'true');
        } else {
          $('#dateFinExperienceDiv').hide();
          $('#dateFinExperienceModal').removeAttr('required', '');
        }
      });
    });

    $(".editExperience").click(function(){
      $('#stautExperience').click(function(){
        if ($('#stautExperience').val() == "termine") {
          $('#dateFinExperienceDiv').show();
          $('#dateFinExperienceModal').attr('required', 'true');
        } else {
          $('#dateFinExperienceDiv').hide();
          $('#dateFinExperienceModal').removeAttr('required', '');
        }
      });
    });

    $("#addFormation").click(function(){
      $('#block-sousDomaine-formation').hide();
      $('#block-domaine-formation').hide();
      $("#idSousDomaineFormation").click(function(){
        if ($("#idSousDomaineFormation").val() == "autre") {
          $('#block-sousDomaine-formation').show();   
        } else {
          $('#block-sousDomaine-formation').hide();
        }
      });

      $("#idDomaineFormation").click(function(){
        if ($("#idDomaineFormation").val() == "autre") {
          $('#block-domaine-formation').show();       
        } else {
          $('#block-domaine-formation').hide(); 
        }
      });
    });

    $(".editFormation").click(function(){
      $('#block-sousDomaine-formation').hide();
      $('#block-domaine-formation').hide();
      $("#idSousDomaineFormation").click(function(){
        if ($("#idSousDomaineFormation").val() == "autre") {
          $('#block-sousDomaine-formation').show();   
        } else {
          $('#block-sousDomaine-formation').hide();
        }
      });

      $("#idDomaineFormation").click(function(){
        if ($("#idDomaineFormation").val() == "autre") {
          $('#block-domaine-formation').show();       
        } else {
          $('#block-domaine-formation').hide(); 
        }
      });
    });

    $('textarea[data-limit-rows=true]')
    .on('keypress', function (event) {
        var textarea = $(this),
            text = textarea.val(),
            numberOfLines = (text.match(/\n/g) || []).length + 1,
            maxRows = parseInt(textarea.attr('rows'));
 
        if (event.which === 4 && numberOfLines === maxRows ) {
          return false;
        }
    });
    $('input[type="checkbox"].qualite').click(function(e){
      addPersonnalite("", e , this);
    });

    $('.update-language').click(function(){
      // recuperer les valeurs de la ligne
      let row = $(this).closest('.row'); // let row = $(this).parent().parent();
      let child = $(row).children()[0];
      $('.form-langue').attr('action', new URL(window.location).origin + '/manage/update-langue'); // change l URL dans le modal 
      $('.delete-language').attr('class','delete-language');
      // change le texte a afiicher sur le header du modal 
      $('#titre-modal-langue').text('Modifier une langue');
      $('input[name="idLangue"].idLanguage').attr('value', $('input[type="hidden"].languageId').val()); // id langue
      $('input[type="text"].nomLangue').attr('value', $('label.nom-langue', row).text()); // Nom langue
      $('input[type="checkbox"]',$('form.form-langue')).attr('checked', false);
      // prend les valeurs des étoiles pour les affichés
      $('#star' + $('input[type="hidden"].niveau-ecrit', row).val()).attr('checked', true);
      $('#stars' + $('input[type="hidden"].niveau-parle', row).val()).attr('checked', true);
      $('input[type="hidden"].niveau-ecrit', $('form.form-langue')).attr('value', $('input[type="hidden"].niveau-ecrit', row).val());
      $('input[type="hidden"].niveau-parle', $('form.form-langue')).attr('value', $('input[type="hidden"].niveau-parle', row).val());
      // met en place la valeur de l id pour la suppression d'une langue
      $('#delete-langue-boutton').attr('data-id', $('input[type="hidden"].languageId').val());
      
     $('#delete-langue-boutton').click(function() {
        $('#modalUpdateLanguage').modal('hide');
        let id = $('#delete-langue-boutton').attr('data-id');
        let href = $('#confirm-yes-langue').attr('href')+id;
        $('#confirm-yes-langue').attr('href', href);
      });

     $('#data-annuler-supprLangue').click(function() {
        $('#modalDeleteLanguage').modal('hide');
        $('#modalUpdateLanguage').modal('show');
        $('#delete-langue-boutton').attr('data-id','');
        let res = $('#confirm-yes').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        $('#confirm-yes-langue').attr('href', $res);
      }); 
    });
    $('#form-langue').submit(function(e) {
      var champsVides = false;
      if ($("#nomLangueModal").val() === ""){
        champsVides = true;
      }
      if (champsVides){
        e.preventDefault();
        $("input").not("[type=submit]").jqBootstrapValidation();
      }
    });

     $('#add-langue').click(function(){
      $('.form-langue').attr('action', new URL(window.location).origin + '/manage/create-langue');
      $('.delete-language').attr('class','delete-language hidden');
      $('#titre-modal-langue').text('Ajouter une langue');
      $('input[name="idLangue"]').attr('value','');
      $('input[name="nomLangue"]').attr('value','');
      $('input[name="niveauEcrit"].niveauEcrits').attr('checked', false);
      $('input[name="niveauParle"].niveauParles').attr('checked', false);
      $('.niveauEcrits').one('click',function(){
       var $this = $(this); 
       $this.addClass('checked').add('checked').siblings().removeClass('checked').remove('checked');
      });

      $('.niveauParles').one('click',function(){
       var $this = $(this);
       $this.addClass('checked').add('checked').siblings().removeClass('checked').remove('checked');
      });
    });

     $('input[type="checkbox"]' ,'form.form-langue').click(function(){
        $($(this).closest('.rate').find('input:checked')[0]).attr('checked', false);
        $(this).attr('checked', true);
    });

     $('.datepicker').datepicker({
      startDate: '-3d'
     });

     $('.update-logiciel').click(function(){
      let card = $(this).closest('.card');
      let child = $(card).children()[0];
      $('#titre-modal-logiciel').text('Modifier un logiciel'); // Changer l'en tête du modal
      // gere l'affichage du boutton supprimer
      $('.delete-logiciel').attr('class','delete-logiciel');
      // met en place l URL pour modifier
      $('.form-logiciel').attr('action', new URL(window.location).origin + '/manage/candidat/update-logiciel');
      // met en palce la valeur de l identifiant du logiciel
      $('input[name="idLogiciel"].idLogiciels').attr('value',$('input[type="hidden"].id-logiciel', card).val());
      // met en place la categorie du logiciel
      $('input[name="categorieLogiciel"].form-control').attr('value',$('input[type="hidden"].categorie-logiciel',card).val());
      // nom logiciel
      $('input[name="nomLogiciel"].form-control').attr('value',$('input[type="hidden"].nom-logiciel', card).val());
      // date de sortie du logicel
      $('input[name="dateDeSortie"].datepicker').attr('value',$('input[type="hidden"].date-deSortie', card).val());
      // fonctionnalité du logiciel
      $('#textarea-logiciel').text($('input[type="hidden"].fonctionnalite-logiciel', card).val());
      // maitrise du logiciel
      $('input[name="maitrise-logiciel"].maitriseLogiciel').attr('value',$('input[type="hidden"].maitrise-logiciel', card).val());
      // mise en place du système du check dans une balise input
      $('input[type="radio"]',$('form.form-logiciel')).attr('checked', false);
      $('#etoile' + $('input[type="hidden"].maitrise-logiciel', card).val()).attr('checked', true);
      $('input[type="hidden"].maitriseLogiciels', $('form.form-logiciel')).attr('value', $('input[type="hidden"].maitrise-logiciel', card).val());
      // prend la valeur de l'image du logiciel
      $('img[name="image-logiciel"].img-fluid').attr('src',$('input[type="hidden"].img-logiciel', card).val());
      $('input[type="file"].form-control').attr('value',$('input[type="hidden"].photo-logiciel', card).val());
      // affiche l'image du logiciel selectionné
      $('img[name="image-logiciels"].img-fluid').attr('src', new URL(window.location).origin + '/../Web/Ressources/images/logiciels/' + $('input[type="hidden"].photo-logiciel', card).val());
      // met en place l identifiant pour la suppression d'un logiciel
      $('#delete-logiciel-boutton').attr('data-id', $('input[type="hidden"].id-logiciel', card).val());

      $('#delete-logiciel-boutton').click(function(){
        $('#modalAddLogiciel').modal('hide');
        let id = $('#delete-logiciel-boutton').attr('data-id');
        let href = $('#confirm-yes-logiciel').attr('href')+id;
        $('#confirm-yes-logiciel').attr('href', href);
       });

      $('#data-annuler-supprLogiciel').click(function() {
        $('#modalDeleteLogiciel').modal('hide');
        $('#modalAddLogiciel').modal('show');
        $('#delete-logiciel-boutton').attr('data-id','');
        let res = $('#confirm-yes-logiciel').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        $('#confirm-yes-logiciel').attr('href', $res);
      });
     });

     $('#form-motDePasse').submit(function(e){
        var mdp = false;
        if($("#ancienMotDePasse").val() === "" || $("#motDePasse").val() === "" || $("confirmation").val() === "") {
          mdp = true;
        }
        if (mdp){
          e.preventDefault();
        $("input").not("[type=submit]").jqBootstrapValidation();
        }
     });

     $('#form-logiciel').submit(function(e) {
      var champsVides = false;
      if ($("#categorieLogicielModal").val() === "" || $("#nomLogicielModal").val() === "" || $("#dateDeSortieModal").val() === "" 
        || $("#textarea-logiciel").val() === "" || $("#etoile").val() === "" ){
        champsVides = true;
      }
      if (champsVides){
        e.preventDefault();
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
      }
    });

     $('.create-logiciel').click(function(){
      $('#titre-modal-logiciel').text('Ajouter un logiciel');
      //change l'URL du modal logiciel
      $('.form-logiciel').attr('action', new URL(window.location).origin + '/manage/create-logiciel');
      //cache le boutton supprimer
      $('.delete-logiciel').attr('class','delete-logiciel hidden');
      //initialise la valeur des champs à remplir
      $('input[name="idLogiciel"].idLogiciels').attr('value','');//
      $('input[name="categorieLogiciel"].form-control').attr('value','');//
      $('input[name="nomLogiciel"].form-control').attr('value','');//
      $('input[name="dateDeSortie"].datepicker').attr('value','');//
      $('#textarea-logiciel').text('');//
      //empeche le check enrengistré de s'afficher
      $('input[name="maitriseLogiciel"].maitriseLogiciels').attr('checked', false);
      //affiche l'image par defaut d'un logiciel
      $('img[name="image-logiciels"].img-fluid').attr('src', new URL(window.location).origin +'/../Web/Ressources/images/logiciel/dashboards/photologiciel_defaut.png');
     });

    $('#fileToUpload').change(function(){
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e)
        {
          //affiche l'image selectionné par l'utilisateur
          $('img[name="image-logiciels"].image-load').attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
        // $('#fileToUpload').attr('name', 'photoLogiciel');
      }
      $('#cancel-logiciel').click(function(){
        //initialise la valeur de l'image affiché dernièrement
        $('img[name="image-logiciels"].image-load').attr("src","");
      });
    });

    $('#fileProfilCouverture').click(function(){
      $('img[name="photoCouverture"].couverture').attr('src', $('#photoCouvertureCandidat').val());
      $('input[type="file"]#PhotoDeCouverture').attr('value',$('#valPhotoCouverture').val());
      $('#submitPhotoDeCouverture').attr('disabled', 'true');

      $('#cancel-photo-couverture').click(function(){
        $('input[type="file"]#PhotoDeCouverture').attr('value','');
      });
    });

    $('#PhotoDeCouverture').change(function(){
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        // changeImage(reader);
        reader.onload = function(e)
        {
          //affiche l'image selectionné par l'utilisateur
          $('img[name="photoCouverture"].couverture').attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
        $('#submitPhotoDeCouverture').removeAttr('disabled', '');
      }
    });

    $('#filesProfil').click(function(){
      $('img[name="photo"].user').attr('src', $('#photoProfilCandidat').val());
      $('input[type="file"]#PhotoDeProfil').attr('value',$('#valPhotoProfil').val());
      $('#submitPhotoDeProfil').attr('disabled', 'true');

      $('#cancel-photo-profil').click(function(){
        $('input[type="file"]#PhotoDeProfil').attr('value','');
      });
    });



    $('#PhotoDeProfil').change(function(){
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        // changeImage(reader);
        reader.onload = function(e)
        {
          //affiche l'image selectionné par l'utilisateur
          $('img[name="photo"].user').attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
        $('#submitPhotoDeProfil').removeAttr('disabled', '');
      }
    });

    //Editer les formations
    $('.editFormation').click(function(){
      let formation = $(this).closest('.formations');
      let child = $(formation).children()[0];
      $('#titre-modal-formation').text("Modifier une formation");
      $('#form-formation').attr('action', new URL(window.location).origin + '/manage/update-formation-candidat');
      $('input[name="idFormation"].formation').attr('value', $('input[type="hidden"].id-formation', formation).val());
      $('input[name="dateDebut"].formation').attr('value',$('input[type="hidden"].dateDebutFormation', formation).val());
      $('input[name="dateFin"].formation').attr('value', $('input[type="hidden"].dateFinFormation', formation).val());
      $('input[name="etablissement"].formation').attr('value', $('input[type="hidden"].etablissementFormation', formation).val());
      $('#textarea-formation').text($('input[type="hidden"].descriptionFormation', formation).val());
      var idSousDomaine = $('input[name="idSousDomaine"].idSousDomaine',formation).val();
      $('#idSousDomaineFormation option[value='+ idSousDomaine +']').prop('selected', true);
      var idNiveauEtude = $('input[name="niveauFormation"].niveauEtudeFormation',formation).val();
      $('#idNiveauEtudeFormation option[value='+ idNiveauEtude +']').prop('selected', true);
      $('#delete-formation-button').attr('data-id', $('input[type="hidden"].id-formation', formation).val());
      $('.delete-formation').attr('class','delete-formation');

      $('#delete-formation-button').click(function(){
        $('#modalAddFormation').modal('hide');
        let id = $('#delete-formation-button').attr('data-id');
        let href = $('#confirm-yes-formation').attr('href')+id;
        $('#confirm-yes-formation').attr('href', href);
      });
      $('#data-annuler-supprFormation').click(function() {
        $('#modalDeleteFormation').modal('hide');
        $('#modalAddFormation').show();
        $('#delete-formation-button').attr('data-id','');
        let res = $('#confirm-yes-formation').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        $('#confirm-yes-formation').attr('href', $res);
      });
    });

    $('#form-formation').submit(function(e) {
      var champsVides = false;
      if ($("#idSousDomaineFormation").val() === "" || $("#dateDebutFormationModal").val() === "" || $("#dateFinFormationModal").val() === "" 
        || $("#etablissementFormationModal").val() === "" ||$("#textarea-formation").val() === "" || $("#idNiveauEtudeFormation").val() === "" ){
        champsVides = true;
      }
      if (champsVides){
        e.preventDefault();
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
      }
    });

    $( "#dateDebutFormationModal" ).on('change',validateDatesFormation);
    $( "#dateFinFormationModal" ).on('change',validateDatesFormation);
    function validateDatesFormation() {
      // Obtenez les valeurs des champs de date
      var startDateFormation = new Date($("#dateDebutFormationModal").val());
      var endDateFormation = new Date($("#dateFinFormationModal").val());
      // Vérifiez si la date de fin est supérieure à la date de début
      if (endDateFormation <= startDateFormation) {
        // Affiche un message d'erreur
        $('#dateFinFormationModal').addClass('is-invalid');
        // $("#dateFinFormationModal").parent().find(".help-block.text-danger").html().append(string);
        $('#submitFormation').attr('disabled','true');
      }
      else{
        $('#submitFormation').removeAttr('disabled','');
        $('#dateFinFormationModal').removeClass('is-invalid');
      }
    }

    $('#addFormation').click(function(){
      $('#titre-modal-formation').text("Ajouter une formation");
      $('#form-formation').attr('action', new URL(window.location).origin + '/manage/create-formation-candidat');
      $('input[name="idFormation"].id-formation').attr('value','');
      $('input[name="dateDebut"].formation').attr('value','');
      $('input[name="dateFin"].formation').attr('value','');
      $('input[name="etablissement"].formation.visible').attr('value','');
      $('#textarea-formation.form-control').text('');
      $('#idSousDomaineFormation option[value=""]').prop('selected', true);
      $('#idNiveauEtudeFormation option[value=""]').prop('selected', true);
      $('.delete-formation').attr('class','delete-formation hidden');
    });

    //Editer les experiences
    $('.editExperience').click(function(){
      let experience = $(this).closest('.experiences');
      let child = $(experience).children()[0];
      $('#titre-modal-experience').text("Modifier une experience");
      $('.form-experience').attr('action', new URL(window.location).origin + '/manage/update-experience-candidat');
      $('input[name="idExperience"].id-experience').attr('value', $('input[name="idExperience"].id-experience', experience).val());
      $('input[name="dateDebut"].experience').attr('value',$('input[type="hidden"].dateDebutExperience', experience).val());
      $('input[name="entreprise"].experience').attr('value', $('input[type="hidden"].nomEntreprise', experience).val());
      $('input[name="poste"].experience').attr('value', $('input[type="hidden"].posteExperience', experience).val());
      $('#textarea-experience').text($('input[type="hidden"].descriptionExperience', experience).val());
      $('#delete-experience-button').attr('data-id', $('input[name="idExperience"].id-experience', experience).val());
      $('.delete-experience').attr('class','delete-experience');
      var dateFinVerification = $('input[type="hidden"].dateFinExperience', experience).val();
        if ( dateFinVerification == '01/01/1970') {
          $('#stautExperience option[value=encours]').prop('selected', true);
          $('#dateFinExperienceDiv').hide();
          $('#dateFinExperienceModal').removeAttr('required', '');
          $('input[name="dateFin"].experience').attr('value', '');
        } else {
          $('#dateFinExperienceModal').attr('required', 'true');
          $('#dateFinExperienceDiv').show();
          $('#stautExperience option[value=termine]').prop('selected', true);
          $('input[name="dateFin"].experience').attr('value', $('input[type="hidden"].dateFinExperience', experience).val());
        }

      $('#delete-experience-button').click(function(){
        $('#modalAddExperience').modal('hide');
        let id = $('#delete-experience-button').attr('data-id');
        let href = $('#confirm-yes-experience').attr('href')+id;
        $('#confirm-yes-experience').attr('href', href);
      });
      $('#data-annuler-supprExperience').click(function() {
        $('#modalDeleteExperience').modal('hide');
        $('#modalAddExperience').show();
        $('#delete-experience-boutton').attr('data-id','');
        let res = $('#confirm-yes-experience').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        $('#confirm-yes-experience').attr('href', $res);
      });
    });

    $('#form-experience').submit(function(e) {
      var champsVides = false;
      if ($("#idSousDomaineExperience").val() === "" || $("#dateDebutExperienceModal").val() === "" || $("#entrepriseExperienceModal").val() === "" || $("#posteExperienceModal").val() === "" ||$("#textarea-experience").val() === "" ){
        champsVides = true;
      }
      if (champsVides){
        e.preventDefault();
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
      }
    });

    $( "#dateDebutExperienceModal" ).on('change',validateDates);
    $( "#dateFinExperienceModal" ).on('change',validateDates);
    function validateDates() {
      // Obtenez les valeurs des champs de date
      var startDate = new Date($("#dateDebutExperienceModal").val());
      var endDate = new Date($("#dateFinExperienceModal").val());

      // Vérifiez si la date de fin est supérieure à la date de début
      if (endDate <= startDate) {
        // Affiche un message d'erreur
        $('#dateFinExperienceModal').addClass('is-invalid');
        // $("#dateFinFormationModal").parent().find(".help-block.text-danger").html().append(string);
        $('#submitExperience').attr('disabled','true');
        // Efface la valeur du champ de date de fin
      }
      else{
        $('#submitExperience').removeAttr('disabled','');
        $('#dateFinExperienceModal').removeClass('is-invalid');
      }
    }

    //creer une experience
    $('.addExperience').click(function(){
      $('#titre-modal-experience').text("Ajouter une experience");
      $('.form-experience').attr('action', new URL(window.location).origin + '/manage/create-experience-candidat');
      $('input[name="idExperience"].id-experience').attr('value', '');
      $('input[name="dateDebut"].experience').attr('value','');
      $('input[name="dateFin"].experience').attr('value', '');
      $('input[name="entreprise"].experience').attr('value', '');
      $('#stautExperience option[value=encours]').prop('selected', true);
      $('input[name="poste"].experience').attr('value','' );
      $('#textarea-experience').text('');
      $('.delete-experience').attr('class','delete-experience hidden');
    });

    //Editer les centres d'interets
    $('.update-centre-interet').click(function(){
      let centre = $(this).closest('.centre');
      let child = $(centre).children()[0];
      $('.form-centre-interet').attr('action', new URL(window.location).origin + '/manage/update-centre_interet');
      $('#titre-modal-centre').text("Modifier un centre d'interet");
      $('input[type="hidden"].id-centre-modal').attr('value', $('input[type="hidden"].id-centre-interet', centre).val());
      $('input[name="categorieCentreInteret"].categorieCentres').attr('value', $('input[type="hidden"].categorie-centre-interet', centre).val());
      $('#description-centre').text($('input[type="hidden"].description-centre-interet', centre).val());
      $('.delete-centre').attr('class','delete-centre');
      $('#delete-centre-boutton').attr('data-id', $('input[type="hidden"].id-centre-interet', centre).val());

      $('#delete-centre-boutton').click(function(){
        $('#modalAddCentreInteret').modal('hide');
        let id = $('#delete-centre-boutton').attr('data-id');
        let href = $('#confirm-yes-centre').attr('href')+id;
        $('#confirm-yes-centre').attr('href', href);
       });

      $('#data-annuler-supprCentre').click(function() {
        $('#modalDeleteCentreInteret').modal('hide');
        $('#modalAddCentreInteret').modal('show');
        $('#delete-centre-boutton').attr('data-id','');
        let res = $('#confirm-yes-centre').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        $('#confirm-yes-centre').attr('href', $res);
      });
    });

    $('#switchArret').change(function(){
      $('#dropdownParametre').addClass('show');
      $('#dropdownContainer').addClass('show');
      $('#bouttonDropdown').attr('aria-expanded', 'true');
      if ($('#switchArret').is(':checked')) {
        $('.custom-control-label').html("Publique <i style='color:green;' class='far fa-eye'>");
        sendPublic(0);
          } 
      else {
        $('.custom-control-label').html("Privée <i style='color:red;' class='far fa-eye-slash'>");
        sendPublic(1);
      }
      });

    $('#form-centre-interet').submit(function(e) {
      var champsVides = false;
      if ($("#categorieCentreModal").val() === "" || $("#description-centre").val() === ""){
        champsVides = true;
      }
      if (champsVides){
        e.preventDefault();
        $("input,textarea").not("[type=submit]").jqBootstrapValidation();
      }
    });

    //Creer un centre d'interet
    $('.create-centre-interet').click(function(){
      $('.form-centre-interet').attr('action', new URL(window.location).origin + '/manage/create-centre_interet');
      $('#titre-modal-centre').text("Ajouter un centre d'interet");
      $('input[type="hidden"].idCentreInteret').attr('value','');
      $('input[name="categorieCentreInteret"].categorieCentres').attr('value','');
      $('#description-centre').text('');
      $('.delete-centre').attr('class','delete-centre hidden');
    });

    $("#submit-profil").click(function(){
      if ($('#select-country').val() != "" && $('#input-phone').val() != "") {
        $('#contact').val($('#select-country').val() + "/" + $('#input-phone').val());
      } 

      var dateAr = $(".datepicker").val().split('/');
      $("#dateNaiss").val(dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0]);
    });
  }
}); 
  idCandidat = $('input[type="hidden"].candidat').val();
  function sendPublic(publicVal)
  {
    $.ajax({
      url:HOST.origin + "/manage/publish-candidat?idCandidat="+ idCandidat,
      data: "publique="+publicVal,
      dataType: "text",
      type:"POST",
    });
  }


    function addPersonnalite(input = "", e)
    {
      let checkboxCount = $("input[type='checkbox'].qualite:checked").length;
      if(checkboxCount > 10/* && $(this).is(':checked')*/) {
        alert("Vous ne pouvez sélectionner que 10 éléments");
        e.preventDefault();
        return false;
      }else {
        checkboxCount = $("#personnaliteCandidat input[type='checkbox']:checked").length;
         let personnalites = [];
        $.each($('input[name="qualite"]:checked'),function(){
           personnalites.push($(this).val());
        });
         let autreQualite = [];
        $.each($('input[name="autreQualite"]'),function(){
           autreQualite.push($(this).val());
        });
        $.ajax({
          url:HOST.origin+ "/manage/save-candidat",
          data:"idCandidat=" + idCandidat + "&autrePersonnalite=" + autreQualite.join('_') + "&personnalite=" + personnalites.join('_') + "_",
          dataType: "text",
          type:"POST", 
          success : function(data)
          {
            if ( autreQualite != "") {
              let string;
              $.each(data.personnalite, function(){
                string = '<label class="text-left">'+ 
                          '<input type="checkbox" name="qualite" id="checkPersonnalite" value="' + data + '" class="qualite" checked="" aria-invalid="false">' +
                          data +
                         '</label></br>';
              }); 
              $('.form-group.trait-personnalite').append(string);
            }
          },
          error: function (error) 
          {
            console.log('error; ' + eval(error));
          }
        }); 
      }   
    }
    
  var HOST = new URL(window.location);
