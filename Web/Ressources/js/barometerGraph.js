/**
 * @Author Lansky 2022-03-17
 * */
var dataXY          = [];
var dataSeries      = [];
var renderSeries    = [];
$.each(responses, function(index, val) {
    if (val.questions) {
        if (Object.keys(val.questions)[0] == 0) {
            $.each(val.questions, function(ind, quest) {
                dataXY.push({ x: quest.question , y: quest.point });
            });
        } else {
            dataXY.push({ x: val.questions.question , y: val.questions.point });
        }
    }
});
    console.log(lastThreeRender)
dataSeries.push(dataXY);
$.each(lastThreeRender, function(key, baromtr) {
    var dataSerie = [];
    $.each(baromtr, function(ind, value) {
        if (value.questions) {
            if (Object.keys(value.questions)[0] == 0) {
                $.each(value.questions, function(ind, quest) {
                    dataSerie.push({ x: quest.question , y: quest.point });
                });
            } else {
                dataSerie.push({ x: value.questions.question , y: value.questions.point });
            }
        }
    });
    dataSeries.push(dataSerie);
});
    console.log(dataSeries)


$.each(dataSeries, function(keys, datas) {
    let renderXY = {
        // Marker
        marker      : {
            visible     : true,
            dataLabel   : { visible: true } // Data label
        },
        type        : "Radar",
        dataSource  : datas,
        xName       : "x",
        width       : dataSeries.length - keys + 2,
        yName       : "y",
        name        : dates[keys]
    };
    renderSeries.push(renderXY);
});



/**@changelog 2022/05/10 [EVOL] (Lansky) Version 2.0 */
$("document").ready(function(){
    var widthContainer  = $('#container_svg').attr('width'),
        widthcircleTmp     = widthContainer + (widthContainer / 2);
        widthcircle     = (((widthcircleTmp/(dataXY.length + 1))+(widthcircleTmp/(dataXY.length + 1) * 2))/1000);
        console.log($('#container_svg').attr('width'));
        console.log(widthcircle);
    $('.triangle').css('width', widthcircle + 'px');
    $('.triangle-img').css('width', widthcircle + 'px');
    $('#mainNav').css('z-index',999999);
});

$(window).on('load', function(){
    console.log(dataXY);
    var taille = $('#container_ChartBorder').attr('width');
    $('.smiley#image').css('margin-top', (taille/2) -80);
    $('.smiley#image').css('margin-left', (taille/2) -75);
    cssGraph(dataXY);
    $('.circle').css('width',($('#containeryAxisCollection')[0].getBoundingClientRect().width) - 11);
    $('.circle').css('height',($('#containeryAxisCollection')[0].getBoundingClientRect().height) - 19);
    /** @changeLog 2023-07-24 [EVOL] (Lansky) Afficher les moyennes du questionnaaire et reduire l'affichage par le classement */
    $('#show-reduced').click(function(){
        var search = new URL(window.location).search;
        if ($('#switchArret').is(':checked')) {
            $('.custom-control-label').html("Reduisez");
            var newSearch = search.replace("&show=all", "&show=reduce");
        } else {
            $('.custom-control-label').html("Affichez tout");
            var newSearch = search.replace("&show=reduce", "&show=all");
        }
    });
    $('#last-three-result').click(function() {
        var html    = '';
        var zIndex  = 0;
        var content = '';
        if ($('#last-three-result').html() === "Résultat unique") {
            html    = "Trois derniers résultat";
            zIndex  = 1;
        } else {
            zIndex  = 0;
            html    = "Résultat unique";
            content = "";
        }
        $('.containers').css('z-index',zIndex);
        $('#last-three-result').html(html);
    });
    tippy('#show-reduced', {
        content: $('#show-reduced').html() == "Reduisez" ? "Réduire le graph et afficher les moyennes" : "Voir toutes les réponses"
    });
    tippy('#last-three-result', {
        content: "Afficher les trois derniers résultats",
        dynamicTitle: true,
        onShown: function(instance) {
            // Changer le contenu du Tippy tooltip
            var contentTxt = this.content === "Résultat unique" ? "Afficher les trois derniers résultats" : "Affichez un résultat";
            this.content = contentTxt;
        }
    });






});
// Redimension la taille de la fenêtre et recupère le style du graphe
// Récupère l'affichage des points lors du dimensionnement d'écran
$(window).bind('resize', function(e){
    window.resizeEvt;
    $(window).resize(function(){
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function () {
        cssGraph(dataXY);
        },550);
    });
});

