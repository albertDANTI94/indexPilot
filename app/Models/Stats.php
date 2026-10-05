<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Stats extends CoreModel
{
    public static function getChart($userId)
    {
        $pdo = Database::getPDO();

        $sql = "
            SELECT DATE(created_at) as date, COUNT(*) as total
            FROM calculs
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ";

        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'labels' => array_column($rows, 'date'),
            'values' => array_column($rows, 'total')
        ];
    }
}