$(document).ready(function(){
	if (new URL(window.location).pathname ==='/manage/candidat/dashboard') {

    $("#addExperience").click(function(){
      $('#block-sousDomaine-experience').hide();
      $('#block-domaine-experience').hide();
      $("#idSousDomaineExperience").click(function(){
        if ($("#idSousDomaineExperience").val() == "autre") {
          $('#block-sousDomaine-experience').show();   
        } else {
          $('#block-sousDomaine-experience').hide();
        }
      });

      $("#idDomaineExperience").click(function(){
        if ($("#idDomaineExperience").val() == "autre") {
          $('#block-domaine-experience').show();       
        } else {
          $('#block-domaine-experience').hide(); 
        }
      });
    });

    $(".editExperience").click(function(){
      $('#block-sousDomaine-experience').hide();
      $('#block-domaine-experience').hide();
      $("#idSousDomaineExperience").click(function(){
        if ($("#idSousDomaineExperience").val() == "autre") {
          $('#block-sousDomaine-experience').show();   
        } else {
          $('#block-sousDomaine-experience').hide();
        }
      });

      $("#idDomaineExperience").click(function(){
        if ($("#idDomaineExperience").val() == "autre") {
          $('#block-domaine-experience').show();       
        } else {
          $('#block-domaine-experience').hide(); 
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

    $('#edit-personnalite').click(function(){
      $(document).ready(function(){

        $("#autre-personnalite").click(function(){
          var text = document.createElement("input");
            text.setAttribute("type", "text");
            text.setAttribute("name", "autreQualite");        
            text.setAttribute("id", "autreQualite");
            text.setAttribute("class", "form-control focus_activated");
            text.setAttribute("placeholder", "Votre personnalité *");
            text.setAttribute("data-validation-regex-regex", "^[a-zA-Z|éèêëôöîïâàùç |'-]*");
            text.setAttribute("data-validation-regex-message", "Caractère non valide");

          var paragraphe = document.createElement("p");
            paragraphe.setAttribute("class", "help-block text-danger");

            document.getElementById("block-personnalite").append(text);
            document.getElementById("block-personnalite").append(paragraphe);
        });

        $("#submit-personnalite").click(function(){
          var checkbox   = document.getElementsByName('qualite');
          var input      = document.getElementsByName('autreQualite');
          var perso      = ""; 
          var autrePerso = "";
          for (var i = 0; i < checkbox.length; i++) {
            var check = checkbox[i].checked;
            if (check) {
              perso += checkbox[i].value + "_";
            }
          }
          for (var i = 0; i < input.length; i++) {
            if (input[i].value != "") {
              autrePerso += (input[i].value).charAt(0).toUpperCase() + (input[i].value).slice(1) + "_";
            }
          }
          $("#autrePersonnalite").val(autrePerso);
          var tabPersonnalite    = ( perso + autrePerso).split("_");   
            var newTabPersonnalite = tabPersonnalite.filter(function(elem, index, self) {
                return index === self.indexOf(elem);
            });

          $("#personnalite").val(newTabPersonnalite.toString().replace(/\,/g, '_'));
        });
      });
    });


     $('.update-language').click(function(){
      // recuperer les valeurs de la ligne
      let row = $(this).closest('.row'); // let row = $(this).parent().parent();
      let child = $(row).children()[0];
      console.log($('input[type="hidden"].id-language', row).val());
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
      $('.niveauEcrits').one('click',function(){
       $('#star' + $('input[type="hidden"].niveau-ecrit', row).val()).attr('checked', false);
       
       var $this = $(this); 
       $this.addClass('checked').add('checked').siblings().removeClass('checked').remove('checked');
      });

      $('.niveauParles').one('click',function(){
       $('#stars' + $('input[type="hidden"].niveau-parle', row).val()).attr('checked', false);
        
       var $this = $(this); 
       $this.addClass('checked').siblings().removeClass('checked').remove('checked');
      });

      // met en place la valeur de l id pour la suppression d'une langue
      $('#delete-langue-boutton').attr('data-id', $('input[type="hidden"].languageId').val());
      
     $('#delete-langue-boutton').click(function() {
        $('#modalUpdateLanguage').modal('hide');
        let id = $('#delete-langue-boutton').attr('data-id');
        console.log(id);
        console.log($('#confirm-yes-langue').attr('href'));
        let href = $('#confirm-yes-langue').attr('href')+id;
        console.log(href);
        $('#confirm-yes-langue').attr('href', href);
      });

     $('#data-annuler-supprLangue').click(function() {
        $('#modalDeleteLanguage').modal('hide');
        $('#modalUpdateLanguage').modal('show');
        $('#delete-langue-boutton').attr('data-id','');
        let res = $('#confirm-yes').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        console.log(res);
        $('#confirm-yes-langue').attr('href', $res);
      }); 
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
        console.log(this)
        $($(this).closest('.rate').find('input:checked')[0]).attr('checked', false);
        $(this).attr('checked', true);
    });

     $('.datepicker').datepicker({
      startDate: '-3d'
     });

     $('.update-logiciel').click(function(){
      let card = $(this).closest('.card');
      let child = $(card).children()[0];
      console.log($('input[type="hidden"].id-logiciel', card).val());
      $('#titre-modal-logiciel').text('Modifier un logiciel'); // Changer l'en tête du modal
      // gere l'affichage du boutton supprimer
      $('.delete-logiciel').attr('class','delete-logiciel');
      // met en place l URL pour modifier
      $('.form-logiciel').attr('action', new URL(window.location).origin + '/manage/update-logiciel');
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
      $('input[type="checkbox"]',$('form.form-logiciel')).attr('checked', false);
      $('#etoile' + $('input[type="hidden"].maitrise-logiciel', card).val()).attr('checked', true);
      $('input[type="hidden"].maitriseLogiciels', $('form.form-logiciel')).attr('value', $('input[type="hidden"].maitrise-logiciel', card).val());
      // prend la valeur de l'image du logiciel
      $('img[name="image-logiciel"].img-fluid').attr('src',$('input[type="hidden"].img-logiciel', card).val());
      $('input[type="file"].form-control').attr('value',$('input[type="hidden"].photo-logiciel', card).val());
      // affiche l'image du logiciel selectionné
      $('img[name="image-logiciels"].img-fluid').attr('src', new URL(window.location).origin + '/../Web/Ressources/images/logiciel/dashboards/' + $('input[type="hidden"].photo-logiciel', card).val());
      // met en place l identifiant pour la suppression d'un logiciel
      $('#delete-logiciel-boutton').attr('data-id', $('input[type="hidden"].id-logiciel', card).val());

      $('#delete-logiciel-boutton').click(function(){
        $('#modalAddLogiciel').modal('hide');
        let id = $('#delete-logiciel-boutton').attr('data-id');
        console.log(id);
        console.log($('#confirm-yes-logiciel').attr('href'));
        let href = $('#confirm-yes-logiciel').attr('href')+id;
        console.log(href);
        $('#confirm-yes-logiciel').attr('href', href);
       });

      $('#data-annuler-supprLogiciel').click(function() {
        $('#modalDeleteLogiciel').modal('hide');
        $('#modalAddLogiciel').modal('show');
        $('#delete-langue-boutton').attr('data-id','');
        let res = $('#confirm-yes-logiciel').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        console.log(res);
        $('#confirm-yes-logiciel').attr('href', $res);
      });

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
      //$('input[name="maitrise-logiciel"].maitriseLogiciel').attr('value','');
      //empeche le check enrengistré de s'afficher
      $('input[name="maitriseLogiciel"].maitriseLogiciels').attr('checked', false);
      //affiche l'image par defaut d'un logiciel
      $('img[name="image-logiciels"].img-fluid').attr('src', new URL(window.location).origin +'/../Web/Ressources/images/logiciel/dashboards/photologiciel_defaut.png');
     });

    $('#fileToUpload').change(function(){
      console.log("fileToUpload == ready");
      if (this.files && this.files[0]) {
        console.log("Condition == succesfull");
        var reader = new FileReader();
        reader.onload = function(e)
        {
          //affiche l'image selectionné par l'utilisateur
          $('img[name="image-logiciels"].image-load').attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
      }
      $('#cancel-logiciel').click(function(){
        console.log("cancel");
        //initialise la valeur de l'image affiché dernièrement
        $('img[name="image-logiciels"].image-load').attr("src","");
      });
    });

    $('#filesProfil').click(function(){
      $('img[name="photo"].user').attr('src', $('input[name="photoProfil"].photo-profil',photoProfil).val());
    });

    $('#PhotoDeProfil').change(function(){
      console.log("PhotoDeProfil == ready");
      if (this.files && this.files[0]) {
        console.log("Condition == succesfull");
        var reader = new FileReader();
        changeImage(reader);
        reader.onload = function(e)
        {
          //affiche l'image selectionné par l'utilisateur
          $('img[name="photo"].user').attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
      }
    });

    //Editer les formations
    $('.editFormation').click(function(){
      let formation = $(this).closest('.formations');
      let child = $(formation).children()[0];
      console.log("tonga");
      $('#titre-modal-formation').text("Modifier une formation");
      $('#form-formation').attr('action', new URL(window.location).origin + '/manage/update-formation-candidat');
      $('input[name="idFormation"].formation').attr('value', $('input[type="hidden"].id-formation', formation).val());
      $('input[name="dateDebut"].formation').attr('value',$('input[type="hidden"].dateDebutFormation', formation).val());
      $('input[name="dateFin"].formation').attr('value', $('input[type="hidden"].dateFinFormation', formation).val());
      $('input[name="theme"].formation').attr('value', $('input[type="hidden"].themeFormation', formation).val());
      $('#textarea-formation').text($('input[type="hidden"].descriptionFormation', formation).val());
      var idSousDomaine = $('input[name="idSousDomaine"].idSousDomaine',formation).val();
      $('#idSousDomaineFormation option[value='+ idSousDomaine +']').prop('selected', true);
      $('#delete-formation-button').attr('data-id', $('input[type="hidden"].id-formation', formation).val());
      $('.delete-formation').attr('class','delete-formation');

      $('#delete-formation-button').click(function(){
        $('#modalAddFormation').hide();
        let id = $('#delete-formation-button').attr('data-id');
        console.log(id);
        console.log($('#confirm-yes-formation').attr('href'));
        let href = $('#confirm-yes-formation').attr('href')+id;
        console.log(href);
        $('#confirm-yes-formation').attr('href', href);
      });
      $('#data-annuler-supprFormation').click(function() {
        $('#modalDeleteFormation').modal('hide');
        $('#modalAddFormation').show();
        $('#delete-formation-button').attr('data-id','');
        let res = $('#confirm-yes-formation').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        console.log(res);
        $('#confirm-yes-formation').attr('href', $res);
      });
    });

    $('#addFormation').click(function(){
      $('#titre-modal-formation').text("Ajouter une formation");
      $('#form-formation').attr('action', new URL(window.location).origin + '/manage/create-formation-candidat');
      $('input[name="idFormation"].id-formation').attr('value','');
      $('input[name="dateDebut"].formation').attr('value','');
      $('input[name="dateFin"].formation').attr('value','');
      $('input[name="theme"].formation').attr('value','');
      $('#textarea-formation').text('');
      $('#idSousDomaineFormation option[value=""]').prop('selected', true);
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
      $('input[name="dateFin"].experience').attr('value', $('input[type="hidden"].dateFinExperience', experience).val());
      $('input[name="entreprise"].experience').attr('value', $('input[type="hidden"].nomEntreprise', experience).val());
      $('input[name="poste"].experience').attr('value', $('input[type="hidden"].posteExperience', experience).val());
      $('#textarea-experience').text($('input[type="hidden"].descriptionExperience', experience).val());
      var idSousDomaine = $('input[name="idSousDomaine"].idSousDomaine',experience).val();
      $('#idSousDomaineExperience option[value='+ idSousDomaine +']').prop('selected', true);
      $('#delete-experience-button').attr('data-id', $('input[name="idExperience"].id-experience', experience).val());
      $('.delete-experience').attr('class','delete-experience');

      $('#delete-experience-button').click(function(){
        $('#modalAddExperience').hide();
        let id = $('#delete-experience-button').attr('data-id');
        console.log(id);
        console.log($('#confirm-yes-experience').attr('href'));
        let href = $('#confirm-yes-experience').attr('href')+id;
        console.log(href);
        $('#confirm-yes-experience').attr('href', href);
      });
      $('#data-annuler-supprExperience').click(function() {
        $('#modalDeleteExperience').modal('hide');
        $('#modalAddExperience').show();
        $('#delete-centre-boutton').attr('data-id','');
        let res = $('#confirm-yes-centre').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        console.log(res);
        $('#confirm-yes-centre').attr('href', $res);
      });
    });

    //creer une experience
    $('.addExperience').click(function(){
      $('#titre-modal-experience').text("Ajouter une experience");
      $('.form-experience').attr('action', new URL(window.location).origin + '/manage/create-experience-candidat');
      $('input[name="idExperience"].id-experience').attr('value', '');
      $('input[name="dateDebut"].experience').attr('value','');
      $('input[name="dateFin"].experience').attr('value', '');
      $('input[name="entreprise"].experience').attr('value', '');
      $('input[name="poste"].experience').attr('value','' );
      $('#textarea-experience').text('');
      $('#idSousDomaineExperience option[value=""]').prop('selected', true);
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
        console.log(id);
        console.log($('#confirm-yes-centre').attr('href'));
        let href = $('#confirm-yes-centre').attr('href')+id;
        console.log(href);
        $('#confirm-yes-centre').attr('href', href);
       });

      $('#data-annuler-supprCentre').click(function() {
        $('#modalDeleteCentreInteret').modal('hide');
        $('#modalAddCentreInteret').modal('show');
        $('#delete-centre-boutton').attr('data-id','');
        let res = $('#confirm-yes-centre').attr('href');
        let ind = res.indexOf('=');    
        $res = res.substring(0,ind+1);
        console.log(res);
        $('#confirm-yes-centre').attr('href', $res);
      });
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


  var HOST = new URL(window.location);
function changeImage(reader)
{
  var file_data = $('#PhotoDeProfil').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);

  console.log("loading");
  console.log(reader);
  console.log(form_data);
  $.ajax({
      url : HOST.origin + "/manage/edit-photo",
      data : "idCandidat=" + $('input[name="idCandidat"].form-control').val() +'&photo=' + $('input[name="photo"].form-control').val() + '&formFile=' + form_data.toString()  ,
      dataType : "text",
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success : function(data)
      {
        console.log(data);
        console.log("succesfull");
      }
});