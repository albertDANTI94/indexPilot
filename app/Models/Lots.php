<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Lots extends CoreModel
{

    /**
     * @var int
     */
    private $chantier_id;
    
    private $user_id;
    /**
     * @var string
     */
    private $chantier_nom;
    /**
     * @var string
     */
    private $nom;
    /**
     * @var int
     */
    private $tarif_origine;
    /**
     * @var int
     */
    private $part_ferme;
    /**
     * @var string
     */
    private $libelle;
    /**
     * @var string
     */
    private $date0;
    /**
     * @var string
     */
    private $dateN;
    /**
     * @var float
     */
    private $indice0;
    /**
     * @var float
     */
    private $indiceN;
    /**
     * @var string
     */
    private $coefficient;
    /**
     * @var string
     */
    private $nouveau_tarif;

    /**
     * @var string
     */
    private $status;
    
    private $libelle_texte;

    /**
     * Get the value of chantier_id
     *
     * @return  int
     */ 
    public function getChantierId()
    {
        return $this->chantier_id;
    }

    /**
     * Set the value of chantier_id
     *
     * @param  int  $chantier_id
     *
     * @return  self
     */ 
    public function setChantierId(int $chantier_id)
    {
        $this->chantier_id = $chantier_id;

        return $this;
    }
    
    public function getChantierNom(): string
    {
        return $this->chantier_nom;
    }
    
    public function setChantierNom(string $chantier_nom)
    {
        $this->chantier_nom = $chantier_nom;

        return $this;
    }
    
    /**
     * Get the value of user_id
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    
    /**
     * Set the value of user_id
     *
     * @param int $user_id
     *
     * @return self
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    
        return $this;
    }

    /**
     * Get the value of nom
     *
     * @return  string
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @param  string  $nom
     *
     * @return  self
     */ 
    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of tarif_origine
     *
     * @return  int
     */ 
    public function getTarifOrigine()
    {
        return $this->tarif_origine;
    }

    /**
     * Set the value of tarif_origine
     *
     * @param  int  $tarif_origine
     *
     * @return  self
     */ 
    public function setTarifOrigine(int $tarif_origine)
    {
        $this->tarif_origine = $tarif_origine;

        return $this;
    }

    /**
     * Get the value of part_ferme
     *
     * @return  int
     */ 
    public function getPartFerme()
    {
        return $this->part_ferme;
    }

    /**
     * Set the value of part_ferme
     *
     * @param  int  $part_ferme
     *
     * @return  self
     */ 
    public function setPartFerme(int $part_ferme)
    {
        $this->part_ferme = $part_ferme;

        return $this;
    }

    /**
     * Get the value of libelle
     *
     * @return  string
     */ 
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set the value of libelle
     *
     * @param  string  $libelle
     *
     * @return  self
     */ 
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get the value of date_depart
     *
     * @return  string
     */ 
    public function getDate0()
    {
        return $this->date0;
    }

    /**
     * Set the value of date_depart
     *
     * @param  string  $date_depart
     *
     * @return  self
     */ 
    public function setDate0(string $date0)
    {
        $this->date0 = $date0;

        return $this;
    }

    /**
     * Get the value of date_fin
     *
     * @return  string
     */ 
    public function getDateN()
    {
        return $this->dateN;
    }

    /**
     * Set the value of date_fin
     *
     * @param  string  $date_fin
     *
     * @return  self
     */ 
    public function setDateN(string $dateN)
    {
        $this->dateN = $dateN;

        return $this;
    }

    /**
     * Get the value of indice0
     *
     * @return  float
     */ 
    public function getIndice0()
    {
        return $this->indice0;
    }

    /**
     * Set the value of indice0
     *
     * @param  float  $indice0
     *
     * @return  self
     */ 
    public function setIndice0(float $indice0)
    {
        $this->indice0 = $indice0;

        return $this;
    }

    /**
     * Get the value of indiceN
     *
     * @return  float
     */ 
    public function getIndiceN()
    {
        return $this->indiceN;
    }

    /**
     * Set the value of indiceN
     *
     * @param  float  $indiceN
     *
     * @return  self
     */ 
    public function setIndiceN(float $indiceN)
    {
        $this->indiceN = $indiceN;

        return $this;
    }

    /**
     * Get the value of coefficient
     *
     * @return  string
     */ 
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * Set the value of coefficient
     *
     * @param  string  $coefficient
     *
     * @return  self
     */ 
    public function setCoefficient(float $coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get the value of nouveau_tarif
     *
     * @return  string
     */ 
    public function getNouveauTarif()
    {
        return $this->nouveau_tarif;
    }

    /**
     * Set the value of nouveau_tarif
     *
     * @param  string  $nouveau_tarif
     *
     * @return  self
     */ 
    public function setNouveauTarif(string $nouveau_tarif)
    {
        $this->nouveau_tarif = $nouveau_tarif;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */ 
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }
    
    public function getLibelleTexte()
    {
        return $this->libelle_texte;
    }
    
    public function setLibelleTexte(string $libelle_texte)
{
    $this->libelle_texte = $libelle_texte;

    return $this;
}
    
    /* =========================
        FIND METHODS
    ========================= */

    public static function find(int $id): ?self
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("SELECT * FROM lots WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public static function findOwnedByUser(int $id, int $userId): ?self
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            SELECT *
            FROM lots
            WHERE id = :id AND user_id = :user_id
        ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public static function findAllByUser(int $userId): array
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            SELECT *
            FROM lots
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ");

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function findWithLibelle(int $id): ?self
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            SELECT lots.*, mi.libelle AS libelle_texte
            FROM lots
            LEFT JOIN mapping_indices mi ON mi.idbank = lots.libelle
            WHERE lots.id = :id
        ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch() ?: null;
    }

    public static function findAllWithChantier(int $userId): array
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            SELECT
                l.*,
                c.nom AS chantier_nom,
                mi.libelle AS libelle_texte
            FROM lots l
            LEFT JOIN chantiers c ON c.id = l.chantier_id
            LEFT JOIN mapping_indices mi ON mi.idbank = l.libelle
            WHERE l.user_id = :user_id
            ORDER BY l.created_at DESC
        ");

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
    
    public static function findAllByChantierIds(array $chantierIds): array
    {
        if (empty($chantierIds)) {
            return [];
        }
    
        $pdo = Database::getPDO();
    
        $placeholders = implode(',', array_fill(0, count($chantierIds), '?'));
    
        $stmt = $pdo->prepare("
            SELECT
                id,
                chantier_id,
                nom,
                tarif_origine,
                indice0,
                indiceN,
                coefficient,
                nouveau_tarif
            FROM lots
            WHERE chantier_id IN ($placeholders)
            ORDER BY id ASC
        ");
    
        $stmt->execute($chantierIds);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
        WRITE METHODS
    ========================= */

    public function insert(): bool
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            INSERT INTO lots (
                user_id,
                chantier_id,
                nom,
                tarif_origine,
                part_ferme,
                libelle,
                date0,
                dateN,
                indice0,
                indiceN,
                coefficient,
                nouveau_tarif,
                status
            )
            VALUES (
                :user_id,
                :chantier_id,
                :nom,
                :tarif_origine,
                :part_ferme,
                :libelle,
                :date0,
                :dateN,
                :indice0,
                :indiceN,
                :coefficient,
                :nouveau_tarif,
                :status
            )
        ");

        return $stmt->execute([
            'user_id' => $this->user_id,
            'chantier_id' => $this->chantier_id,
            'nom' => $this->nom,
            'tarif_origine' => $this->tarif_origine,
            'part_ferme' => $this->part_ferme,
            'libelle' => $this->libelle,
            'date0' => $this->date0,
            'dateN' => $this->dateN,
            'indice0' => $this->indice0,
            'indiceN' => $this->indiceN,
            'coefficient' => $this->coefficient,
            'nouveau_tarif' => $this->nouveau_tarif,
            'status' => $this->status,
        ]);
    }

    public function update(): bool
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            UPDATE lots SET
                nom = :nom,
                chantier_id = :chantier_id,
                tarif_origine = :tarif_origine,
                part_ferme = :part_ferme,
                libelle = :libelle,
                date0 = :date0,
                dateN = :dateN,
                indice0 = :indice0,
                indiceN = :indiceN,
                coefficient = :coefficient,
                nouveau_tarif = :nouveau_tarif,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $this->id,
            'nom' => $this->nom,
            'chantier_id' => $this->chantier_id,
            'tarif_origine' => $this->tarif_origine,
            'part_ferme' => $this->part_ferme,
            'libelle' => $this->libelle,
            'date0' => $this->date0,
            'dateN' => $this->dateN,
            'indice0' => $this->indice0,
            'indiceN' => $this->indiceN,
            'coefficient' => $this->coefficient,
            'nouveau_tarif' => $this->nouveau_tarif,
            'status' => $this->status,
        ]);
    }

    public function delete(): bool
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("DELETE FROM lots WHERE id = :id");

        return $stmt->execute([
            'id' => $this->id
        ]);
    }
    
    
}
