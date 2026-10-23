<html dir="rtl">
<head>
<title>کد های شکلک های - بارانی چت</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="shortcut icon" href="/Favicon.png"/>
      <link rel="apple-touch-icon" href="/apple.png" />
      <link rel="icon" type="image/png" sizes="192x192"  href="/android.png">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <style>
    body{ background: url(/theme/history/images/users.bg.jpg) repeat-x scroll 0 128px #fff;}
    table{ background: #35ff73a3;}
    @font-face {
    font-family: 'Yekan';
    src: url('https://cdn.fontcdn.ir/Font/Persian/Yekan/Yekan.eot');
    src: url('https://cdn.fontcdn.ir/Font/Persian/Yekan/Yekan.eot?#iefix') format('embedded-opentype'),
		 url('https://cdn.fontcdn.ir/Font/Persian/Yekan/Yekan.woff') format('woff'),
		 url('https://cdn.fontcdn.ir/Font/Persian/Yekan/Yekan.ttf') format('truetype');
    font-weight: normal;
}

    
  </style>
</head>
<body>
  <SCRIPT language=JavaScript>
document.onmousedown=click
var times=0
var times2=10
function click() {
if ((event.button==2) || (event.button==3)) {
if (times>=1) { bye() }
alert("داری دزدی می کنی ...؟؟؟   این کارو نکن عزیز دل برادر ضرز می کنی ...از من گفتن.."); 
times++ } }
function bye() {
alert("من که گفتم کلیک نکن... حالا ریست کن سیستمت رو  تاحالت گرفته شه !");
bye() }
</SCRIPT>
<div style="width: 700px;
    margin: 0 auto;
    direction: rtl;
    text-align: center;
    align-content: center;
    font-family: 'Yekan';">
<h3>کد های شکلک های ویژه برای استفاده در چت ها</h3>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th style='width:50px;text-align: center; align-content: center;'>شماره</th>
<th style='width:50px;text-align: center; align-content: center;'>عکس</th>
<th style='width:50px;text-align: center; align-content: center;'>کد</th>
</tr>
</thead>
<tbody>
<?php
include('db.php');
  $con -> set_charset("utf8");
    header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
	} else {
		$page_no = 1;
        }

	$total_records_per_page = 20;
    $offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2"; 
	$result_count = mysqli_query($con,"SELECT COUNT(*) As total_records FROM `ahyir_smilies`  WHERE name NOT LIKE '(%'");
	$total_records = mysqli_fetch_array($result_count);
	$total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total page minus 1
    $result = mysqli_query($con,"SELECT * FROM `ahyir_smilies`  WHERE name NOT LIKE '(%' LIMIT $offset, $total_records_per_page");
    while($row = mysqli_fetch_array($result)){
		echo "<tr>
			  <td style='text-align: center; align-content: center;'>".$row['id']."</td>
			  <td style='text-align: center; align-content: center;'><img src='/theme/images/smilies/".$row['img']." '></td>
	 		  <td style='text-align: center; align-content: center;'>".$row['name']."</td>
		   	  </tr>";
        }
	mysqli_close($con);
    ?>
</tbody>
</table>

<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>صفخه <?php echo $page_no." از ".$total_no_of_pages; ?></strong>
</div>

<ul class="pagination">
	<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>اولین صفحه</a></li>"; } ?>
    
	<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>قبلی</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>بعدی</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a href='?page_no=$total_no_of_pages'>آخرین &rsaquo;&rsaquo;</a></li>";
		} ?>
</ul>

<script> function close_window() {
  if (confirm("این پنجره بسته شود؟")) {
    close();
    window.close()
  }
}
  </script>
<br /><br />
  <a  href="javascript:close_window();"><strong>بستن این پنجره</strong></a> <br /><br />
بارانی چت بهتری چت روم در دنیا <a href=""><strong>Rainychat.ir</strong></a>
</div>
</body>
</html>