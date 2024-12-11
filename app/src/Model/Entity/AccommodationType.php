<?php

namespace App\Model\Entity;

use App\Model\Repository\CategoryRepository;
use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class AccommodationType extends Entity
{
    //functions avec ses getter and setter :

    protected string $label;

        /**
     * Get the value of label
     */ 
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @return  self
     */ 
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }





}