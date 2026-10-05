<?php

namespace App\Services;

use App\Models\Lots;
use InvalidArgumentException;

class LotService
{
    public function __construct(
        private RevisionCalculator $calculator
    ) {}

    /**
     * Création d'un lot métier complet
     */
    public function create(array $data, int $userId): array
    {
        $this->validate($data);

        // ----------------------------
        // CALCUL MÉTIER
        // ----------------------------
        $result = $this->calculator->calculate(
            $data['tarif_origine'],
            $data['indice0'],
            $data['indiceN'],
            $data['part_ferme']
        );

        // ----------------------------
        // HYDRATATION MODEL
        // ----------------------------
        $lot = new Lots();

        $lot->setUserId($userId);
        $lot->setChantierId((int)$data['chantier_id']);
        $lot->setNom($data['nom']);
        $lot->setTarifOrigine((float)$data['tarif_origine']);
        $lot->setPartFerme((float)$data['part_ferme']);
        $lot->setLibelle($data['libelle']);
        $lot->setDate0($this->normalizeDate($data['date0']));
        $lot->setDateN($this->normalizeDate($data['dateN']));
        $lot->setIndice0((float)$data['indice0']);
        $lot->setIndiceN((float)$data['indiceN']);

        $lot->setCoefficient($result->getCoefficient());
        $lot->setNouveauTarif($result->getNouveauTarif());
        $lot->setStatus($data['status']);

        // ----------------------------
        // INSERT BDD
        // ----------------------------
        if (!$lot->insert()) {
            throw new \RuntimeException("Erreur lors de l'insertion du lot");
        }

        return [
            'success' => true,
            'lot_id' => $lot->getId(),
            'coefficient' => $result->getCoefficient(),
            'nouveau_tarif' => $result->getNouveauTarif()
        ];
    }

    /**
     * UPDATE métier complet
     */
    public function update(int $id, array $data, int $userId): array
    {
        $this->validate($data);

        $lot = Lots::findOwnedByUser($id, $userId);

        if (!$lot) {
            throw new \RuntimeException("Lot introuvable");
        }

        $result = $this->calculator->calculate(
            $data['tarif_origine'],
            $data['indice0'],
            $data['indiceN'],
            $data['part_ferme']
        );

        $lot->setNom($data['nom']);
        $lot->setChantierId((int)$data['chantier_id']);
        $lot->setTarifOrigine((float)$data['tarif_origine']);
        $lot->setPartFerme((float)$data['part_ferme']);
        $lot->setLibelle($data['libelle']);
        $lot->setDate0($this->normalizeDate($data['date0']));
        $lot->setDateN($this->normalizeDate($data['dateN']));
        $lot->setIndice0((float)$data['indice0']);
        $lot->setIndiceN((float)$data['indiceN']);

        $lot->setCoefficient($result->getCoefficient());
        $lot->setNouveauTarif($result->getNouveauTarif());
        $lot->setStatus($data['status']);

        if (!$lot->update()) {
            throw new \RuntimeException("Erreur lors de la mise à jour du lot");
        }

        return [
            'success' => true,
            'lot_id' => $id,
            'coefficient' => $result->getCoefficient(),
            'nouveau_tarif' => $result->getNouveauTarif()
        ];
    }


    private function normalizeDate(string $date): string
    {
        return preg_match('/^\d{4}-\d{2}$/', $date)
            ? $date . '-01'
            : $date;
    }

    /**
     * VALIDATION MÉTIER CENTRALISÉE
     */
    private function validate(array $data): void
    {
        $required = [
            'chantier_id',
            'nom',
            'tarif_origine',
            'part_ferme',
            'libelle',
            'date0',
            'dateN',
            'indice0',
            'indiceN',
            'status'
        ];

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new InvalidArgumentException("Champ manquant : $field");
            }
        }

        if (strlen($data['nom']) < 3) {
            throw new InvalidArgumentException("Nom trop court");
        }

        if (strlen($data['nom']) > 64) {
            throw new InvalidArgumentException("Nom trop long");
        }
    }
}

