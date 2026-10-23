
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
font-family: Pashaei;
box-shadow: #BDBDBD 1px 1px 1px;
padding-right: 5px;
padding-left: 5px;
border-radius: 10px;
position:fixed; left: 30% ;top:30%;
padding-bottom: 15px; 
font-family: Pashaei;
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



</style><div id="closead">
<div id="dwin">
<div id="dclose" onclick="document.getElementById ('closead').style.display='none'" >بستن اين پنجره [X]</div>
<div id="ptablig">
 

<span id="head_Title"><?php echo($options['head_Title']) ?></span>

<div align="center"><font color="#FFCC00" size="3"><?php echo($options['title']) ?>  <b></b>  </font><font size="2"><br></font></div>
<span style="font-size: medium; color: #fafafa;"> <?php echo($options['pm_one']) ?></span>  <p>  
<div align="center"><font face="Comic Sans MS" color="#FFCC00" size="5"><?php echo($options['Fst_url']) ?></font></div>
<div align="center"><font face="Comic Sans MS" color="#FFCC00" size="5"><?php echo($options['Sec_url']) ?></font></div>
<div align="center"><font face="Comic Sans MS" color="#FFCC00" size="5"><?php echo($options['thd_url']) ?></font></div>
<div align="center"><font size="5" color="#FFCC00" face="Comic Sans MS"> <?php echo($options['pm_else']) ?> </font><br><a href="<?php echo($options['link']) ?>" target="_blank" style="font-size: 25px;color: red;"> <?php echo($options['id_tel']) ?> <br> <?php echo($options['pm_link']) ?> </a></div>