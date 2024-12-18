<?php

namespace App\Controller;

use App\Model\Entity\Accommodation;
use App\Model\Entity\Addresse;
use App\Model\Repository\RepoManager;
use App\Model\tools\FunctionsSecurity;
use Laminas\Diactoros\ServerRequest;
use Symplefony\Controller;
use Symplefony\View;

class AccommodationController extends Controller
{
    /**
     * Pages Administrateur
     */

    // Annoncer: Affichage du formulaire de création d'un bien
    public function add(): void
    {
        $view = new View( 'accommodation:create' );
        $accommodation_types = RepoManager::getRM()->getAccommodationTypeRepo()->getAll();

        $data = [
            'title' => 'Ajouter un bien',
            'accommodation_types' => $accommodation_types
        ];
        
        $view->render( $data );
    }



    // Annoncer: Liste
    public function index(): void
    {
        $view = new View( 'accommodation:list' );

        $accommodations = RepoManager::getRM()->getAccommodationRepo()->getAll();

        foreach ($accommodations as $accommodation) {
            $accommodation->setAddress(RepoManager::getRM()->getAddressRepo()->getById($accommodation->getId_address()));
        }


        $data = [
            'title' => 'Liste des locations',
            'accommodations' => $accommodations
        ];
        $view->render( $data );
    }


    // Formulaire de création d'une address puis de l'accommodation
    public function create(ServerRequest $request): void
    {
        $accommodation_data = $request->getParsedBody();


        if (empty($accommodation_data["number_street"])) {
            $accommodation_data["number_street"] = null;
        }

        //Création de l'adresse
        $address_data = new Addresse([
            'number_street' => $accommodation_data,
            'street' => $accommodation_data['street'],
            'city' => $accommodation_data['city'],
            'country' => $accommodation_data['country']
        ]);


        $address_created = RepoManager::getRM()->getAddressRepo()->create($address_data);

        if (is_null($address_created)) {
            $this->redirect('/accommodation/add?error=Erreur lors de la création de l\'adresse');
        }
        
        //Création de l'accommodation

        $accommodation = new Accommodation([
            'title' => $accommodation_data['title'],
            'price' => $accommodation_data['price'],
            'surface' => $accommodation_data['surface'],
            'description' => $accommodation_data['description'],
            'capacity' => $accommodation_data['capacity'],
            'id_owner' => FunctionsSecurity::secureData($accommodation_data['id_owner']),
            'id_type' => $accommodation_data['id_type'],
            'id_address' => $address_data->getId()
        ]);

        $accommodation_created = RepoManager::getRM()->getAccommodationRepo()->create($accommodation);

        if (is_null($accommodation_created)) {
            $this->redirect('/accommodation/add?error=Erreur lors de la création de l\'accommodation');
        }

        $this->redirect('/accommodations');



    }



}