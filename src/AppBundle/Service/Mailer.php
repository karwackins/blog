<?php
/**
 * Created by PhpStorm.
 * User: kurs
 * Date: 31.10.18
 * Time: 13:45
 */

namespace AppBundle\Service;


class Mailer
{
    /**
     * @var  \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $recipient - do kogo ma zostac wyslany mejl z potw.
     */

    public function sendConfirmationEmail($recipient)
    {
        $message = \Swift_Message::newInstance();
        $message
            ->setTo($recipient)
            ->setFrom("kurs@alx.yum.pl")
            ->setSubject("Potwierdzenie dodania komentarza")
            ->setBody("Dziekujemy za dodanie komentarza")
            ;

            $this->mailer->send($message);

    }
}