// À voir sson fonctionnement après
//  $("#resizable").resizable ({
//     // stop event of the resizable is triggered
//     stop: function (event, ui) {
//     // resized container height and width are obtained
//        var height = ui.size.height;
//         var width = ui.size.width;
//         var chart = $("#Chart1").ejChart("instance");
//     // chart is redrawn with the resized values
//         chart.model.size.width = width.toString();
//         chart.model.size.height = height.toString();
//         chart.redraw();
//         $(".e-input")[0].value = height.toString();
//         $(".e-input")[2].value = width.toString();
//     }
// });
function cssGraph (data = null) {
    for ( i = 1; i < 6; i++) {
        $('#containerTextGroup' + i).children('text').attr('fill','gray');
    }
    // Gérer le contenu de label question
    $.each(data, function(ind, quest) {
        let txt = '';
        $('text#container_Series_'+quest.y+'_Point_'+ind+'_Text_0').attr('fill', 'black');
        $('text#container_Series_'+quest.y+'_Point_'+ind+'_Text_0').css('font-size', '15px');
        if ($.trim(quest.x) == 'Mon manager a été à mon écoute et a été est accessible') {
            txt = '<tspan >Mon manager</tspan><tspan> et moi</tspan>';
        } else if ($.trim(quest.x) == 'Mon environnement de travail était agréable') {
            txt = 'Mon environnement de travail était';
        } else if ($.trim(quest.x) == 'Mes outils de travail étaient performants') {
            txt = 'Mes outils de travail étaient';
        } else if ($.trim(quest.x) == 'J’ai eu le sentiment que toutes mes compétences étaient mobilisées') {
            txt = 'Toutes mes compétences étaient mobilisées';
        }
        // @changelog [EVOL] (Lansky) 2022/05/13 Modifier de façcon static le contenu de label et Pour mettre à la ligne le text dans le label du axe x
        else if ($.trim(quest.x) == "Comment ça va en ce moment?") {
            txt = '<tspan>Bien-être</tspan>';
        } else if ($.trim(quest.x).indexOf("Mon cadre de travail me permet de bien travailler") != -1) {
            txt = '<tspan>Espace de travail</tspan>';
        } else if ($.trim(quest.x) == "La relation avec mes collègues est enthousiasmante") {
            txt = '<tspan>Relations collaborateurs</tspan>';
        } else if ($.trim(quest.x) == "Je suis satisfait(e) de mes perspectives d'évolution") {
            txt = '<tspan>Perspectives Evolution</tspan>';
        } else if ($.trim(quest.x) == "Je me sens suffisamment autonome dans ma mission") {
            txt = '<tspan>Autonomie</tspan>';
        } else if ($.trim(quest.x) == "Je me sens influent(e) dans la vie de l'agence") {
            txt = '<tspan>Influence</tspan>';
        } else if ($.trim(quest.x).indexOf("fier(e) de travailler") != -1) {
            txt = '<tspan>Fierté d&apos;appartenance</tspan>';
        } else if ($.trim(quest.x) == "Je me sens reconnu(e) grâce aux retours que l'on peut me faire au travail") {
            let axesY7 = parseInt($("#container0_AxisLabel_7").attr("y")) + 20;
            txt = '<tspan>Reconnaissance</tspan><tspan x="20" y="'+ axesY7 +'">/Feedback</tspan>';
        } else if ($.trim(quest.x) == "J'apprécie la relation que j'ai avec mon dirigeant") {
            let axesY8 = parseInt($("#container0_AxisLabel_8").attr("y")) + 20;
            txt = '<tspan>Relation avec</tspan><tspan x="25" y="'+ axesY8 +'">Manager</tspan>';
        } else if ($.trim(quest.x).indexOf("orientations stratégiques") != -1) {
            txt = '<tspan>Alignement Vision</tspan>';
        } else if ($.trim(quest.x).split('<br>&emsp;').length > 1) {
            let label   = $.trim(quest.x).replace('1 = peu satisfaisant / 5 = très satisfaisant','').trim();
                label   = label.split('<br>&emsp;');
                label   = !label[1].trim() ? label[0] : label[1];
                txt     = label.replace('*','').trim();
                txt     = txt.indexOf('vous') > -1 ? txt.substr(txt.indexOf('vous') + 5).charAt(0).toUpperCase() + txt.substr(txt.indexOf('vous') + 5).slice(1) : txt.trim();
        } else {
            if ($.trim(quest.x).length > 25) {
                findWords   = ['a-t-il', 'étaient-elles','étaient-ils','était-il'];
                txt         = quest.x;
                found       = false;
                $.each(findWords, function () {
                    if (txt.search(this.toString()) > -1) {
                        var removeItem = 'vous';
                        sub = txt.substr(0, txt.indexOf(this.toString()));
                        arr = sub.trim().split(' ');
                        txt = (arr[arr.length - 1].toLowerCase() == 'vous') ? $.grep(arr, function(value) { return value != removeItem; }).join(' ') : sub;
                        return false;
                    }
                });
            } else {
                txt = $.trim(quest.x);
            }
        }
        txt = txt.replace('?', '');
        // Pour mettre à la ligne le text dans le label du axe X et du axe Y => OK
        /*if (txt.length > 40) {
            let axesY = parseFloat($("#container0_AxisLabel_"+ind).attr("y")) + 20;
            let axesX = parseFloat($("#container0_AxisLabel_"+ind).attr("x"));
            let tmpTxt = '';
            let tspan = '';
            $.each(txt.split(' '), function (){
                if (tmpTxt.length < 40) {
                    tmpTxt += this.toString() + ' ';
                } else {
                    if (tspan.search('y=') > -1) {
                        axesY +=20;
                    }
                    tspan += !tspan.trim() ? '<tspan>'+tmpTxt+'</tspan>': '<tspan x="'+axesX+'" y="'+axesY+'">'+tmpTxt+'</tspan>';
                    tmpTxt = this.toString() + ' ';
                }
            });
            if (tmpTxt.trim()) {
                axesY = tspan.search('y=') > -1 ? axesY + 20 : axesY;
                tspan += '<tspan x="'+axesX+'" y="'+axesY+'">'+tmpTxt+'</tspan>';
            }
            txt = tspan;
        }*/
        // Temporaire
       /* txt = txt.length > 30 ? txt.substr(0, 30) +'...' : txt;
        console.log(quest.x)
        console.log(txt)*/
        $('#container0_AxisLabel_'+ind).html(txt);
        $('#container0_AxisLabel_'+ind).attr('fill','black');
        $('#container0_AxisLabel_'+ind).css('inline-size', '250px');
        $('#container0_AxisLabel_'+ind).css('font-size', '18px');
    });
    $('#container0_AxisLabel_15').attr('y', 10 + parseFloat($('#container0_AxisLabel_15').attr('y'))); // Décaler +10 du text
    $('#container0_AxisLabel_0').attr('y',$('#container0_AxisLabel_0').attr('y') - 10); // Décaler -10 du text
    chart.ejChart({
        //Make Chart responsive by enabling canResize property
        canResize: true,
    });
}

