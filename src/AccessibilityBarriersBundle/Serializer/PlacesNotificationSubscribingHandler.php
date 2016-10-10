<?php

namespace AccessibilityBarriersBundle\Serializer;

use AccessibilityBarriersBundle\Entity\Rating;
use AccessibilityBarriersBundle\Service\GooglePlacesService;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use AccessibilityBarriersBundle\Entity\Notification;

class PlacesNotificationSubscribingHandler implements EventSubscriberInterface
{
    /** @var GooglePlacesService */
    private $placesService;

    public function __construct(GooglePlacesService $googlePlacesService)
    {
        $this->placesService = $googlePlacesService;
    }

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
        $address = $this->placesService->getPlaceName($notification->getLatitude(), $notification->getLongitude());

        $visitor->addData('address', $address);
    }
}
