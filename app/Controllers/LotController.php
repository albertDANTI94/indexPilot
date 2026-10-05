<?php

namespace App\Controllers;

use App\Services\LotService;
use App\Services\RevisionCalculator;
use App\Models\Utilisateurs;
use App\Models\Lots;
use App\Models\Chantiers;


class LotController extends CoreController {
    
    
    
    private LotService $lotService;

    public function __construct($router, $acl)
    {
        parent::__construct($router, $acl);

        $this->lotService = new LotService(
            new RevisionCalculator()
        );
    }
    
    public function index() {
        
        
        
        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            die("Utilisateur non connecté");
        }
    
        // ---------------- USER ----------------
        $user = Utilisateurs::find($userId);
    
        // -------------- CHANTIERS ----------------
        //$lots = Lots::findAllByUser($userId);
        $lots = Lots::findAllWithChantier($userId);
    
        if (!$lots) {
            $lots = [];
        }
        
        $lotsData = [];

        foreach ($lots as $lot) {
        
            $lotsData[] = [
                'id' => $lot->getId(),
                'nom' => $lot->getNom(),
                'chantierId' => $lot->getChantierId(),
                'chantierNom' => $lot->getChantierNom(),
                'tarifOrigine' => $lot->getTarifOrigine(),
                'partFerme' => $lot->getPartFerme(),
                'coefficient' => $lot->getCoefficient(),
                'nouveauTarif' => $lot->getNouveauTarif(),
                'status' => $lot->getStatus(),
                'updatedAt' => $lot->getUpdatedAt(),
                'libelle' => $lot->getLibelle(),
                'libelleTexte' => $lot->getLibelleTexte(),
            ];
        }
        
        $this->show('lots', [
            'lots' => $lots,
            'lotsData' => $lotsData
            ]);
    }
    
    public function create(){
        $chantiers = \App\Models\Chantiers::findAll();
        $this->show('lots/create', [
            'chantiers' => $chantiers
        ]);
    }
    
    /*public function createPost()
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
            return;
        }

        try {
            $result = $this->lotService->create($_POST, (int)$userId);

            echo json_encode([
                'success' => true,
                'lot_id' => $result['lot_id'],
                'Cn' => $result['coefficient'],
                'nouveauTarif' => $result['nouveau_tarif']
            ]);

        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);

        } catch (\RuntimeException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }*/
    
    public function createPost()
    {
        $userId = $_SESSION['userId'] ?? null;
    
        header('Content-Type: application/json; charset=utf-8');
    
        try {
            if (!$userId) {
                http_response_code(401);
    
                echo json_encode([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ]);
                exit;
            }
    
            $result = $this->lotService->create($_POST, (int) $userId);
    
            http_response_code(200);
    
            echo json_encode([
                'success' => true,
                'message' => 'Lot créé',
                'lot_id' => $result['lot_id'] ?? null,
                'Cn' => $result['coefficient'] ?? null,
                'nouveauTarif' => $result['nouveau_tarif'] ?? null
            ]);
            exit;
    
        } catch (\Throwable $e) {
            http_response_code(500);
    
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            exit;
        }
    }

    public function update($id)
    {
        $lot = \App\Models\Lots::findWithLibelle($id);
        $chantiers = \App\Models\Chantiers::findAll();

        $this->show('lots/update', [
            'lot' => $lot,
            'chantiers' => $chantiers
        ]);
    }

    public function updatePost($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
            return;
        }

        try {
            $result = $this->lotService->update((int)$id, $_POST, (int)$userId);

            echo json_encode([
                'success' => true,
                'lot_id' => $result['lot_id'],
                'Cn' => $result['coefficient'],
                'nouveauTarif' => $result['nouveau_tarif']
            ]);

        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        $lot = \App\Models\Lots::find($id);

        if ($lot) {
            $lot->delete();
        }

        header("Location: " . $this->router->generate('Lot-index'));
    }
    
    
}