<?php

namespace App\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class CustomMailer {

    protected $subject;

    protected $to = [];

    public function __construct()
    {
        $this->subject = 'Surat Bebas Lab';
    }

    public function to($users)
    {
        array_push($this->to, $users);

        return $this;
    }

    public function subject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function send($view, $data = [])
    {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->SMTPDebug = false;
                $mail->Host = $_ENV['MAIL_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['MAIL_USERNAME'];
                $mail->Password = $_ENV['MAIL_PASSWORD'];
                $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
                $mail->Port = $_ENV['MAIL_PORT'];
                $mail->setFrom($_ENV['MAIL_USERNAME'], 'Surat Bebas Lab');

                $mail->SMTPOptions= array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                foreach($this->to as $to) {
                    $mail->addAddress($to);
                }

                $mail->isHTML(true);
                $mail->Subject = $this->subject;

                if(is_string($view))
                    $mail->Body = view($view, $data)->render();

                $mail->send();
            }
            catch (PHPMailerException $err) {
                echo 'Gagal mengirim email';
            } catch (\Throwable $e) {
                //
            }
    }

}