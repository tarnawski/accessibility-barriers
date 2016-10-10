<?php

namespace AccessibilityBarriersBundle\Notification;

use AccessibilityBarriersBundle\Entity\Area;
use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Notification\Strategies\SendingStrategy;
use AccessibilityBarriersBundle\Repository\NotificationRepository;
use AccessibilityBarriersBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use OAuthBundle\Entity\User;

class SenderEngine
{
    /** @var UserRepository */
    private $userRepository;

    /** @var NotificationRepository */
    private $notificationRepository;

    /** @var StrategiesFactory */
    private $strategiesFactory;

    /** @var EntityManager */
    private $em;

    public function __construct(
        UserRepository $userRepository,
        NotificationRepository $notificationRepository,
        StrategiesFactory $strategiesFactory,
        EntityManager $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
        $this->strategiesFactory = $strategiesFactory;
        $this->em = $entityManager;
    }

    public function distribute()
    {
        $users = $this->userRepository->findAll();
        $notifications = $this->notificationRepository->findAllNotDistributed();

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            /** @var User $user */
            foreach ($users as $user) {
                if ($user->getEmail()) {
                    /** @var SendingStrategy $strategy */
                    $strategy = $this->strategiesFactory->get('email');
                    $strategy->send($user, $notification);
                    $notification->setSend(true);
                    $this->em->persist($notification);
                    $this->em->flush();
                }
            }
        }
    }
}
