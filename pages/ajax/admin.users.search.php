<?php

if (!session('id') or session('admin') == '0') {
    exit();
}
adminpanel::check_Permission('exit', 'users');
$numshowrows = isset($_REQUEST['iDisplayLength']) ? safe::number($_REQUEST['iDisplayLength']) : 100;
$page = isset($_REQUEST['iDisplayStart']) ? safe::number($_REQUEST['iDisplayStart']) : 1;
if ($page == 1 or $page == 0) {
    $startrow = 0;
} else {
    $startrow = ($page - 1) * $numshowrows;
}



$if = "1";
$sort = '`score` DESC';
$rows = array();
$sql->orderBy('score', 'DESC');
$rows = $sql->withTotalCount()->get('users', array($startrow,$numshowrows), array('id', 'username', 'name', 'gender', 'type', 'ip', 'score'));
$numrows = $sql->totalCount;

//header::tojson();
echo("{\"sEcho\":1,\"iTotalRecords\":$numrows,\"iTotalDisplayRecords\":$numrows,\"aaData\":[");
$firstrow = true;
foreach ($rows as $row) {
    if ($firstrow == false) {
        echo',';
    }
    $firstrow = false;
    echo('[');
    $firstcol = true;
    foreach ($row as $col => $value) {
        if ($firstcol == false) {
            echo',';
        }
        $firstcol = false;
        echo("\"$value\"");
    }
    echo(']');
}
echo(']}');
/*
  echo json_encode(array(
  'sEcho' => 1,
  'iTotalRecords' => $numrows,
  'iTotalDisplayRecords' => $numrows,
  'aaData' => $rows


  ), JSON_UNESCAPED_UNICODE); */
?>