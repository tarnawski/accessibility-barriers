<?php

namespace AccessibilityBarriersBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use AccessibilityBarriersBundle\Entity\Area;

class AreaCoordinateSubscribingHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_serialize', 'method' => 'myOnPostSerializeMethod', 'class' => Area::class]
        ];
    }

    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        /** @var Area $area */
        $area = $event->getObject();
        $visitor = $event->getVisitor();

        $coordinates = [
            'latitude' => $area->getLatitude(),
            'longitude' => $area->getLongitude()
        ];

        $visitor->addData('coordinates', $coordinates);
    }
}
