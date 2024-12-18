<?php

namespace App\Controller;

use App\App;
use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Session;
use App\Model\Entity\User;
use App\Model\Repository\RepoManager;
use App\Model\tools\FunctionsSecurity;

/**
 * Gestion des fonctionnalités d'authentification
 */
class AuthController extends Controller
{
    /**
     * L'utilisateur est connecté
     *
     * @return bool
     */
    public static function isAuth(): bool
    {
        return !is_null(Session::get(Session::USER));
    }

    /**
     * L'utilisateur connecté est un customer
     *
     * @return bool
     */
    public static function isCustomer(): bool
    {
        if (!self::isAuth()) {
            return false;
        }

        $user = Session::get(Session::USER);

        return $user->getRole() === User::ROLE_CUSTOMER;
    }

    /**
     * L'utilisateur connecté est un customer
     *
     * @return bool
     */
    public static function isAnnouncer(): bool
    {
        if (!self::isAuth()) {
            return false;
        }

        $user = Session::get(Session::USER);

        return $user->getRole() === User::ROLE_ANNOUNCER;
    }





    public static function getUser(): ?User
    {
        if (!self::isAuth()) {
            return null;
        }

        return Session::get(Session::USER);
    }


    // --- Actions de routes ---

    // - Visiteurs seulement -
    /**
     * Page du formulaire d'authentification
     *
     * @return void
     */
    public function signIn(): void
    {
        $view = new View('auth:sign-in', auth_controller: self::class);

        $data = [
            'title' => 'Connexion - Autodingo.com'
        ];

        $view->render($data);
    }

    /**
     * Traitement du formulaire d'authentification
     *
     * @param  mixed $request
     * @return void
     */
    public function checkCredentials(ServerRequest $request): void
    {
        $form_data = $request->getParsedBody();

        // Si les données du formulaire sont vides ou inexistantes
        if (!isset($form_data['email']) || !isset($form_data['password'])) {
            $this->redirect('/sign-in?error=Missing data');
        }

        // Si les données du formulaire sont vides ou inexistantes
        if (empty($form_data['email']) || empty($form_data['password'])) {
            $this->redirect('/sign-in?error=Empty data');
        }

        // On nettoie les espaces en trop
        $email = trim($form_data['email']);
        $password = trim($form_data['password']);

        // Si les données sont vides après nettoyage
        if (empty($email) || empty($password)) {
            $this->redirect('/sign-in?error=Empty data after trim');
        }

        // On vérifie les identifiants de connexion
        $password_hash = App::strHash($password);
        $user = RepoManager::getRM()->getUserRepo()->checkAuth($email, $password_hash);
        

        // Si échec
        if (is_null($user)) {
            $this->redirect('/sign-in?error=Identifiants invalides');
        }

        // Vérifiez que l'objet $user contient les informations nécessaires
        if ($user) {
            // On enregistre l'utilisateur correspondant dans la session
            $user->setPassword('');
            Session::set(Session::USER, $user);
        } else {
            $this->redirect('/sign-in?error=User data invalid');
        }
        // Vérifiez que l'objet $user contient le role
        if ($user)
        {
            // Vérifiez que le rôle de l'utilisateur est défini
            $role = $user->getRole();
            if (empty($role))
            {
                $this->redirect('/sign-in?error=User role invalid');
            }
        
        }


        // On redirige vers une page en fonction du rôle de l'utilisateur
        $redirect_url = match ($user->getRole()) {
            User::ROLE_CUSTOMER => '/',
            User::ROLE_ANNOUNCER => '/',
                //User::ROLE_ADMIN => '/admin',
            default => '/sign-in?error=Unhandled role'
        };

        $this->redirect($redirect_url);
    }


    // -- Utilisateurs connectés (tous rôles) --    
    /**
     * Déconnexion de la session
     *
     * @return void
     */
    public function signOut(): void
    {
        Session::remove(Session::USER);
        $this->redirect('/');
    }


}
