<?php

namespace App\Service;

use SendGrid\Mail\Mail;

class MailSender
{
    public function send()
    {
        $email = new Mail();
        $email->setFrom("test@example.com", "Example User");
        $email->setSubject("Sending with Twilio SendGrid is Fun");
        $email->addTo("test@example.com", "Example User");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid('SENDGRID_API_KEY');
        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
        } catch (Exception $e) {
            // echo 'Caught exception: '.  $e->getMessage(). "\n";
        }
    }
}