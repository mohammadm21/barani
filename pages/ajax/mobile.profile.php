<?php

$output = array();


$user = isset($_GET['user']) ? safe::number($_GET['user']) : '';
if ($user and $user = $sql->where("id", $user)->getOne('users')) {
    if ($user['active'] == 1) {
        if ($profile = $sql->where("id", $user['id'])->getOne('profiles')) {
            if ($profile['active'] == 1) {
                //$profile['contact'] = json_decode($profile['contact'], true);
                //$profile['socialnetworks'] = json_decode($profile['socialnetworks'], true);
                //$profile['additional'] = json_decode($profile['additional'], true);
                $profile['additional'] = $profile['socialnetworks'] = $profile['contact'] = array();
                if (!empty($profile['contact'])) {
                    if (isset($profile['contact']['web']))
                        $output['p']['c']['w'] = $profile['contact']['web'];
                    if (isset($profile['contact']['mail']))
                        $output['p']['c']['m'] = $profile['contact']['mail'];
                    if (isset($profile['contact']['tel']))
                        $output['p']['c']['t'] = $profile['contact']['tel'];
                }
                if (!empty($profile['socialnetworks'])) {
                    if (isset($profile['socialnetworks']['fb']))
                        $output['p']['s']['f'] = $profile['socialnetworks']['fb'];
                    if (isset($profile['socialnetworks']['g+']))
                        $output['p']['s']['g'] = $profile['socialnetworks']['g+'];
                    if (isset($profile['socialnetworks']['yt']))
                        $output['p']['s']['y'] = $profile['socialnetworks']['yt'];
                    if (isset($profile['socialnetworks']['tw']))
                        $output['p']['s']['t'] = $profile['socialnetworks']['tw'];
                }
                if (!empty($profile['additional'])) {
                    if (isset($profile['additional']['fn']))
                        $output['p']['a']['f'] = $profile['additional']['fn'];
                    if (isset($profile['additional']['ln']))
                        $output['p']['a']['l'] = $profile['additional']['ln'];
                    if (isset($profile['additional']['cu']))
                        $output['p']['a']['cu'] = $profile['additional']['cu'];
                    if (isset($profile['additional']['ci']))
                        $output['p']['a']['ci'] = $profile['additional']['ci'];
                    if (isset($profile['additional']['br']))
                        $output['p']['a']['b'] = $profile['additional']['br'];
                    if (isset($profile['additional']['in']))
                        $output['p']['a']['i'] = $profile['additional']['in'];
                }
                if ($profile['avatar'] != $options['defaultavatar'])
                    $output['p']['av'] = $profile['avatar'];
                $output['p']['ui'] = (int) $user['id'];
                $output['p']['un'] = $user['name'];
                $output['p']['ut'] = (int) $user['type'];
                $output['p']['ug'] = (int) $user['gender'];
                $output['p']['uv'] = jdate::start('l d F Y', $user['lastview']);
                $output['status'] = true;
            }else {
                $output = array(
                    'status' => false,
                    'error' => 'deactiveprofile'
                );
            }
        } else {
            $output = array(
                'status' => false,
                'error' => 'notfoundprofile'
            );
        }
    } else {
        $output = array(
            'status' => false,
            'error' => 'deactiveuser'
        );
    }
} else {
    $output = array(
        'status' => false,
        'error' => 'notfounduser'
    );
}

header::tojson();
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
