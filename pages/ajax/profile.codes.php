<?php

header::tojson();
$codes = array();
$sql->where("page", 'profile');
foreach($sql->get('codes',null, array('location','code')) as $row){
    $codes[] = array(
        'location' => $row['location'],
        'code' => $row['code']
    );
}
$ads = array();
$sql->where("page", "profile");
foreach($sql->get('ads') as $row){
    $inputs = json_decode($row['inputs'], true);
    if (isset($inputs['cockie'])) {
        $inputs['cockie'] = round(($inputs['cockie'] - time()) / 86400);
        if ($inputs['cockie'] < 1)
            $inputs['cockie'] = 0;
    }
    $ads[] = array(
        'id' => $row['id'],
        'type' => $row['type'],
        'url' => $row['url'],
        'location' => $row['location'],
        'inputs' => $inputs
    );
}
echo json_encode(array('codes' => $codes, 'ads' => $ads), JSON_UNESCAPED_UNICODE);
?>