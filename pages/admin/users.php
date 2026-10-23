<?php

$el3 = giu('el3');
switch ($el3) {
    case('list'):
        $el4 = giu('el4');
        if ($el4 or $el4 != '4') {
            $el4 = safe::number($el4);
            $if = "`type`='$el4' ORDER BY `id`  ASC";
            $el = 'el5';
        } else {
            $if = '1 ORDER BY `id`  ASC';
            $el = 'el4';
        }
        break;
    case('man'):$if = "`gender`='1'  ORDER BY `id` ASC";
        $el = 'el4';
        break;
    case('woman'):$if = "`gender`='2'  ORDER BY `id` ASC";
        $el = 'el4';
        break;
    case('ban'):case('bann'):$if = "`active`='0'  ORDER BY `lastview`  ASC";
        $el = 'el4';
        break;
    case('edit'):$if = '1 ORDER BY `id`  ASC';
        $el = 'el3';
	if ($useredit = $sql->where("id", safe::number(giu('el4')))->getOne('users')) {
            include(theme::file('users.edit.php'));
            exit();
        };
        break;
    case('search'):
        if (isset($_REQUEST['search_type'])) {
            if ($_REQUEST['search_type'] == 'username')
                $if = "`username` REGEXP '" . safe::string($_REQUEST['username']) . "'";
            else if ($_REQUEST['search_type'] == 'name')
                $if = "`name`REGEXP '" . safe::string($_REQUEST['name']) . "'";
            else if ($_REQUEST['search_type'] == 'gender')
                $if = "`gender`='" . safe::number($_REQUEST['gender']) . "'";
            else if ($_REQUEST['search_type'] == 'ip')
                $if = "`ip` REGEXP '" . safe::string($_REQUEST['ip']) . "'";
            else {
                header::Go(url('admin/users'));
                exit();
            }
        } else {
            $if = '';
            if (isset($_REQUEST['username']) and ! empty($_REQUEST['username'])) {
                $if = "`username` REGEXP '" . safe::string($_REQUEST['username']) . "'";
            }
            if (isset($_REQUEST['name']) and ! empty($_REQUEST['name'])) {
                if ($if != '')
                    $if = " AND ";
                $if .= "`name` REGEXP '" . safe::string($_REQUEST['name']) . "'";
            }
            if (isset($_REQUEST['gender']) and ! empty($_REQUEST['gender'])) {
                if ($if != '')
                    $if = " AND ";
                $if .= "`gender` REGEXP '" . safe::number($_REQUEST['gender']) . "'";
            }
            if (isset($_REQUEST['ip']) and ! empty($_REQUEST['ip'])) {
                if ($if != '')
                    $if = " AND ";
                $if .= "`ip` REGEXP '" . safe::string($_REQUEST['ip']) . "'";
            }
        }
        if ($if == '')
            $if = "1";
        $if .= " ORDER BY `id`  DESC";
        $el = 'el4';
        break;
    default:$if = '1 ORDER BY `id`  ASC';
        $el = 'el3';
        break;
}
$page = safe::number(giu($el)) ? ((int) safe::number(giu($el))) : 1;
if ($page == 1)
    $startpage = 0;
else
    $startpage = ($page - 1) * $msgshostory;
include(theme::file('users.php'));
?>