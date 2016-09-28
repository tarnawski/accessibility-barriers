<?php

namespace AccessibilityBarriersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Category
{
    /** @var integer */
    private $id;

    /** @var string */
    private $name;

    /** @var  ArrayCollection|Notification[] */
    private $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param Notification
     * @return Notification
     */
    public function addNotification(Notification $notification)
    {
        if (!$this->notifications->contains($notification)) {
            $notification->setCategory($this);
            $this->notifications[] = $notification;
        }
        return $this;
    }

    /**
     * @param Notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}