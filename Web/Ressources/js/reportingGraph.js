var HOST = new URL(window.location).origin;
var waiting;
    waiting         = setInterval(function() { // 0% besoin
        if (typeof(datas) != 'undefined') {
            clearInterval(waiting);
            var taches = drawGraphReporting(datas.taskGroupe);
            callBack();
            changeTextAxeY();
            showUserGroupTaskOnSelect(datas.userTaskGroup);
            console.log(datas)
        }
    }, 100);
var roomData = [
    { Name: 'Présent', Id: 1, Capacity: 8, Color: '#1aaa55', Type: 'Présent' },
    { Name: 'Absent', Id: 2, Capacity: 25, Color: '#ff0000', Type: 'Absent' },
    { Name: 'Congé', Id: 3, Capacity: 5, Color: '#5978ee', Type: 'Congé' },
    { Name: 'Permission', Id: 4, Capacity: 20, Color: '#865fcf', Type: 'Permission' },
    { Name: 'Repos médical', Id: 5, Capacity: 20, Color: '#f754eb', Type: 'Repos médical' },
    { Name: 'Férié', Id: 6, Capacity: 25, Color: '#c44fe1', Type: 'Jour férié' },
    { Name: 'WK', Id: 7, Capacity: 10, Color: '#4f5bb3', Type: 'Week-end' }
];


// var colors              = [{
//         'Repos médical' : '#',
//         'Congé'         : '#',
//         'Permission'    : '#',
//         'Présent'       : '#',
//         'Absent'        : '#'
//     }];

// var colors              = [
//             '#ff8787', '#9775fa', '#748ffc', '#3bc9db', '#69db7c',
//             '#fdd835', '#748ffc', '#9775fa', '#df5286', '#7fa900',
//             '#fec200', '#5978ee', '#00bdae', '#ea80fc'
//     ];

$('document').ready(function(){
    if ($('#mois').val() != null) {
        // loadDate($('#mois').val(), $('#endDate').val(), $('#startDay').val());
        // loadContenu($("#filter-group").val(), $('#employe').val(), $('#mois').val(), $('#endDate').val(), $('#startDay').val());
    }
    $('.carousel').carousel({
        interval: false
    });
});

var currentDate     = new Date(); // Créer un nouvel objet Date
// Obtenir l'année, le mois et le jour à partir de l'objet Date
var currentYear     = currentDate.getFullYear();
var currentMonth    = currentDate.getMonth() + 1; // Note: Janvier est égale 0
var currentDay      = currentDate.getDate();
var weekEndOfMonth  = getWeekEndOfMonth(currentYear, currentMonth+1);
var dateOptions = { /*weekday: 'long',*/ year: 'numeric', month: 'long', day: 'numeric' }; // Formater la date en utilisant toLocaleDateString()

