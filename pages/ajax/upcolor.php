<?php
if(!session('id'))exit("");
if (isset($_REQUEST['fontname'])) {
    $font = safe::string($_REQUEST['fontname']);
            session('fontname', (string)$font);
} else if (isset($_REQUEST['fontsize'])) {
    $size = safe::number($_REQUEST['fontsize']) . "px";
    if ($size == '13px' || ($size > '13px' && $size < '18px')) {
        session('fontsize', $size);

    }
} else if ($_POST['color'] == 'bold') {
    session('bold', 'bold');
} else if ($_REQUEST['color'] == 'nobold') {
    session('bold', 'normal');
} else if ($_REQUEST['color'] == 'italic') {
    session('italic', 'italic');
} else if ($_REQUEST['color'] == 'noitalic') {
    session('italic', 'normal');
} else {
    session('color', substr(safe::string($_POST['color']), 0, 6));
}
$setting = array('fontname'=>session('fontname'),'fontsize'=>session('fontsize'),'color'=>session('color'),'bold'=>session('bold'));
$setting = json_encode($setting, JSON_UNESCAPED_UNICODE);
    $sql->where("id", (int)session('id'))->update('users', array(
        'setting' => $setting
    ));
?>