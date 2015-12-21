/*** First Chart in Dashboard page ***/

	$(document).ready(function() {
		$("[name='autosign']").bootstrapSwitch();
		$("[name='autocj']").bootstrapSwitch();
		info = new Highcharts.Chart({
			chart: {
				renderTo: 'load',
				margin: [0, 0, 0, 0],
				backgroundColor: null,
                			plotBackgroundColor: 'none',
							
			},
			credits: {  
 				 enabled: false  
			}, 
			
			title: {
				text: null
			},

			tooltip: {
				formatter: function() { 
					return this.point.name +': '+ this.y +' %';
						
				} 	
			},
		    	series: [
				{
				borderWidth: 2,
				borderColor: '#F1F3EB',
				shadow: false,	
				type: 'pie',
				name: 'Income',
				innerSize: '65%',
				data: [
					{ name: '签到成功', y: succjs, color: '#b2c831' },
					{ name: '签到失败', y: failjs, color: '#3d3d3d' }
				],
				dataLabels: {
					enabled: false,
					color: '#000000',
					connectorColor: '#000000'
				}
			}]

		});
		
	});



