var categLength     = 0;
var parentLength    = 0;
var index           = 0;
var moyenneTotal    = 0.0;
var sousCategory    = [];
var category        = [];
var dataXY          = [];
var colorCateg      = [];
var colorSousCat    = [];
var colorQuestion   = [];
var colors          = ["#dc3545", "#28a745", "#ffc107", "#007bff", "#ff00ff", "#17a2b8", "#e83e8c", "#fd7e14", "#20c997"];
// Récupérer les questions
$.each(responsePoint, function(key,valueOne) {
    console.log(valueOne);
    if ($.isNumeric(key)) {
        category[key]   = valueOne['parent'];
        colorCateg[key] = colors[key];
        $.each(valueOne['category'], function(keyTwo,valueTwo) {
            sousCategory[categLength] = valueTwo['sousCategories'];
            colorSousCat[categLength] = colorCateg[key];
            categLength++;
            $.each(valueTwo['questionnaires'], function(keyThree, valueThree) {
                dataXY[index]           = { x:valueThree['question']['libelle'] , y: Math.round(valueThree['point']) };
                colorQuestion[index]    = colors[key];
                index++;
            });
        });
        parentLength++;
    } else { 
        moyenneTotal = valueOne;
    }
});
// Redimension la taille de la fenêtre et recupère le style du graphe
// Récupère l'affichage des points lors du dimensionnement d'écran
$(window).bind('resize', function(e){
    window.resizeEvt;
    $(window).resize(function(){
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function () {
        $('.style-graphe').remove();
        cssGraph()
        },550);
    });
});
/***************************** GRAPH *****************************/
/** @class 
 *  CANVAS 
 * */
var Xagon = (function () {
    function Xagon(sides, radius, angle, lineWidth) 
    {
        if (sides       === void 0) { sides = 4; }
        if (radius      === void 0) { radius = 4; }
        if (angle       === void 0) { angle = 0; }
        if (lineWidth   === void 0) { lineWidth = 1; }
        this.sides      = sides;
        this.radius     = radius;
        this.angle      = angle;
        this.lineWidth  = lineWidth;
    }
    Xagon.prototype.draw = function (ctx, offset, handleSide) 
    {
        if (offset === void 0) { offset = { x: 0, y: 0 }; }
        var sideOffset = 360 / this.sides;
        for (var side = 0; side < this.sides; side++) {
            var ang = Xagon.degreetoRadian(this.angle + sideOffset * side);
            ctx.beginPath();
            ctx.arc(offset.x, offset.y, this.radius - 0.5 * this.lineWidth, ang, ang);
            ctx.arc(offset.x, offset.y, this.radius + 0.5 * this.lineWidth, ang, ang);
            ctx.arc(offset.x, offset.y, this.radius + 0.5 * this.lineWidth, ang - Xagon.degreetoRadian(sideOffset), ang - Xagon.degreetoRadian(sideOffset));
            ctx.arc(offset.x, offset.y, this.radius - 0.5 * this.lineWidth, ang - Xagon.degreetoRadian(sideOffset), ang - Xagon.degreetoRadian(sideOffset));
            elements.push({
              x: offset.x,
              linesMoins: this.radius - 0.5 * this.lineWidth,
              linesPlus: this.radius + 0.5 * this.lineWidth,
              ang: ang,
              angMoins: ang - Xagon.degreetoRadian(sideOffset),
              y: offset.y
            });
            ctx.closePath();
            if (handleSide !== void 0) {
                handleSide(ctx, side);
            }
        }
    };
    Xagon.prototype.setAngle = function (valueAngle) {
        if (valueAngle === void 0) { valueAngle = this.angle; }
        this.angle = Math.abs(valueAngle) % 360;
        return this;
    };
    Xagon.degreetoRadian = function (degree) { return degree * (Math.PI / 180); };
    return Xagon;
}());
// Positionner le SVG dans le panel canvas
var panels      = document.getElementById('container').appendChild(document.createElement("canvas"));
var sizeCarre   = panels.width = panels.height = 1400;  // ZOOM
var ctxCarre    = panels.getContext("2d");
// Rotation du graphe
rotParent = 0;
if (categLength > 4) {
    var rot = 328;
} else {
    var rot = 0;
}
if (parentLength == 3) {
    rotParent = 28;
}
// Générer la forme du graphe
if (parentLength < 3) {
    var carreDraw   = new Xagon(360, sizeCarre * 0.43, rotParent, 10);
    var hexDraw     = new Xagon(categLength, sizeCarre * 0.485, rot, 10);
} else {
    var carreDraw   = new Xagon(parentLength, sizeCarre * 0.45, rotParent, 15);
    var hexDraw     = new Xagon(categLength, sizeCarre * 0.5, rot, 10);
}
var interval;
// Dessiner le bordure du graphe
function update() {
    ctxCarre.clearRect(0, 0, sizeCarre, sizeCarre);
    carreDraw.setAngle(carreDraw.angle + 1);
    carreDraw.draw(ctxCarre, { x: sizeCarre / 2, y: sizeCarre / 2 }, function side(ctxCarre, sideIndex) {
        ctxCarre.fillStyle = colorCateg[sideIndex % colors.length];
        ctxCarre.fill();
    });
    hexDraw.setAngle(hexDraw.angle + 1);
    hexDraw.draw(ctxCarre, { x: sizeCarre / 2, y: sizeCarre / 2 }, function side(ctxCarre, sideIndex) {
        ctxCarre.fillStyle = colorSousCat[sideIndex % colors.length];
        ctxCarre.fill();
    });
    cancelAnimationFrame(interval);
}
interval        = requestAnimationFrame(update);
// CHART JS
var dataShow    = [];

    $.each(responsePoint, function (k, v) {
        if ($.isNumeric(k)) {
            if (k == 0) {
                tmp =  {
                    // Marker
                    marker  : {
                        visible     : true,
                        dataLabel   : {visible: true },
                    },
                    type        :"Radar",
                    dataSource  : dataXY,
                    xName       :"x",
                    width       : 2,
                    yName       :"y",
                    name        : "Questionnaire"
                };
                dataShow.push(tmp);
            }
            else {
                var dataSeries              = {};
                    dataSeries.type         = "Radar";
                    dataSeries.dataSource   = dataXY;
                    dataSeries.xName        = "x";
                    dataSeries.width        = 2;
                    dataSeries.yName        = "y";
                    // Marker
                    marker                  = {};
                    marker.visible          = false;
                    dataSeries.marker       = marker;
                dataShow.push(dataSeries);
            }
        }
    });
    // Begin to fill questions by spider's graph
    var chart = new ej.charts.Chart({
        // Tooltip
        tooltip: {
            enable: true
        },
        //Initializing Primary X Axis
        primaryXAxis: {
            valueType:          "Category",
            labelPlacement:     "OnTicks",
            interval:           1,
            visible :           false
        },
        //Initializing Primary Y Axis
        primaryYAxis: {
            minimum:            0,
            maximum:            4,
            interval:           1,
            stroke :            '#000',
            edgeLabelPlacement: "Shift",
            labelFormat:        "{value}"
        },
        //Initializing Chart Series
        series: dataShow,
        width:  '' + (sizeCarre / 2),
        height: '' + (sizeCarre / 2)
    });
    chart.appendTo("#container");
    cssGraph();
