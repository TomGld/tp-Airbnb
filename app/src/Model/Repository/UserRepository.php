<?php

namespace App\Model\Repository;

use Symplefony\Model\Repository;

use App\Model\Entity\User;

class UserRepository extends Repository
{
    protected function getTableName(): string { return 'users'; }

    /* Crud: Create */
    public function create( array $user ): ?User
    {
        $query = sprintf(
            'INSERT INTO `%s` 
                (`email`,`password`,`lastname`,`firstname`,`phone_number`, `id_role`)
                VALUES (:email,:password,:lastname,:firstname,:phone_number,:id_role)',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare( $query );

        // Si la préparation échoue
        if( ! $sth ) {
            return null;
        }
        $success = $sth->execute($user);

        // Si echec de l'insertion
        if( ! $success ) {
            return null;
        }

        // Ajout de l'id de l'item créé en base de données
        return $this->getById( (int) $this->pdo->lastInsertId() );
    }

    /* cRud: Read tous les items */
    public function getAll(): array
    {
        return $this->readAll( User::class );
    }

    /* cRud: Read un item par son id */
    public function getById( int $id ): ?User
    {
        return $this->readById( User::class, $id );
    }


    /* cruD: Delete */
    public function deleteOne(int $id): bool
    {
        // On récupère l'user pour pouvoir connaîre son address_id 
        $user = $this->getById( $id );

        if( is_null( $user ) ) {
            return false;
        }

        $addressRepo = RepoManager::getRM()->getAddressRepo();
        // On récupère l'addresse
        $address = $addressRepo->getById( $user->getAddressId() );

        if( is_null( $address ) ) {
            return false;
        }

        // On supprime l'user
        $success = parent::deleteOne( $id );

        // Si cela a fonctionné, on supprimé l'addresse liée
        if( $success ) {
            $success = $addressRepo->deleteOne( $address->getId());
        }

        return $success;
    }


    /**
     * Valide les données d'authentification
     *
     * @param  string $email Email de l'utilisateur
     * @param  string $password Mot de passe de l'utilisateur
     * 
     * @return mixed User | null en cas d'échec
     */
    public function checkAuth(string $email, string $password): ?User
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE `email`=:email AND `password`=:password',
            $this->getTableName()
        );

        $sth = $this->pdo->prepare($query);

        if (! $sth) {
            return null;
        }

        $sth->execute(['email' => $email, 'password' => $password]);

        $user_data = $sth->fetch();

        if (! $user_data) {
            return null;
        }

        return new User($user_data);
    }



}