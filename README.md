# tp-Airbnb

Affichage des listes des réservations : non-fonctionnelle.
Table de mapping equipments : non-fonctionnelle.


Installation :

--- Connexion à la BDD:
copier le .env.test dans le dossier app/
Le renommer en .env
Compléter les "" avec les informations du docker-compose.yml

--- Vendor
    --Executer le dossier avec docker:
        -Se placer dans le dossier racine dans un terminal, éxecuter "docker compose up".
    --Création du vendor:

        -Installation de composer:
        "docker exec -it IDCONTAINER_CONTENANTPHP_POO composer install"

        -Installation du vendor:
        "docker exec -it IDCONTAINER_CONTENANTPHP_POO dump-autoload"

