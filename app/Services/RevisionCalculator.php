<?php

namespace App\Services;

use InvalidArgumentException;

class RevisionCalculator
{
    public function calculate(
        float $tarifOrigine,
        float $indice0,
        float $indiceN,
        float $partFerme
    ): RevisionResult {

        $this->validate($tarifOrigine, $indice0, $indiceN, $partFerme);

        $ratio = $this->computeRatio($indice0, $indiceN);

        $coefFixe = $partFerme / 100;
        $coefVariable = (100 - $partFerme) / 100;

        $coefficient = $this->round3($ratio);

        $nouveauTarif = $this->round2(
            $tarifOrigine * ($coefFixe + ($coefVariable * $ratio))
        );

        return new RevisionResult(
            $ratio,
            $coefficient,
            $nouveauTarif,
            $coefFixe,
            $coefVariable
        );
    }

    private function computeRatio(float $indice0, float $indiceN): float
    {
        return $indiceN / $indice0;
    }

    private function round3(float $value): float
    {
        return round(ceil($value * 1000) / 1000, 3);
    }

    private function round2(float $value): float
    {
        return round($value, 2);
    }

    private function validate(
        float $tarifOrigine,
        float $indice0,
        float $indiceN,
        float $partFerme
    ): void {

        if ($tarifOrigine <= 0) {
            throw new InvalidArgumentException("Tarif invalide");
        }

        if ($indice0 <= 0) {
            throw new InvalidArgumentException("Indice 0 invalide");
        }

        if ($indiceN <= 0) {
            throw new InvalidArgumentException("Indice N invalide");
        }

        if ($partFerme < 0 || $partFerme > 100) {
            throw new InvalidArgumentException("Part ferme invalide");
        }
    }
}