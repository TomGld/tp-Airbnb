<?php

namespace App;

use Symplefony\AbstractSession;

/**
 * Classe de gestion de la session du projet.
 * Contient les constantes des noms des clés utilisées.
 */
class Session
{
    const USER = 'user';

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }
}