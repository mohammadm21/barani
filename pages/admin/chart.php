<?php
$ARRAY = array('genderonlines','genderusers','typesonlines','workin7days');
$re = giu('el3');
if (in_array($re,$ARRAY) && is_file("pages/admin/chart/$re.php"))
    include("pages/admin/chart/$re.php"); 
else 
 echo 'inja omadi chikar :/';
?>