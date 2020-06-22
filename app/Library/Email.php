<?php

declare(strict_types=1);

namespace App\Library;

use App\Library\Config;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    private $mailer;
    public function __construct()
    {
        $this->mailer = new PHPMailer();
        //Server settings
        $this->mailer->isSMTP();                                    // Set mailer to use SMTP
        $this->mailer->SMTPAuth     = true;                         // Enable SMTP authentication
        $this->mailer->Host         = getenv('EMAIL_HOST'); // Specify main and backup SMTP servers
        $this->mailer->Username     = getenv('EMAIL_USER'); // SMTP username
        $this->mailer->Password     = getenv('EMAIL_PW');   // SMTP password
        $this->mailer->SMTPSecure   = getenv('SMTP_PROTOCOL');  // Enable TLS encryption, `ssl` also accepted
        $this->mailer->Port         = getenv('EMAIL_PORT');
        $this->mailer->setFrom(getenv('EMAIL_FROM'), Config::get('company_name'));
        $this->mailer->SMTPOptions = array(
            'ssl' => array(
                'allow_self_signed' => true
            )
        );
    }
    public function sendEmail($to, $name, $subject, $message, $data)
    {
        //Recipients
        $this->mailer->addAddress($to, $name);  // Add a recipient
        // Content
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $message['html']->render($data);
        $this->mailer->AltBody = $message['plain']->render($data);
        if (!$this->mailer->send()) {
            return false;
        }
        return true;
    }

    public function sendEmaildynamic($to, $name, $subject,  $data)
    {
        //Recipients
        $this->mailer->addAddress($to, $name);  // Add a recipient
        // Content
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $data;
        $this->mailer->AltBody = '';
        if (!$this->mailer->send()) {
            return false;
        }
        return true;
    }

    public function sendEmailAttachment($to, $name, $subject, $message, $is_attachment = array())
    {
        //Recipients
        $this->mailer->addAddress($to, $name);  // Add a recipient

        // Content
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $message['html']->render(['test']);
        $this->mailer->AltBody = $message['plain']->render(['test']);
        if (isset($is_attachment) && !empty($is_attachment))
            $this->mailer->addAttachment($is_attachment['path'], $is_attachment['name'], $is_attachment['encoding'], $is_attachment['type']);

        if (!$this->mailer->send()) {
            return false;
        }
        return true;
    }
}
