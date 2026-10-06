<?php

namespace App\Controllers;

use App\Models\Calculs;
use App\Models\CalculDTO;
use App\Services\RevisionCalculator;

class CalculController extends CoreController
{
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

    public function savePost()
    {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['userId'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté'
            ]);
            return;
        }

        try {
            $tarif = (float) ($_POST['Tarif'] ?? 0);
            $indice0 = (float) ($_POST['ICHT-N0'] ?? 0);
            $indiceN = (float) ($_POST['ICHT-Nn'] ?? 0);
            $partFerme = (float) ($_POST['part_ferme'] ?? 0);

            $calculator = new RevisionCalculator();
            $result = $calculator->calculate(
                $tarif,
                $indice0,
                $indiceN,
                $partFerme
            );

            $calcul = new Calculs();

            $calcul->setUserId((int) $userId);
            $calcul->setReference(Calculs::generateReference());
            $calcul->setTarifOrigin($tarif);
            $calcul->setIndiceN0($indice0);
            $calcul->setIndiceNn($indiceN);
            $calcul->setCoefficient($result->getCoefficient());
            $calcul->setNouveauTarif($result->getNouveauTarif());
            $calcul->setLibelle((string) ($_POST['libelle'] ?? ''));
            $calcul->setDate0($this->normalizeDate((string) ($_POST['date0'] ?? '')));
            $calcul->setDateN($this->normalizeDate((string) ($_POST['dateN'] ?? '')));
            $calcul->setPartFerme($partFerme);

            if (!$calcul->insert()) {
                throw new \RuntimeException('Erreur lors de l\'enregistrement du calcul');
            }

            echo json_encode([
                'success' => true,
                'Cn' => $result->getCoefficient(),
                'nouveauTarif' => $result->getNouveauTarif(),
                'reference' => $calcul->getReference()
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
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }
    }

    private function normalizeDate(string $date): string
    {
        return preg_match('/^\d{4}-\d{2}$/', $date)
            ? $date . '-01'
            : $date;
    }

    public function ope()
    {
        $this->show('calculateur');
    }

    public function delete($id)
    {
        $CalculToDelete = Calculs::find($id);

        if ($CalculToDelete) {
            $CalculToDelete->delete();

            header('Location: ' . $this->router->generate('Calcul-index'));
        }
    }
}