function drawGraphReporting (taskGroupe)
{
    var zero                = 0;
    var sum                 = 0; // initialize a variable to store the sum of the array
    let sommeTime           = 0;
    var middleWorkedTime    = 4;
    var minValue            = 8;
    var maxValue            = 18;
    var userTotalWorkTime   = 1900800;
    var dataGroup           = [];
    var task                = [];
    var percentTask         = [];
    var polarCategory       = [];
    var journeyTime         = [];
    var renderWorked        = [];
    let chartDataJourney    = [];
    let dateCollections     = [];
    var renderMin           = '';
    var renderMoy           = '';
    var renderMax           = '';
    var nextDayMin          = '';
    var nextDayMax          = '';
    var nextDayMoy          = '';
    var isSameMonth         = true;
    var themeColor          = [
          '#FF9747',   '#33DEA0',   '#DFFF54',
        '#FF746F',  '#82F9FF',  '#A0EB75',   '#FFD3A3',   '#D5CDEB',
        '#FFCFCC',   '#DDFFB9',  '#EBD5A9',  '#A7EBE2',   '#DFDBFF',
        '#FFEBB0',   '#A39EFF',  '#EB6B6A',  '#C0EBD7',   '#FCF497',
        '#D0FFA4',   '#BDD9FF',  '#8DF0C9',  '#CFB2EB',   '#FCB1BA', '#3F58F5', '#DE2FBB'
    ];
    var colors              = [{
        'Repos médical' : '#f754eb',
        'Congé'         : '#ff8787',
        'Permission'    : '#69db7c',
        'Présent'       : '#748ffc',
        'Absent'        : '#ff0000'
    }];
    var groupColor          = ['#dd8abd', '#70ad47', '#7f84e8', '#7bb4eb', '#ea7a57','#00bdae', '#357cd2', '#e56590' ,'#f8b883'];
    var tbodyMin            = $('tbody', '#render-workedMin');
    var tbodyMax            = $('tbody', '#render-workedMax');
    var tbodyMoy            = $('tbody', '#render-workedMoy');
    var numberTask          = 0;
    var cmpMonth            = -1;
    var fullYear            = currentYear;
    var parametrePointageEntreprise = datas.parametrePointageEntreprise;
    let key                 = 0;

    if (parametrePointageEntreprise.isFixedTime == 'NO') {
        var heureArret  = parametrePointageEntreprise.heureArret;
        var heureDebut  = parametrePointageEntreprise.heureDebut;
        var middelTime  = secondsToHMS(((timeToSeconds(heureArret) - timeToSeconds(heureDebut)) / 2) + timeToSeconds(heureDebut));
    }
    $.each(taskGroupe, function() {$.each(this, function(index, val) {if($.isNumeric(index)){ if(val.during.time > 0){numberTask++;}}});});
    $.each(taskGroupe, function() {
        if (this.mission.tracking.time > 0) {
            dataGroup.push({ x: this.mission.description , y: this.mission.tracking.time, text : '58%', color: groupColor[key]});
            key++;
        }
        $.each(this, function(index, val) {
            if ($.isNumeric(index)) {
                if (val.during.time > 0) {
                    task.push({ x: val.titre , y: val.during.time});
                    percentTask.push({text: val.titre, percent: (val.during.time * 100 / userTotalWorkTime).toFixed(2)});
                    sommeTime += val.during.time;
                }
            }
        });
    });
    task = fusionTaskes(task);
    $.each(task, function(index, val) {
        let rayon = 100 + ((100 / (task.length + 1)) * index);
        this.r = '' + rayon;
        this.during = this.y;
        this.y = Math.round(this.y * 100 / sommeTime);  // this.y = (this.y * 100 / sommeTime).toFixed(2);
        this.color = themeColor[index];
    });
    /**
     * 
     * Ajouter la durée du travail selon le filtre 
     *   EX: 
     *      si le filtre mois donc 173.33H => environs 1900800  var_dump(strtotime('22 days',0));
     *      si le filtre semaine donc 40H à 45H => environ 691200  var_dump(strtotime('8 days',0));
     * 
    */
    percentTask.push({text: 'somme inactif ou en pause', percent: (100 - (sommeTime * 100 / userTotalWorkTime)).toFixed(2)});
    percentTask.push({text: 'effectif travailée', percent: (sommeTime * 100 / userTotalWorkTime).toFixed(2)});
    var piechart = new ej.charts.AccumulationChart({
        series: [
            {
                dataSource: task,
                dataLabel: {
                    visible: true, position: 'Outside',
                    name: 'x',
                    font: { fontWeight: '600' }
                },
                radius: 'r', xName: 'x',
                yName: 'y', innerRadius: '40%',
                startAngle: 0,
                endAngle: 360,
                pointColorMapping: 'color'
            },
        ],
        textRender: (args= IAccTextRenderEventArgs) => {
            args.color = args.point.color;
            args.font.size = '13px';
            // args.point.color = coloring;
        },
        enableSmartLabels: true,
        legendSettings: {
            visible: true,
            position: 'Bottom'
        },
        enableAnimation: true,
        tooltip:{
            enable: true,
            format: '${point.x} : <b>${point.y}%</b>'
        },
        // height: '300',
        tooltipRender: (args = IAccTooltipRenderEventArgs) => {
           let value  = args.point.y;
           args.text = args.point.x + ' : ' + Math.ceil(value) + '' + '%';
        },
        // titleStyle: {
        //     fontFamily: "Arial",
        //     fontStyle: 'italic',
        //     fontWeight: 'regular',
        //     color: "#E27F2D",
        //     size: '23px'
        // }
    }, '#element');
    var piechart2 = new ej.charts.AccumulationChart({
        series: [
            {
                dataSource: dataGroup,
                dataLabel: {
                    visible: true, 
                    position: 'Outside',
                    // font: {color: #ffffff},
                    font: {
                        fontWeight: '600',
                        fontSize: '13'
                    },
                    // name: 'text'
                    name: 'x'
                },
                innerRadius: '55%',
                xName: 'x',
                yName: 'y',
                pointColorMapping: 'color'/*,
                showInLegend: false*/
            }
        ],
        textRender: (args= IAccTextRenderEventArgs) => {
            $.each(args.series.resultData, function(index, element) {
                if (args.text === element.x) {
                    let coloring = element.color;
                    args.color = coloring;
                    args.font.size = '13px';
                    // args.point.color = coloring;
                }                    
            });
        },
        legendSettings:{  
            enablePages: false, 
            position: 'Bottom',
            visible: false
        },
        height: '500',
        title: 'Tâche réalisée',
    },
     '#element2');
    $.each(datas.journey.data, function(index, val) {  // ato no alaina daolo ny donnée rehtra
        let point           = {};
        let nameX           = this.info.date;
        let presence        = zero;
        let absence         = zero;
        let objectString    = {};
        let names           = '';
        if (this.presence != null ) {
            if (this.info.temps.time > 0) {
                presence = valDouble(this.info.temps.hour + ':' + this.info.temps.minute + ':' + this.info.temps.second);
            } else {
                if (this.info.retard == null) {
                    absence = maxValue;
                }
            }
        } else {
            if (this.presence == null && (this.info.enConge || this.info.enPermission || this.info.auRepos)) {
                absence = zero;
            } else {
                absence = maxValue;
            }
        }
        objectString.x                              = nameX;
        objectString.y                              = this.info.retard != null ? valDouble(this.info.retard.during) + minValue : minValue;
        objectString.y1                             = absence;
        objectString.y2                             = presence >= maxValue ? presence - objectString.y : presence;
        this.info.enConge ? objectString.y3         =  this.info.enConge.beginto > 0 ? this.info.enConge.beginto + this.info.enConge.during : this.info.enConge.heureDebut == 1 && this.info.enConge.heureFin == 3 ? maxValue : maxValue + middleWorkedTime : '';
        resY                                        = this.info.temps.time > 0 ? valDouble(this.info.temps.hour+':'+this.info.temps.minute+':'+this.info.temps.second) : 0;
        objectString.color                          = themeColor[index % themeColor.length - 1];
        // y5: permission,
        // y6: repos médical,
        // y7: pause,

        chartDataJourney[index]                     = objectString;
        polarCategory[index]                        = point;
        let point2                                  = { x: nameX, y: resY };
        /**@changelog [EVOL] Lansky 21/03/2023 Ajouter planning de congé */
        /**
         * 
         * À gérer raha sady présent no congé ao anaty cellule iray sns ... 
         * 
         */
        let startTime   = new Date(this.info.id + ' ' + heureDebut);
        let endTime     = new Date(this.info.id + ' ' + heureArret);

        if (this.info.auRepos) {
            names       = 'Repos médical';
        } else if (this.info.enConge) {
            names       = 'Congé';
            if (parseInt(this.info.enConge.heureDebut) != 1 || parseInt(this.info.enConge.heureFin) != 3) {
                if (this.info.enConge.fin == this.info.id) {
                    let setHeureDebut = heureDebut;
                    let setHeureArret = heureArret;
                    if (parseInt(this.info.enConge.beginto) > 0) {
                        setHeureDebut   = (parseInt(this.info.enConge.beginto) < 10 ? "0" + this.info.enConge.beginto : this.info.enConge.beginto) + ":00:00";
                        let someTime    = parseInt(this.info.enConge.beginto) + parseInt(this.info.enConge.during);
                        setHeureArret   = (someTime < 10 ? "0" + someTime : someTime) + ":00:00";
                    } else {
                        if (this.info.enConge.heureDebut == 2) {
                            setHeureDebut = middelTime;
                        }
                        if (this.info.enConge.heureFin == 2) {
                            setHeureArret = middelTime;
                        }
                    }

                console.log(setHeureDebut)
                console.log(this.info.enConge.heureDebut)
                console.log(setHeureArret)
                console.log(this.info.enConge.heureFin)

                    startTime   = new Date(this.info.id + ' ' + setHeureDebut);
                    endTime     = new Date(this.info.id + ' ' + setHeureArret);
                } else if (this.info.enConge.debut == this.info.id && this.info.enConge.heureDebut == 2) {
                    setHeureDebut = middelTime;
                }
            }
        } else if (this.info.enPermission) {
            names       = 'Permission';
        } else if (this.info.temps.time > 0) {
            names       = 'Présent';
            if ('pointage' in this.presence) {
                startTime   = new Date(this.info.id + ' ' + this.presence.pointage[0].debut);
                endTime     = new Date(this.info.id + ' ' + this.presence.pointage[Object.keys(this.presence.pointage).pop()].fin);
            }
        } else if (this.info.isFerie) {
            names       = 'Férié';
        } else if (this.info.isWeekend) {
            names       = 'WK';
        } else /*if (!this.info.isFerie && !this.info.isWeekend)*/ {
            names       = 'Absent';
        }
        if (cmpMonth < 0) {
            let newDate     = new Date(this.info.id); 
                fullYear    = newDate.getFullYear();
                cmpMonth    = newDate.getMonth();
        }
        let tmpCurrentDate = new Date(this.info.id);
        if (cmpMonth != tmpCurrentDate.getMonth() || index == datas.journey.data.length - 1) {
            if (index == datas.journey.data.length - 1 && names.trim()) {
                dateCollections.push({
                    RoomId: $.grep(roomData, function(e){ return e.Name === names; })[0].Id,
                    Id: index + 1,
                    Subject: names,
                    StartTime: startTime,
                    EndTime: endTime,
                    IsAllDay: (index + 1 % 10) ? true : false,
                    // EventColor: colors.names,
                    TaskId: (index + 1 % 5) + 1
                });
            }
            let randomized  = Math.floor(Math.random()*1000);
            setHtml(randomized);
            drawSchedulePlanning('scheduleObjArg'+randomized, '#Schedule'+randomized, fullYear, cmpMonth, dateCollections);
            cmpMonth    = tmpCurrentDate.getMonth();
            fullYear    = tmpCurrentDate.getFullYear();

        }
        if (names.trim()) {
            dateCollections.push({
                RoomId: $.grep(roomData, function(e){ return e.Name === names; })[0].Id,
                Id: index + 1,
                Subject: names,
                StartTime: new Date(this.info.id),
                EndTime: new Date(this.info.id),
                IsAllDay: (index + 1 % 10) ? true : false,
                // EventColor: colors.names,
                TaskId: (index + 1 % 5) + 1
            });
        }

        /*********************************/
        journeyTime.push(point2);
        let dataMonth = $.inArray(nameX.split(' ')[1], MONTHS_FR);
        if(currentYear != nameX.split(' ')[2] || currentMonth != dataMonth - 1) {
            weekEndOfMonth = getWeekEndOfMonth(nameX.split(' ')[2], dataMonth);
        }
        let dataDate = new Date(nameX.split(' ')[2] + '-' + ($.inArray(nameX.split(' ')[1], MONTHS_FR)+1) + '-' + nameX.split(' ')[0]);
        if ($.inArray(nameX.toLowerCase(), weekEndOfMonth) < 0 /*&& nameX*/ && dataDate <= currentDate) {
            renderWorked.push(point2);
        }
    });
    console.log(datas.journey.data)
    console.log(dateCollections)
    renderWorked    = groupBy(renderWorked, "y"); // Grouper les valeurs identiques
    var objKeys     = Object.keys(renderWorked); // Récupérer les indexs d'objet
    objKeys.sort((a, b) => a - b); // Trier en ordre croissant l'objet
    // filter the array to only include the values that exist
    var existingValues = $.grep(objKeys, function(value) {
      return value != null;
    });
    // loop through the array and calculate the sum
    $.each(existingValues, function(index, value) {
      sum += parseFloat(value);
    });
    // calculate the average by dividing the sum by the length of the array
    var average = sum / existingValues.length;
    // search for the average value in the array and get its key
    var averageKey = $.inArray(average, objKeys);
    if (averageKey < 0) {
        let diff = average;
        $.each(objKeys, function(ind, val) {
            if (diff > Math.abs(parseFloat(val) - average)) {
                averageKey = ind;
            }
        });
    }
    // console.log(datas)
    /* Remplir le tableau du légende */
    $.each(renderWorked[objKeys[0]], function(k, v) {
        if (k < renderWorked[objKeys[0]].length - 1) {
            var cmp1 = parseInt(renderWorked[objKeys[0]][k+1].x.split(' ')[0]);
        }
        if (cmp1 === parseInt(this.x.split(' ')[0]) + 1) {
            if (!$.trim(nextDayMin)) {
                nextDayMin = this.x.split(' ')[0] + '-';
            }
        } else {
            renderMin +='<tr> ' +
                            '<td >' + nextDayMin + this.x + '</td> ' +
                            '<td class="text-important">' + floatToHour(this.y) + '</td> ' +
                        '</tr>';
            nextDayMin = '';
        }
    });
    $.each(renderWorked[objKeys[averageKey]], function(k, v) {
        if (k < renderWorked[objKeys[averageKey]].length - 1) {
            var cmp2 = parseInt(renderWorked[objKeys[averageKey]][k+1].x.split(' ')[0]);
        }
        if (cmp2 === parseInt(this.x.split(' ')[0]) + 1) {
            if (!$.trim(nextDayMoy)) {
                nextDayMoy = this.x.split(' ')[0] + '-';
            }
        } else {
            renderMoy +='<tr> ' +
                            '<td >' + nextDayMoy + this.x + '</td> ' +
                            '<td class="text-important">' + floatToHour(this.y) + '</td> ' +
                        '</tr>';
            nextDayMoy = '';
        }
    });
    $.each(renderWorked[objKeys[objKeys.length - 1]], function(k, v) {
        if (k < renderWorked[objKeys[objKeys.length - 1]].length - 1) {
            var cmp3 = parseInt(renderWorked[objKeys[objKeys.length - 1]][k+1].x.split(' ')[0]);
        }
        if (cmp3 === parseInt(this.x.split(' ')[0]) + 1) {
            if (!$.trim(nextDayMax)) {
                nextDayMax = this.x.split(' ')[0] + '-';
            }
        } else {
            renderMax +='<tr> ' +
                            '<td >' + nextDayMax + this.x + '</td> ' +
                            '<td class="text-important">' + floatToHour(this.y) + '</td> ' +
                        '</tr>';
            nextDayMax = '';
        }
    });
    $('section#back-section').css('overflow-y','visible');
    tbodyMin.append(renderMin);
    tbodyMax.append(renderMax);
    tbodyMoy.append(renderMoy);
    var chart4 = new ej.charts.Chart({
            primaryXAxis: {
                title: 'Hebdomadaire/ Mensuel/ Annuel',
                interval: 1,
                labelIntersectAction : 'Rotate45',
                valueType: 'Category'
            },
            primaryYAxis:
            {
                title: 'Pointage journalier',
                minimum: minValue, maximum: maxValue, interval: 1
            },
            tooltip: {
                enable: true
            },
            /*axes:[
                {
                    majorGridLines: { width: 0 },
                    rowIndex: 1, opposedPosition: true,
                    lineStyle: { width: 0 },
                    minimum: 24, maximum: 36, interval: 2,
                    name: 'yAxis', title: 'Temperature (Celsius)',
                    labelFormat: '{value}°C'
                }
            ],*/
            series: [
                {
                    type: 'StackingColumn', dataSource: chartDataJourney,
                    xName: 'x', yName: 'y', name: 'En retard'
                },
                {
                    type: 'StackingColumn', dataSource: chartDataJourney,
                    xName: 'x', yName: 'y1', name: 'Absence'
                },
                {
                    type: 'StackingColumn', dataSource: chartDataJourney,
                    xName: 'x', yName: 'y2', name: 'Présence'
                },
                {
                    type: 'StackingColumn', dataSource: chartDataJourney,
                    xName: 'x', yName: 'y3', name: 'En congé'

                },
                /*
                    {
                        type: 'StackingColumn', dataSource: chartDataJourney,
                        xName: 'x', yName: 'y5', name: 'En permission'
                    },
                    {
                        type: 'StackingColumn', dataSource: chartDataJourney,
                        xName: 'x', yName: 'y6', name: 'En répos médical'

                    },
                    {
                        type: 'StackingColumn', dataSource: chartDataJourney,
                        xName: 'x', yName: 'y7', name: 'En pause'

                    },
                */
                //         dataSource: chartData, width:2,
                // xName: 'x', yName: 'y1', yAxisName: 'yAxis',
                // marker: { visible: true, width: 10, height: 10, border: { width: 2, color: '#F8AB1D' } 
                /*{
                    type: 'Line',  name: 'Time tracked',
                    dataSource: chartDataJourney, xName: 'x', yName: 'y4', yAxisName: 'yAxis',
                    width: 4, opacity: 0.9,
                    marker: {
                        visible: true,
                        width: 10, opacity: 0.6,
                        height: 10
                    },
                }*/
            ],
            tooltipRender: (args = IAccTooltipRenderEventArgs) => {
               let value  = args.point.y;
                if ($(args.headerText).filter("b").replaceWith("").end().text() === "En retard") {
                    args.text = args.point.x + ' : ' + (value - minValue).toFixed(2) + 'H';
               } else {
                    args.text = args.point.x + ' : ' + value.toFixed(2) + 'H';
               }
            },
            title: "journey's users"
    }, '#journey');
    var chartxy = new ej.charts.Chart({
        primaryXAxis: {
            title: 'Temps suivi',
            valueType: 'Category',
            labelIntersectAction : 'Rotate45'
        },
        primaryYAxis: {
            title: 'temps de travail journalier',
            labelFormat: '{value}H',
        },
        series:[{
            dataSource: journeyTime,
            xName: 'x', yName: 'y',
            // Series type as SplineArea
            type: 'SplineArea',
        }],
        //Zooming for chart
        zoomSettings:
        {
            enableMouseWheelZooming: true,
            enablePinchZooming: true,
            enableSelectionZooming: true
        },
    }, '#journey-time');


    generateClasses();
    animateTableOnGraph();
    $($('button.dropdown-toggle[type="button"]'), $('.selectpicker').parent()).css('height', '34px');
    $($('button.dropdown-toggle[type="button"]'), $('.selectpicker').parent()).css('color', 'black');
    
    // drawSchedulePlanning('scheduleObjArg', '#Schedule', 10, dateCollections);
    // drawSchedulePlanning('scheduleObjArg1', '#Schedule1', 3);
    // drawSchedulePlanning('scheduleObjArg2', '#Schedule2', 6);
    // drawSchedulePlanning('scheduleObjArg3', '#Schedule3', 10);
    return task;
}
// Redimension la taille de la fenêtre et recupère le style du graphe
// Récupère l'affichage lors du dimensionnement d'écran
$(window).bind('resize', function(e){
    window.resizeEvt;
    $(window).resize(function(){
        var intervalId;
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function () {
            callBack();
        },350);
        intervalId = setInterval(function() { // 0% besoin
            if ($("#element_Series_0").find('path').length > 0) {
                clearInterval(intervalId);
                animateTableOnGraph();
                generateClasses();
            }
        }, 400);
    });
});

