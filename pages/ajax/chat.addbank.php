<?php
if (!session('id')) {
    exit('login');
}
$bank = safe::number($_POST['bank']);
if (isset($bank)) {
    if (!is_numeric($bank)) {
        exit("مقدار موجودی درخواست شده صحیح نمیباشد");
    }
    if (session('type') == 1) {
        exit('مهمان اجازه افزایش امتیاز بانک ندارد');
    }
    $perm = json_decode($options['useroptions'], true);
    if (!in_array(session('type'), $perm['addbank'])) {
        exit('برای کارگروه شما محدودیت قرار داده شده است');
    }
    $sql->where('id', session('id'));
    $row = $sql->getOne('users');
    $dbbank = $options['add_bank'];
    $emd = $row['score'] / $options['add_bank'];
    if ($bank > $emd) {
        exit("امتیاز شما برای افزایش این مقدار موجودی کافی نمیباشد \n هر $dbbank امتیاز یک امتیاز بانک ");
    } else {
        $res = $dbbank * $bank;
        $score = $row['score'] - $res;
        $bank2 = $row['bank'] + $bank;
        $sql->where("id", session('id'))->update('users', array(
            'score' => $score,
            'bank' => $bank2
        ));
        $sql->where("id", session('id'))->update('onlines', array(
            'score' => $score,
            'bank' => $bank2
        ));;
        echo "با موفقیت اضافه شد";
    }
}
?>