// end of filling questions
// Legend
var legend  = document.getElementById('legend');
var table   = legend.appendChild(document.createElement("table"));
var thead   = table.appendChild(document.createElement("thead"));
var tbody   = table.appendChild(document.createElement("tbody"));
    th      = thead.appendChild(document.createElement("th"));
    th.setAttribute("class", "col-md-5");
    th.append("Traits de personnalités");
    th      = thead.appendChild(document.createElement("th"));
    th.setAttribute("class", "col-md-1");
    th.append("Moyenne");
    th      = thead.appendChild(document.createElement("th"));
    th.setAttribute("class", "col-md-5");
    th.append("Dimensions");
    th      = thead.appendChild(document.createElement("th"));
    th.setAttribute("class", "col-md-1");
    th.append("Moyenne");
    th      = thead.appendChild(document.createElement("th"));
    th.setAttribute("class", "col-md-10");
    th.append("Évaluateurs");
    $.each(sousCategory, function(key, valueCateg) {
        tr  = tbody.appendChild(document.createElement("tr"));
        td  = tr.appendChild(document.createElement("td"));
        if (category[key]) {
            button  = td.appendChild(document.createElement("button"));
            button.setAttribute("style","background: " + colorCateg[key] + "; height: 10px; width: 20px; border: none;");
            td.append("\xa0");
            td.append("\xa0");
            td.append(category[key]['libelle'].charAt(0).toUpperCase() + category[key]['libelle'].slice(1));
        }
        td  = tr.appendChild(document.createElement("td"));
        if (category[key]) {
            td.append(Number(category[key]['moyenne'].toFixed(3)));
        }
        td  = tr.appendChild(document.createElement("td"));
        tmp = td.appendChild(document.createElement("button"));
        tmp.setAttribute("style","background: " + colorSousCat[key] + "; height: 10px; width: 20px; border: none;");
        td.append("\xa0");
        td.append("\xa0");
        td.append(valueCateg['libelle'].charAt(0).toUpperCase() + valueCateg['libelle'].slice(1));
        td  = tr.appendChild(document.createElement("td"));
        td.append(Number(valueCateg['moyenne'].toFixed(3)));
        if (evaluateurs[key]) {
            evaluateur  = $.map(evaluateurs[key], function(value, index) {return [[index, value]];});
            td          = tr.appendChild(document.createElement("td"));
            a           = td.appendChild(document.createElement("a"));
            a.setAttribute("href","affiche_graph?idEvaluation="+idEvaluation+"&idEvaluateur="+evaluateurs[key]['idEmploye']);
            a.setAttribute("class","btn btn-info");
            a.setAttribute("id","eval"+evaluateurs[key]['idEmploye']);
            var itlic   = a.appendChild(document.createElement("i"));
            itlic.setAttribute("class","far fa-chart-bar");
            a.append("\xa0");
            a.append(evaluateurs[key]['prenom'] +' '+ evaluateurs[key]['nom']);
            // Désactiver le bouton déjà selectionné
            if (new URL(window.location).search.split('&')[1] &&
                new URL(window.location).search.split('&')[1].split('=')[1] == evaluateurs[key]['idEmploye']) {
                $('td > a#eval'+evaluateurs[key]['idEmploye']).attr("disabled", "disabled").on("click", function() {
                    return false; 
                });
                $('td > a#eval'+evaluateurs[key]['idEmploye']).css('background-color','#aacbe8');
                $('.infoEvaluation').append("<h6>Résultat d'évaluation de "+evaluateurs[key]['prenom']+" "+evaluateurs[key]['nom']+"</h6>");
            }
        }
        if (key == evaluateurs.length) {
            td          = tr.appendChild(document.createElement("td"));
            a           = td.appendChild(document.createElement("a"));
            a.setAttribute("class","btn btn-info");
            a.setAttribute("href","affiche_graph?idEvaluation="+idEvaluation);
            var itlic   = a.appendChild(document.createElement("i"));
            itlic.setAttribute("class","far fa-chart-bar");
            a.append("\xa0");
            a.append("Résultat moyenne");
        }
    });