/*     À traiter ity
     
// define the tooltip template
var tooltipTemplate = '<div class="tooltip-wrap">' +
    '<div class="event-subject">${Subject}</div>' +
    '<div class="event-details">' +
    '<div class="event-time">${getFormattedTime(data.StartTime)} - ${getFormattedTime(data.EndTime)}</div>' +
    '<div class="event-description">${Description}</div>' +
    '</div>' +
    '</div>';



*/










function drawSchedulePlanning (scheduleObjArg, identifier, year, mois, datas) {
    // create the Schedule component instance
    console.log(year)
    var scheduleObjArg = new ej.schedule.Schedule({
        width: '300px',
        height: 'auto',
        selectedDate: new Date(year, mois, 9),
        currentView: 'Month',
        views: ['Week', 'Month'],
        eventSettings: {
            dataSource: ej.base.extend([], datas, null, true),
            tooltipTemplate: '#tooltipTemplate'
        },
        resources: [{
            field: 'RoomId', title: 'Room Type', name: 'MeetingRoom', textField: 'Name', idField: 'Id',
            colorField: 'Color', dataSource: ej.base.extend([], roomData, null, true)
        }]/*,
        quickInfoTemplates: {
            header: '#header-template',
            content: '#content-template',
            footer: '#footer-template'
        }*/
    });
    scheduleObjArg.appendTo(identifier);



}



