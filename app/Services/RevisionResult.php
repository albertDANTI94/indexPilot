<?php

namespace App\Services;

class RevisionResult
{
    public function __construct(
        private float $ratio,
        private float $coefficient,
        private float $nouveauTarif,
        private float $coefFixe,
        private float $coefVariable
    ) {}

    public function getRatio(): float
    {
        return $this->ratio;
    }

    public function getCoefficient(): float
    {
        return $this->coefficient;
    }

    public function getNouveauTarif(): float
    {
        return $this->nouveauTarif;
    }

    public function getCoefFixe(): float
    {
        return $this->coefFixe;
    }

    public function getCoefVariable(): float
    {
        return $this->coefVariable;
    }

    // 🔥 BONUS (utile pour debug / API)
    public function toArray(): array
    {
        return [
            'ratio' => $this->ratio,
            'coefficient' => $this->coefficient,
            'nouveauTarif' => $this->nouveauTarif,
            'coefFixe' => $this->coefFixe,
            'coefVariable' => $this->coefVariable,
        ];
    }
}