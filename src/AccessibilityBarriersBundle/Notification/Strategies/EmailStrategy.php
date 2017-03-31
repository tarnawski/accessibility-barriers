<?php

namespace AccessibilityBarriersBundle\Notification\Strategies;

use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Entity\Subscribe;
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

    public function sendToUser(User $user, Notification $notification)
    {
        $message = new \Swift_Message();
        $message->setSubject('Nowa notyfikacja');
        $message->setFrom('rzeszowbezbarier@ttarnawski.usermd.net');
        $message->setTo($user->getEmail());
        $message->setBody(
            $this->twig->render(
                'Emails/user.html.twig',
                [
                    'name' => $notification->getName(),
                    'description' => $notification->getDescription(),
                    'address' => $notification->getAddress(),
                    'id' => $notification->getId()
                ]
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }

    public function sendToSubscriber(Subscribe $subscribe, Notification $notification)
    {
        $message = new \Swift_Message();
        $message->setSubject('Nowa notyfikacja');
        $message->setFrom('rzeszowbezbarier@ttarnawski.usermd.net');
        $message->setTo($subscribe->getEmail());
        $message->setBody(
            $this->twig->render(
                'Emails/subscriber.html.twig',
                [
                    'name' => $notification->getName(),
                    'description' => $notification->getDescription(),
                    'address' => $notification->getAddress(),
                    'id' => $notification->getId(),
                    'secret' => $subscribe->getSecret()
                ]
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
}
