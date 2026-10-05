<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Chantiers extends CoreModel
{
    /**
     * @var int
     */
    private $user_id;
    /**
     * @var string
     */
    private $nom;
    /**
     * @var string
     */
    private $client;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $status;
    
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
     * Set the value of user_id
     *
     * @param  int  $user_id
     *
     * @return  self
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
     * Get the value of client
     *
     * @return  string
     */ 
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @param  string  $client
     *
     * @return  self
     */ 
    public function setClient(string $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

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
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($chantiersId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `chantiers` WHERE `id` =' . $chantiersId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $chantier = $pdoStatement->fetchObject('App\Models\Chantiers');

        // retourner le résultat
        return $chantier;
    }
    
    /**
     * Méthode permettant de récupérer tous les enregistrements de la table chantiers
     * findAll() est une méthode STATIQUE, ça veut dire qu'on peut l'appeler SANS devoir instancier d'objet
     * les méthodes STATIQUES sont des méthodes liées à la classe, et pas à un objet spécifique !
     *
     * @return Chantier[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `chantiers`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Chantiers');

        return $results;
    }
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table chantiers en fonction d'un id utilisateur
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function findAllByUser($userId)
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT *
            FROM chantiers
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ";
    
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            'App\Models\Chantiers'
        );
    }
    
    public static function findAllByUserDashboard($userId)
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT *
            FROM chantiers
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT 4
        ";
    
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->fetchAll(
            PDO::FETCH_CLASS,
            'App\Models\Chantiers'
        );
    }
    
    public static function getKPIsByUser($userId): array
    {
        $pdo = Database::getPDO();
    
        $sql = "
            SELECT
                c.id,
                COUNT(l.id) AS lots_count,
                COALESCE(SUM(l.tarif_origine), 0) AS total_origine,
                COALESCE(SUM(l.nouveau_tarif), 0) AS total_nouveau
            FROM chantiers c
            LEFT JOIN lots l ON l.chantier_id = c.id
            WHERE c.user_id = :user_id
            GROUP BY c.id
        ";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId
        ]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Méthode permettant d'ajouter un enregistrement dans la table chantiers
     * L'objet courant doit contenir toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     *
     * @return bool
     */
    public function insert() {
        
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `chantiers` (nom, client, description, status) 
            VALUES ('$this->nom', '$this->client', '$this->description', '$this->status')
        ";

        //! ATTENTION, la requête ci-dessus est vulnérable aux injections SQL ...
        //* pour s'en prémunir, on doit utiliser des REQUÊTES PRÉPARÉES

        // on écrit la requête
        $sql = "INSERT INTO `chantiers` (user_id, nom, client, description, status) 
                VALUES (:user_id, :nom, :client, :description, :status)";

        // on prépare la requête
        $stmt = $pdo->prepare($sql);

        // on "bind" (associe) nos paramètres
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':client', $this->client);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);

        // on lance la requête avec execute()
        // qui renvoit true si tout s'est bien passé, false sinon !
        if ($stmt->execute()) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    
    }
    
    /**
     * Méthode permettant de mettre à jour un enregistrement dans la table product
     * L'objet courant doit contenir l'id, et toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     *
     * @return bool
     */
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `chantiers`
            SET
                nom = :nom,
                client = :client,
                description = :description,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ";

        // on prépare la requête
        $stmt = $pdo->prepare($sql);

        // on "bind" (associe) nos paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':client', $this->client);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        // on execute la requête et on renvoit true ou false
        return $stmt->execute();
    }

    public function delete() {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            DELETE FROM `chantiers`
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