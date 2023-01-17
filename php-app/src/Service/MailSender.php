<?php

namespace App\Service;

use SendGrid\Mail\Mail;

class MailSender
{
    public function __construct(private string $env)
    {
        $this->apiKey = $env;
    }

    public function send(string $to, string $subject, string $body)
    {
        $email = new Mail();
        $email->setFrom("imagines.and.words@gmail.com", "Tino");
        $email->setSubject($subject);
        $email->addTo($to, strtoupper($to));
        $email->addContent("text/plain", $body);
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