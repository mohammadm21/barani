<?php
$time = strtotime("-7 day 00:00:00");
$sql->where("day", $time, '>=');
$sql->orderBy('day', 'ASC');
$days = $sql->get('stats_num');
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>گزارش کارکرد در 7 روز گذشته</title>
        <script src="<?php echo(url('theme/admin/js/libs/jquery-1.6.1.min.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo(url('theme/admin/js/highcharts.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo(url('theme/admin/js/modules/exporting.js')); ?>"></script>
		<script type="text/javascript">
		
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'line',
						marginRight: 0,
						marginBottom: 0
					},
					title: {
						text: 'گزارش کارکرد در 7 روز گذشته',
						x: -20 //center
					},
					subtitle: {
						text: '',
						x: -20
					},
					xAxis: {
						categories: [<?php
                            $x= 0;
                            foreach($days as $day){
                                if($x > 0)echo(',');
                                echo("'".jdate::start('l', $day['day'])."'");
                                $x++;
                                
                            }
                            ?>]
					},
					yAxis: {
						title: {
							text: ''
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						formatter: function() {
						  switch(this.series.name){
						      case('بازدید'): return '<b>'+ this.series.name +'</b><br/>'+this.x +': '+ this.y +'بار';break;
                              case('اخراج'):case('عضویت'): return '<b>'+ this.series.name +'</b><br/>'+this.x +': '+ this.y +'نفر';break;
                              case('پیام های ارسالی'): return '<b>'+ this.series.name +'</b><br/>'+this.x +': '+ this.y +'عدد';break;
     
						  }
				               
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},
					series: [{
					  
						name: 'بازدید',
						data: [ <?php
                        $x= 0;
                        foreach($days as $day){
                            if($x > 0)echo(',');
                            echo($day['visit']);
                            $x++;
                        }
                        ?>]
					}, {
						name: 'اخراج',
						data: [ <?php
                        $x= 0;
                        foreach($days as $day){
                            if($x > 0)echo(',');
                            echo($day['bann']);
                            $x++;
                        }
                        ?>]
					}, {
						name: 'پیام های ارسالی',
						data: [ <?php
                        $x= 0;
                        foreach($days as $day){
                            if($x > 0)echo(',');
                            echo($day['msgs']);
                            $x++;
                        }
                        ?>]
					}, {
						name: 'عضویت',
						data: [ <?php
                        $x= 0;
                        foreach($days as $day){
                            if($x > 0)echo(',');
                            echo($day['register']);
                            $x++;
                        }
                        ?>]
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
