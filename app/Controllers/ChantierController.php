<?php

namespace App\Controllers;

use App\Models\Chantiers;
use App\Models\Utilisateurs;

class ChantierController extends CoreController {
    
    public function index() {
        
        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            die("Utilisateur non connecté");
        }
    
        // ---------------- USER ----------------
        $user = Utilisateurs::find($userId);
    
        // ---------------- CHANTIERS ----------------
        $chantiers = Chantiers::findAllByUser($userId);
    
        if (!$chantiers) {
            $chantiers = [];
        }
        
        $kpis = Chantiers::getKPIsByUser($userId);
        
        $kpiMap = [];
        
        foreach ($kpis as $kpi) {
            $kpiMap[$kpi['id']] = $kpi;
        }
        
        $chantiersData = [];
        
        foreach ($chantiers as $chantier) {
        
            $id = $chantier->getId();
            $data = $kpiMap[$id] ?? null;
        
            $totalOrigine = $data['total_origine'] ?? 0;
            $totalNouveau = $data['total_nouveau'] ?? 0;
        
            $chantiersData[] = [
                'id' => $id,
                'nom' => $chantier->getNom(),
                'client' => $chantier->getClient(),
                'description' => $chantier->getDescription(),
                'status' => $chantier->getStatus(),
                'createdAt' => $chantier->getCreatedAt(),
        
                // KPI LOTS
                'lotsCount' => $data['lots_count'] ?? 0,
                'totalOrigine' => $totalOrigine,
                'totalNouveau' => $totalNouveau,
        
                // calcul métier
                'gain' => $totalNouveau - $totalOrigine
            ];
        }
        
        $this->show('chantiers', [
            'chantiers' => $chantiers,
            'chantiersData' => $chantiersData
            ]);
    }
    
    public function create() {
        $this->show('chantiers/create');
    }
    
    public function createPost() {
        // méthode qui réceptionne le form d'ajout
    
        // on récupère les données avec filter_input
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
        $client = filter_input(INPUT_POST, 'client', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        // validation des données du form (vérifier la longueur, vérifier si l'URL de l'image est bien correcte, etc.)
        // un tableau d'erreurs qui sera renvoyé & affiché sur le formulaire en cas d'erreurs
        $errors = [];

        if(is_null($nom)) {
            // si name est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ nom est manquant !";
        }
        
        if(is_null($client)) {
            // si name est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ nom est manquant !";
        }

        if(is_null($description)) {
            // si subtitle est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ description est manquant !";
        }

        if(is_null($status)) {
            // si picture est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ statut est manquant !";
        }

        if(mb_strlen($nom) < 3) {
            // erreur !
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Le nom doit contenir au moins 3 caractères !";
        }

        if(mb_strlen($nom) > 64) {
            // erreur !
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Le nom doit contenir moins de 64 caractères !";
        }

        
        // on veut ajouter une catégorie en BDD
        // vu qu'on bosse avec des objets, on instancie la classe Chantiers
        $chantier = new Chantiers();

        $chantier->setUserId($_SESSION["userId"]);

        $chantier->setNom($nom);
        $chantier->setClient($client);
        $chantier->setDescription($description);
        $chantier->setStatus($status);

        // on vérifie s'il y a eu une erreur ou pas !
        if(empty($errors)) {
            // le tableau d'erreur est vide, donc on ajoute en BDD !

            // on demande à notre objet de s'insérer en BDD
            // si l'ajout s'est bien passé, on redirige vers la liste !
            
            if($chantier->insert()) {
                // insert() a renvoyé true, on redirige !
                //header('Location: /product/list');
                // c'est mieux avec $router->generate() !
                header("Location: " . $this->router->generate('Chantier-index'));
                exit;
            } else {
                //die("Erreur lors de l'ajout d'une catégorie.");
                $errors[] = "Erreur lors de l'ajout d'un chantier.";
            }
        }

        // si on arrive à cet endroit là, c'est forcément qu'il y a eu une erreur !
        // on réaffiche le form d'ajout de catégorie, et on lui envoie notre tableau d'erreurs !
        // on renvoit également l'objet category pré-rempli avec les données du form, pour que l'utilisateur n'ait pas à tout retaper !
        $this->show('chantiers/create', [
            'errors' => $errors,
            'chantier' => $chantier
        ]);
    }
    
    // affichage du form de modification de produit
    public function update($id)
    {
        // on récupère le produit à modifier
        $chantier = Chantiers::find($id);


        $this->show('chantiers/update', [
            'chantier' => $chantier
        ]);
    }

    // réception du form de modif de produit
    public function updatePost($id)
    {
        // on récupère les données avec filter_input
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
        $client = filter_input(INPUT_POST, 'client', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        // validation des données du form (vérifier la longueur, vérifier si l'URL de l'image est bien correcte, etc.)
        // un tableau d'erreurs qui sera renvoyé & affiché sur le formulaire en cas d'erreurs
        // validation des données du form (vérifier la longueur, vérifier si l'URL de l'image est bien correcte, etc.)
        // un tableau d'erreurs qui sera renvoyé & affiché sur le formulaire en cas d'erreurs
        $errors = [];

        if(is_null($nom)) {
            // si name est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ nom est manquant !";
        }
        
        if(is_null($client)) {
            // si name est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ nom est manquant !";
        }

        if(is_null($description)) {
            // si subtitle est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ description est manquant !";
        }

        if(is_null($status)) {
            // si picture est null, c'est que le champ n'était pas présent
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Erreur, le champ statut est manquant !";
        }

        if(mb_strlen($nom) < 3) {
            // erreur !
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Le nom doit contenir au moins 3 caractères !";
        }

        if(mb_strlen($nom) > 64) {
            // erreur !
            
            // on ajoute le message d'erreur au tableau !
            $errors[] = "Le nom doit contenir moins de 64 caractères !";
        }
        

        // on veut mettre à jour le produit, donc on le récupère !
        $chantier = Chantiers::find($id);

        // on remplit notre objet
        $chantier->setNom($nom);
        $chantier->setClient($client);
        $chantier->setDescription($description);
        $chantier->setStatus($status);

        // on vérifie s'il y a eu une erreur ou pas !
        if(empty($errors)) {
            // le tableau d'erreur est vide, donc on met à jour en BDD !

            // on demande à notre objet de se mettre à jour en BDD
            // si l'ajout s'est bien passé, on redirige vers la liste !
            if($chantier->update()) {
                // update() a renvoyé true, on redirige !
                header("Location: " . $this->router->generate('Chantier-index'));
                exit;
            } else {
                //die("Erreur lors de l'ajout d'une catégorie.");
                $errors[] = "Erreur lors de la modification du chantier.";
            }
        }

        // si on arrive à cet endroit là, c'est forcément qu'il y a eu une erreur !
        // on réaffiche le form de modification de produit, et on lui envoie notre tableau d'erreurs !
        // on renvoit également l'objet chantier pré-rempli avec les données du form, pour que l'utilisateur n'ait pas à tout retaper !

        $this->show('chantiers/update', [
            'errors' => $errors,
            'chantier' => $chantier
        ]);
    }

     // supprime produit
     public function delete($id)
     {
        $chantierToDelete = Chantiers::find($id);

        if ($chantierToDelete){
            $chantierToDelete->delete();

            header('Location: '. $this->router->generate('Chantier-index'));
        }

    }
    
  
    
}