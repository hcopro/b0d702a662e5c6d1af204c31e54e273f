$(document).ready(function(){
	/*var chartData = {
		labels: ['test', 'test2'],
		datasets: [
			{
				label: "data 1",
				backgroundColor: 'rgba(200,200,200,0.75)',
				borderColor: 'rgba(200,200,200,0.75)',
				hoverBackgroundColor: 'rgba(200,200,200,1)',
				hoverBorderColor: 'rgba(200,200,200,1)',
				data: [1,2]
			},
			{
				label: "data 2",
				backgroundColor: 'rgba(0,200,200,0.75)',
				borderColor: 'rgba(0,200,200,0.75)',
				hoverBackgroundColor: 'rgba(0,200,200,1)',
				hoverBorderColor: 'rgba(0,200,200,1)',
				data: [2,4]
			}
		]
	}; 
	var ctx = $('#mycanvas');
	var barGraph = new Chart(ctx, {
		type: 'bar',
		data: chartData
	});*/
	/*$.ajax({
		url: "http://sirh.s187620.mos2.atester.fr/manage/tableau_bord",
		method: "GET",
		success: function(data) {
			console.log(data);
		},
		error: function(data) {
			console.log(data);
		}
	});*/


	var options1 = {
		animationEnabled: true,
		title: {
			text: "test"
		},
		data: [{
			type: "column", //change it to line, area, bar, pie, etc
			dataPoints: [
				{ x: 1, y: 2, label: "poste1"},
		        { x: 2, y: 5,  label: "poste2" },
		        { x: 3, y: 1,  label: "poste3"},
		        { x: 4, y: 3,  label: "poste4"},
		        { x: 5, y: 2,  label: "poste5"},
		        { x: 6, y: 2,  label: "poste6"}
			]
		}]
	};

	$("#resizable").resizable({
		create: function (event, ui) {
			//Create chart.
			$("#chartContainer1").CanvasJSChart(options1);
		},
		resize: function (event, ui) {
			//Update chart size according to its container size.
			$("#chartContainer1").CanvasJSChart().render();
		}
	});
});