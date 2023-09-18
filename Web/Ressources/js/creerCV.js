$(document).ready(function(){

  var HOST = new URL(window.location);
    $("#couleur-texte-gauche").hover(function(){
        $(this).animate($('#textGauche').addClass('resize'), "fast");
      }, function(){
        $(this).animate($('#textGauche').removeClass('resize') , "fast");
    });
    $('#couleur-texte-gauche').click(function(){
        if ($('#couleur-texte-gauche').val() == "rouge") {
          $('#textGauche').attr('style','color:red');
        } else if ($('#couleur-texte-gauche').val() == "vert") {
          $('#textGauche').attr('style','color:green');
        } else if ($('#couleur-texte-gauche').val() == "bleu") {
          $('#textGauche').attr('style','color:blue');
        } else if ($('#couleur-texte-gauche').val() == "blanc") {
          $('#textGauche').attr('style','color:white');
        } else if ($('#couleur-texte-gauche').val() == "violet") {
          $('#textGauche').attr('style','color:purple');
        } else if ($('#couleur-texte-gauche').val() == "rose") {
          $('#textGauche').attr('style','color:pink');
        } else {
    	  $('#textGauche').removeAttr('style','');
        }
    });

    $("#couleur-texte-droite").hover(function(){
        $(this).animate($('#textDroite').addClass('resize'), "fast");
      }, function(){
        $(this).animate($('#textDroite').removeClass('resize') , "fast");
    });

    $('#couleur-texte-droite').click(function(){
        if ($('#couleur-texte-droite').val() == "rouge") {
          $('#textDroite').attr('style','color:red');
        } else if ($('#couleur-texte-droite').val() == "vert") {
          $('#textDroite').attr('style','color:green');
        } else if ($('#couleur-texte-droite').val() == "bleu") {
          $('#textDroite').attr('style','color:blue');
        } else if ($('#couleur-texte-droite').val() == "blanc") {
          $('#textDroite').attr('style','color:white');
        } else if ($('#couleur-texte-droite').val() == "acquableu") {
          $('#textDroite').attr('style','color:#17a2b8')
        } else {
    	  $('#textDroite').removeAttr('style','');
        }
    });

    $("#picker-en-tete").hover(function(){
        $(this).animate($('.format-nomPrenom').addClass('resize'), "fast");
      }, function(){
        $(this).animate($('.format-nomPrenom').removeClass('resize') , "fast");
    });

    $("#couleur-titre").hover(function(){
        $(this).animate($('.underline').addClass('resize'), "fast");
      }, function(){
        $(this).animate($('.underline').removeClass('resize') , "fast");
    });

    $('#couleur-titre').click(function(){
        if ($('#couleur-titre').val() == "rouge") {
          $('.underline').attr('style','color:red');
        } else if ($('#couleur-titre').val() == "vert") {
          $('.underline').attr('style','color:green');
        } else if ($('#couleur-titre').val() == "bleu") {
          $('.underline').attr('style','color:blue');
        } else if ($('#couleur-titre').val() == "blanc") {
          $('.underline').attr('style','color:white');
        } else if ($('#couleur-titre').val() == "acquableu") {
          $('.underline').attr('style','color:#17a2b8')
        } else if ($('#couleur-titre').val() == "noir") {
          $('.underline').attr('style','color:black');
        } else {
          $('.underline').removeAttr('style','');
        }
    });

    $("#couleur-partie-haut").hover(function(){
        $(this).animate($('#photo-nom').addClass('wave'), "fast");
      }, function(){
        $(this).animate($('#photo-nom').removeClass('wave') , "fast");
    });

    $('#couleur-partie-haut').click(function(){
        if ($('#couleur-partie-haut').val() == "couleur1") {
          $('#photo-nom').attr('style','background-color:#FF9999');
        } else if ($('#couleur-partie-haut').val() == "couleur2") {
          $('#photo-nom').attr('style','background-color:#FFCC99');
        } else if ($('#couleur-partie-haut').val() == "couleur3") {
          $('#photo-nom').attr('style','background-color:#FFFF99');
        } else if ($('#couleur-partie-haut').val() == "couleur4") {
          $('#photo-nom').attr('style','background-color:#CCFF99');
        } else if ($('#couleur-partie-haut').val() == "couleur5") {
          $('#photo-nom').attr('style','background-color:#99FF99');
        } else if ($('#couleur-partie-haut').val() == "couleur6") {
          $('#photo-nom').attr('style','background-color:#99FFCC');
        } else if ($('#couleur-partie-haut').val() == "couleur7") {
          $('#photo-nom').attr('style','background-color:#99FFFF');
        } else if ($('#couleur-partie-haut').val() == "couleur8") {
          $('#photo-nom').attr('style','background-color:#99CCFF');
        } else if ($('#couleur-partie-haut').val() == "couleur9") {
          $('#photo-nom').attr('style','background-color:#9999FF');
        } else if ($('#couleur-partie-haut').val() == "couleur10") {
          $('#photo-nom').attr('style','background-color:#CC99FF');
        } else if ($('#couleur-partie-haut').val() == "couleur11") {
          $('#photo-nom').attr('style','background-color:#FF99FF');
        } else if ($('#couleur-partie-haut').val() == "couleur12") {
          $('#photo-nom').attr('style','background-color:#FF99CC');
        } else if ($('#couleur-partie-haut').val() == "couleur13") {
          $('#photo-nom').attr('style','background-color:#E0E0E0');
        } else if ($('#couleur-partie-haut').val() == "couleur14") {
          $('#photo-nom').attr('style','background-image: linear-gradient(rgba(0, 0, 255, 0.5), rgba(254 208 208))');
        } else if ($('#couleur-partie-haut').val() == "couleur15") {
          $('#photo-nom').attr('style','background-image: linear-gradient(rgba(10, 50, 255, 0.5), rgba(254 208 208))');
        } else if ($('#couleur-partie-haut').val() == "couleur16") {
          $('#photo-nom').attr('style','background-image: linear-gradient(rgba(50, 10, 255, 0.5), rgba(254 208 208))');
        } else if ($('#couleur-partie-haut').val() == "couleur17") {
          $('#photo-nom').attr('style','background-image: linear-gradient(rgba(200, 100, 255, 0.5), rgba(254 208 208))');
        } else {
          $('#photo-nom').removeAttr('style','');
        }
    });

    $("#couleur-partie-gauche").hover(function(){
        $(this).animate($('#elementGauche').addClass('wave'), "fast");
      }, function(){
        $(this).animate($('#elementGauche').removeClass('wave') , "fast");
    });

    $('#couleur-partie-gauche').click(function(){
        if ($('#couleur-partie-gauche').val() == "couleur1") {
          $('#elementGauche').attr('style','background-color:#FF9999');
        } else if ($('#couleur-partie-gauche').val() == "couleur2") {
          $('#elementGauche').attr('style','background-color:#FFCC99');
        } else if ($('#couleur-partie-gauche').val() == "couleur3") {
          $('#elementGauche').attr('style','background-color:#FFFF99');
        } else if ($('#couleur-partie-gauche').val() == "couleur4") {
          $('#elementGauche').attr('style','background-color:#CCFF99');
        } else if ($('#couleur-partie-gauche').val() == "couleur5") {
          $('#elementGauche').attr('style','background-color:#99FF99');
        } else if ($('#couleur-partie-gauche').val() == "couleur6") {
          $('#elementGauche').attr('style','background-color:#99FFCC');
        } else if ($('#couleur-partie-gauche').val() == "couleur7") {
          $('#elementGauche').attr('style','background-color:#99FFFF');
        } else if ($('#couleur-partie-gauche').val() == "couleur8") {
          $('#elementGauche').attr('style','background-color:#99CCFF');
        } else if ($('#couleur-partie-gauche').val() == "couleur9") {
          $('#elementGauche').attr('style','background-color:#9999FF');
        } else if ($('#couleur-partie-gauche').val() == "couleur10") {
          $('#elementGauche').attr('style','background-color:#CC99FF');
        } else if ($('#couleur-partie-gauche').val() == "couleur11") {
          $('#elementGauche').attr('style','background-color:#FF99FF');
        } else if ($('#couleur-partie-gauche').val() == "couleur12") {
          $('#elementGauche').attr('style','background-color:#FF99CC');
        } else if ($('#couleur-partie-gauche').val() == "couleur13") {
          $('#elementGauche').attr('style','background-color:#E0E0E0');
        } else if ($('#couleur-partie-gauche').val() == "couleur14") {
          $('#elementGauche').attr('style','background-image: linear-gradient(rgba(0, 0, 255, 0.5), rgba(254 208 208))');
        } else if ($('#couleur-partie-gauche').val() == "couleur15") {
          $('#elementGauche').attr('style','background-image: linear-gradient(rgba(10, 50, 255, 0.5), rgba(254 208 208))');
        } else if ($('#couleur-partie-gauche').val() == "couleur16") {
          $('#elementGauche').attr('style','background-image: linear-gradient(rgba(50, 10, 255, 0.5), rgba(254 208 208))');
        } else if ($('#couleur-partie-gauche').val() == "couleur17") {
          $('#elementGauche').attr('style','background-image: linear-gradient(rgba(200, 100, 255, 0.5), rgba(254 208 208))');
        } else {
          $('#elementGauche').removeAttr('style','');
        }
    });

    $("#couleur-partie-droite").hover(function(){
        $(this).animate($('#candidatCV').addClass('wave'), "fast");
      }, function(){
        $(this).animate($('#candidatCV').removeClass('wave') , "fast");
    });

    $('#couleur-partie-droite').click(function(){
        if ($('#couleur-partie-droite').val() == "couleur1") {
          $('#candidatCV').attr('style','background-color:#FF9999');
        } else if ($('#couleur-partie-droite').val() == "couleur2") {
          $('#candidatCV').attr('style','background-color:#FFCC99');
        } else if ($('#couleur-partie-droite').val() == "couleur3") {
          $('#candidatCV').attr('style','background-color:#FFFF99');
        } else if ($('#couleur-partie-droite').val() == "couleur4") {
          $('#candidatCV').attr('style','background-color:#CCFF99');
        } else if ($('#couleur-partie-droite').val() == "couleur5") {
          $('#candidatCV').attr('style','background-color:#99FF99');
        } else if ($('#couleur-partie-droite').val() == "couleur6") {
          $('#candidatCV').attr('style','background-color:#99FFCC');
        } else if ($('#couleur-partie-droite').val() == "couleur7") {
          $('#candidatCV').attr('style','background-color:#99FFFF');
        } else if ($('#couleur-partie-droite').val() == "couleur8") {
          $('#candidatCV').attr('style','background-color:#99CCFF');
        } else if ($('#couleur-partie-droite').val() == "couleur9") {
          $('#candidatCV').attr('style','background-color:#9999FF');
        } else if ($('#couleur-partie-droite').val() == "couleur10") {
          $('#candidatCV').attr('style','background-color:#CC99FF');
        } else if ($('#couleur-partie-droite').val() == "couleur11") {
          $('#candidatCV').attr('style','background-color:#FF99FF');
        } else if ($('#couleur-partie-droite').val() == "couleur12") {
          $('#candidatCV').attr('style','background-color:#FF99CC');
        } else if ($('#couleur-partie-droite').val() == "couleur13") {
          $('#candidatCV').attr('style','background-color:#E0E0E0');
        } else if ($('#couleur-partie-droite').val() == "couleur14") {
          $('#candidatCV').attr('style','background-image: linear-gradient(156deg, #083d8c, #2f66a4, #5f8eb7, #96b5c9, #d4dbde)');
        } else if ($('#couleur-partie-droite').val() == "couleur15") {
          $('#candidatCV').attr('style','background-image: linear-gradient(156deg, #c79e23, #a57348, #917b39, #949698, #5e84a9)');
        } else if ($('#couleur-partie-droite').val() == "couleur16") {
          $('#candidatCV').attr('style','background-image: linear-gradient(156deg, #00eea7, #588779, #496944, #2b4932, #0f4a39);');
        } else if ($('#couleur-partie-droite').val() == "couleur17") {
          $('#candidatCV').attr('style','background-image: linear-gradient(156deg, #fafdff, #b7ffea, #bffcb6, #71e68c, #12dfa4);');
        } else {
    	  $('#candidatCV').removeAttr('style','');
        }
    });

    $("#imageFormat").hover(function(){
        $(this).animate($('.image-candidat').addClass('rotation'), "fast");
      }, function(){
        $(this).animate($('.image-candidat').removeClass('rotation') , "fast");
    });

    $('#imageFormat').click(function(){
    	if($('#imageFormat').val() == "cercle"){
    	   // $('.image-candidat').attr('style', 'border-radius:50%!important');
         $('.image-candidat').addClass('image-rond');
    	} else if ($('#imageFormat').val() == "arrondi") {
         $('.image-candidat').attr('style', 'border-radius:20%!important');
         $('.image-candidat').removeClass('image-rond');
      } else if ($('#imageFormat').val() == "losange") {
         $('.image-candidat').attr('style', 'clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);');
         $('.image-candidat').removeClass('image-rond');
      } else {
    		$('.image-candidat').removeAttr('style','');
        $('.image-candidat').removeClass('image-rond');
    	}
    });

    $('#resolutionCV').click(function(){
      if($('#resolutionCV').val() == "paysage"){
        $('#control-boutton').addClass('row');
        $('#experience-case').addClass('col-md-7');
        $('#formation-case').addClass('col-md-5');
        $('#experience-formation').addClass('row');
        $('#cv-template').removeClass('size-a4');
        $('#cv-template').addClass('size-a4-paysage rotate');
        $('#adresse').addClass('hidden');
        $('#idAdresse').removeClass('hidden');
        $('#create_pdf_paysage').removeClass('hidden');
        $('#create_pdf_portrait').addClass('hidden');
        $('.small').addClass('police-small');
        $('.sous-titre').addClass('sous-titre-size');
      } else {
        $('#control-boutton').removeClass('row');
        $('#experience-case').removeClass('col-md-7');
        $('#formation-case').removeClass('col-md-5');
        $('#experience-formation').removeClass('row');
        $('#cv-template').addClass('size-a4');
        $('#cv-template').removeClass('size-a4-paysage rotate');
        $('#adresse').removeClass('hidden');
        $('#idAdresse').addClass('hidden');
        $('#create_pdf_paysage').addClass('hidden');
        $('#create_pdf_portrait').removeClass('hidden');
        $('.small').removeClass('police-small');
        $('.sous-titre').removeClass('sous-titre-size');
      }
    });

    $('#modeleCV').click(function(){
      if ($('#modeleCV').val() == "modele-2") {
        $('.modele-2').removeClass('hidden');
        $('.adrese-model').addClass('hidden');
        $('#nom-prenom').addClass('hidden');
        $('#photo-background').addClass('hidden');
      } else {
        $('.modele-2').addClass('hidden');
        $('.adrese-model').removeClass('hidden');
        $('#nom-prenom').removeClass('hidden');
        $('#photo-background').removeClass('hidden');
      }
    });

    $("#picker1").kendoColorPicker({
        input: false,
        preview:false,
        value: "#E0E0E0",
        buttons: false,
        select: preview,
        views: ["gradient", "palette"]
    });

    function preview(e) {
      $("#elementGauche").css("background-color", e.value);
    }

    $("#picker-titre").kendoColorPicker({
        input: false,
        titre:false,
        value: "#1f487c",
        buttons: false,
        select: titre,
        views: ["gradient", "palette"]
    });

    function titre(e) {
      $(".underline").css("color", e.value);
    }

    $("#picker-text-droite").kendoColorPicker({
        input: false,
        textDroite:false,
        value: "#000000",
        buttons: false,
        select: textDroite,
        views: ["gradient", "palette"]
    });

    function textDroite(e) {
      $("#textDroite").css("color", e.value);
    }

    $("#picker-text-gauche").kendoColorPicker({
        input: false,
        textGauche:false,
        value: "#000000",
        buttons: false,
        select: textGauche,
        views: ["gradient", "palette"]
    });

    function textGauche(e) {
      $("#textGauche").css("color", e.value);
    }

    $("#picker-couleur-droite").kendoColorPicker({
        input: false,
        partieDroite:false,
        value: "#ffffff",
        buttons: false,
        select: partieDroite,
        views: ["gradient", "palette"]
    });

    function partieDroite(e) {
      $("#candidatCV").css("background-color", e.value);
    }

    $("#picker-en-tete").kendoColorPicker({
        input: false,
        enTete:false,
        value: "darkgrey",
        buttons: false,
        select: enTete,
        views: ["gradient", "palette"]
    });

    function enTete(e) {
      $(".format-nomPrenom").css("color", e.value);
    }


    $("#picker2").kendoColorPicker({
        input: false,
        next:false,
        value: "#E0E0E0",
        buttons: false,
        select: next,
        views: ["gradient", "palette"]
    });

    function next(e) {
      $("#photo-nom").css("background-color", e.value);
    }

    $(".export-img").click(function() {
        // Convert the DOM element to a drawing using kendo.drawing.drawDOM
        kendo.drawing.drawDOM($(".canidatCV"))
        .then(function(group) {
            // Render the result as a PNG image
            return kendo.drawing.exportImage(group);
        })
        .done(function(data) {
            // Save the image file
            kendo.saveAs({
                dataURI: data,
                fileName: "CV-image.png",
                proxyURL: "https://demos.telerik.com/kendo-ui/service/export"
            });
        });
    });

    $(".export-pdf-paysage").click(function() {
        // Convert the DOM element to a drawing using kendo.drawing.drawDOM
        kendo.drawing.drawDOM($(".canidatCV"))
        .then(function(group) {
            // Render the result as a PDF file
            return kendo.drawing.exportPDF(group, {
                paperSize: "a4",
                landscape: true,
                margin: { left: "1cm", top: "1cm", right: "1cm", bottom: "1cm" }
            });
        })
        .done(function(data) {
            // Save the PDF file
            kendo.saveAs({
                dataURI: data,
                fileName: "CV-candidat.pdf",
                proxyURL: HOST.origin + "/Web/Ressources/Kendo UI/examples/pdf-export/index"
            });
        });
    });

    $(".export-pdf-portrait").click(function() {
        // Convert the DOM element to a drawing using kendo.drawing.drawDOM
        kendo.drawing.drawDOM($(".canidatCV"))
        .then(function(group) {
            // Render the result as a PDF file
            return kendo.drawing.exportPDF(group, {
                paperSize: "a4",
                margin: { left: "1cm", top: "1cm", right: "1cm", bottom: "1cm" }
            });
        })
        .done(function(data) {
            // Save the PDF file
            kendo.saveAs({
                dataURI: data,
                fileName: "CV-candidat.pdf",
                proxyURL: HOST.origin + "/Web/Ressources/Kendo UI/examples/pdf-export/index"
            });
        });
    });
});