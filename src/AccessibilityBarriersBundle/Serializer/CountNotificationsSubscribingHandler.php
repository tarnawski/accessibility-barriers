<?php

namespace AccessibilityBarriersBundle\Serializer;

use AccessibilityBarriersBundle\Entity\Category;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class CountNotificationsSubscribingHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_serialize', 'method' => 'myOnPostSerializeMethod', 'class' => Category::class]
        ];
    }

    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        /** @var Category $category */
        $category = $event->getObject();
        $visitor = $event->getVisitor();

        $notificationCount = count($category->getNotifications());

        $visitor->addData('notification_count', $notificationCount);
    }
}
