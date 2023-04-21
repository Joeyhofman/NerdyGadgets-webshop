<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include dirname(__FILE__).'/../../vendor/autoload.php';

function sendMail($subject, $recipient,  $emailTemplate, $data=[]){
    $mail = new PHPMailer();
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username ='8c9dc4d9440dd8';
        $mail->Password = 'cff72d2065c04d';
        $mail->SMTPSecure = true;
        $mail->Port = 2525;

        $mail->setFrom('no-reply@nerdygadgets.com', 'NerdyGadgets');
        $mail->addAddress($recipient);

        ob_start();
        $data = $data;
        require dirname(__FILE__).'/../../emails/'.$emailTemplate.".php";
        $html = ob_get_clean();

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $html;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}