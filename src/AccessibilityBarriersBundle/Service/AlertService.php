<?php

namespace AccessibilityBarriersBundle\Service;

use AccessibilityBarriersBundle\Entity\Alert;
use AccessibilityBarriersBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use OAuthBundle\Entity\User;

class AlertService
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param User $user
     * @param Notification $notification
     * @param $message
     */
    public function create(User $user, Notification $notification, $message)
    {
        $alert = new Alert();
        $alert->setNotification($notification);
        $alert->setUser($user);
        $alert->setActive(true);
        $alert->setCreatedAt(new \DateTime());
        $alert->setMessage($message);
        $this->em->persist($alert);

        $this->em->flush($alert);
    }
}
