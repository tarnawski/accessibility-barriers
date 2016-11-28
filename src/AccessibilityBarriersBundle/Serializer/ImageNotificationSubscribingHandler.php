<?php

namespace AccessibilityBarriersBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use AccessibilityBarriersBundle\Entity\Notification;

class ImageNotificationSubscribingHandler implements EventSubscriberInterface
{
    private $imageUrl;

    public function __construct($imageUrl)
    {
        $this->imageUrl = $imageUrl;
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

        if ($this->hasGroup($event, 'NOTIFICATION_BASIC')) {
            return;
        }

        $image = $notification->getImage() ? sprintf('%s/%s', $this->imageUrl, $notification->getImage()->getName()) : null;
        $visitor->addData('image', $image);
    }

    private function hasGroup(ObjectEvent $event, $group)
    {
        $groups = $event->getContext()->attributes->get('groups')->get('value');

        return in_array($group, $groups);
    }
}