/*
function generateEventsxy(count = 250, date = new Date()) {
        let names = [
            'Bering Sea Gold', 'Technology', 'Maintenance', 'Meeting', 'Travelling', 'Annual Conference', 'Birthday Celebration',
            'Farewell Celebration', 'Wedding Anniversary', 'Alaska: The Last Frontier', 'Deadliest Catch', 'Sports Day',
            'MoonShiners', 'Close Encounters', 'HighWay Thru Hell', 'Daily Planet', 'Cash Cab', 'Basketball Practice',
            'Rugby Match', 'Guitar Class', 'Music Lessons', 'Doctor checkup', 'Brazil - Mexico', 'Opening ceremony', 'Final presentation'
        ];
        let colors = [
            '#ff8787', '#9775fa', '#748ffc', '#3bc9db', '#69db7c',
            '#fdd835', '#748ffc', '#9775fa', '#df5286', '#7fa900',
            '#fec200', '#5978ee', '#00bdae', '#ea80fc'
        ];
        let startDate = new Date(date.getFullYear() - 2, 0, 1);
        let endDate = new Date(date.getFullYear() + 2, 11, 31);
        let dateCollections = [];
        for (let a = 0, id= 1; a < count; a++) {
            let start = new Date(Math.random() * (endDate.getTime() - startDate.getTime()) + startDate.getTime());
            let end = new Date(new Date(start.getTime()).setHours(start.getHours() + 1));
            let nCount = Math.floor(Math.random() * names.length);
            let n = Math.floor(Math.random() * colors.length);
            dateCollections.push({
                RoomId: (a % 5) + 1,
                Id: id,
                Subject: names[nCount],
                StartTime: new Date(start.getTime()),
                EndTime: new Date(end.getTime()),
                IsAllDay: (id % 10) ? true : false,
                EventColor: colors[n],
                TaskId: (id % 5) + 1
            });
            id++;
        }
        // console.log(dateCollections)
        return dateCollections;
    }

*/
function animateTableOnGraph()
{
    $("#element_Series_0").find('path').hover(function(){
        let pattern     = new RegExp("â€Ž", "i");
        let selectorTr  = $('.task-annimate_' + $(this).attr('id').replace('element_Series_0_Point_',''));
            selectorTr.css('background-color', $(this).attr('fill'));
            selectorTr.css('display', 'table-header-group');
        /*$.each($('text#element_tooltip_text tspan'), function() {
            console.log($(this).text())
            if (pattern.test($(this).text())) {
                $(this).text(':');
            }
        });*/
        // console.log($($('text#element_tooltip_text tspan')[1]).text())
        // setTimeout($($('text#element_tooltip_text tspan')[1]).text(':'),200);
    }, function(){
        let selectorTr = $('.task-annimate_' + $(this).attr('id').replace('element_Series_0_Point_',''));
        selectorTr.css('background-color', selectorTr.is(":nth-of-type(odd)") ? "rgba(0,0,0,.05)": "");
        selectorTr.css('display', 'table-row');
    });
}

