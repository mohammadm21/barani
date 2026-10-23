<?php
$chat_id = "-1001453688163";

function sendMessage($chat_id, $text)
{
    $url = "https://oflinemessage.mohammadelyasi21.workers.dev/?chat_id=" .
        urlencode($chat_id) .
        "&text=" .
        urlencode($text);

    @file_get_contents($url);
}

function tlg($text, $id)
{
    global $chat_id;

    $text .= "\nچتروم بزرگ بارانی چت\n@baranichat";

    if ($id == "chat") {

        $adminId = explode(',', $chat_id);

        if (count($adminId) == 1) {

            sendMessage(trim($chat_id), $text);

        } else {

            foreach ($adminId as $chatid) {

                sendMessage(trim($chatid), $text);

            }

        }

    } elseif ($id == "designer") {

        $tarhpardaz_id = '';

        if (!empty($tarhpardaz_id)) {
            sendMessage(trim($tarhpardaz_id), $text);
        }

    }
}
?>