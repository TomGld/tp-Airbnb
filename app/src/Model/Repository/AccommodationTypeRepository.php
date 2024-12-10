<?php

namespace App\Model\Repository;

use App\Model\Entity\AccommodationType;
use Symplefony\Model\Repository;

class AccommodationTypeRepository extends Repository
{
    protected function getTableName(): string { return 'accommodation_types'; }

    /* Crud: Create */
    public function create( AccommodationType $accommodationType ): ?AccommodationType
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
            'label' => $accommodationType->getLabel(),
        ]);

        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        $accommodationType->setId( $this->pdo->lastInsertId() );

        return $accommodationType;
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( AccommodationType::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?AccommodationType
    {
        return $this->readById( AccommodationType::class, $id );
    }

    /* crUd: Update */
    public function update( AccommodationType $accommodationType ): ?AccommodationType
    {
        $query = sprintf(
            'UPDATE `%s` 
                SET
                    `label`=:label,
                WHERE id=:id',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }

        $success = $sth->execute([
            'label' => $accommodationType->getLabel(),
            'id' => $accommodationType->getId()
        ]);

        // Si echec de la mise à jour
        if( ! $success ) {
            return null;
        }

        return $accommodationType;
    }

    /* cruD: Delete */
    public function deleteOne(int $id): bool
    {
        // On supprime d'abord toutes les liaisons avec les catégories
        $success = RepoManager::getRM()->getCategoryRepo()->detachAllForCar( $id );

        // Si cela a fonctionné on invoke la méthode deleteOne parente
        if( $success) {
            $success = parent::deleteOne( $id );
        }

        return $success;
    }
}