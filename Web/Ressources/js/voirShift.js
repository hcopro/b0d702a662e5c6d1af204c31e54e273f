    $(document).ready(function() {
        $("#taskBoard").kendoTaskBoard({
            columns: shiftColumn,
            dataSource: {
                transport: {
                    read: function(options) {
                        options.success(cardsData);
                    },
                    update: function(options) {
                        console.log(options)
                        // Créer un nouvel objet Date à partir de la chaîne de date et d'heure d'origine
                        let dateTime            = new Date($('#endDateTime').val());
                        // Formater la date et l'heure au format souhaité
                        let formattedDate   = dateTime.toLocaleString("fr-FR", { 
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit'
                        });
                        let formattedTime   = dateTime.toLocaleString("fr-FR", { 
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        // options.data.description = formattedDateTime;
                        // envoyer une requête AJAX pour mettre à jour la carte sur le serveur
                        let params = 'idShift=' + options.data.id + '&endDate=' + formattedDate + '&endTime=' + formattedTime + '&currentStatus=' + options.data.status + '&idEmploye=' + options.data.employe;
                        // mes =  "Vous n'avez pas le droit de les modifier";
                        executeActionShift('update-shift',params);
                    },
                    create: function (e) {
                        // Assign an ID to the new item.
                        /*e.data.ProductID = sampleDataNextID++;*/
                        // Save data item to the original datasource.
                        /*sampleData.push(e.data);*/
                        console.log(" CREATE AN add NEW CARD")
                        console.log(e)
                        console.log(e.data)
                        // mes = "Une erreur survenu lors de la création !";
                        executeActionShift('add-shift','status=' + e.data.status);
                        // On success.
                        e.success(e.data);
                        // On failure.
                        //e.error("XHR response", "status code", "error message");
                    },
                    destroy: function (e) {
                        // Locate item in original datasource and remove it.
                       /*   */
                        // On success.
                        console.log("DESTROYED DESTOYESD")
                        console.log(e)
                        console.log(e.data)
                        // mes = "Suppression impossible";
                        executeActionShift('delete-shift', 'idShift=' + e.data.id);
                        e.success();
                        // On failure.
                        e.error("XHR response", "status code", "error message");
                    }
                },
                schema: {
                    model: {
                        id: "id",
                        fields: {
                            id: {type: "number"},
                            order: {type: "number", defaultValue: 0},
                            title: {field: "title", defaultValue: "No title"},
                            date: {field: "date", defaultValue: new Date()},
                            description: {field: "description", validation: {required: true}},
                            image: {from: "image", field:"title", defaultValue: ""},
                        }
                    }
                }
            },
            dataStatusField: "status",
            dataOrderField: "order",
            dataCategoryField: "color",
            resources: [
                {
                    field: "color",
                    dataSource: colorItem
                }
            ],
            template: "<div class='k-card-header k-hbox'><a class='k-card-title k-link font-weight-bold' data-id='#= data.idEmploye #' data-target='cardUser' data-toggle='modal' data-source='" + new URL(window.location).origin.origin +"'data-statut='#= data.status #' data-user='#= data.id #' data-image='#= data.image #' data-title='#= data.title #' data-image='#= data.imageUrl #' id='titleCard'><img src='#=data.imageUrl#' class='rounded-circle' style='height:30px;width:30px'> #= data.title #</a><span class='k-spacer'></span><div class='k-card-header-actions'><button aria-label='menu' class='k-button k-button-md k-rounded-md k-button-flat k-button-flat-base k-taskboard-card-menu-button k-icon-button'><span class='k-button-icon k-icon k-i-more-vertical'></span></button></div></div><div class='k-card-body'> <p id='paragraphCard'> #= data.description # </p></div>"
        });

        let changeTxt = $('ul.k-widget.k-reset.k-menu.k-menu-vertical.k-context-menu.k-popup.k-group').find('li[data-command="EditCardCommand"]');
            $(changeTxt).find('.k-menu-link-text').text('Modifier shift');
            changeTxt = $('ul.k-widget.k-reset.k-menu.k-menu-vertical.k-context-menu.k-popup.k-group').find('li[data-command="DeleteCardCommand"]');
            $(changeTxt).find('.k-menu-link-text').text('Supprimer shift');
            $('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-base.k-toolbar-tool').find('.k-button-text').text('Ajouter une campagne');
            $('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-base.k-toolbar-tool[aria-label="Add column"]').attr('title', 'Ajout');
            $('input[aria-label="Search"]','span.k-toolbar-tool.k-toolbar-item').attr('placeholder', 'Chercher...');
            $('input[aria-label="Search"]','span.k-toolbar-tool.k-toolbar-item').attr('title', 'Recherche');

        
       
        // $("#taskBoard").on("click", "a.k-card-title", function(e) {
        //     e.preventDefault();
        //     // Get the necessary data for the new modal
        //     var id = $(this).data("id");
        //     var idCandidature = $(this).data("candidature");
        //     var title = $(this).data("title");
        //     var source = $(this).data("source");
        //     var description = $(this).data("description");
        //     var imageUrl = $(this).data("imageUrl");
        //     var image = $(this).data("image");
        //     var statut = $(this).data("statut");
        //     var url = window.location;
        //     var temp = url.origin;
        //     // Render the new modal
        //     var newModalTemplate = kendo.template($("#newModalTemplate").html());
        //     var newModal = $(newModalTemplate({
        //         id: id,
        //         // idCandidature: idCandidature,
        //         title: title,
        //         source:source,
        //         description: description,
        //         imageUrl: imageUrl,
        //         image:image,
        //         statut:statut,
        //         temp:temp
        //     })).appendTo("body");
        //     // Show the new modal
        //     newModal.modal("show");
    });
    $(window).on('load', function(event) {
        console.log("load fffff");
        setTimeout(function(){

            $('.k-item.k-menu-item').on("click", function() { // Edit un shift
                setTimeout(function () {
                    if ($('.k-taskboard-pane.k-taskboard-edit-pane').length != 0){
                        moment.locale('fr');
                        let text = $('.k-taskboard-pane-header-text', '.k-taskboard-pane.k-taskboard-edit-pane').text();
                        $('.k-taskboard-pane-header-text', '.k-taskboard-pane.k-taskboard-edit-pane').text(text.replace("Edit", "Edition:"));
                        $('.k-taskboard-pane.k-taskboard-edit-pane').find('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-primary').attr("title", "Enregistrer");
                        $('.k-taskboard-pane.k-taskboard-edit-pane').find('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-primary').text("Enregistrer");
                        $('.k-taskboard-pane.k-taskboard-edit-pane').find('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-base').text("Annuler");
                        $('.k-taskboard-pane.k-taskboard-edit-pane').find('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-base').attr("title", "Annuler");
                        $('.k-taskboard-pane.k-taskboard-edit-pane').find('label[for="title"].k-label.k-form-label').text("Nom:");
                        $('.k-taskboard-pane-content').find('.k-widget.k-form');
                        let lastDate    = $('.k-taskboard-pane-content').find('input#description').val().split('jusqu\'à')[1];
                        let dateArray   = lastDate.split(' à ');
                        let endDate     = moment(dateArray[0], "DD MMMM YYYY").format("YYYY/MM/DD");
                        let endTime     = dateArray[1].replace(/[^0-9]/g, ':').replace(/(:)\1+/g, '$1').replace(/^:|:$/g, '');
                        let endDateTime = endDate + ' ' + endTime;
                        $('.k-taskboard-pane-content').find('.k-form-buttons').before('<div class="k-form-field "><label for="endDatetimepicker">Terminer sa campagne ce : <input id="endDateTime"/></label></div>');
                        $("#endDateTime").kendoDateTimePicker({
                            value: new Date(endDateTime),
                            weekNumber: true,
                        });
                        $('#endDateTime').on('change',function(){
                            // Créer un nouvel objet Date à partir de la chaîne de date et d'heure d'origine
                            let dateTime            = new Date($('#endDateTime').val());
                            // Formater la date et l'heure au format souhaité
                            let formattedDateTime   = dateTime.toLocaleString("fr-FR", { 
                                year: 'numeric', 
                                month: '2-digit', 
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            $('#description').val(formattedDateTime);

                        });
                    }else if ($('.k-window-titlebar.k-dialog-titlebar.k-hstack').length != 0){
                        $('.k-window-title.k-dialog-title', '.k-window-titlebar.k-dialog-titlebar.k-hstack').text("Suppression du shift");
                        $('.k-window-content.k-dialog-content', '.k-widget.k-window.k-dialog.k-confirm').text("Êtes-vous sure de supprimer ce shift?");
                        $('.k-widget.k-window.k-dialog.k-confirm').find('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-primary').text("Supprimer");
                        $('.k-widget.k-window.k-dialog.k-confirm').find('button.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-base').text("Annuler");
                    }
                }, 60);
            });

            $(".k-taskboard-column-header-actions", "#taskBoard").off("click", 'button.k-taskboard-column-action-button').on("click", 'button.k-taskboard-column-action-button', function() {
                setTimeout(function(){ // Supprimer cette classe k-taskboard-edit-pane lors ajout.
                    $('.k-taskboard-pane.k-taskboard-edit-pane').remove();
                    $('.k-taskboard-content').css('margin-right',0);
                }, 1);
                // if ($(this).attr('data-command') != 'DeleteColumnCommand') {
                    var uid         = $(this).closest('.k-taskboard-column').attr('data-uid');
                    var text        = $(this).closest('.k-taskboard-column-header').find('.k-taskboard-column-header-text').text();
                    console.log($('.k-taskboard-column-edit').length)
                    let interval    = setInterval(function() {
                        console.log($('.k-taskboard-column-edit').length)
                        console.log(text);
                        console.log(uid);
                        if ($('.k-taskboard-column-edit').length === 0) {
                            clearInterval(interval); // Stop the interval
                            if ($('.add-column-campaign').length > 0) {
                                $('.add-column-campaign').remove();
                            }
                            var columnEdited    = $('.k-taskboard-column[data-uid="'+uid+'"]');
                            let editColumn      = $(columnEdited).find('.k-taskboard-column-cards.taskBoard-kendosortable');
                            $(editColumn).prepend('<div class="k-taskboard-column-cards taskBoard-kendosortable add-column-campaign"></div>');
                            console.log($(editColumn));
                            console.log($('.add-column-campaign'));
                            templateColumn($('.k-taskboard-column-cards.taskBoard-kendosortable.add-column-campaign'), null, text);
                        }
                    }, 1000); // Check every 1 second (you can adjust the interval as needed)
                // }
            });

            $("#taskBoard").off("click", "button.k-taskboard-column-action-button").on("click", "button.k-taskboard-column-action-button", function(arg) {
                console.log("test");
                // console.log(arg);
                console.log($(this).attr('data-command'));
                let libelle = $('.k-taskboard-column-header-text', $(this).closest('.k-taskboard-column-header')).text();    
                let action = '';
                let searchParams = '';
                let messError = '';
                /*if ($(this).attr('data-command').indexOf('Add') !== -1) {
                  // Word found in the string
                  action = 'add-campaign';
                  searchParams = "libelle=" + libelle;
                console.log(action);

                }*/
                if ($(this).attr('data-command').indexOf('Delete') !== -1) {
                    // Traduction du boîte de dialogue anglais en français
                    $('span.k-window-title.k-dialog-title','.k-widget.k-window.k-dialog.k-confirm').text("Suppression campagne");
                    $('.k-window-content.k-dialog-content','.k-widget.k-window.k-dialog.k-confirm').text("Êtes-vous sure de supprimer cette campagne?");
                    $('.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-primary','.k-dialog-buttongroup.k-actions.k-hstack.k-justify-content-end').text('Supprimer');
                    $('.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-base','.k-dialog-buttongroup.k-actions.k-hstack.k-justify-content-end').text('Annuler');
                    $('.k-button.k-button-md.k-rounded-md.k-button-solid.k-button-solid-primary','.k-dialog-buttongroup.k-actions.k-hstack.k-justify-content-end').click(function(){
                        executeActionShift('delete-campaign', "libelle=" + libelle);
                    });
                } else if ($(this).attr('data-command') === 'AddCardCommand') {
                    // if ($('.k-taskboard-column-cards.taskBoard-kendosortable.add-column-campaign').length > 0) {
                    // }
                }
            });



            // $('.k-taskboard-column-header-actions').first().attr("hidden", true); tsy mety mihitsy
            $('button[data-command="AddColumnCommand"]' , $("#taskBoard")).click(function () {
                console.log("AddColumnCommand");
                // $('.k-taskboard-column').last().remove();
                // $($('.k-taskboard-column').first(), $('.k-taskboard-columns-container')).after($(taskboardColumn));
                // let taskboardColumn =  $('.k-taskboard-column').last();
                let interval = setInterval(function() {
                if ($('.k-taskboard-column-header-text', $('.k-taskboard-column').last()).text().trim()) {
                    $('.k-taskboard-column-cards.taskBoard-kendosortable').last().prepend('<div class="k-taskboard-column-cards taskBoard-kendosortable add-column-campaign"></div>');
                    templateColumn($('.k-taskboard-column-cards.taskBoard-kendosortable.add-column-campaign'), null);
                    clearInterval(interval); // Stop the interval
                    // Your code here after the variable is not null
                    console.log("myVariable is not null!");
                    
                  }
                }, 1000); // Check every 1 second (you can adjust the interval as needed)
            });
        }, 1000);


    });
   /*
        function changeHtml(selector, html) {
          var elem = $(selector);
          jQuery.event.trigger('htmlchanging', { elements: elem, content: { current: elem.html(), pending: html} });
          elem.html(html);
          jQuery.event.trigger('htmlchanged', { elements: elem, content: html });
        }
        $(document).on('htmlchanging', function (e, data) {
          // Logique à exécuter avant le changement de contenu du div
          console.log('before')
        });

        $(document).on('htmlchanged', function (e, data) {
          // Logique à exécuter après le changement de contenu du div
          console.log('after')
        });

        // Utilisation de la fonction changeHtml pour modifier le contenu du div
        changeHtml('.k-taskboard-column-header-text', 'Nouveau contenu');
    */

    function getCampaign(libelle)
    {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: new URL(window.location).origin + "/manage/getCampaignResponse",
                type: 'GET',
                data: 'libelle=' + libelle + '&return=json',
                dataType: 'json',
                success: function(data) {
                    // Résoudre la promesse avec l'objet renvoyé dans la réponse Ajax
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    // gérer l'erreur si nécessaire et  Rejeter la promesse avec l'erreur de la requête Ajax
                    reject(error);
                }
            });
        });
    }
    function executeActionShift(action,searchParams)
    {
        $.ajax({
            url: new URL(window.location).origin + "/manage/" + action,
            type: 'POST',
            data: searchParams,
            dataType: 'json',
            success: function(data) {
                // gérer la réponse si nécessaire
            },
            error: function(error) {
                // gérer l'erreur si nécessaire
                alert(error.responseText.split('"')[1]);
            }
        });
    }
    /*function convertValues(value) {
        var data = {};

        value = $.isArray(value) ? value : [value];

        for (var idx = 0; idx < value.length; idx++) {
            data["values[" + idx + "]"] = value[idx];
        }

        return data;
    }*/
    function addZeroNumber(number)
    {
        return number < 10 ? '0' + number : number;
    }
    function createUpdateCampaign(startDateTime,endDateTime, idCampaign, libelle, campaign)
    {
        console.log(startDateTime)
        console.log(endDateTime)
        let startTime       = addZeroNumber(startDateTime.getHours()) + ':' + addZeroNumber(startDateTime.getMinutes());
        let endTime         = addZeroNumber(endDateTime.getHours()) + ':' + addZeroNumber(endDateTime.getMinutes());
        let startDate       = startDateTime.getFullYear() + '-' + addZeroNumber(startDateTime.getMonth() + 1) + '-' + addZeroNumber(startDateTime.getDate());
        let endDate         = endDateTime.getFullYear() + '-' + addZeroNumber(endDateTime.getMonth() + 1) + '-' + addZeroNumber(endDateTime.getDate());
        console.log(campaign);
        if (startDate >= endDate) {
            alert(" La date debut doit être plus récente qu'à la date fin !");
            return false;
        }
        if (Object.keys(campaign).length) {
            if (campaign.startDate >= moment(startDateTime).format("YYYY-MM-DD")) {
                if (campaign.startDate < moment().format("YYYY-MM-DD")) {
                    startDateTime = campaign.startDate + ' ' + campaign.startTime;
                }
            } else{
                alert("Impossible de modifier la date debut de la campagne en cours !");
                $('#startDateTime').val(moment(campaign.startDate + ' ' + campaign.startTime).format("MM/DD/YYYY HH:MM"));
                return false;
            }
        }
        let searchParams = 'libelle=' + libelle + '&startDate=' + startDate + '&endDate='  + endDate + '&startTime=' + startTime + '&endTime=' + endTime + '&workerList=' + $('#workers').val().join(',');
            searchParams += idCampaign > 0 ? '&idCampaign=' + idCampaign : '';
            let link = idCampaign > 0 ? 'update' : 'addNew';
        $.ajax({
            url: new URL(window.location).origin + "/manage/" + link + "-campaign",
            type: 'POST',
            data: searchParams,
            success: function(response) {
                // gérer la réponse si nécessaire
                $('.k-taskboard-column-cards.taskBoard-kendosortable.add-column-campaign').remove();
            },
            error: function(error) {
                // gérer l'erreur si nécessaire
                alert("Impossible de créer en une !");
            }
        });
        console.log("call script CREATE OR UPDATE");
    }

    function templateColumn(selector, element, text=null)
    {
        var startDateInput  = new Date();
        var endDateInput    = new Date();
        var idCampaign      = 0;
        var listWorker      = [];
        var returnedObject  = {};
        console.log("eeeeeeeeeeeeeee")
        console.log(text)
        if (text) {
            // Inserer les champs à la modification
            // Appeler la fonction getCampaign pour effectuer la requête Ajax
            var ajaxPromise = getCampaign(text);
            // Utiliser la promesse pour récupérer l'objet renvoyé
            ajaxPromise.then(function(response) {
                console.log(response)
                returnedObject  = response.campaign;
                idCampaign      = returnedObject.idCampaign;
                $.each(response.shifts, function() {
                    listWorker.push(this.idEmploye);
                });
                if (!isNaN(Date.parse(returnedObject.startDate))) {
                    startDateInput  = new Date(returnedObject.startDate + ' ' + returnedObject.startTime);
                    endDateInput    = new Date(returnedObject.endDate + ' ' + returnedObject.endTime);
                }
                console.log(startDateInput);
                console.log(endDateInput);
                console.log(returnedObject);
                console.log(listWorker);
            }).catch(function(xhr, status, error) {
                // Gérer les erreurs ici
                console.log(error);
            });
        }
        // $("#startDateTime").change(function () {
        //     let startDate       = new Date($("#startDateTime").val());
        //     /*let datetimepicker  = $("#endDateTime").data("kendoDateTimePicker");
        //     // detach events
        //     datetimepicker.destroy();*/
        //     $("#endDateTime").kendoDateTimePicker({
        //         // Add some basic configuration.
        //         value: new Date(),
        //         // Définissez la date minimale et maximale
        //         min: new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate() + 1),
        //         // Définissez le format
        //         weekNumber: true,
        //         // format: "dd/mm/yyyy HH:mm"
        //     });
        // });
        // console.log($("#endDateTime"))
        let render = '<label for="startDatetimepicker">Débutara : <input id="startDateTime"/></label>' +
                        '<label for="endDatetimepicker">Terminera : <input id="endDateTime"/></label>' +
                        '<div class="k-d-flex k-justify-content-center">' +
                            '<div class="k-w-300">' +
                                '<label for="workers">Choisir salariés à la campagne</label>' +
                                '<select id="workers" style="width: 100%;"></select>' +
                            '</div>' +
                        '</div>' +
                        '<p><a id="saveChangedButton">Enregistrer</a><a id="cancelButton">Annuler</a></p>';
        $(selector).prepend(render);
        $("#saveChangedButton").kendoButton({
            themeColor  : "primary",
            enable      : true,
            icon        : "save"
        });
        $("#cancelButton").kendoButton({
            themeColor  : "base",
            enable      : true,
            icon        : "cancel"
        });
        $(selector).prepend('<lable for="libelle">Libelle :<div class="k-form-field-wrap" data-container-for="libelle"><span class="k-input k-textbox k-input-solid k-input-md k-rounded-md k-valid" style=""><input type="text" id="libelle" name="libelle" title="libelle" data-bind="value:libelle" data-role="textbox" aria-disabled="false" class="k-input-inner" autocomplete="off" style="width: 100%;"></span></div></label>');
        let libelle = $(selector).closest('.k-taskboard-column').find('.k-taskboard-column-header-text').text();
        $('#libelle').val(libelle);
        setTimeout(function(){
            // Target the input element by using jQuery and then call the kendoDateTimePicker() method.
            $("#startDateTime").kendoDateTimePicker({
                // Add some basic configuration.
                value: startDateInput,
                // Définissez le format
                min: new Date(startDateInput.getFullYear(), 0, 1),
                weekNumber: true,
                // format: "dd/mm/yyyy HH:mm"
            });
            // Target the input element by using jQuery and then call the kendoDateTimePicker() method.
            $("#endDateTime").kendoDateTimePicker({
                // Ajout quelques configurations basics .
                value: endDateInput,
                // Définissez la date minimale et maximale
                min: new Date(endDateInput.getFullYear(), endDateInput.getMonth(), endDateInput.getDate() + 1),
                // Définissez le format
                weekNumber: true,
                // format: "dd/mm/yyyy HH:mm"
            });
            $("#workers").kendoMultiSelect({
                dataTextField   : "ContactName", // anarana ary CompanyName atao service
                dataValueField  : "WorkerID", // idemploye
                headerTemplate  : '<div class="dropdown-header k-widget k-header">' +
                                    '<span>Photo & Nom du salariés</span>' +
                                  '</div>',
                footerTemplate  : 'Total #: instance.dataSource.total() # items ont trouvé',
                itemTemplate    : '<span class="k-state-default" style="background-image: url(\'' + new URL(window.location).origin + '/../Web/Ressources/images/employes/#:data.Photo#\')"></span>' +
                                  '<span class="k-state-default"><h3>#: data.ContactName #</h3><p>#: data.CompanyName #</p></span>',
                tagTemplate     : '<span class="selected-value" style="background-image: url(\'' + new URL(window.location).origin + '/../Web/Ressources/images/employes/#:data.Photo#\')"></span>' +
                                  '<span>#:data.ContactName#</span>',
                dataSource      : {
                    transport   : {
                        read    : {
                            dataType    : "json",
                            url         : new URL(window.location).origin + "/manage/getAllworker",
                        }
                    }
                },
                value           : listWorker,
                filter          : "contains",
                height          : 400
            });
            var workers = $("#workers").data("kendoMultiSelect");
            if (typeof(workers) != "undefined") {
                workers.value(listWorker); // Initialiser la valeur du select
                workers.wrapper.attr("id", "workers-wrapper");
            }
        }, 350);    
        $("#saveChangedButton").click(function () {
            let startDateTime   = new Date($('#startDateTime').val());
            let endDateTime     = new Date($('#endDateTime').val());
            let campaign        = returnedObject;
            let idCampaign      = campaign ? campaign.idCampaign : 0;
            let libelle         = $('#libelle').length > 0 ? $('#libelle').val().trim() : $(this).closest('.k-taskboard-column').find('.k-taskboard-column-header-text').text().trim();
            $(this).closest('.k-taskboard-column').find('.k-taskboard-column-header-text').text(libelle);
            if (startDateTime <= endDateTime) {
                createUpdateCampaign(startDateTime,endDateTime, idCampaign, libelle,campaign);
            } else {
                alert("Vérifier les dates");
            }
        });    
        $("#cancelButton").click(function () {
            $(this).closest('.k-taskboard-column-cards.taskBoard-kendosortable.add-column-campaign').remove();
        });
        // return returnedObject;
    }