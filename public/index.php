<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// On commence par inclure le fichier autoload.php. Ce fichier se charge d'inclure toutes les classes téléchargées via composer.
require __DIR__.'/../vendor/autoload.php';

session_start();

/* ------------
--- ACL     ---
-------------*/
require_once '../app/config/acl.php';

// On utilise la classe AltoRouter pour gérer nos routes. 
// On doit donc commencer par l'instancier

$router = new AltoRouter();

// On configure AltoRouter pour qu'il ne prenne pas en compte la partie fixe de l'url (le chemin vers notre dossier)
//! ATTENTION, si la partie fixe (BASE_URI) contient des caractères spéciaux ou des espaces, AltoRouter ne fonctionne pas ! 
//$router->setBasePath($_SERVER['BASE_URI']);

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
} else { // sinon
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}


// On crée notre routeur dans lequel on fait l'association entre une URL et une méthode d'un controller.

// On crée une route pour la page d'accueil
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/',  // url de la route (/ = home)
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\MainController',
        'method' => 'home'
    ],
    'home'
);

/* Afficher le dashboard */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/dashboard',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\DashboardController',
        'method' => 'data'
    ],
    'dashboard'
);

/* Afficher la page login */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/login', 
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\AuthController',
        'method' => 'login'
    ],
    'Auth-login'
);

/* Envoi des données de la page login */
$router->map(
    'POST',
    '/login',
    [
        'method' => 'loginPost',
        'controller' => '\App\Controllers\AuthController'
    ],
    'user-loginPost'
);

/* Afficher la page register */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/register',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\AuthController',
        'method' => 'register'
    ],
    'Auth-register'
);

/* Envoi des données de la page register */
$router->map(
    'POST',  // Méthode HTTP de la requete (get ou post)
    '/register',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\AuthController',
        'method' => 'registerPost'
    ],
    'Auth-registerPost'
);

/* Afficher la page calculs */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/calculs',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\CalculController',
        'method' => 'index'
    ],
    'Calcul-index'
);

/* Supprimer un calcul */
$router->map(
    'GET',
    '/calculs/delete/[i:id]', // l'URL
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\CalculController'
    ],
    'Calcul-delete'
);

/* Afficher le calculateur */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/calculateur',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\CalculController',
        'method' => 'ope'
    ],
    'Calcul-ope'
);

/* Afficher les chantiers */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/chantiers',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\ChantierController',
        'method' => 'index'
    ],
    'Chantier-index'
);

/* Afficher le formulaire d'ajout de chantiers */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/chantiers/create',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\ChantierController',
        'method' => 'create'
    ],
    'Chantier-create'
);

/* Envoyer les données du form de création de chantiers */
$router->map(
    'POST',  // Méthode HTTP de la requete (get ou post)
    '/chantiers/create',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\ChantierController',
        'method' => 'createPost'
    ],
    'Chantier-createPost'
);

/* Afficher le formulaire de modification de chantiers */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/chantiers/update/[i:id]',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\ChantierController',
        'method' => 'update'
    ],
    'Chantier-update'
);

// Réception des données du form de modification de chantier
$router->map(
    'POST',
    '/chantiers/update/[i:id]', // l'URL
    [
        'method' => 'updatePost',
        'controller' => '\App\Controllers\ChantierController'
    ],
    'Chantier-updatePost'
);

/* Supprimer un chantier */
$router->map(
    'GET',
    '/chantiers/delete/[i:id]', // l'URL
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\ChantierController'
    ],
    'Chantier-delete'
);



/* Afficher les lots */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/lots',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\LotController',
        'method' => 'index'
    ],
    'Lot-index'
);

/* Afficher le formulaire d'ajout de lots */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/lots/create',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\LotController',
        'method' => 'create'
    ],
    'Lot-create'
);

/* Envoyer les données du form de création de lots */
$router->map(
    'POST',  // Méthode HTTP de la requete (get ou post)
    '/lots/create',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\LotController',
        'method' => 'createPost'
    ],
    'Lot-createPost'
);

