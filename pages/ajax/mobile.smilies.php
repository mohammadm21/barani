<?php

$output = array('s' => array());
$sql->orderBy('priority', 'DESC');
foreach($sql->get('smilies', null, array('img', 'code', 'name')) as $row){
    $o = array(
        'i' => $row['img'],
        'c' => $row['code'],
        't' => 1
    );
    if ($row['name'] != null) {
        $o['n'] = $row['name'];
    }
    $output['s'][] = $o;
}
$output['status'] = true;
header::tojson();
echo json_encode($output, true);
?>