function changeTextAxeY() {
    setTimeout(function(){
        $.each($('#journeyAxisLabels1').children(), function() {
            let id = $(this).attr('id').split('_');
            let index = id[id.length - 1];
            let text = 8 + parseInt(index);
            $(this).text(text);
        });
        changeTextAxeY();
    }, 150);
}

function valDouble(args) {
    let res     = args.split(':');
        min     = parseInt(res[1]) / 60;
        hour    = parseInt(res[0]);
        // let xyz = hour + min - minValue;
    return hour + min;
}

// Get the float value
function floatToHour (floatValue)
{
    // Convert the float value to the number of hours
    let hours = Math.floor(floatValue);
    let minutes = Math.round((floatValue - hours) * 60);
    // Format the hours and minutes as a string
    return hours + "h " + minutes + "m";
}

function groupBy(objectArray, property) {
    return objectArray.reduce((acc, obj) => {
        const key = obj[property];
        const curGroup = acc[key] ?? [];
        return { ...acc, [key]: [...curGroup, obj] };
    }, {});
}

function callBack() {
    setTimeout(function(){
        $('#element_border').attr('fill','transparent');
        $('#element2_border').attr('fill','transparent');
        $('#element_chart_legend_translate_g').click(function(){
            callBack();
        });
    }, 150);
}

