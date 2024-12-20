<?php

namespace App\Controller;

use Laminas\Diactoros\ServerRequest;

use Symplefony\Controller;
use Symplefony\View;

use App\Model\Entity\Category;
use App\Model\Entity\Reservation;
use App\Model\Repository\RepoManager;

class ReservationController extends Controller
{
    /**
     * Pages Administrateur
     */

    // Admin: Affichage du formulaire de création d'un utilisateur
    public function add(): void
    {
        $view = new View( 'reservation/create' );

        $data = [
            'title' => 'Créer une réservation'
        ];

        $view->render( $data );
    }

    // Admin: Traitement du formulaire de création d'une catégorie
    public function create( ServerRequest $request ): void
    {
        $reservation_data = $request->getParsedBody();

        $reservation = new Reservation( $reservation_data );

        $reservation_created = RepoManager::getRM()->getReservationRepo()->create( $reservation );

        if( is_null( $reservation_created ) ) {
            // TODO: gérer une erreur
            $this->redirect( '/reservations/add' );
        }

        $this->redirect( '/reservation' );
    }

    // Admin: Liste
    public function index(): void
    {
        $view = new View( 'reservation:list' );

        $reservations = RepoManager::getRM()->getReservationRepo()->getAll();
        $reservation = new Reservation($reservations);
        //set les informations de l'accommodation
        foreach ($reservation as $res) {
            $res->setAccommodation(RepoManager::getRM()->getAccommodationRepo()->getById($res->getId_accommodation()));
        }
        

        $data = [
            'title' => 'Liste des réservations',
            'reservation' => $reservation
        ];


        $view->render( $data );
    }


    //Function pour afficher les détails d'un bien en particulier
    public function showReservation(int $id): void
    {
        $view = new View('reservation:details');

        $reservation = RepoManager::getRM()->getReservationRepo()->getById($id);

        //Si l'accommodation n'existe pas
        if (is_null($reservation)) {
            View::renderError(404);
            return;
        }

        $data = [
            'title' => 'Détails du bien',
            'reservation' => $reservation
        ];

        $view->render($data);
    }




}