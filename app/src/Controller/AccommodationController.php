<?php

namespace App\Controller;

use App\Model\Entity\Accommodation;
use App\Model\Repository\RepoManager;
use Laminas\Diactoros\ServerRequest;
use Symplefony\Controller;
use Symplefony\View;

class AccommodationController extends Controller
{
    /**
     * Pages Administrateur
     */

    // Admin: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'accommodation:announcer:create' );
        $accommodation_types = RepoManager::getRM()->getAccommodationTypeRepo()->getAll();

        $data = [
            'title' => 'Ajouter un bien',
            'accommodation_types' => $accommodation_types
        ];
        
        $view->render( $data );
    }



    // Admin: Liste
    public function index(): void
    {
        $view = new View( 'accommodation:announcer:list' );

        $data = [
            'title' => 'Liste des biens',
            'accommodations' => RepoManager::getRM()->getAccommodationRepo()->getAll()
        ];
        $view->render( $data );
    }




}