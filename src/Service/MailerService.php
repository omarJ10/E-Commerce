<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService{
    private $replyTo;
    private $mailer;
    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }
    public function sendEmail(
        $to = 'jalledomar2001@gmail.com',
        $content,
        $subject = 'Time for Symfony Mailer!'
    ): void
    {
        $email = (new Email())
            ->from('omarecommerce11@gmail.com@example.com')
            ->to($to)
            ->subject($subject)
            ->html($content);
             $this->mailer->send($email);
    }
}
