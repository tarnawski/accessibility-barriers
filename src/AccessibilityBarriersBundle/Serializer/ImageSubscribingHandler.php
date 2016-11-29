<?php

namespace AccessibilityBarriersBundle\Serializer;

use AccessibilityBarriersBundle\Entity\Image;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class ImageSubscribingHandler implements EventSubscriberInterface
{
    private $imageUrl;

    public function __construct($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public static function getSubscribedEvents()
    {
        return [
            ['event' => 'serializer.post_serialize', 'method' => 'myOnPostSerializeMethod', 'class' => Image::class]
        ];
    }

    public function myOnPostSerializeMethod(ObjectEvent $event)
    {
        /** @var Image $image */
        $image = $event->getObject();
        $visitor = $event->getVisitor();

        $visitor->addData('original', sprintf('%s/%s', $this->imageUrl, $image->getOriginal()));
        $visitor->addData('thumbnail', sprintf('%s/%s', $this->imageUrl, $image->getThumbnail()));
    }
}
