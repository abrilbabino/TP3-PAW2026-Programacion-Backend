<?php

namespace Paw\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    public function enviarConfirmacionReserva($destinatario, $datosReserva) {
        global $config;
        global $log;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $config->get('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = $config->get('MAIL_USER');
            $mail->Password   = $config->get('MAIL_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $config->get('MAIL_PORT');

            $mail->setFrom($config->get('MAIL_USER'), 'PawPrints');
            $mail->addAddress($destinatario);

            $mail->isHTML(false);
            $mail->Subject = "Nueva Reserva: " . $datosReserva['libro'];
            $mail->Body    = "Reserva de: " . $datosReserva['nombre'] . "\n" .
                             "Email: " . $datosReserva['email'] . "\n" .
                             "Cantidad: " . $datosReserva['cantidad'];

            $mail->send();
            return true;
        } catch (Exception $e) {
            $log->error("Error SMTP: {$mail->ErrorInfo}");
            return false;
        }
    }
}