<?php

$method = $_SERVER['REQUEST_METHOD'];
$c = true;

if ($method === 'POST') {

    $project_name = trim($_POST["project_name"]);
    $admin_email  = "designdharmm@gmail.com";  // your email
    $form_subject = trim($_POST["form_subject"]);

    $message = '';
    $postData = [];

    foreach ($_POST as $key => $value) {
        if ($value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject") {
            $message .= "
            " . (($c = !$c) ? '<tr>' : '<tr style="background-color: #f8f8f8;">') . "
                <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
                <td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
            </tr>
            ";
            $postData[$key] = $value;
        }
    }

    // Send to Google Sheets
    $scriptURL = "YOUR_SCRIPT_URL"; // Replace this with your deployed Google Apps Script URL
    $ch = curl_init($scriptURL);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

$message = "<table style='width: 100%;'>$message</table>";

function adopt($text) {
    return '=?UTF-8?B?' . Base64_encode($text) . '?=';
}

$headers = "MIME-Version: 1.0" . PHP_EOL .
"Content-Type: text/html; charset=utf-8" . PHP_EOL .
'From: ' . adopt($project_name) . ' <' . $admin_email . '>' . PHP_EOL .
'Reply-To: ' . $admin_email . '' . PHP_EOL;

mail($admin_email, adopt($form_subject), $message, $headers);
