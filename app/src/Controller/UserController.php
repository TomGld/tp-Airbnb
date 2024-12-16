<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Addresses;
use App\Model\Repository\RepoManager;
use App\Model\Entity\User;
use App\Model\tools\FunctionsSecurity;

class UserController extends Controller
{

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

    // Visiteur: Traitement du formulaire de création d'un utilisateur
    public function create( ServerRequest $request ): void
    {
        $user_data = $request->getParsedBody();
        
        //Vérification format email avec validEmail
        // TODO: if


        $user = [
            'email' => FunctionsSecurity::secureData($user_data['email']),
            'password' => password_hash(FunctionsSecurity::validPassword($user_data['password']), PASSWORD_BCRYPT ),
            'lastname' => FunctionsSecurity::secureData($user_data['lastname']),
            'firstname' => FunctionsSecurity::secureData($user_data['firstname']),
            'phone_number' => FunctionsSecurity::secureData($user_data['phone_number']),
            'id_role' => FunctionsSecurity::secureData($user_data['role']),
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