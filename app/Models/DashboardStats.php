<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class DashboardStats extends CoreModel
{
    public static function getKpis(int $userId): array
    {
        $pdo = Database::getPDO();

        // -------------------------
        // 1. Chantiers actifs
        // -------------------------
        $sqlChantiers = "
            SELECT COUNT(*) AS chantiers_actifs
            FROM chantiers
            WHERE user_id = :user_id
            AND status = 'active'
        ";

        $stmt = $pdo->prepare($sqlChantiers);
        $stmt->execute(['user_id' => $userId]);
        $chantiers = $stmt->fetch(PDO::FETCH_ASSOC);


        // -------------------------
        // 2. Calculs du mois
        // -------------------------
        $sqlCalculs = "
            SELECT COUNT(*) AS calculs_mois
            FROM calculs
            WHERE user_id = :user_id
            AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
        ";

        $stmt = $pdo->prepare($sqlCalculs);
        $stmt->execute(['user_id' => $userId]);
        $calculs = $stmt->fetch(PDO::FETCH_ASSOC);


        // -------------------------
        // 3. Économies (somme des gains)
        // -------------------------
        $sqlEconomies = "
            SELECT COALESCE(SUM(nouveau_tarif - tarif_origin), 0) AS economies
            FROM calculs
            WHERE user_id = :user_id
        ";

        $stmt = $pdo->prepare($sqlEconomies);
        $stmt->execute(['user_id' => $userId]);
        $economies = $stmt->fetch(PDO::FETCH_ASSOC);


        // -------------------------
        // 4. Alertes (placeholder pour l’instant)
        // -------------------------
        $sqlAlertes = "
            SELECT COUNT(*) AS alertes
            FROM activity_logs
            WHERE user_id = :user_id
            AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ";

        $stmt = $pdo->prepare($sqlAlertes);
        $stmt->execute(['user_id' => $userId]);
        $alertes = $stmt->fetch(PDO::FETCH_ASSOC);


        // -------------------------
        // RETURN FINAL
        // -------------------------
        return [
            'chantiers_actifs' => (int) ($chantiers['chantiers_actifs'] ?? 0),
            'calculs_mois'     => (int) ($calculs['calculs_mois'] ?? 0),
            'economies'        => (float) ($economies['economies'] ?? 0),
            'alertes'          => (int) ($alertes['alertes'] ?? 0),
        ];
    }
}