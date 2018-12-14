<?php
namespace App\DTO;

class createCompetition
{
    public $name;
    public $homeVisitor;
    public $location;
    public $tag = [];
    public $competitor = [];
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getHomeVisitor()
    {
        return $this->homeVisitor;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return multitype:
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return multitype:
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $homeVisitor
     */
    public function setHomeVisitor($homeVisitor)
    {
        $this->homeVisitor = $homeVisitor;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param multitype: $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @param multitype: $competitor
     */
    public function setCompetitor($competitor)
    {
        $this->competitor = $competitor;
    }

    
    
}

