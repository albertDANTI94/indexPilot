<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Utilisateurs extends CoreModel
{

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $role;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $pending_token;

    /**
     * @var int
     */
    private $stripe_customer_id;

    /**
     * @var string
     */
    private $stripe_subscription_id;

    /**
     * @var int
     */
    private $plan_id;

    /**
     * @var int
     */
    private $last_login;

    private $entreprise;
    
    private $secteur;
    
    private $size;


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
     * Get the value of prenom
     *
     * @return  string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @param  string  $prenom
     *
     * @return  self
     */
    public function setPrenom(string $prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * Get the value of entreprise
     *
     * @return  string
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * Set the value of entreprise
     *
     * @param  string  $entreprise
     *
     * @return  self
     */
    public function setEntreprise(string $entreprise)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get the value of secteur
     *
     * @return  string
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set the value of secteur
     *
     * @param  string  $secteur
     *
     * @return  self
     */
    public function setSecteur(string $secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get the value of size
     *
     * @return  string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @param  string  $size
     *
     * @return  self
     */
    public function setSize(string $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of role
     *
     * @return  string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param  string  $role
     *
     * @return  self
     */
    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     *
     * @return  self
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of pending_token
     *
     * @return  string
     */
    public function getPendingToken()
    {
        return $this->pending_token;
    }

    /**
     * Set the value of pending_token
     *
     * @param  string  $pending_token
     *
     * @return  self
     */
    public function setPendingToken(string $pending_token)
    {
        $this->pending_token = $pending_token;

        return $this;
    }

    /**
     * Get the value of stripe_customer_id
     *
     * @return  int
     */
    public function getStripeCustomerId()
    {
        return $this->stripe_customer_id;
    }

    /**
     * Set the value of stripe_customer_id
     *
     * @param  int  $stripe_customer_id
     *
     * @return  self
     */
    public function setStripeCustomerId(int $stripe_customer_id)
    {
        $this->stripe_customer_id = $stripe_customer_id;

        return $this;
    }

    /**
     * Get the value of stripe_subscription_id
     *
     * @return  string
     */
    public function getStripeSubscriptionId()
    {
        return $this->stripe_subscription_id;
    }

    /**
     * Set the value of stripe_subscription_id
     *
     * @param  string  $stripe_subscription_id
     *
     * @return  self
     */
    public function setStripeSsubscriptionId(string $stripe_subscription_id)
    {
        $this->stripe_subscription_id = $stripe_subscription_id;

        return $this;
    }

    /**
     * Get the value of plan_id
     *
     * @return  int
     */
    public function getPlanId()
    {
        return $this->plan_id;
    }

    /**
     * Set the value of plan_id
     *
     * @param  int  $plan_id
     *
     * @return  self
     */
    public function setPlanId(int $plan_id)
    {
        $this->plan_id = $plan_id;

        return $this;
    }

    /**
     * Get the value of last_login
     *
     * @return  int
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * Set the value of last_login
     *
     * @param  int  $last_login
     *
     * @return  self
     */
    public function setLastLogin(int $last_login)
    {
        $this->last_login = $last_login;

        return $this;
    }

    
    public static function find($id)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = '
            SELECT *
            FROM utilisateurs
            WHERE id = ' . $id;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $user = $pdoStatement->fetchObject('App\Models\Utilisateurs');

        // retourner le résultat
        return $user;
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `utilisateurs`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Utilisateurs');

        return $results;
    }

    public static function findByEmail($email)
    {
        $pdo = Database::getPDO();

        // on écrit la requête
        $sql = "SELECT * FROM `utilisateurs` 
        WHERE email = :email";

        // on prépare la requête
        $stmt = $pdo->prepare($sql);

        // on "bind" (associe) nos paramètres
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        return $stmt->fetchObject(self::class);
    }
    
    public function insert()
    {
    // Récupération de l'objet PDO représentant la connexion à la DB
    $pdo = Database::getPDO();
    
    // on écrit la requête
    $sql = "INSERT INTO `utilisateurs` (`email`, `password`, `prenom`, `nom`, `entreprise`, `secteur`, `size`) 
            VALUES (:email, :password, :prenom, :nom, :entreprise, :secteur, :size)";
    
    // on prépare la requête
    $stmt = $pdo->prepare($sql);
    
    // on "bind" (associe) nos paramètres
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':prenom', $this->prenom);
    $stmt->bindParam(':nom', $this->nom);
    $stmt->bindParam(':entreprise', $this->entreprise);
    $stmt->bindParam(':secteur', $this->secteur);
    $stmt->bindParam(':size', $this->size);
    
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
}
