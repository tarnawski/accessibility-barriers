<?php

namespace AccessibilityBarriersBundle\Entity;

class Image
{
    /** @var integer */
    private $id;

    /** @var string */
    private $original;

    /** @var string */
    private $thumbnail;

    /** @var Notification */
    private $notification;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @param string $original
     */
    public function setOriginal($original)
    {
        $this->original = $original;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }
}
