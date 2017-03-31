<?php

namespace AccessibilityBarriersBundle\Notification\Strategies;

use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Entity\Subscribe;
use OAuthBundle\Entity\User;

interface SendingStrategy
{
    /**
     * @param User $user
     * @param Notification $notification
     * @return mixed
     */
    public function sendToUser(User $user, Notification $notification);

    /**
     * @param Subscribe $subscribe
     * @param Notification $notification
     * @return mixed
     */
    public function sendToSubscriber(Subscribe $subscribe, Notification $notification);
}
