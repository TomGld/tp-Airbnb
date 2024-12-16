<?php

namespace App\Controller;

use App\App;
use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Addresses;
use App\Model\Repository\RepoManager;
use App\Model\Entity\User;
use App\Model\tools\FunctionsSecurity;

class UserController extends Controller
{

    // Visiteur: Affichage du formulaire de création de compte
    public function add(): void
    {
        $view = new View('user:create_account', auth_controller: AuthController::class);

        $data = [
            'title' => 'Créer mon compte - Autodingo.com'
        ];

        $view->render($data);
    }



    // Visiteur: Traitement du formulaire de création d'un utilisateur
    public function create( ServerRequest $request ): void
    {
        $user_data = $request->getParsedBody();

        $isValidPassword = FunctionsSecurity::validPassword($user_data['password']);
        $isvalidEmail = FunctionsSecurity::validEmail($user_data['email']);

        if(!$isValidPassword)
        {
            //TODO: traiter l'erreur
        }    

        $user = [
            'email' => ($user_data['email']),
            'password' => App::strHash($user_data['password']),
            'lastname' => FunctionsSecurity::secureData($user_data['lastname']),
            'firstname' => FunctionsSecurity::secureData($user_data['firstname']),
            'phone_number' => FunctionsSecurity::secureData($user_data['phone_number']),
            'id_role' => FunctionsSecurity::secureData($user_data['role']),
        ];
        $user_created = RepoManager::getRM()->getUserRepo()->create( $user );

        if( is_null( $user_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/sign-up/add' );
        }

        $this->redirect( '/sign-in?msg=Compte crée avec succès' );
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