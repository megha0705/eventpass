<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/load-env.php';

load_env(__DIR__ . '/.env');
// Sends one email to each person. Returns ['sent' => n, 'failed' => n, 'error' => last error text]
function send_bulk_email($recipients, $subject, $body) {
  $result = ['sent' => 0, 'failed' => 0, 'error' => ''];

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
    $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';       // the 16-character app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->SMTPKeepAlive = true;                     // reuse one connection for all emails
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($_ENV['MAIL_USERNAME'] ?? '', 'EventHub');
    $mail->Subject = $subject;
    $mail->Body    = $body;                          // plain text
  } catch (Exception $e) {
    $result['failed'] = count($recipients);
    $result['error']  = $mail->ErrorInfo;
    return $result;
  }

  foreach ($recipients as $person) {
    try {
      $mail->clearAddresses();
      $mail->addAddress($person['email'], $person['name']);
      $mail->send();
      $result['sent']++;
    } catch (Exception $e) {
      $result['failed']++;
      $result['error'] = $mail->ErrorInfo;
    }
  }

  $mail->smtpClose();
  return $result;
}