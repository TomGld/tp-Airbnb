<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\Addresse;

class AddresseRepository extends Repository
{
    protected function getTableName(): string { return 'addresses'; }

    /* Crud: Create */
    public function create( Addresse $address ): ?Addresse
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`number_street`,`street`,`city`,`country`) 
                VALUES (:number_street, :street, :city,:country)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'number_street' => $address->getNumber_street(),
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'country' => $address->getCountry()
        ]);

        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $address->setId( $this->pdo->lastInsertId() );

        return $address;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( Addresse::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?Addresse
    {
        return $this->readById( Addresse::class, $id );
    }

    /* crUd: Update */
    public function update( Addresse $address ): ?Addresse
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET
                    `city`=:city,
                    `country`=:country
                WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'id' => $address->getId()
        ]);

        // Si echec de la mise à jour
        if( ! $success ) {
            return null;
        }

        return $address;
    }
}