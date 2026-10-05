<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Calculs extends CoreModel
{
    /**
     * @var int
     */
    private $user_id;
    /**
     * @var string
     */
    private $reference;
    /**
     * @var int
     */
    private $tarif_origin;
    /**
     * @var int
     */
     private $part_ferme;
    /**
     * @var float
     */
    private $indice_n0;
    /**
     * @var float
     */
    private $indice_nn;
    /**
     * @var float
     */
    private $coefficient;
    /**
     * @var float
     */
    private $nouveau_tarif;
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
     * Get the value of user_id
     *
     * @return  int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of tarif_origin
     *
     * @param  string  $tarif_origin
     *
     * @return  self
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
    
    /**
     * Get the value of reference
     *
     * @return  int
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the value of reference
     *
     * @param  string  $reference
     *
     * @return  self
     */
    public function setReference(string $reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the value of tarif_origin
     *
     * @return  string
     */
    public function getTarifOrigin()
    {
        return $this->tarif_origin;
    }

    /**
     * Set the value of tarif_origin
     *
     * @param  string  $tarif_origin
     *
     * @return  self
     */
    public function setTarifOrigin(string $tarif_origin)
    {
        $this->tarif_origin = $tarif_origin;

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
     * @param  string  $part_ferme
     *
     * @return  self
     */
    public function setPartFerme(int $user_id)
    {
        $this->part_ferme = $part_ferme;

        return $this;
    }

    /**
     * Get the value of indice_n0
     *
     * @return  string
     */
    public function getIndiceN0()
    {
        return $this->indice_n0;
    }

    /**
     * Set the value of indice_n0
     *
     * @param  string  $indice_n0
     *
     * @return  self
     */
    public function setIndiceN0(string $indice_n0)
    {
        $this->indice_n0 = $indice_n0;

        return $this;
    }

    /**
     * Get the value of indice_nn
     *
     * @return  string
     */
    public function getIndiceNn()
    {
        return $this->indice_nn;
    }

    /**
     * Set the value of indice_nn
     *
     * @param  string  $indice_nn
     *
     * @return  self
     */
    public function setIndiceNn(string $indice_nn)
    {
        $this->indice_nn = $indice_nn;

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
    public function setCoefficient(string $coefficient)
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
     * Get the value of date0
     *
     * @return  string
     */
    public function getDate0()
    {
        return $this->date0;
    }

    /**
     * Set the value of date0
     *
     * @param  string  $date0
     *
     * @return  self
     */
    public function setDate0(string $date0)
    {
        $this->date0 = $date0;

        return $this;
    }

    /**
     * Get the value of dateN
     *
     * @return  string
     */
    public function getDateN()
    {
        return $this->dateN;
    }

    /**
     * Set the value of dateN
     *
     * @param  string  $dateN
     *
     * @return  self
     */
    public function setDateN(string $dateN)
    {
        $this->dateN = $dateN;

        return $this;
    }
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table calculs en fonction d'un id donné
     *
     * @param int $lotId ID du lot
     * @return lot
     */
    public static function find($calculId)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM calculs
            WHERE id = ' . $calculId;


        $pdoStatement = $pdo->query($sql);

        $result = $pdoStatement->fetchObject('App\Models\Caluls');

        return $result;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table calculs
     *
     * @return Calcul[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `calculs`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Calculs');

        return $results;
    }
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table lots en fonction d'un id utilisateur
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function findAllByUser($userId)
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT *
            FROM calculs
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ";
    
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            'App\Models\Calculs'
        );
    }
    
    public static function getLatestByUser($userId)
    {
        $pdo = Database::getPDO();

        $sql = "
            SELECT *
            FROM calculs
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT 10
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getLibelleText($userId)
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT
                calculs.*,
                mapping_indices.libelle AS libelle_texte
            FROM calculs
            LEFT JOIN mapping_indices
                ON calculs.libelle = mapping_indices.idbank
            WHERE calculs.user_id = :user_id
        ";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getCalculsWithLibelle($userId)
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT
                c.*,
                m.libelle AS libelle_text
            FROM calculs c
            LEFT JOIN mapping_indices m
                ON CAST(c.libelle AS CHAR) = CAST(m.idbank AS CHAR)
            WHERE c.user_id = :user_id
            ORDER BY c.created_at DESC
        ";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete() {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            DELETE FROM `calculs`
            WHERE `id` = :id
        ";

        // on prépare la requête
        $stmt = $pdo->prepare($sql);

        // on "bind" (associe) nos paramètres
        $stmt->bindParam(':id', $this->id);

        // on execute la requête et on renvoit true ou false
        return $stmt->execute();
    }
}
