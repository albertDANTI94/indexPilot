<?php

namespace App\Controllers;

use App\Models\Utilisateurs;

class AuthController extends CoreController {
    
    public function login()
    {
        $this->show('login');
    }
    
    public function register()
    {
        $this->show('register');
    }
    
    
    public function loginPost()
    {
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');

        $user = Utilisateurs::findByEmail($email);

        // si l'utilisateur existe :
            // si l'utilisateur trouvé a le meme mot de passe que celui saisi :
                // ok
            // sinon pb
        // sinon pb

        if ($user){
            // on vérifie que le password saisi soit compatible avec le hash stocké
            if (password_verify($password, $user->getPassword())){          
                $_SESSION["userId"] = $user->getId();               
                $_SESSION["userObject"] = $user;               
                header("Location: " . $this->router->generate('dashboard'));
                return;
            }
        }    
        
        // si on arrive ici, un problème s'est produit :
        // - email et/ou password non saisi,
        // - email inexistant
        // - email et mot de passe non concordants
        $this->show('login', [
            "errors" => [
                "Mauvais e-mail et/ou mot de passe"
            ]
        ]);
    }

    /**
     * Méthode qui déconnecte l'utilisateur
     *
     * @return void
     */
    public function logout()
    {   
        // plan d'action :
        // on supprime de la session les information qui signifient qu'on est connecté (userId et userObject)
        // on redirige l'utilisateur vers le formulaire de login
        
        unset($_SESSION["userId"]);
        unset($_SESSION["userObject"]);

        header("Location: " . $this->router->generate("Auth-login"));
    }
    
    public function registerPost() {
        // on récupère les données avec filter_input
        
        
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        //! pas de filtre, on veut laisser le password tel quel, avec les caractères chosis, meme les bizarres !
        $password = filter_input(INPUT_POST, 'password');
        $confirm = filter_input(INPUT_POST, 'confirm');
        $entreprise = filter_input(INPUT_POST, 'entreprise', FILTER_SANITIZE_SPECIAL_CHARS);
        $secteur = filter_input(INPUT_POST, 'secteur', FILTER_SANITIZE_SPECIAL_CHARS);
        $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_SPECIAL_CHARS);

        $errors = [];

        if(is_null($email)) {
            $errors[] = "Erreur, le champ e-mail est manquant !";
        }
        // validation du champ email
        if(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = "Erreur, le champ e-mail est incorrect !";
        }
        if(is_null($password)) {
            $errors[] = "Erreur, le champ mot de passe est manquant !";
        }
        if(is_null($confirm)) {
            $errors[] = "Erreur, le champ confirmez mot de passe est manquant !";
        }
        if(is_null($nom)) {
            $errors[] = "Erreur, le champ nom est manquant !";
        }
        if(is_null($prenom)) {
            $errors[] = "Erreur, le champ prénom est manquant !";
        }
        if(is_null($entreprise)) {
            $errors[] = "Erreur, le champ entreprise est manquant !";
        }
        if(is_null($secteur)) {
            $errors[] = "Erreur, le champ secteur est manquant !";
        }
        if(is_null($size)) {
            $errors[] = "Erreur, le champ taille d'entreprise est manquant !";
        }
        
        // Vérification de la correspondance des mots de passe
        if ($password !== $confirm) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // validation des données
        /*
        le mot de passe doit contenir :
        au moins 8 caractères
        au moins une lettre en minuscule
        au moins une lettre en majuscule
        au moins un chiffre
        au moins un caractère spécial parmi ['_', '-', '|', '%', '&', '*', '=', '@', '$']
        */

        /*
        if (mb_strlen($password) < 8){
            $errors[] = "Erreur, le champ mot de passe doit contenir au moins 8 caractères";
        }
        */
        
        // avec une expression régulière, cela donnerait :
        // pour y arriver, de la sueur, regex101.com et stack overflow sont vos amis !
        if (1 !== preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[_\-|%&*=@$]).{8,}$/', $password)){
            $errors[] = "Erreur, le mot de passe n'est pas suffisamment sécurisé";
        }

        
        $user = new Utilisateurs;

        $user->setEmail($email);

        // Pour le mot de passe, on le hache avant de le positionner dans notre modèle
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setEntreprise($entreprise);
        $user->setSecteur($secteur);
        $user->setSize($size);

        if (empty($errors)){
            // on réalise le traitement
            if ($user->insert()){
                header("Location: ". $this->router->generate('dashboard'));
                exit;
            }else{
                $errors[] = "Erreur lors de l'inscription.";
            }
        }

        $this->show('register', [
            "errors" => $errors,
            "user" => $user,
        ]);
    
    }
    
}