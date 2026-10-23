<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نوع کاربران آنلاین</title>
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
						text: 'نوع کاربران آنلاین'
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
							['مهمان',   <?php echo($sql->where('type', 1)->getValue('onlines', 'count(*)')); ?>],
							['عضو',    <?php echo($sql->where('type', 2)->getValue('onlines', 'count(*)')); ?>],
                            ['ویژه',    <?php echo($sql->where('type', 3)->getValue('onlines', 'count(*)')); ?>],
							['فعال',    <?php echo($sql->where('type', 4)->getValue('onlines', 'count(*)')); ?>],
							['ارشد',    <?php echo($sql->where('type', 5)->getValue('onlines', 'count(*)')); ?>],
							['سرپرست', <?php echo($sql->where('type', 6)->getValue('onlines', 'count(*)')); ?>],
                            ['ناظر',     <?php echo($sql->where('type', 7)->getValue('onlines', 'count(*)')); ?>],
                            ['مدیر',     <?php echo($sql->where('type', 8)->getValue('onlines', 'count(*)')); ?>]
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
