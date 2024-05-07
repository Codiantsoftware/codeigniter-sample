<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/PHPMailer/PHPMailer.php';
require_once APPPATH . 'third_party/PHPMailer/SMTP.php';
require_once APPPATH . 'third_party/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('send_email')) {    

    /**
     * Method send_email
     *
     * @param $email      $email 
     * @param $subject    $subject 
     * @param $content    $content 
     * @param $attachment $attachment 
     *
     * @return array
     */
    function send_email($email, $subject, $content, $attachment = "")
    {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host     = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->Port     = SMTP_PORT;
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->CharSet = "text/html; charset=UTF-8;";
            $mail->isHTML(true);
            

            $mail->Body = $content;
            $mail->send();

            $response = [
                'error' => false,
                'message' => 'Email sent successfully!'
            ];
        } catch (Exception $e) {
            $response = [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
        return $response;
    }
}

if (!function_exists('send_notification')) {
        
    /**
     * Method send_notification
     *
     * @param $message  $message 
     * @param $title    $title 
     * @param $fcmArray $fcmArray 
     *
     * @return bool
     */
    function send_notification($message, $title, $fcmArray)
    {
        $path_to_fcm = "https://fcm.googleapis.com/fcm/send";
        $server_key = FCM_SERVER_KEY;
        $headers = array(
            'Authorization:key=' . $server_key,
            'Content-Type:application/json',
            'Content-Transfer-Encoding: binary'
        );

        $fields = array(
            'registration_ids' => $fcmArray,
            'notification' => array(
                'body' => $message,
                'title' => $title,
                'vibrate' => 1,
                'foreground' => true,
                'coldstart' => true,
                "android_channel_id" => "happysmile",
                'click_action' => "NavigationHostActivity"
            ),
        );


        $payload = json_encode($fields);
        $curl_session = curl_init();
        curl_setopt($curl_session, CURLOPT_URL, $path_to_fcm);
        curl_setopt($curl_session, CURLOPT_POST, true);
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

        $result = curl_exec($curl_session);
        $result = json_decode($result);
        curl_close($curl_session);
        if ($result) {
            if (!$result->success) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
