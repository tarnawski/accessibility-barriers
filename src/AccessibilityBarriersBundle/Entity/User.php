<?php

namespace AccessibilityBarriersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    /** @var integer */
    private $id;

    /** @var ArrayCollection|Issue */
    private $issues;

    /** @var ArrayCollection|Comment */
    private $comments;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
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
     * @param Issue
     * @return Issue
     */
    public function addIssue(Issue $issue)
    {
        if (!$this->issues->contains($issue)) {
            $issue->setUser($this);
            $this->issues[] = $issue;
        }
        return $this;
    }

    /**
     * @param Issue
     */
    public function removeIssue(Issue $issue)
    {
        $this->issues->removeElement($issue);
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
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