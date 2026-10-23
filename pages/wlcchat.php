
  <style type="text/css">        
#dwin{
width: 400px;
margin:0 auto;
background-color: #000;
border:solid 5px #7E7E7E;
text-align: center;
font-family: Tahoma;
font-size: 16px;
color: #6E6E6E;
font-family: AGoogle;
box-shadow: #BDBDBD 1px 1px 1px;
padding-right: 5px;
padding-left: 5px;
border-radius: 10px;
position:fixed; left: 30% ;top:30%;
padding-bottom: 15px; 

}
#dwin:hover{
box-shadow:#BDBDBD 2px 2px 2px 1px;
transition: ease-in 0.3s all ;
background-color: #000;
color: #FFFFFF;
border: solid 5px #E0E0E0;
}
#dclose{
float: right;
margin-right: 5px;
color: #FF0033;
cursor: pointer;
font-weight: bold; 
font-family: AGoogle;
}
#ptablig{
margin-top: 20px;
font-size: 0.9em; 
font-family: AGoogle;
}
#head_Title{
font-size: medium; 
color: #29a2d5;
text-align:center;
margin-left: 150px;
}

<?php $pmwlc = json_decode($options['pmwlc'], true); ?>


</style><div id="closead">
<div id="dwin">
<div id="dclose" onclick="document.getElementById ('closead').style.display='none'" >بستن اين پنجره [X]</div>
<div id="ptablig">
 

<span id="head_Title"><?php echo($pmwlc['head_Title']) ?></span>

<div align="center"><font color="<?php echo($pmwlc['title_color']); ?>" size="3"><?php echo($pmwlc['Title']) ?>  <b></b>  </font><font size="2"><br></font></div>
<span style="font-size: medium; color: <?php echo($pmwlc['pmone_color']); ?>;"> <?php echo($pmwlc['pm_one']) ?></span>  <p>  
<div align="center"><font face="AGoogle" color="<?php echo($pmwlc['Fsturl_color']); ?>" size="5"><?php echo($pmwlc['Fst_url']) ?></font></div>
<div align="center"><font face="AGoogle" color="<?php echo($pmwlc['Securl_color']); ?>" size="5"><?php echo($pmwlc['Sec_url']) ?></font></div>
<div align="center"><font face="AGoogle" color="<?php echo($pmwlc['thdurl_color']); ?>" size="5"><?php echo($pmwlc['thd_url']) ?></font></div>
<div align="center"><font size="5" color="<?php echo($pmwlc['pmelse_color']); ?>" face="AGoogle"> <?php echo($pmwlc['pm_else']) ?> </font><br><a href="<?php echo($pmwlc['link']) ?>" target="_blank" style="font-size: 25px;color: <?php echo($pmwlc['id_color']); ?>;"> <?php echo($pmwlc['id_tel']) ?> <br> <?php echo($pmwlc['pm_link']) ?> </a></div>