function showUserGroupTaskOnSelect(group) {
    let selectValue = $('#user-task-group').val() != 'all' ? $('#user-task-group').val() : 'all';
    $('option',$('#user-task-group')).remove();
    let innerHtml = '<option value="all">Tout</option>';
    $.each(group, function() {
        innerHtml += '<option value="' + this.idMission + '">' + this.description + '</option>';
    });
    $('#user-task-group').append(innerHtml);
    $('#user-task-group').find('option[value="' + selectValue + '"]').attr("selected", "selected");
}

function fusionTaskes(myArray) {
    let combinedArray = [];
    $.each(myArray, function(i, obj) {
        let x = obj.x;
        let y = obj.y;
        let existingObj = $.grep(combinedArray, function(e){ return e.x.toLowerCase() == x.toLowerCase(); });
        if (existingObj.length) { 
            existingObj[0].y += y;
        } else {
            combinedArray.push({x: x.charAt(0).toUpperCase() + x.slice(1), y: y});
        }
    });
    return combinedArray;
}

function generateClasses(){
    let pattern = new RegExp("task-annimate", "i");
    $.each($('#element_datalabel_Series_0').children(), function(index, val) {
        let description = $('text',this).text().toLowerCase();
        $.each($('#list-taskes tr td.description'), function() {
            if (description === $(this).text().toLowerCase() && !pattern.test($(this).parent().attr('class'))) {
                $(this).parent().addClass('task-annimate_' + index);
            }
        });
    });
}

/**
 * Récupérer le jour week-end dans un mois
 * 
 * @param intetger year      Remplacer en année désirée
 * @param  intetger month    Remplacer en mois NB: (Janvier => 0, Février => 1, etc...)
 * 
 * @return array
*/
function getWeekEndOfMonth(year, month) {
    // var month = 2; //
    var dates = [];
    let daysInMonth = new Date(year, month + 1, 0).getDate(); // Obtenir le nombre de jours dans le mois spécifié
    // Parcourir chaque jour du mois
    for (let day = 1; day <= daysInMonth; day++) {
        let date = new Date(year, month, day);
        // Vérifier si le jour est un samedi ou un dimanche
        if (date.getDay() == 6 || date.getDay() == 0) {
            let addZero = date.getDate() < 10 ? '0' : '';
            // C'est un jour de week-end
            dates.push(addZero + '' + date.toLocaleDateString('fr-FR', dateOptions));
        }
    }
    return dates;
}


/**
 * Créer html code dans le DOM
 * 
 * @param intetger year      Remplacer en année désirée
 * @param  intetger month    Remplacer en mois NB: (Janvier => 0, Février => 1, etc...)
 * 
 * @return empty
*/
function setHtml(randomized) {
    let html = '<div class="col-md-3">' +
                    '<div class="control-section">' +
                        '<div class="content-wrapper">' +
                            '<div id="Schedule'+ randomized +'"></div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
    $('.row.row-planning-conge').append(html);
}

function timeToSeconds(time) {
    time = time.split(/:/);
    return time[0] * 3600 + time[1] * 60 + parseInt(time[2]);
}

