<?php

namespace App\Controller;

use Symplefony\Controller;
use Symplefony\View;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard(): void
    {
        $view = new View( 'page:announcer:home' );

        $data = [
            'title' => 'Tableau de bord - Admin Autodingo.com'
        ];

        $view->render( $data);
    }
}