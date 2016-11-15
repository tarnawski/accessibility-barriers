<?php

namespace AccessibilityBarriersBundle\Notification\Strategies;

use AccessibilityBarriersBundle\Entity\Notification;
use OAuthBundle\Entity\User;

class EmailStrategy implements SendingStrategy
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $mailer;

    public function __construct(
        \Twig_Environment $twig,
        $mailer
    ) {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function send(User $user, Notification $notification)
    {
        $message = new \Swift_Message();
        $message->setSubject('Nowa notyfikacja');
        $message->setFrom('tarnawski@go2.pl');
        $message->setTo($user->getEmail());
        $message->setBody(
            $this->twig->render(
                'Emails/notification.html.twig',
                [
                    'name' => $notification->getName(),
                    'description' => $notification->getDescription()
                ]
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
}
