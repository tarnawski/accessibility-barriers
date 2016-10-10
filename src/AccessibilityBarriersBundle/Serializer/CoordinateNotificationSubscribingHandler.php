<?php

namespace AccessibilityBarriersBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use AccessibilityBarriersBundle\Entity\Notification;

class CoordinateNotificationSubscribingHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_serialize', 'method' => 'myOnPostSerializeMethod', 'class' => Notification::class]
        ];
    }

    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        /** @var Notification $notification */
        $notification = $event->getObject();
        $visitor = $event->getVisitor();

        $coordinates = [
            'latitude' => $notification->getLatitude(),
            'longitude' => $notification->getLongitude()
        ];

        $visitor->addData('coordinates', $coordinates);
    }
}
