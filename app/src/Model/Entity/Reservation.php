<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class Reservation extends Entity
{
    //functions avec ses getter and setter :

    //id du bien
    protected int $id_accommodation;
    public function getId_accommodation(){return $this->id_accommodation;}
    public function setId_accommodation($id_accommodation){
        $this->id_accommodation = $id_accommodation;
        return $this;
    }

    //id du propriétaire
    protected int $id_customer;
    public function getId_customer(){return $this->id_customer;}
    public function setId_customer($id_customer){
        $this->id_customer = $id_customer;
        return $this;
    }


    //Date de début de location
    protected string $date_from;
    public function getDateFrom(){return $this->date_from;}
    public function setDateFrom($dateFrom){
        $this->date_from = $dateFrom;
        return $this;
    }

    //Date de fin de location
    protected string $date_to;
    public function getDateTo(){return $this->date_to;}
    public function setDateTo($dateTo){
        $this->date_to = $dateTo;
        return $this;

    }

    //Liaison avec la table accommodation
    protected Accommodation $accommodation;
    public function getAccommodation(){
        if( ! isset( $this->accommodation ) ) {
            $this->accommodation = RepoManager::getRM()->getAccommodationRepo()->getById( $this->id_accommodation );
        }
        return $this->accommodation;
    }
    public function setAccommodation( Accommodation $accommodation ){
        $this->accommodation = $accommodation;
        return $this;
    }

    //get accommodation by id_accommodation depuis l'id de la reservation
    public function getAccommodationByIdRerservation(){
        return RepoManager::getRM()->getAccommodationRepo()->getById( $this->id_accommodation );
    }

    //Liaison avec la table user
    protected User $customer;
    public function getCustomer(){
        if( ! isset( $this->customer ) ) {
            $this->customer = RepoManager::getRM()->getUserRepo()->getById( $this->id_customer );
        }
        return $this->customer;
    }
    public function setCustomer( User $customer ){
        $this->customer = $customer;
        return $this;
    }

    //Récupérer les accommodations du propriétaire quand l'on transmet id_accommodation depuis la réservation
    public function getCustomerByIdReservation(){
        return RepoManager::getRM()->getUserRepo()->getById( $this->id_customer );
    }

    

}