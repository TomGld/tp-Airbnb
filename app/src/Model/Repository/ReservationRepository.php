<?php

namespace App\Model\Repository;

use App\Model\Entity\Reservation;
use Symplefony\Model\Repository;

class ReservationRepository extends Repository
{
    protected function getTableName(): string { return 'reservation'; }

    /* Crud: Create */
    public function create( Reservation $rental ): ?Reservation
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`id_accommodation`, `id_customer`, `date_from`, `date_to`) 
                VALUES (:id_accommodation, :id_customer, :dateFrom, :dateTo)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'id_accommodation' => $rental->getId_accommodation(),
            'id_customer' => $rental->getId_customer(),
            'date_from' => $rental->getDateFrom(),
            'date_to' => $rental->getDateTo()
        ]);
        
        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $rental->setId( $this->pdo->lastInsertId() );

        return $rental;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( Reservation::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?Reservation
    {
        return $this->readById( Reservation::class, $id );
    }


}