/**
 * Contrôler le css du graphe 
 * @param null
 * @return empty
 */
function cssGraph() {
    var style   = document.createElement('style');
    for ( let i = 0; i < index; i++ ) {
        series  = document.getElementById("container_Series_0_Point_"+i+"_Symbol");
        cx      = series.getAttribute("cx");
        cy      = series.getAttribute("cy");
        lines   = document.getElementById("container_MajorGridLine_0_"+i);
        dist    = lines.getAttribute("d").split('L');
        dist[1] = 'L ' + cx + ' ' + cy;
        dist    = dist[0] + dist[1];
        style.setAttribute("id","style");
        style.setAttribute("class","style-graphe");
        style.innerHTML += '#container_MajorGridLine_0_'+i+' { ' +
                                'stroke: ' + colorQuestion[i] + '; ' +
                                'stroke-width: 4; ' +
                                'rx: 10; ' +
                                'ry: 10; ' +
                            ' } ' ;
        lines.setAttribute("d", dist);
        $('#container_MajorTickLine_0_'+i).css('stroke-width', 0);
    }
    /* Centrer la poisition de Chart */
    style.innerHTML += '#container_svg { ' +
                            ' margin: '+(sizeCarre / 4) +'px; ' +
                            ' position: absolute; ' +
                            ' top:25px; ' +
                            ' background: rgba(0,0,0,0); ' +
                        ' }';
    document.body.appendChild(style);
    $('#container_Secondary_Element').css('position','relative');
    $('#container_Secondary_Element').css('left','349px');
    $('#container_Secondary_Element').css('top','-1041px');
    var indice = 0;
    colorQuestion[-1] = '#ffffff';
    var tabDim = $('#container_Series_0').attr('d').split('M ')[1].split(' L ');
    var selector = '';
    // Coloration les extrémités de points
    $.each(tabDim, function (key, value) {
        if (colorQuestion[key / 2] != colorQuestion[key / 2 - 1]) {
            selector = '#container_Series_'+indice;
            $(selector).attr('stroke', colorQuestion[key / 2]);
            $(selector).css('stroke', colorQuestion[key / 2]);
            $(selector).css('stroke-width', 4);
            $(selector).attr('d','M '+value);
            indice++;
        } else {
            $(selector).attr('d',$(selector).attr('d')+' L '+value);
        }
    });
    // Taille du texte
    $('#containerTextGroup0').find('text').css('font-size','15px');
}


// Ajout un événemment hover
$(document).on({
    mouseenter: function () {
        console.log('enter');
       console.log(elemLeft);
       console.log(elemTop);
    },
    mouseleave: function () {
        console.log('leave');
        console.log(elemLeft);
       console.log(elemTop);
    }
}, "canvas"); //pass the element as an argument to .on

// Add event listener for `click` events.
var elemLeft    = panels.offsetLeft,
    elements    = [],
    elemTop     = panels.offsetTop;
// Add event listener for `click` events.
panels.addEventListener('click', function(event) {
    var x = event.pageX - elemLeft,
        y = event.pageY - elemTop;
    console.log(x, y);
    elements.forEach(function(element) {
        if (y > element.top && y < element.top + element.height && x > element.left && x < element.left + element.width) {
            alert('clicked an element');
        }
    });
}, false);
console.log(elements);
// Add element.
/*elements.push({
  colour: '#05EFFF',
  width: 150,
  height: 100,
  top: 20,
  left: 15
});*/