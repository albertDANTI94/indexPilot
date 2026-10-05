<?php

namespace App\Controllers;

use App\Models\Utilisateurs;
use App\Models\Chantiers;
use App\Models\DashboardStats;
use App\Models\Indices;
use App\Models\ActivityLogs;
use App\Models\Calculs;
use App\Models\Stats;

class DashboardController extends CoreController {

 
     
     public function data()
{
    $userId = $_SESSION["userId"] ?? null;

    if (!$userId) {
        die("Utilisateur non connecté");
    }

    // ---------------- USER ----------------
    $user = Utilisateurs::find($userId);

    // ---------------- CHANTIERS ----------------
    $chantiers = Chantiers::findAllByUserDashboard($userId);

    if (!$chantiers) {
        $chantiers = [];
    }

    // ---------------- KPI ----------------
    $kpis = DashboardStats::getKpis($userId);

    // ---------------- INDICES (table réelle) ----------------
    $indices = Indices::getRecent();

    // ---------------- ALERTES (future table activity_logs ou subscriptions) ----------------
    $alerts = ActivityLogs::getLatest($userId);

    // ---------------- CALCULS (calculs_tarifs OU calculs si renommé) ----------------
    $calculs = Calculs::getLatestByUser($userId);

    // ---------------- CHART (temporaire ou stats calculs) ----------------
    $chart = Stats::getChart($userId);

    $this->show('dashboard', [
        'user' => $user,
        'kpis' => $kpis,
        'indices' => $indices,
        'alerts' => $alerts,
        'chantiers' => $chantiers,
        'calculs' => $calculs,
        'chart' => $chart
    ]);
}
     
     

}