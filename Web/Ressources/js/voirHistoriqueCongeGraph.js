window.onload = function () {
	/**
	 * ---------------------------------------
	 * This demo was created using amCharts 4.
	 *
	 * For more information visit:
	 * https://www.amcharts.com/
	 *
	 * Documentation is available at:
	 * https://www.amcharts.com/docs/v4/
	 * ---------------------------------------
	 */
	drawGraphHistoriqueConge(stockConge,conges);
}

function drawGraphHistoriqueConge(stockConge,conges) {
	// Create chart instance
	var chart = am4core.create("chartdiv", am4charts.PieChart);
	var	stock = stockConge.duree;
	// Add data
	if (stock > 0) {
		chart.data = [
			{
			  "conge": "Validée",
			  "value": conges[2].valide.toString()
			},
			{
			  "conge": "Réfusée",
			  "value": conges[0].refus.toString()
			}, {
			  "conge": "Attente",
			  "value": conges[1].attente.toString()
			}, {
			  "conge": "Solde",
			  "value": stock
			}
		];
		// Add and configure Series
		var pieSeries 						= chart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value 			= "value";
		pieSeries.dataFields.category 		= "conge";
		pieSeries.labels.template.disabled 	= true;
		pieSeries.ticks.template.disabled 	= true;
		chart.legend 						= new am4charts.Legend();
		chart.legend.position 				= "right";
		chart.innerRadius 					= am4core.percent(60);
		var label 							= pieSeries.createChild(am4core.Label);
		label.text 							= "Solde restant";
		label.horizontalCenter 				= "middle";
		label.verticalCenter 				= "middle";
		label.fontSize 						= 40;
		var label1 							= pieSeries.createChild(am4core.Label);
		label1.text 						= "{value.close}jour".concat(stock >= 2 ? "s": "");
		label1.horizontalCenter 			= "middle";
		label1.verticalCenter 				= "middle";
		label1.fontSize 					= 40;
		label1.y 							= 40;
		chart.legend.labels.template.text 	= "[bold {color}]{name} - {value}j"; // Personnalize the legend
		/* @changelog 29/09/2021 [OPT] (Lansky) Modifier la contenue du tspan affiche le solde du congé */
		setTimeout(function() {
			let translate = $('g[aria-label="Legend"]').attr('transform').replace('translate','').replace('(','').replace(')','').split(',');
			$('g[aria-label="Legend"]').attr('transform', 'translate(' + (parseInt(translate[0]) - 150) + ',' + translate[1] + ')')
			$.each($('tspan'), function(){
				if ($(this).text().match(/â€¦/i)) {
					$(this).text($(this).parent().parent().parent().attr('aria-label').replace('[bold ]',''));
				}
				let parentG = $(this).parent().parent().parent().parent();
				if ($(parentG).children().last().attr('aria-label') === "%") {
					$(parentG).children().last().attr('transform','translate(249,3)');
				}
				if($(this).text().trim().search('jour') > 0 || $(this).text().trim().search('j') > 0){
					var res 			= $(this).text().trim().substring(0,$(this).text().trim().search('j')).split(' '); 
					let val 			= res[res.length - 1].split('.');
					var t 				= parseInt(val[0]) > 1 ? 's' : '';
					res[res.length - 1] = val.length == 1 ? val[0] + 'jour' + t :  val[0] + 'jour' + t + ' ' + parseInt(parseFloat('0.' +val[1]) * 8) + 'h';
					$(this).text(res.join(' ')); 
				}
			});
			$('g[aria-label="Legend"]').children().append($('g[aria-label="Legend"]').children().children().last().clone());
			let soldAnnuel = $('g[aria-label="Legend"]').children().children().last(); // Assigner le dernier élément en solde annuel
			let differenceY = parseInt($(soldAnnuel).attr('transform').split(',')[1]) + 39; // Prendre le coordonnée de l'écart
			$(soldAnnuel).attr('transform', $(soldAnnuel).attr('transform').split(',')[0] + ',' + differenceY + ')'); // Changer les coordonnées transform
			$($('tspan', soldAnnuel)[0]).css('fill', '#cf0f2e');
			$($($(soldAnnuel).children()[1]).children()[0]).children().last().children().attr('fill','#cf0f2e');
			$($($(soldAnnuel).children()[1]).children()[1]).attr('aria-label', '[bold ]Solde anuuel - ' + conges[3].stockAnnuel.toString() + 'j');
			let val 	= conges[3].stockAnnuel.toString().split('.');
			let text	= parseInt(val[0]) > 1 ? 's' : '';
				text 	= val.length == 1 ? val[0] + 'jour' + text :  val[0] + 'jour' + text + ' ' + parseInt(parseFloat('0.' +val[1]) * 8) + 'h';
			$($('tspan',soldAnnuel)[0]).text('Solde Annuel - ' + text);
			$($('tspan',soldAnnuel)[1]).text('100%');
		} , 150);
	} else {
		chart.data = [
			{
				"conge": "Solde annuel",
		  		"value": 0
			},
			{
		  		"conge": "Solde",
		  		"value": 0
			},
		];
		// Add and configure Series
		var pieSeries 						= chart.series.push(new am4charts.PieSeries());
		pieSeries.dataFields.value 			= "value";
		pieSeries.dataFields.category 		= "conge";
		pieSeries.labels.template.disabled 	= true;
		pieSeries.ticks.template.disabled 	= true;
		pieSeries.stroke 					= am4core.color("#807dd7");
		pieSeries.fill 						= am4core.color("#e3e3e3");
		chart.legend 						= new am4charts.Legend();
		chart.legend.position 				= "right";
		chart.innerRadius 					= am4core.percent(60);
		var label 							= pieSeries.createChild(am4core.Label);
		label.text 							= "Solde restant";
		label.horizontalCenter 				= "middle";
		label.verticalCenter 				= "middle";
		label.fontSize 						= 40;
		var label1 							= pieSeries.createChild(am4core.Label);
		label1.text 						= "0jour";
		label1.horizontalCenter 			= "middle";
		label1.verticalCenter 				= "middle";
		label1.fontSize 					= 40;
		label1.y 							= 40;
		chart.legend.labels.template.text 	= "[bold {color}]{name} - 0j[/]"; // Personnalize the legend
		setTimeout(function(){
			$('#id-113').css('fill', '#f7f7f7');
			$('#id-113').css('stroke', '#dbdbdb');
		},10);		
	}
	
	// chart.html = $('#liste').clone(); // inactif car on a changer cette ligne de code manuellement
	// Enable export EN ATTENTE EVOLUTION FUTURE
	// chart.exporting.menu = new am4core.ExportMenu();
}

