<?php
/**
 * Classe de démarrage de l'application
 */

// Déclaration du namespace de ce fichier
namespace App;

use App\Controller\AccommodationController;
use Exception;
use Throwable;

use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Routing\Attributes;

use Symplefony\View;

use App\Controller\AdminController;
use App\Controller\AuthController;
use App\Controller\CarController;
use App\Controller\CategoryController;
use App\Controller\PageController;
use App\Controller\ReservationController;
use App\Controller\UserController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AnnouncerMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\Customer_AnnouncerMiddleware;
use App\Middleware\Customer_VisitorMiddleware;
use App\Middleware\VisitorMiddleware;
use App\Middleware\CustomerMiddleware;
use App\Model\tools\FunctionsSecurity;
use Symplefony\Security;

final class App
{
    private static ?self $app_instance = null;

    // Le routeur de l'application
    private Router $router;
    public function getRouter(): Router { return $this->router; }

    public static function getApp(): self
    {
        // Si l'instance n'existe pas encore on la crée
        if( is_null( self::$app_instance ) ) {
            self::$app_instance = new self();
        }

        return self::$app_instance;
    }

    /**
     * Hache une chaîne de caractères en servant du "sel" et du "poivre" définis dans .env
     *
     * @param  string $str Chaîne à hacher
     * 
     * @return string Résultat
     */
    public static function strHash(string $str): string
    {
        return Security::strButcher($str, $_ENV['security_salt'], $_ENV['security_pepper']);
    }

    // Démarrage de l'application
    public function start(): void
    {
        session_start();
        $this->registerRoutes();
        $this->startRouter();
    }

    private function __construct()
    {
        // Création du routeur
        $this->router = Router::create();
    }

    // Enregistrement des routes de l'application
    private function registerRoutes(): void
    {
        //Pages COMMUNES
        // Accueil
        $this->router->get('/', [PageController::class, 'index']);
        // Contact
        $this->router->get('/mentions-legales', [PageController::class, 'legalNotice']);
        // Détail
        $this->router->get( '/accommodations/{id}', [ AccommodationController::class, 'showAccommodation' ] );


        // -- Visiteurs (non-connectés) --
        $visitorAttributes = [
            Attributes::MIDDLEWARE => [VisitorMiddleware::class]
        ];
        $this->router->group($visitorAttributes, function (Router $router) {
            // Login
            $router->get('/sign-in', [AuthController::class, 'signIn']);
            $router->post('/sign-in', [AuthController::class, 'checkCredentials']);

            //Create account
            $router->get('/sign-up', [UserController::class, 'add']);
            $router->post('/sign-up/add', [UserController::class, 'create']);
        });


        // -- Utilisateurs connectés (tous rôles) --
        $authAttributes = [
            Attributes::MIDDLEWARE => [AuthMiddleware::class]
        ];

        $this->router->group($authAttributes, function (Router $router) {

            // Logout
            $router->get('/sign-out', [AuthController::class, 'signOut']);
        });

        //Visitor & Customer
        $visitorCustomerAttributes = [
            Attributes::MIDDLEWARE => [Customer_VisitorMiddleware::class]
        ];
        $this->router->group($visitorCustomerAttributes, function (Router $router) {
            // -- Pages de visiteur et client --

            // -- Accommodation --
            // Liste
            $router->get('/accommodations', [AccommodationController::class, 'index']);
            // Détail

        });


        // -- PAGES customer
        $customerAttributes = [
            Attributes::MIDDLEWARE => [CustomerMiddleware::class]
        ];
        $this->router->group($customerAttributes, function (Router $router) {
            // -- Pages de client --

            // -- Reservation --
            // Liste
            $router->get('/reservations', [ReservationController::class, 'index']);
            // Ajout
             $router->get('/reservations/add', [ReservationController::class, 'add']);
             $router->post('/reservations', [ReservationController::class, 'create']);
        });

        // -- PAGES customer & announcer --
        $customerAnnouncerAttributes = [
            Attributes::MIDDLEWARE => [Customer_AnnouncerMiddleware::class]
        ];
        $this->router->group($customerAnnouncerAttributes, function (Router $router) {
            // -- Pages de client et annonceur --

            // -- Reservation --
            // Détail
            $router->get('/reservations/{id}', [ReservationController::class, 'showReservation']);
        });

        // -- PAGES ANNOUNCERS --
        $announcerAttributes = [
            Attributes::MIDDLEWARE => [AnnouncerMiddleware::class ]
        ];

        $this->router->group( $announcerAttributes, function( Router $router ) {
            // -- Pages d'annonceur --

            // -- Accommodation --
            // Ajout
            $router->get( '/accommodations/add', [ AccommodationController::class, 'add' ] );
            $router->post( '/accommodations', [ AccommodationController::class, 'create' ] );


        });

    }

    // Démarrage du routeur
    private function startRouter(): void
    {
        try{
            $this->router->dispatch();
        }
        // Page 404 avec status HTTP adequat pour les pages non listée dans le routeur
        catch( RouteNotFoundException $e ) {
            View::renderError( 404 );
        }
        // Erreur 500 pour tout autre problème temporaire ou non
        catch( Throwable $e ) {
            View::renderError( 500 );
            var_dump( $e );
        }
    } 

    private function __clone() { }
    public function __wakeup()
    {
        throw new Exception( "Non c'est interdit !" );
    }
}