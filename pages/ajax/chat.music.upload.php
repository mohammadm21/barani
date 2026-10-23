<?php
if ((trim(session('type')) == "") || (trim(session('type')) == 1)) {
    exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> !!!!کاربر مهمان اجازه ارسال موزيک ندارد </font> </p>');
}
if (!session('id')) {
    exit('login');
}

$allow_users = json_decode($options['useroptions'], true);

if ($options['allow_send_music'] != "true") {
    exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> اين قسمت توسط مدير چت روم غيرفعال شده است </font> </p>');
}
if (!in_array(session('type'), $allow_users['send_music'])) {
    exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> براي گروه کاربري شما محدوديت قرار داده شده است </font> </p>');
}

$sql->where("id", session('id'));
$user = $sql->getOne('users', array('bank'));
if ($user['bank'] - $options['music_score'] < 0) {
    exit('<p style="text-align:center;direction:rtl;"> <font style="color:red;"> امتياز شما کافي نيست </font> </p>');
}

// همون فيلتر ضد تتلو که در chat.send.php هست، روي نام فايل ارسالي هم اجرا ميشه
function Music_BadWordsCheck($string) {
    $words = array('Tataloo', 'pr3m.ir', 'jenabmusic', 'JENABMUSIC', 'tataloo', 'تتلو', 'امیرتتلو', 'tatalooo', 'TATALOOO', 'TATALOO', '_tataloo_', '_Tataloo_', '_TATALOO_', 'Tataloo_', '_Tataloo', '_TATALOO', 'TATALOO_', 'amirtataloo', 'Amirtataloo', 'AmirTataloo', 'Amir_Tataloo', 'amir_tataloo');
    $pattern = '/\b(' . implode('|', $words) . ')\b/i';
    return preg_match($pattern, $string);
}

function Upload_Music($file_up) {
    global $options, $sql, $usertype;

    if (!isset($file_up) || $file_up['error'] != 0 || $file_up['tmp_name'] == '') {
        return 'فايلي انتخاب نشده است';
    }

    if (Music_BadWordsCheck($file_up['name'])) {
        return ' تتلو نفرست';
    }

    $allowed = array(
        'audio/mpeg' => 'mp3',
        'audio/mp3' => 'mp3',
        'audio/ogg' => 'ogg',
        'audio/wav' => 'wav',
        'audio/x-wav' => 'wav',
        'audio/x-m4a' => 'm4a',
        'audio/mp4' => 'm4a'
    );

    if (!isset($allowed[$file_up['type']])) {
        return 'فرمت فايل ارسالي مجاز نيست (فقط mp3, ogg, wav, m4a مجاز است)';
    }
    $format = $allowed[$file_up['type']];

    $max_size = isset($options['music_max_size']) ? (int) $options['music_max_size'] : 12000000; // پيش فرض 8 مگابايت
    if ($file_up['size'] > $max_size) {
        return '(12MB)حجم فايل ارسالي بيشتر از حد مجاز است';
    }

    $folder = isset($options['music_dir']) ? $options['music_dir'] : 'files/music';
    if (!is_dir($folder)) {
        @mkdir($folder, 0755, true);
    }

    $id = session('id');
    $name = $id . "_" . time() . "_" . rand(1000, 9999) . ".$format";
    $filename = "$folder/$name";

    if (!move_uploaded_file($file_up['tmp_name'], $filename)) {
        return 'فايل بارگزاري نشد';
    }

    // چک امنيتي ساده، مشابه آپلود آواتار، براي جلوگيري از آپلود فايل php با پسوند تقلبي
    $head = @file_get_contents($filename, false, null, 0, 4096);
    if ($head !== false && stripos($head, '<?php') !== false) {
        @unlink($filename);
        return 'فايل ارسالي مورد تاييد نمي باشد';
    }

    $music_url = url("$folder/$name");
    $text = '<audio controls preload="none"><source src="' . $music_url . '" type="audio/mp3"></audio>';
    $me_bank = $options['music_score'];

    $sql->insert('message', array(
        'user' => session('id'),
        'username' => session('name'),
        'color' => session('color'),
        'room' => session('room'),
        'text' => $text,
        'date' => time(),
        'to' => -1,
        'into' => 'main',
        'type' => session('admin')
    ));
    $sql->where("id", session('id'))->update('onlines', array(
        'score' => $sql->dec($me_bank)
    ));
    $sql->where("id", session('id'))->update('users', array(
        'score' => $sql->dec($me_bank)
    ));

    // اطلاع رساني به مديران، مشابه chat.send.php
    $admins = array();
    $is_admin = array(1, 2, 3);
    $allow_admins = json_decode($options['useroptions'], true);
    foreach ($usertype as $types) {
        if (in_array($types['admin'], $is_admin)) {
            $admins[] = $types['id'];
        }
    }
    $note = '<table dir="rtl" cellpadding="3" border="1" align="center">
					<tr><td><b>کاربر:</b></td><td><b><font color=red>' . session('name') . '</font></b></td></tr>
					<tr><td><b>درجه:</b></td><td><b><font color=red><img src="/' . $usertype[Users::get('type', session('id'))]['icon'] . '"></font></b></td></tr>
					<tr><td><b>فعاليت:</b></td><td><b><font color=red>ارسال موزيک (آپلود فايل) در عمومي . از کاربر مقدار ' . $options['music_score'] . ' امتياز بانکي کم شد</font></b></td></tr>
				</table><hr>';
    $sql->where("type", $admins, 'in');
    foreach ($sql->get('onlines', null, array('id', 'type', 'roomid')) as $row) {
        if ($row['roomid'] == '1') {
            $row['roomid'] = '-1';
        }
        if (in_array($row['type'], $admins)) {
            if (in_array($row['type'], $allow_admins['send_user2private_admins'])) {
                $sql->insert('message', array(
                    'user' => -1,
                    'username' => '<center>SYSTEM [ <b><font color=red>ارسال گزارش</font></b> ]</center>',
                    'color' => '000000',
                    'room' => $row['roomid'],
                    'text' => $note,
                    'date' => time(),
                    'to' => $row['id'],
                    'into' => 'pm',
                    'type' => session('admin')
                ));
            }
        }
    }

    return array('status' => 'ok', 'url' => $music_url);
}

