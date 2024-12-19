<?php

namespace App\Model\Repository;

use App\Model\Entity\Reservation;
use Symplefony\Database;
use Symplefony\Model\RepositoryManagerTrait;

class RepoManager
{
    use RepositoryManagerTrait;

    private UserRepository $user_repository;
    public function getUserRepo(): UserRepository { return $this->user_repository; }

    private AddresseRepository $address_repository;
    public function getAddressRepo(): AddresseRepository { return $this->address_repository; }

    private AccommodationRepository $accommodation_repository;
    public function getAccommodationRepo(): AccommodationRepository { return $this->accommodation_repository;}

    private AccommodationTypeRepository $accommodationType_repository;
    public function getAccommodationTypeRepo(): AccommodationTypeRepository { return $this->accommodationType_repository;}

    private ReservationRepository $reservation_repository;
    public function getReservationRepo(): ReservationRepository { return $this->reservation_repository; }


    private function __construct()
    {
        $pdo = Database::getPDO();

        $this->user_repository = new UserRepository( $pdo );
        $this->address_repository = new AddresseRepository( $pdo );
        $this->accommodation_repository = new AccommodationRepository( $pdo );
        $this->accommodationType_repository = new AccommodationTypeRepository( $pdo );
        $this->reservation_repository = new ReservationRepository( $pdo );

    }
}