var chart = new ej.charts.Chart({
        // Tooltip
        tooltip: {enable: true},
        //Initializing Primary X Axis
        primaryXAxis : {
            valueType       : "Category",
            labelPlacement  : "OnTicks",
            interval        : 1,
            visible         : true
        },
        //Initializing Primary Y Axis
        primaryYAxis : {
            minimum             : 0,
            maximum             : 5,
            interval            : 1,
            stroke              : '000',
            edgeLabelPlacement  : "Shift",
            labelFormat         : "{value}"
        },
        //Initializing Chart Series
        series: renderSeries,
        width:  900,
        height: 900
    });
    chart.appendTo("#container");
    $('#containerTextGroup0').children().attr('fill','white'); // Ne pas afficher les mark des points
document.getElementById('print').onclick = () => {
    var triangle        = $('.triangle');
    var cibleHeader     = $('.infoBarometre');
    var cibleFooter     = $('.row.offre-footer');
    var top             = $('.containers').css('top');
    var left            = $('.containers').css('left');
    var topSvg          = $('#container_svg').css('top');
    var leftSvg         = $('#container_svg').css('margin-left');
    var contentWidth    = $('.row.row-body').css('width');
    var contentWidth    = $('.row.row-body').css('width');
    $('.infoBarometre').remove();
    $('.row.offre-footer').remove();
    $('#container').prepend($(cibleHeader).clone());
    $('#container').append($(cibleFooter).clone());

    $('#containerTextGroup0').children().attr('fill','black');
    $('.containers').css('top', 220);
    $('.containers').css('left', 150);
    $('.containers').css('z-index', -2);
    $('#container').css('height','1400px');
    $('.smiley#image').css('margin-left', '512px');
    $('#label-emojy').css('margin-left', '565px');
    $('#container').css('overflow','unset!important');
    $('#container_svg').css('margin-left', '92px');
    $('#container_svg').css('top', '170px');
    // $('#suggest').find('label').css('margin-left', '-140px');
    $('.row.offre-footer').css('margin-top', '970px');
    chart.print();
    setTimeout(function () {
        //Repositionner le graph comme auparavant
        $('#container').css('height','1000px');
        $('.infoBarometre').remove();
        $('.row.offre-footer').remove();
        $('#container_svg').css('margin-left', '0px');
        $('#label-emojy').css('margin-left', '472px');
        $('.smiley#image').css('margin-left', '425px');
        $('.containers').css('z-index', 1);
        $('.containers').css('top', top);
        $('.containers').css('left', left);
        $('#container_svg').css('top', topSvg);
        $('#container_svg').css('margin-left',leftSvg);
        $('.row.offre-body').css('width', contentWidth);
        // $('.row.row-footer').css('margin-top', ' 0px');
        $('#containerTextGroup0').children().attr('fill','white');
        $('.row.offre-body').append($(cibleFooter).clone());
        $('.infoBarom').append($(cibleHeader).clone());
        $('#suggest').find('label').css('margin-left', 0);
    }, 3000);
};