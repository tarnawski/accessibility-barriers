<?php

namespace AccessibilityBarriersBundle\Notification\Strategies;

use AccessibilityBarriersBundle\Entity\Notification;
use OAuthBundle\Entity\User;

interface SendingStrategy
{
    /**
     * @param User $user
     * @param Notification $notification
     * @return mixed
     */
    public function send(User $user, Notification $notification);
}