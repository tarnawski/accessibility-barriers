<?php

namespace OAuthBundle\Entity;

use AccessibilityBarriersBundle\Entity\Area;
use AccessibilityBarriersBundle\Entity\Comment;
use AccessibilityBarriersBundle\Entity\Notification;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

class User extends BaseUser
{
    const ROLE_API = 'ROLE_API';

    public static $ROLES = array(
        self::ROLE_API => self::ROLE_API
    );

    /** @var integer */
    protected $id;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var  ArrayCollection|Notification[] */
    private $notifications;

    /** @var  ArrayCollection|Comment[] */
    private $comments;

    /** @var  ArrayCollection|Area[] */
    private $areas;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->areas = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification $notification
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
     * @param Notification $notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
 * @return ArrayCollection
 */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
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
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }


    /**
     * @return ArrayCollection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @param Area $area
     * @return Area
     */
    public function addArea(Area $area)
    {
        if (!$this->areas->contains($area)) {
            $area->setUser($this);
            $this->areas[] = $area;
        }
        return $this;
    }

    /**
     * @param Area $area
     */
    public function removeArea(Area $area)
    {
        $this->areas->removeElement($area);
    }
}
