<?php

namespace AccessibilityBarriersBundle\Notification;

use AccessibilityBarriersBundle\Notification\Strategies\SendingStrategy;

class StrategiesFactory
{

    private $emailStrategy;
    private $alertStrategy;
    private $smsStrategy;

    const EMAIL_STRATEGY = 'email';
    const ALERT_STRATEGY = 'alert';
    const SMS_STRATEGY = 'sms';

    public function __construct(
        SendingStrategy $emailStrategy,
        SendingStrategy $alertStrategy,
        SendingStrategy $smsStrategy
    ) {
        $this->emailStrategy = $emailStrategy;
        $this->alertStrategy = $alertStrategy;
        $this->smsStrategy = $smsStrategy;
    }

    public function get($method)
    {
        switch ($method) {
            case self::EMAIL_STRATEGY:
                return $this->emailStrategy;
                break;
            case self::ALERT_STRATEGY:
                return $this->alertStrategy;
                break;
            case self::SMS_STRATEGY:
                return $this->smsStrategy;
                break;
            default:
                return $this->emailStrategy;
        }
    }
}
