<?php

namespace AccessibilityBarriersBundle\Notification;

use AccessibilityBarriersBundle\Entity\Area;
use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Notification\Strategies\SendingStrategy;
use AccessibilityBarriersBundle\Repository\UserRepository;
use OAuthBundle\Entity\User;

class SenderEngine
{
    /** @var UserRepository */
    private $userRepository;

    /** @var StrategiesFactory */
    private $strategiesFactory;

    public function __construct(
        UserRepository $userRepository,
        StrategiesFactory $strategiesFactory
    ) {
        $this->userRepository = $userRepository;
        $this->strategiesFactory = $strategiesFactory;
    }

    public function send(Notification $notification)
    {
        $users = $this->userRepository->findAll();

        /** @var User $user */
        foreach ($users as $user){
            if($user->getEmail()) {
                /** @var SendingStrategy $strategy */
                $strategy = $this->strategiesFactory->get('email');
                $strategy->send($user, $notification);
            }
        }
    }
}