<?php

namespace App\Controller;

use PDO;

use Symplefony\Controller;
use Symplefony\Database;
use Symplefony\View;

use App\Model\UserModel;
class PageController extends Controller
{
    // Page d'accueil
    public function index(): void
    {
        $view = new View( 'page:home' );

        $data = [
            'title' => 'Accueil - Airbnb.com'
        ];

        $view->render( $data );
    }

    // Page mentions légales
    public function legalNotice(): void
    {

        $view = new View( 'page:legal_notice' );

        $data = [
            'title' => 'Mentions-légales - Airbnb.com'
        ];

        $view->render($data);
    }
}