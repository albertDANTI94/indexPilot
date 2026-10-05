<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class ActivityLogs extends CoreModel
{

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $payload;

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
     * Get the value of action
     *
     * @return  string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @param  string  $action
     *
     * @return  self
     */
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }



    public static function getLatest($userId)
    {
        $pdo = Database::getPDO();

        $sql = "
            SELECT *
            FROM activity_logs
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
