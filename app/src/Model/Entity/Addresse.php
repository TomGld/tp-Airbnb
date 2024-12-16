<?php

namespace App\Model\Entity;

use Symplefony\Model\Entity;

class Addresse extends Entity
{

    protected string $number_street;
    /**
     * Get the value of number_street
     */ 
    public function getNumber_street()
    {
        return $this->number_street;
    }

    /**
     * Set the value of street
     *
     * @return  self
     */ 
    public function setNumber_street($number_street)
    {
        $this->number_street = $number_street;

        return $this;
    }


    protected string $street;
        /**
     * Get the value of street
     */ 
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     *
     * @return  self
     */ 
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }
    

    protected string $city;
    public function getCity(): string { return $this->city; }
    public function setCity( string $city ): self 
    {
        $this->city = $city;
        return $this;
    }

    protected string $country;
    public function getCountry(): string { return $this->country; }
    public function setCountry( string $country ): self 
    {
        $this->country = $country;
        return $this;
    }


}