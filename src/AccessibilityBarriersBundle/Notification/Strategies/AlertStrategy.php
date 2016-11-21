<?php

namespace AccessibilityBarriersBundle\Notification\Strategies;

use AccessibilityBarriersBundle\Entity\Alert;
use AccessibilityBarriersBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use OAuthBundle\Entity\User;

class AlertStrategy implements SendingStrategy
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function send(User $user, Notification $notification)
    {
        $alert = new Alert();
        $alert->setNotification($notification);
        $alert->setUser($user);
        $alert->setActive(true);
        $alert->setCreatedAt(new \DateTime());
        $this->em->persist($alert);
        $this->em->flush($alert);
    }
}
