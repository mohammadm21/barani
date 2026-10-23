<?php

if ($options['autolinker'] == 'true') {
    if ($_SESSION['captcha-txt'] != safe::string($_REQUEST['captcha'])) {
        echo('captcha');
        return;
    }
    $address = isset($_POST['address']) ? safe::url($_POST['address']) : '';
    if ($address == '') {
        echo('noaddress');
        return;
    }
    $checker = parse_url($address);
    if (str_replace(array('http://', 'www.', 'https://'), '', $checker['host']) == $_SERVER['SERVER_NAME'])
        die('لطفا آدرس را صحیح وارد کنید');
    error_reporting(0);
    if ($html = file_get_contents($address)) {
        if (strpos($html, $site_url[0]) !== false) {
            $title = isset($_POST['title']) ? safe::string($_POST['title']) : $address;
            $sql->where("url", $address);
            if ($sql->has('links')) {
                $sql->where("url", $address)->update('links', array(
                    'active' => 1
                ));
            } else {
                $sql->insert('links', array(
                    'title' => $title,
                    'url' => $address,
                    'active' => 0,
                    'click' => 0
                ));
            }
            echo('ok');
        } else {
            echo('nolink');
        }
    } else {
        echo('noaccsess');
    }
} else {
    echo 'سیستم آفلاین میباشد';
}
?>