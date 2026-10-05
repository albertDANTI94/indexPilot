<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Indices extends CoreModel {
    
    /**
     * @var string
     */
    private $idbank;
    /**
     * @var string
     */
    private $date;
    /**
     * @var float
     */
    private $valeur;

    /**
     * Get the value of idbank
     *
     * @return  string
     */
    public function getIdbank()
    {
        return $this->idbank;
    }

    /**
     * Set the value of idbank
     *
     * @param  string  $idbank
     *
     * @return  self
     */
    public function setIdbank(string $idbank)
    {
        $this->idbank = $idbank;

        return $this;
    }

    /**
     * Get the value of valeur
     *
     * @return  float
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set the value of valeur
     *
     * @param  float  $valeur
     *
     * @return  self
     */
    public function setValeur(float $valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }
    
    public static function getRecent()
    {
        $pdo = Database::getPDO();

        $sql = "
            SELECT
    i.idbank AS code,
    i.valeur,
    i.date,
    m.libelle,

    prev.valeur AS ancienne_valeur,

    COUNT(l.id) AS lots_impactes

    FROM indices i
    
    LEFT JOIN mapping_indices m
        ON m.idbank = i.idbank
    
    LEFT JOIN lots l
        ON l.libelle = i.idbank
    
    LEFT JOIN indices prev
        ON prev.idbank = i.idbank
        AND prev.date = (
            SELECT MAX(date)
            FROM indices p
            WHERE p.idbank = i.idbank
            AND p.date < i.date
        )
    
    WHERE i.date = (
        SELECT MAX(date)
        FROM indices x
        WHERE x.idbank = i.idbank
    )
    
    GROUP BY
        i.idbank,
        i.valeur,
        i.date,
        m.libelle,
        prev.valeur
    
    ORDER BY i.idbank
        ";
    
        $stmt = $pdo->query($sql);
    
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public static function getHistoryByCode(string $code)
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT
                date,
                valeur
            FROM indices
            WHERE idbank = :code
            AND date >= '2010-01-01'
            ORDER BY date ASC
        ";
    
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute([
            'code' => $code
        ]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
        /**
     * Retourne la date la plus récente disponible dans la table indices.
     *
     * @return string|null
     */
    public static function getMaxDate(): ?string
    {
        $pdo = Database::getPDO();

        $sql = "
            SELECT MAX(date) AS max_date
            FROM indices
        ";

        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['max_date'] ?? null;
    }

    /**
     * Retourne la liste des indices disponibles avec leur libellé.
     *
     * @return array
     */
    public static function getLibelles(): array
    {
        $pdo = Database::getPDO();

        $sql = "
            SELECT
                m.idbank,
                m.libelle
            FROM mapping_indices m
            ORDER BY m.libelle ASC
        ";

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne la valeur d'un indice pour une date donnée.
     *
     * @param string $idbank
     * @param string $date
     *
     * @return array|null
     */
    public static function findValueByIdbankAndDate(
        string $idbank,
        string $date
    ): ?array {
        $pdo = Database::getPDO();

        $sql = "
            SELECT
                idbank,
                date,
                valeur
            FROM indices
            WHERE idbank = :idbank
            AND date = :date
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'idbank' => $idbank,
            'date'   => $date
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

}