function secondsToHMS(seconds) {
    let hours   = Math.floor(seconds / 3600);
    let minutes = Math.floor((seconds % 3600) / 60);
        seconds = seconds % 60;

  return (
    (hours < 10 ? "0" + hours : hours) +
    ":" +
    (minutes < 10 ? "0" + minutes : minutes) +
    ":" +
    (seconds < 10 ? "0" + seconds : seconds)
  );
}



 /* Pas la paine

    function loadContenu(groupe, id, mois, endDate, startDay)
    {
        $.ajax({
            url : HOST + "/manage/collaborater/contenuPlanning",
            data : "mois=" + mois + "&groupe=" + groupe + "&id=" + id + "&endDate=" + endDate + "&startDay=" + startDay,
            dataType : "json",
            success : function(data)
            {
                console.log(dat);
                $('#container-contenus').empty();
                $.each(data, function(indice, employeDates) {
                    $('#container-contenus').append('<div id="container-ligne-' + indice + '" class="p-0 mt-1" style="max-height: 36px!important;"></div>');
                    $.each(employeDates, function(numero, date) {
                        var classeContenu  = "card p-2 text-center card-container card-1 card-down ligne-" + indice;
                        var contenu = ".";
                        if (date['isFerie'] != false) {
                            setTippy('fe' + numero, date['isFerie']);
                            classeContenu += " card-light-blue fe" + numero;
                            contenu = "<span class='text-important'>" + date['isFerie'] + "</span>";
                        } else if (date['isWeekend'] != false) {
                            classeContenu += " card-light-grey we";
                            contenu = "<span class='text-important'>We</span>";
                            setTippy('we', 'Weekend');
                        } else {
                            if (date['presence'] != false) {
                                if (date['isPresent'] == true) {
                                    if (date['isConge'] != false) {
                                        if (date['isConge']['dateDebut'] == date['isConge']['dateFin']) {
                                            if (date['isConge']['heureFin'] == 2) {
                                                classeContenu += " card-cyan-to-green co-pr";
                                                contenu = "<i class='fa fa-sun mr-2 text-warning'></i><i class='fa ml-2 fa-check text-success'></i>";
                                                setTippy('co-pr', 'congé le matin, présent l\'après-midi');
                                            } else if (date['isConge']['heureDebut'] == 2) {
                                                classeContenu += " card-green-to-cyan pr-co";
                                                contenu = "<i class='fa mr-2 fa-check text-success'></i><i class='fa fa-sun ml-2 text-warning'></i>";
                                                setTippy('pr-co', 'présent le matin, congé l\'après-midi');
                                            }
                                        } else {
                                            if (date['dateEcrite'] == date['isConge']['dateDebut'] && date['isConge']['heureDebut'] == 2) {
                                                classeContenu += " card-green-to-cyan pr-co";
                                                contenu = "<i class='fa mr-2 fa-check text-success'></i><i class='fa fa-sun ml-2 text-warning'></i>";
                                                setTippy('pr-co', 'présent le matin, congé l\'après-midi');
                                            } else if (date['dateEcrite'] == date['isConge']['dateFin'] && date['isConge']['heureFin'] == 2) {
                                                classeContenu += " card-cyan-to-green co-pr";
                                                contenu = "<i class='fa fa-sun mr-2 text-warning'></i><i class='fa ml-2 fa-check text-success'></i>";
                                                setTippy('co-pr', 'congé le matin, présent l\'après-midi');
                                            }
                                        }
                                    } else {
                                        classeContenu += " card-light-green pr";
                                        contenu = "<i class='fa fa-check text-success'></i>";
                                        setTippy('pr', 'présent');
                                    }
                                } else if (date['isPermission'] != false) {
                                    setTippy('pe-' + indice + '-' + numero, "permission : " + date['isPermission']);
                                    classeContenu += " card-yellow pe-" + indice + '-' + numero;
                                    contenu = "<span class='text-important'>"  + date['isPermission'] + "</span>";
                                } else if (date['isRepos'] != false) {
                                    classeContenu += " card-pink re";
                                    contenu = "<i class='fa fa-plus'></i>";
                                    setTippy('re', 'repos médical');
                                } else if (date['isConge'] != false) {
                                    if (date['isConge']['dateDebut'] == date['isConge']['dateFin']) {
                                        if (date['isConge']['heureFin'] == 2) {
                                            classeContenu += " card-cyan-to-red co-ab";
                                            contenu = "<i class='fa fa-sun mr-2 text-warning'></i><i class='fa ml-2 fa-times text-danger'></i>";
                                            setTippy('co-ab', 'congé le matin, absent l\' après-midi');
                                        } else if (date['isConge']['heureDebut'] == 2) {
                                            classeContenu += " card-red-to-cyan ab-co";
                                            contenu = "<i class='fa mr-2 fa-times text-danger'></i><i class='fa fa-sun ml-2 text-warning'></i>";
                                            setTippy('ab-co', 'absent le matin, congé l\'après-midi');
                                        } else {
                                            classeContenu += " card-cyan co";
                                            contenu = "<i class='fa fa-sun text-warning'></i>";
                                            setTippy('co', "congé");
                                        }
                                    } else {
                                        if (date['dateEcrite'] == date['isConge']['dateDebut'] && date['isConge']['heureDebut'] == 2) {
                                            classeContenu += " card-red-to-cyan ab-co";
                                            contenu = "<i class='fa mr-2 fa-times text-danger'></i><i class='fa fa-sun ml-2 text-warning'></i>";
                                            setTippy('ab-co', 'absent le matin, congé l\'après-midi');
                                        } else if (date['dateEcrite'] == date['isConge']['dateFin'] && date['isConge']['heureFin'] == 2) {
                                            classeContenu += " card-cyan-to-red co-ab";
                                            contenu = "<i class='fa fa-sun mr-2 text-warning'></i><i class='fa ml-2 fa-times text-danger'></i>";
                                            setTippy('co-ab', 'congé le matin, absent l\'après-midi');
                                        } else {
                                            classeContenu += " card-cyan co";
                                            contenu = "<i class='fa fa-sun text-warning'></i>";
                                            setTippy('co', "congé");
                                        }
                                    }
                                } else {
                                    classeContenu += " card-light-red ab";
                                    contenu = "<i class='fa fa-times text-danger'></i>";
                                    setTippy('ab', 'absent');
                                }
                            } else if (date['isConge'] != false) {
                                if (date['isConge']['dateDebut'] == date['isConge']['dateFin']) {
                                    if (date['isConge']['heureFin'] == 2) {
                                        classeContenu += " card-cyan-to-grey co-ma";
                                        contenu = "<i class='fa fa-sun mr-2 text-warning'></i><span class='ml-2'> . </span>";
                                        setTippy('co-ma', 'congé le matin');
                                    } else if (date['isConge']['heureDebut'] == 2) {
                                        classeContenu += " card-grey-to-cyan ma-co";
                                        contenu = "<span class='mr-2'> . </span><i class='fa fa-sun ml-3 text-warning'></i>";
                                        setTippy('ma-co', 'congé l\'après-midi');
                                    } else {
                                        classeContenu += " card-cyan co" + date['isConge']['idConge'];
                                        contenu = "<i class='fa fa-sun text-warning'></i>";
                                        setTippy('co' + date['isConge']['idConge'], "congé le " + date['isConge']['dateDebut'] + " jusqu'au " + date['isConge']['dateFin']);
                                    }
                                } else {
                                    if (date['dateEcrite'] == date['isConge']['dateDebut'] && date['isConge']['heureDebut'] == 2) {
                                        classeContenu += " card-grey-to-cyan ma-co";
                                        contenu = "<span class='mr-2'> . </span><i class='fa fa-sun ml-2 text-warning'></i>";
                                        setTippy('ma-co', 'congé l\'après-midi');
                                    } else if (date['dateEcrite'] == date['isConge']['dateFin'] && date['isConge']['heureFin'] == 2) {
                                        classeContenu += " card-cyan-to-grey co-ma";
                                        contenu = "<i class='fa fa-sun mr-2 text-warning'></i><span class='ml-2'> . </span>";
                                        setTippy('co-ma', 'congé le matin');
                                    } else {
                                        classeContenu += " card-cyan co" + date['isConge']['idConge'];
                                        contenu = "<i class='fa fa-sun text-warning'></i>";
                                        setTippy('co' + date['isConge']['idConge'], "congé le " + date['isConge']['dateDebut'] + " jusqu'au " + date['isConge']['dateFin']);
                                    }
                                }
                            } else if (date['isPermission'] != false) {
                                setTippy('pe-' + indice + '-' + numero, "permission : " + date['isPermission']);
                                classeContenu += " card-yellow pe-" + indice + '-' + numero;
                                contenu = "<span class='text-important'>"  + date['isPermission'] + "</span>";
                            } else if (date['isRepos'] != false) {
                                classeContenu += " card-pink re";
                                contenu = "<i class='fa fa-plus'></i>";
                                setTippy('re', 'repos médical');
                            } else {
                                classeContenu += " card-extra-light-grey";
                            }
                        }
                        $('#container-ligne-' + indice).append('<div class="' + classeContenu + '" style="display: inline-block; height:36px!important;">' + contenu + '</div>');
                    });
                });
            }
        });
    }

*/















