<?php

namespace App\Service;

use SendGrid\Mail\Mail;

class MailSender
{
    public function __construct(private string $env)
    {
        $this->apiKey = $env;
    }

    public function send()
    {
        $email = new Mail();
        $email->setFrom("imagines.and.words@gmail.com", "Tino");
        $email->setSubject("Sending with Twilio SendGrid is Fun");
        $email->addTo("tino_zorotovic@epam.com", "Tino");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        $sendgrid = new \SendGrid($this->apiKey);
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