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
use App\Controller\UserController;
use App\Middleware\AdminMiddleware;

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

    // Démarrage de l'application
    public function start(): void
    {
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
        // -- Formats des paramètres --
        // {id} doit être un nombre
        $this->router->pattern( 'id', '\d+' );

        // -- Pages communes --
        $this->router->get( '/', [ PageController::class, 'index' ] );
        $this->router->get( '/mentions-legales', [ PageController::class, 'legalNotice' ]);
        
        // TODO: Groupe Visiteurs (non-connectés)


        // -- PAGES ANNOUNCERS --
        $announcerAttributes = [
            Attributes::PREFIX => '/announcer',
            Attributes::MIDDLEWARE => [ AdminMiddleware::class ]
        ];

        $this->router->group( $announcerAttributes, function( Router $router ) {
            // -- Pages d'annonceur --
            $router->get( '', [ AccommodationController::class, 'index' ]);

            // -- Accommodation --
            // Ajout
            $router->get( '/accommodations/add', [ AdminController::class, 'add' ] );
            $router->post( '/accommodations', [ AdminController::class, 'create' ] );
            // Liste
            $router->get( '/accommodations', [ AdminController::class, 'index' ]);
            // Détail
            $router->get( '/accommodations/{id}', [ AdminController::class, 'show' ] );
            $router->post( '/accommodations/{id}', [ AdminController::class, 'update' ] );
            // Suppression
            $router->get( '/accommodations/{id}/delete', [ AdminController::class, 'delete' ] );
        });



        // -- Pages d'admin --
        $adminAttributes = [
            Attributes::PREFIX => '/admin',
            Attributes::MIDDLEWARE => [ AdminMiddleware::class ]
        ];

        $this->router->group( $adminAttributes, function( Router $router ) {
            $router->get( '', [ AdminController::class, 'dashboard' ]);

            // -- User --
            // Ajout
            $router->get( '/users/add', [ UserController::class, 'add' ] );
            $router->post( '/users', [ UserController::class, 'create' ] );
            // Liste
            $router->get( '/users', [ UserController::class, 'index' ]);
            // Détail
            $router->get( '/users/{id}', [ UserController::class, 'show' ] );
            $router->post( '/users/{id}', [ UserController::class, 'update' ] );
            // Suppression
            $router->get( '/users/{id}/delete', [ UserController::class, 'delete' ] );

            // -- Category --
            // Ajout
            $router->get( '/categories/add', [ CategoryController::class, 'add' ] );
            $router->post( '/categories', [ CategoryController::class, 'create' ] );
            // Liste
            $router->get( '/categories', [ CategoryController::class, 'index' ]);
            // Détail/modification
            $router->get( '/categories/{id}', [ CategoryController::class, 'show' ] );
            $router->post( '/categories/{id}', [ CategoryController::class, 'update' ] );
            // Suppression
            $router->get( '/categories/{id}/delete', [ CategoryController::class, 'delete' ] );

            // -- Car --
            // Ajout
            $router->get( '/cars/add', [ CarController::class, 'add' ] );
            $router->post( '/cars', [ CarController::class, 'create' ] );
            // Liste
            $router->get( '/cars', [ CarController::class, 'index' ]);
            // Détail/modification
            $router->get( '/cars/{id}', [ CarController::class, 'show' ] );
            $router->post( '/cars/{id}', [ CarController::class, 'update' ] );
            // Suppression
            $router->get( '/cars/{id}/delete', [ CarController::class, 'delete' ] );
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