$upload_result = null;
if (!empty($_FILES['fileToUpload']) && $_FILES['fileToUpload']['tmp_name'] != '') {
    $upload_result = Upload_Music($_FILES['fileToUpload']);

    // اگر درخواست از طريق Ajax (آپلود با پراگرس بار) اومده، فقط JSON برگردون
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        if (is_array($upload_result) && $upload_result['status'] == 'ok') {
            echo json_encode(array('status' => 'ok', 'url' => $upload_result['url']));
        } else {
            echo json_encode(array('status' => 'error', 'message' => $upload_result));
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo ("/theme/fonts/fonts.css") ?>">
        <style>
            *{
                font-family:Byekan;
                direction:rtl;
            }
            .prim{
                border:0px;
                cursor:pointer;
                border-radius:4px;
                background:rgb(59, 136, 195);
                color:#fff;
                padding-top:4px;
                padding-bottom:4px;
                width:160px;
                margin-top:6px;
            }
            .dang{
                border:0px;
                cursor:pointer;
                border-radius:4px;
                background:rgb(200, 0, 0);
                color:#fff;
                padding-top:4px;
                padding-bottom:4px;
                width:160px;
                margin-top:6px;
            }
        </style>
    </head>
    <body>
        <?php if ($usertype[session('type')]['guest'] == 1) { ?>
            <div style="text-align: center; font-weight: bolder; text-shadow: 0px 0px 5px #ff0000">براي ارسال موزيک ابتدا عضو شويد</div>
        <?php } else { ?>
        <center>
            <div style="margin-bottom:8px;">
                <label style="cursor:pointer;margin-left:14px;"><input type="radio" name="music_mode" value="upload" checked> آپلود از سيستم</label>
                <label style="cursor:pointer;"><input type="radio" name="music_mode" value="link"> ارسال لينک مستقيم</label>
            </div>

            <div id="upload_section">
                <form id="music_upload_form" action="<?= url('ajax/chat.music.upload'); ?>" method="POST" enctype="multipart/form-data">
                    <input type='file' style="display:none;" id='fileToUpload' name='fileToUpload' accept="audio/*" />
                    <input class="prim" type="button" onclick="fileToUpload.click();" value="انتخاب فايل موزيک">
                    <div id="file_name_display" style="margin-top:6px;font-size:12px;color:#333;"></div>
                    <input type='submit' class="dang" id="btn_submit" value="ارسال موزيک" />
                    <br>بعد از انتخاب فايل روي ارسال موزيک کليک کنيد<br>
                    <div id="progress_wrapper" style="display:none;width:90%;background:#eee;border-radius:4px;margin-top:10px;height:14px;overflow:hidden;">
                        <div id="progress_bar" style="width:0%;height:100%;background:#1AB71C;transition:width .15s;"></div>
                    </div>
                    <div id="progress_percent" style="font-size:11px;color:#555;margin-top:2px;"></div>
                </form>
            </div>

            <div id="link_section" style="display:none;">
                <p style="font-size:12px;color:#555;">افزودن آدرس مستقيم موزيک<br>براي اپلود با لينک مستقيم ميتوانيد از www.vip.opload.ir استفاده کنيد</p>
                <input type="text" id="music_link_input" placeholder="http://..." style="width:85%;padding:5px;direction:ltr !important;" />
                <br>
                <input type="button" class="dang" id="btn_link_submit" value="ارسال موزيک" style="margin-top:10px;" />
            </div>

            <div id="upload_status" style="font-weight:bolder;text-shadow:0px 0px 5px #ff0000;margin-top:6px;"></div>
        </center>
        <script>
            (function(){
                var uploadSection = document.getElementById('upload_section');
                var linkSection = document.getElementById('link_section');
                var radios = document.getElementsByName('music_mode');
                var statusBox = document.getElementById('upload_status');

                for (var i = 0; i < radios.length; i++) {
                    radios[i].addEventListener('change', function () {
                        statusBox.textContent = '';
                        if (this.value == 'upload') {
                            uploadSection.style.display = 'block';
                            linkSection.style.display = 'none';
                        } else {
                            uploadSection.style.display = 'none';
                            linkSection.style.display = 'block';
                        }
                    });
                }

                // ---------- بخش آپلود از سيستم ----------
                var fileInput = document.getElementById('fileToUpload');
                var nameDisplay = document.getElementById('file_name_display');
                var form = document.getElementById('music_upload_form');
                var progressWrapper = document.getElementById('progress_wrapper');
                var progressBar = document.getElementById('progress_bar');
                var progressPercent = document.getElementById('progress_percent');
                var submitBtn = document.getElementById('btn_submit');

                fileInput.addEventListener('change', function () {
                    if (fileInput.files && fileInput.files.length > 0) {
                        nameDisplay.textContent = 'فايل انتخاب شده: ' + fileInput.files[0].name;
                    } else {
                        nameDisplay.textContent = '';
                    }
                });

                function showSuccessAndClose(musicUrl) {
                    statusBox.style.color = '#1AB71C';
                    statusBox.textContent = 'موزيک با موفقيت ارسال شد';
                    try {
                        if (window.parent && window.parent.$) {
                            window.parent.$("chatinhalt").innerHTML += 'موزيک شما : <audio controls controls preload="none"><source src="' + musicUrl + '" type="audio/mp3"></audio>';
                            if (window.parent.$windows && window.parent.$windows.music) {
                                setTimeout(function () {
                                    window.parent.$windows.music.close();
                                }, 800);
                            }
                        }
                    } catch (err) {}
                }

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!fileInput.files || fileInput.files.length == 0) {
                        statusBox.style.color = '#c80000';
                        statusBox.textContent = 'ابتدا يک فايل انتخاب کنيد';
                        return;
                    }
                    statusBox.textContent = '';
                    submitBtn.disabled = true;
                    progressWrapper.style.display = 'block';
                    progressBar.style.width = '0%';
                    progressPercent.textContent = '0%';

                    var formData = new FormData();
                    formData.append('fileToUpload', fileInput.files[0]);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    xhr.upload.addEventListener('progress', function (evt) {
                        if (evt.lengthComputable) {
                            var percent = Math.round((evt.loaded / evt.total) * 100);
                            progressBar.style.width = percent + '%';
                            progressPercent.textContent = percent + '%';
                        }
                    });

                    xhr.onload = function () {
                        submitBtn.disabled = false;
                        var res;
                        try {
                            res = JSON.parse(xhr.responseText);
                        } catch (err) {
                            statusBox.style.color = '#c80000';
                            statusBox.textContent = 'خطا در دريافت پاسخ از سرور';
                            return;
                        }
                        if (res.status == 'ok') {
                            progressBar.style.width = '100%';
                            progressPercent.textContent = '100%';
                            showSuccessAndClose(res.url);
                        } else {
                            progressWrapper.style.display = 'none';
                            statusBox.style.color = '#c80000';
                            statusBox.textContent = res.message || 'خطا در آپلود فايل';
                        }
                    };

                    xhr.onerror = function () {
                        submitBtn.disabled = false;
                        progressWrapper.style.display = 'none';
                        statusBox.style.color = '#c80000';
                        statusBox.textContent = 'خطا در ارتباط با سرور';
                    };

                    xhr.send(formData);
                });

                // ---------- بخش ارسال لينک مستقيم (همون رفتار قبلي) ----------
                var linkInput = document.getElementById('music_link_input');
                var linkBtn = document.getElementById('btn_link_submit');

                linkBtn.addEventListener('click', function () {
                    var link = linkInput.value.trim();
                    if (link == '') {
                        statusBox.style.color = '#c80000';
                        statusBox.textContent = 'ورودي نبايد خالي باشد';
                        return;
                    }
                    var token = '';
                    try {
                        token = window.parent.$setting.token;
                    } catch (err) {}

                    linkBtn.disabled = true;
                    statusBox.style.color = '#333';
                    statusBox.textContent = 'در حال ارسال...';

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', <?= json_encode(url('ajax/chat.send')); ?>, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        linkBtn.disabled = false;
                        if (xhr.responseText == 'ok') {
                            showSuccessAndClose(link);
                        } else {
                            statusBox.style.color = '#c80000';
                            statusBox.textContent = xhr.responseText;
                        }
                    };
                    xhr.onerror = function () {
                        linkBtn.disabled = false;
                        statusBox.style.color = '#c80000';
                        statusBox.textContent = 'خطا در ارتباط با سرور';
                    };
                    xhr.send('switch=music&url=' + encodeURIComponent(link) + '&token=' + encodeURIComponent(token));
                });
            })();
        </script>
        <?php } ?>
    </body>
</html>