<?php

namespace App\Controllers;


// Cette classe sert de base aux autres controllers. Tous les controllers de ce projet étendent cette classe afin d'hériter de ses méthodes/propriétés.
class CoreController {
    
    // une propriété pour stocker le router (et pouvoir générer des liens avec $router->generate)
    protected $router;
    
    // une propriété pour stocker les acl
    protected $acl;
    
    // constructeur, méthode appelée automatiquement dès que l'un des contrôleurs est instancié par AltoDispatcher
    public function __construct($router, $acl)
    {
        // on récupère le router envoyé en paramètre par AltoDispatcher
        // et on le stocke dans la propriété privée prévue à cet effet !
        $this->router = $router;
        $this->acl = $acl;        

        // -----------------
        // gestion des ACL :
        // -----------------
        
        // 1 - A partir du router, on récupère le nom de la route qui a matchée
        $match = $router->match();
        $routeName = $match['name'];

        // 2 - Si cette route est référencée dans la liste des controles d'acces (cette route n'est pas publique)
        if (isset($this->acl[$routeName])){
            // 3 - on récupère la liste des roles qui ont accès à cette route
            $authorizedRoles = $this->acl[$routeName];
            // 4 - On demande à la méthode checkAuthorizations de vérifier si l'utilisateur connecté à le bon role
            $this->checkAuthorizations($authorizedRoles);
        }
    }
    
    protected function checkAuthorizations($roles){
        /*
        Si l'utilisateur n'est pas connecté :   
            On le redirige vers la page de login
        Sinon (si l'utilisateur est connecté) :
            Si le role de l'utilisateur connecté est présent dans le tableau roles
                On laisse accéder à la fonctionnalité
            Sinon 
                On bloque l'accès à al fonctionnalité
        */
        if (!isset($_SESSION["userId"])){
            header("Location: " . $this->router->generate("Auth-login"));
            exit;
        }

        $currentUser = $_SESSION["userObject"];
        $currentUserRole = $currentUser->getRole();

        if (!in_array($currentUserRole, $roles)){
            header('HTTP/1.0 403 Forbidden');
            $this->show('error/err403');
            exit;
        }
    }

    /**
     * Fonction qui se charge d'afficher une page donnée
     *
     * @param string $viewName Nom du template de page à afficher
     * @param array $viewData Tableau contenant les différentes informations qu'on veut passer à notre vue
     * @return void
     */
    // Pour sécuriser encore plus notre code, on peut obliger les paramètres à avoir un certain type. Ici, en écrivant "array" devant $viewData, on oblige le 2ème paramètre à etre un tableau.
    protected function show($viewName, array $viewData = [])
    {
       
        // On demande à PHP d'aller chercher la variable $router pour pouvoir l'utiliser dans nos templates.
        //! C'est une mauvaise pratique. Elle passe outre les différents principes mis en place avec notre architecture. Donc on verra plus tard comment procéder autrement.
        //global $router;
        
         $viewData['currentPage'] = $viewName;

        // Sur toutes les pages, on a besoin d'avoir accès à la variable $absoluteUrl. Celle-ci contient le chemin vers le dossier public et permet de générer les liens vers les assets.
        $absoluteUrl = $_SERVER['BASE_URI'];


        // On "déballe" le  "colis" $viewData. C'est à dire on extrait chacune de ses entrées dans des variables qui portent le meme nom que les entrées
        // Exemple  pour un tableau donné : 
        // $array = [
        //     'truc' => true,
        //     'machin' => false,
        //     'bidule' => 5
        // ];
        // extract($array) //donnera trois variables : $truc, $machin, $bidule.

        extract($viewData);
        
        // pour éviter d'avoir à modifier toutes les vues, 
        $router = $this->router;

        require_once __DIR__ . '/../Views/layout/header.tpl.php';
        require_once __DIR__ . '/../Views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../Views/layout/footer.tpl.php';
    }
    
}