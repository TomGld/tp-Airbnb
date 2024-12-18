<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class Accommodation extends Entity
{
    //functions avec ses getter and setter :

    protected string $title ;
    public function getTitle(){return $this->title;}
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


    protected float $price;

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }


    protected float $surface;

    /**
     * Get the value of surface
     */ 
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set the value of surface
     *
     * @return  self
     */ 
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }


    protected string $description;

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


    protected int $capacity;

    /**
     * Get the value of capacity
     */ 
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set the value of capacity
     *
     * @return  self
     */ 
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    protected int $id_owner;


    /**
     * Get the value of id_owner
     */ 
    public function getId_owner()
    {
        return $this->id_owner;
    }

    /**
     * Set the value of id_owner
     *
     * @return  self
     */ 
    public function setId_owner($id_owner)
    {
        $this->id_owner = $id_owner;

        return $this;
    }

    protected int $id_type;

    /**
     * Get the value of id_type
     */ 
    public function getId_type()
    {
        return $this->id_type;
    }

    /**
     * Set the value of id_type
     *
     * @return  self
     */ 
    public function setId_type($id_type)
    {
        $this->id_type = $id_type;

        return $this;
    }

    protected int $id_address;

    /**
     * Get the value of id_address
     */ 
    public function getId_address()
    {
        return $this->id_address;
    }

    /**
     * Set the value of id_address
     *
     * @return  self
     */ 
    public function setId_address($id_address)
    {
        $this->id_address = $id_address;

        return $this;
    }

    //Liaisons
    //Liaison avec la table address
    protected Addresse $address;
    public function getAddress(): Addresse
    {
        if (!isset($this->address)) {
            $this->address = RepoManager::getRm()->getAddressRepo()->getById($this->id_address);
        }

        return $this->address;
    }



    public function setAddress(Addresse $address): self
    {
        $this->address = $address;
        return $this;
    }

    //Liaison avec la table user
    protected User $owner;
    public function getOwner(): User
    {
        if (!isset($this->owner)) {
            $this->owner = RepoManager::getRm()->getUserRepo()->getById($this->id_owner);
        }

        return $this->owner;
    }

    //Liaison avec la table type
    protected AccommodationType $type;
    public function getType(): AccommodationType
    {
        if (!isset($this->type)) {
            $this->type = RepoManager::getRm()->getAccommodationTypeRepo()->getById($this->id_type);
        }

        return $this->type;
    }


}