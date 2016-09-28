<?php

namespace AccessibilityBarriersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    /** @var integer */
    private $id;

    /** @var ArrayCollection|Notification */
    private $notifications;

    /** @var ArrayCollection|Comment */
    private $comments;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Notification
     * @return Notification
     */
    public function addNotification(Notification $notification)
    {
        if (!$this->notifications->contains($notification)) {
            $notification->setUser($this);
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

    /**
     * @param Comment
     * @return Comment
     */
    public function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $comment->setUser($this);
            $this->comments[] = $comment;
        }
        return $this;
    }

    /**
     * @param Comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}