function printDiv () {
	$('g[aria-label=Series]').parent().parent().addClass('cible-circle');
	$('#liste').attr('style','');
	var print_div = "<style type='text/css'> " +
						" .row { " +
					        " display:flex; " +
					        " flex-wrap: wrap; " +
						    " margin-right: -15px; " +
						    " margin-left: -15px; " +
					    " } " +
					    " .col-md-3 { " +
					    	" -ms-flex: 0 0 25%; " +
					    	" flex: 0 0 25%; " +
					    	" max-width: 25%; " +
					    " } " +
					    " .text-center { " +
					    	" text-align: center!important; " +
					    " } " +
					    " .hidden { " +
					    	" display: none;" +
					    " } " +
					    " div { " +
					    	" display: block; " +
					    " } " +
					    " div > a#print { " +
					    	" display: none; " +
					    " } " +
					    " #liste { " +
					    	" width: 100%; " +
					    	" overflow-y: hidden; " +
					    " } " +
					    " .p-2 { " +
							" padding: 0.5rem!important; " +
						" } " +
						" .m-2 { " +
							" margin: 0.5rem!important; " +
						" } " +
						" .card { " +
							" position: relative; " +
							" display: flex; " +
							" flex-direction: column; " +
							" min-width: 0; " +
							" word-wrap: break-word; " +
							" border-radius: 0.25rem; " +
						" } " +
						" .card.m2.p2.list-head { " +
							" border: 1px solid #1219c5; " +
							" background: #b90303;	     " +
					    	" font-size: 20px; " +
						" } " +
						" .card.list-body { " +
							" background-color: #fff; " +
							" background-clip: border-box; " +
						" } " +
						" .col-md-2 { " +
							" -ms-flex: 0 0 16.666667%; " +
							" flex: 0 0 16.666667%; " +
							" max-width: 16.666667%; " +
						" } " +
						" .list-head { " +
							" border: 1px solid #1219c5; " +
							" background: #e3e3e3; " +
							" font-size: 20px; " +
						" } " +
						" g [aria-label=Legend] { " +
							" transform: translate(68%, 28%); " +
						" } " +
						" g.cible-circle { " +
							" transform: translate(30%, 47%); " +
						" } " +
						" .tete-list-section { " +
						    " margin: 0px!important; " +
						    " padding: 10px; " +
						    " width: 100%; " +
						    " background: #c8d0e4; " +
						    " border-radius: 5px 5px 0px 0px; " +
						    " white-space: nowrap; " +
						" } " +
						" .select-time { " +
						    " border-radius: 5px!important; " +
						    " font-size: 1.0em!important; " +
						    " font-weight: bold!important; " +
						    " /* width: 150px!important; */ " +
						" } " +
						" .col-md-5 { " +
						    " -ms-flex: 0 0 41.666667%; " +
						    " flex: 0 0 41.666667%; " +
						    " max-width: 41.666667%; " +
						" } " +
						" .date-time, .select-time { " +
						    " width: 120px; " +
						    " text-align: center!important; " +
						    " font-size: small; " +
						" } " +
					" </style>";
	print_div +=  $(".row.page-complet").html();
	var print_area = window.open();
	print_area.document.write(print_div);
	print_area.document.close();
	print_area.focus();
	print_area.print();
	print_area.close();

}