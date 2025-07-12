<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'anonymousz.sender111@gmail.com';
$mail->Password = 'shhgavwmgjxxckme'; // App password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('anonymousz.sender111@gmail.com', 'Test Mail');
$mail->addAddress('febriadi060204@gmail.com'); // Ganti email tujuan

$mail->isHTML(true);
$mail->Subject = 'Tes Email';
$mail->Body = 'Ini email tes dari PHPMailer';

$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';

try {
  $mail->send();
  echo "Email berhasil dikirim";
} catch (Exception $e) {
  echo "Gagal kirim email: {$mail->ErrorInfo}";
}
