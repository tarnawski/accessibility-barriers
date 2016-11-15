<?php

namespace AccessibilityBarriersBundle\Serializer;

use AccessibilityBarriersBundle\Entity\Rating;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use AccessibilityBarriersBundle\Entity\Notification;

class RatingNotificationSubscribingHandler implements EventSubscriberInterface
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

        if ($this->hasGroup($event, 'NOTIFICATION_BASIC')) {
            return;
        }

        if (count($notification->getRatings()) === 0) {
            $rating = [
                'average' => 0,
                'count' => 0
            ];
        } else {
            $sum = 0;
            /** @var Rating $rating */
            foreach ($notification->getRatings() as $rating) {
                $sum = $sum + $rating->getValue();
            }

            $rating = [
                'average' => $sum / count($notification->getRatings()),
                'count' => count($notification->getRatings())
            ];
        }

        $visitor->addData('rating', $rating);
    }

    private function hasGroup(ObjectEvent $event, $group)
    {
        $groups = $event->getContext()->attributes->get('groups')->get('value');

        return in_array($group, $groups);
    }
}
