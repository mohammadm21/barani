<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>جنیست کاربران آنلاین</title>
        <script src="<?php echo(url('theme/admin/js/libs/jquery-1.6.1.min.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo(url('theme/admin/js/highcharts.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo(url('theme/admin/js/modules/exporting.js')); ?>"></script>
		<script type="text/javascript">
		
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: 'جنیست کاربران آنلاین'
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ this.y +' نفر';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								color: '#000000',
								connectorColor: '#000000',
								formatter: function() {
									return '<b>'+ this.point.name +'</b>: '+ this.y +' نفر';
								}
							}
						}
					},
				    series: [{
						type: 'pie',
						name: 'Browser share',
						data: [
							['مذکر',   <?php echo($sql->where('gender', 1)->getValue('onlines', 'count(*)')); ?>],
							['مونث',      <?php echo($sql->where('gender', 2)->getValue('onlines', 'count(*)')); ?>]
						]
					}]
				});
			});
				
		</script>
		
	</head>
	<body>
		<!-- 3. Add the container -->
		<div id="container" style="width: 620px; height: 350px; margin: 0 auto"></div>
	</body>
</html>
