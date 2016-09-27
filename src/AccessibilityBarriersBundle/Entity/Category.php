<?php

namespace AccessibilityBarriersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Category
{
    /** @var integer */
    private $id;

    /** @var string */
    private $name;

    /** @var  ArrayCollection|Issue[] */
    private $issues;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
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
     * @param Issue
     * @return Issue
     */
    public function addIssue(Issue $issue)
    {
        if (!$this->issues->contains($issue)) {
            $issue->setCategory($this);
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
}