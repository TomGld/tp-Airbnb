<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Address;
use App\Model\Repository\RepoManager;
use App\Model\Entity\User;

class UserController extends Controller
{
    /**
     * Pages publiques
     */
    // Visiteur: Affichage du formulaire de création de compte
    public function displaySubscribe(): void
    {
        $view = new View( 'user:create-account' );

        $data = [
            'title' => 'Créer mon compte - Autodingo.com'
        ];

        $view->render( $data );
    }

    // Visiteur: Traitement du formulaire de création de compte
    public function processSubscribe(): void
    {
        // TODO: AJOUTER LE TRAITEMENT DU FORMULAIRE DEPUIS LE JS
        
    }

    /**
     * Pages Administrateur
     */

    // Admin: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'user:create_account' );

        $data = [
            'title' => 'Ajouter un utilisateur'
        ];

        $view->render( $data );
    }

    // Admin: Traitement du formulaire de création d'un utilisateur
    public function create( ServerRequest $request ): void
    {
        $user_data = $request->getParsedBody();
        

        $user = [
            'email' => $user_data['email'],
            'password' => password_hash( $user_data['password'], PASSWORD_BCRYPT ),
            'lastname' => $user_data['lastname'],
            'firstname' => $user_data['firstname'],
            'phone_number' => $user_data['phone_number'],
        ];

        $user_created = RepoManager::getRM()->getUserRepo()->create( $user );

        if( is_null( $user_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/users/add' );
        }

        $this->redirect( '/users' );
    }

    // Admin: Liste
    public function index(): void
    {
        $view = new View( 'user:admin:list' );

        $data = [
            'title' => 'Liste des utilisateurs',
            'users' => RepoManager::getRM()->getUserRepo()->getAll()
        ];

        $view->render( $data );
    }

    // Admin: Détail
    public function show( int $id ): void
    {
        $view = new View( 'user:admin:details' );

        $user = RepoManager::getRM()->getUserRepo()->getById( $id );

        // Si l'utilisateur demandé n'existe pas
        if( is_null( $user ) ) {
            View::renderError( 404 );
            return;
        }

        $data = [
            'title' => 'Utilisateur: '. $user->getEmail(),
            'user' => $user
        ];

        $view->render( $data );
    }

    // Admin: Traitement du formulaire de modification
    public function update( ServerRequest $request, int $id ): void
    {
        $user_data = $request->getParsedBody();

        $user = new User( $user_data );
        $user->setId( $id );

        $user_updated = RepoManager::getRM()->getUserRepo()->update( $user );

        if( is_null( $user_updated ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/admin/users/'. $id );
        }

        $this->redirect( '/admin/users' );
    }

    // Admin: Suppression
    public function delete( int $id ): void
    {
        $delete_success = RepoManager::getRM()->getUserRepo()->deleteOne( $id );

        if( ! $delete_success ) {
            // TODO: gérer une erreur
            $this->redirect( '/admin/users/'. $id );
        }

        $this->redirect( '/admin/users' );
    }
}