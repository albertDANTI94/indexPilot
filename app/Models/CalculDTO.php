<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class CalculDTO extends CoreModel
{
    public $id;
    public $reference;
    public $libelle;
    public $libelleTexte;

    public $tarifOrigin;
    public $nouveauTarif;
    public $impact;

    public $indiceN0;
    public $indiceNn;
    public $variation;

    public $partFerme;

    public $date;
    public $date0;
    public $dateN;

    public static function fromArray(array $c): self
    {
        $dto = new self();

        $dto->id = $c['id'];
        $dto->reference = $c['reference'];

        $dto->libelle = $c['libelle'];
        $dto->libelleTexte = $c['libelle_text'] ?? null;

        $dto->tarifOrigin = (float)$c['tarif_origin'];
        $dto->nouveauTarif = (float)$c['nouveau_tarif'];

        $dto->indiceN0 = (float)$c['indice_n0'];
        $dto->indiceNn = (float)$c['indice_nn'];

        $dto->variation = ($dto->indiceN0 > 0)
            ? (($dto->indiceNn - $dto->indiceN0) / $dto->indiceN0) * 100
            : 0;

        $dto->impact = $dto->nouveauTarif - $dto->tarifOrigin;

        $dto->partFerme = (float)$c['part_ferme'];

        $dto->date = str_replace(' ', 'T', $c['created_at']);
        $dto->date0 = $c['date0'] ?? null;
        $dto->dateN = $c['dateN'] ?? null;

        return $dto;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}