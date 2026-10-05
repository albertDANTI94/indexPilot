<?php

namespace App\Controllers;

use App\Models\Calculs;
use App\Models\Utilisateurs;
use App\Models\CalculDTO;

class CalculController extends CoreController {
    /*public function index() {
        
        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            die("Utilisateur non connecté");
        }
    
        // ---------------- USER ----------------
        $user = Utilisateurs::find($userId);
        
        $calculs = Calculs::getLibelleText($userId);
        
        foreach ($calculs as &$calcul) {

            $indiceN0 = (float) $calcul['indice_n0'];
            $indiceNn = (float) $calcul['indice_nn'];
    
            $calcul['variation'] = ($indiceN0 > 0)
                ? (($indiceNn - $indiceN0) / $indiceN0) * 100
                : 0;
    
            $calcul['impact'] =
                (float)$calcul['nouveau_tarif']
                - (float)$calcul['tarif_origin'];
        }
        
       // $calculsData = [];
        

        foreach ($calculs as $calcul) {

            $indiceN0 = (float) $calcul->getIndiceN0();
            $indiceNn = (float) $calcul->getIndiceNn();
        
            $variation = ($indiceN0 > 0)
                ? (($indiceNn - $indiceN0) / $indiceN0) * 100
                : 0;
        
            $calculsData[] = [
                'reference' => $calcul->getReference(),
                'id' => $calcul->getId(),
                'libelle' => $calcul->getLibelle(),
                'partFerme' => $calcul->getPartFerme(),
                'tarif' => (float)$calcul->getTarifOrigin(),
                'indiceN0' => $indiceN0,
                'indiceNn' => $indiceNn,
                'nouveauTarif' => (float)$calcul->getNouveauTarif(),
                'variation' => $variation,
                'impact' =>
                    $calcul->getNouveauTarif()
                    - $calcul->getTarifOrigin(),
                'date' => $calcul->getCreatedAt(),
                'date0' => $calcul->getDate0(),
                'dateN' => $calcul->getDateN(),
            ];
        }
        
        $this->show('calculs', [
            'calculs' => $calculs,
            //'calculsData' => $calculsData,
            ]);
        
    }*/
    
    public function index()
    {
        $userId = $_SESSION["userId"] ?? null;

        if (!$userId) {
            die("Utilisateur non connecté");
        }
    
        $rows = Calculs::getCalculsWithLibelle($userId);
    
        $calculsDTO = [];
    
        foreach ($rows as $row) {
            $calculsDTO[] = CalculDTO::fromArray($row);
        }
    
        $this->show('calculs', [
            'calculs' => $calculsDTO
        ]);
    }
    
    public function ope() {
        $this->show('calculateur');
    }
    
    // supprime produit
     public function delete($id)
     {
        $CalculToDelete = Calculs::find($id);

        if ($CalculToDelete){
            $CalculToDelete->delete();

            header('Location: '. $this->router->generate('Calcul-index'));
        }

    }
}