/* Afficher le formulaire de modification de lots */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/lots/update/[i:id]',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\LotController',
        'method' => 'update'
    ],
    'Lot-update'
);

// Réception des données du form de modification de lots
$router->map(
    'POST',
    '/lots/update/[i:id]', // l'URL
    [
        'method' => 'updatePost',
        'controller' => '\App\Controllers\LotController'
    ],
    'Lot-updatePost'
);

/* Supprimer un lot */
$router->map(
    'GET',
    '/lots/delete/[i:id]', // l'URL
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\LotController'
    ],
    'Lot-delete'
);

/* Afficher la page indices */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/indices',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\InseeController',
        'method' => 'index'
    ],
    'Insee-index'
);

/*$router->map(
    'GET',
    '/indices/history/[a:code]',
    [
        'controller' => 'InseeController',
        'method' => 'history'
    ],
    'indices-history'
);*/

/* Afficher les documents */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/documents',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\DocController',
        'method' => 'index'
    ],
    'Doc-index'
);

/* Afficher les settings */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/settings',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\UserController',
        'method' => 'index'
    ],
    'User-index'
);

/* Afficher les alertes */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/alerts',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\AlertController',
        'method' => 'index'
    ],
    'Alert-index'
);

/* Afficher les rapports */
$router->map(
    'GET',  // Méthode HTTP de la requete (get ou post)
    '/reports',  
    // Tableau contenant le controller et la méthode liée à la page
    [    
        'controller' => '\App\Controllers\ReportController',
        'method' => 'index'
    ],
    'Report-index'
);


/* API — Date maximale des indices */
$router->map(
    'GET',
    '/api/indices/max-date',
    [
        'controller' => '\App\Controllers\InseeController',
        'method' => 'maxDate'
    ],
    'Api-indices-max-date'
);

/* API — Libellés des indices */
$router->map(
    'GET',
    '/api/indices/libelles',
    [
        'controller' => '\App\Controllers\InseeController',
        'method' => 'libelles'
    ],
    'Api-indices-libelles'
);

/* API — Valeur d'un indice */
$router->map(
    'GET',
    '/api/indices/valeur',
    [
        'controller' => '\App\Controllers\InseeController',
        'method' => 'valeur'
    ],
    'Api-indices-valeur'
);




// La méthode match permet à AltoRouter de savoir si la page demandée existe dans la liste des routes
// $match contient un tableau avec les informations de la route actuelle (controller, méthode, nom, etc)
// Si la route actuelle n'existe pas, $match contient false
$match = $router->match();

// Décommenter la ligne suivante pour voir le contenu de $match
// dump($match);


// ---- DISPATCHER ----- 
// On vérifie que la page demandée fait partie des routes existantes. Si $match ne contient pas false, on est sur une route existante
/*if($match !== false) {
  
    // On récupère le nom du controller dans lequel est rangé notre méthode qui gère la page demandée
    $controllerToUse = 'App\Controllers\\' . $match['target']['controller'];

    // On récupère dans le tableau des routes le nom de la méthode à exécuter. 
    $methodToUse = $match['target']['method'];


    // On récupère les paramètres dynamiques de l'url (exemple : id)
    $params = $match['params'];

    // On instancie le controller dans lequel est rangé la méthode
    // Si $controllerToUse contient "MainController", ça revient à écrire "new MainController()"
    $controller = new $controllerToUse();

    // On utilise la variable $methodToUse pour exécuter la méthode de controller dont le nom est stocké dedans.
    // Si  $methodToUse contient "homeAction", c'est comme si on écrivait "$controller->homeAction($params)"
    $controller->$methodToUse($params);

} else {
    echo "Erreur 404 - la page n'existe pas";
}*/

/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

//dd($match);

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');

// on a besoin d'avoir accès à la variable $router un peu partout dans notre code
// on va donc l'envoyer comme paramètre lors de l'instanciation de nos contrôleurs
$dispatcher->setControllersArguments($router, $acl);

// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();