/*Day.getMonthCellText = Week.getMonthCellText = Month.getMonthCellText = TimelineViews.getMonthCellText = function (date) {
    if (date.getMonth() === 10 && date.getDate() === 23) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 9) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 13) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 22) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 24) {
        return '<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 25) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 1) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 14) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    }
    return '';
};*/
/*window.TemplateFunction = function (date) {
    var weekEnds = [0, 6];
    if (weekEnds.indexOf(date.getDay()) >= 0) {
        return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
    }
    return '';
};

var TemplateFunction = {
    getWorkCellText: function(date) {
        var weekEnds = [0, 6];
        if (weekEnds.indexOf(date.getDay()) >= 0) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
        }
        return '';
    },
    getMonthCellText: function(date) {
        if (date.getMonth() === 10 && date.getDate() === 23) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 9) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 13) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 22) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 24) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg' />";
        } else if (date.getMonth() === 11 && date.getDate() === 25) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg' />";
        } else if (date.getMonth() === 0 && date.getDate() === 1) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
        } else if (date.getMonth() === 0 && date.getDate() === 14) {
            return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg' />";
        }
        return '';
    }
};
*/
// To apply this function to the Schedule control, you can use the following code:
/*$('#Schedule').ejSchedule({
    width: "100%",
    height: "500px",
    views: ["Day", "Week", "WorkWeek", "Month"],
    workHours: {
        highlight: false
    },
    cssClass: 'schedule-cell-template',
    cellTemplate: '${if(type === "workCells")}<div class="templatewrap">${getWorkCellText(data.date)}</div>${/if}${if(type === "monthCells")}<div class="templatewrap">${getMonthCellText(data.date)}</div>${/if}',
    selectedDate: new Date(2022, 2, 16),
    timeScale: {
        enable: true,
        interval: 60,
        slotCount: 2
    },
    showQuickInfo: false,
    eventSettings: {
        dataSource: [
            {
                Id: 1,
                Subject: "Meeting",
                StartTime: new Date(2022, 2, 3, 10, 0),
                EndTime: new Date(2022, 2, 3, 12, 0),
                isReadonly: 'IsDone',
                IsAllDay: false
            }
        ]
    },
    eventRendered: function (args) {
        $(args.element).find('.e-cell-content').html(window.TemplateFunction(args.data.StartTime));
    }
});*/

/*$("#schedule2").ej2Schedule({
    views: ["Day", "Week", "Month", "Timeline"],
    currentView: "Month",
    height: "550px",
    monthCellRender: function(args) {
        var date = args.date;
        if (date.getDate() === 23) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg"/>');
        } else if (date.getDate() === 9) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg"/>');
        } else if (date.getDate() === 13) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg"/>');
        } else if (date.getDate() === 22) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg"/>');
        } else if (date.getDate() === 24) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg"/>');
        } else if (date.getDate() === 25) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg"/>');
        } else if (date.getDate() === 1) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg"/>');
        } else if (date.getDate() === 14) {
            args.element.append('<img src="https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg"/>');
        }
    }
});
*/
/*Schedule.Inject(Day, Week, Month, TimelineViews);
(window as TemplateFunction).getMonthCellText = (date: Date) => {
    if (date.getMonth() === 10 && date.getDate() === 23) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 9) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/get-together.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 13) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 22) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/thanksgiving-day.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 24) {
        return '<img src="https://ej2.syncfusion.com/demos/src/schedule/images/christmas-eve.svg" />';
    } else if (date.getMonth() === 11 && date.getDate() === 25) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/christmas.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 1) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg" />';
    } else if (date.getMonth() === 0 && date.getDate() === 14) {
        return '<img src= "https://ej2.syncfusion.com/demos/src/schedule/images/birthday.svg" />';
    }
    return '';
};
(window as TemplateFunction).getWorkCellText = (date: Date) => {
    let weekEnds: number[] = [0, 6];
    if (weekEnds.indexOf(date.getDay()) >= 0) {
        return "<img src='https://ej2.syncfusion.com/demos/src/schedule/images/newyear.svg' />";
    }
    return '';
};

interface TemplateFunction extends Window {
    getWorkCellText?: Function;
    getMonthCellText?: Function;
}
let scheduleObj: Schedule = new Schedule({
        width: '100%',
        height: '550px',
        views: ['Day','Week', 'TimelineWeek', 'Month'],
        cssClass: 'schedule-cell-template',
        cellTemplate: '${if(type === "workCells")}<div class="templatewrap">${getWorkCellText(data.date)}</div>${/if}${if(type === "monthCells")}<div class="templatewrap">${getMonthCellText(data.date)}</div>${/if}',
        selectedDate: new Date(2017, 11, 16)
});
scheduleObj.appendTo('#Schedule');

*/