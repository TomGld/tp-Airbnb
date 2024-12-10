<?php

namespace App\Model\Repository;

use Symplefony\Database;
use Symplefony\Model\RepositoryManagerTrait;

class RepoManager
{
    use RepositoryManagerTrait;

    private UserRepository $user_repository;
    public function getUserRepo(): UserRepository { return $this->user_repository; }

    private CategoryRepository $category_repository;
    public function getCategoryRepo(): CategoryRepository { return $this->category_repository; }

    private CarRepository $car_repository;
    public function getCarRepo(): CarRepository { return $this->car_repository; }

    private AddressRepository $address_repository;
    public function getAddressRepo(): AddressRepository { return $this->address_repository; }

    private AccommodationRepository $accommodation_repository;
    public function getAccommodationRepo(): AccommodationRepository { return $this->accommodation_repository;}

    private AccommodationTypeRepository $accommodationType_repository;
    public function getAccommodationTypeRepo(): AccommodationTypeRepository { return $this->accommodationType_repository;}


    private function __construct()
    {
        $pdo = Database::getPDO();

        $this->user_repository = new UserRepository( $pdo );
        $this->category_repository = new CategoryRepository( $pdo );
        $this->car_repository = new CarRepository( $pdo );
        $this->address_repository = new AddressRepository( $pdo );
        $this->accommodation_repository = new AccommodationRepository( $pdo );
        $this->accommodationType_repository = new AccommodationTypeRepository( $pdo );

    }
}