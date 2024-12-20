<?php

namespace App\Model\Repository;

use App\Model\Entity\Accommodation;
use Symplefony\Model\Repository;

class AccommodationRepository extends Repository
{
    protected function getTableName(): string { return 'accommodations'; }

    /* Crud: Create */
    public function create( Accommodation $accommodation ): ?Accommodation
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`title`,`price`,`surface`,`description`,`capacity`,`id_owner`,`id_type`,`id_address`) 
                VALUES (:title,:price,:surface,:description,:capacity,:id_owner,:id_type,:id_address)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'title' => $accommodation->getTitle(),
            'price' => $accommodation->getPrice(),
            'surface' => $accommodation->getSurface(),
            'description' => $accommodation->getDescription(),
            'capacity' => $accommodation->getCapacity(),
            'id_owner' => $accommodation->getId_owner(),
            'id_type' => $accommodation->getId_type(),
            'id_address' => $accommodation->getId_address()
        ]);
        
        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $accommodation->setId( $this->pdo->lastInsertId() );

        return $accommodation;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( Accommodation::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?Accommodation
    {
        return $this->readById( Accommodation::class, $id );
    }


    /* crUd: Update */
    public function update( Accommodation $accommodation ): ?Accommodation
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET
                    `title`=:title,
                    `price`=:price,
                    `surface`=:surface,
                    `description`=:description,
                    `capacity`=:capacity,
                    `id_owner`=:id_owner,
                    `id_type`=:id_type
                    `id_address`=:id_address
                WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'title' => $accommodation->getTitle(),
            'price' => $accommodation->getPrice(),
            'surface' => $accommodation->getSurface(),
            'description' => $accommodation->getDescription(),
            'capacity' => $accommodation->getCapacity(),
            'id_owner' => $accommodation->getId_owner(),
            'id_type' => $accommodation->getId_type(),
            'id_address' => $accommodation->getId_address(),
            'id' => $accommodation->getId()
        ]);

        // Si echec de la mise à jour
        if( ! $success ) {
            return null;
        }

        return $accommodation;
    }


}