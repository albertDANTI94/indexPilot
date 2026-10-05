<?PHP

namespace App\Controllers;

use App\Models\Indices;

class InseeController extends CoreController {
     public function index()
    {
        
        
        $indices = Indices::getRecent();
        
        if (!$indices) {
            $indices = [];
        }
        
        $indicesData = [];
    
        foreach ($indices as $indice) {
    
            $ancienne = (float)($indice['ancienne_valeur'] ?? 0);
            $valeur = (float)$indice['valeur'];
    
            $variation = ($ancienne > 0)
                ? (($valeur - $ancienne) / $ancienne) * 100
                : 0;
    
            $indicesData[] = [
                'code' => $indice['code'],
                'libelle' => $indice['libelle'],
                'valeur' => $valeur,
                'ancienne_valeur' => $ancienne,
                'variation' => $variation,
                'date' => str_replace(' ', 'T', $indice['date']),
                'lots' => (int)($indice['lots_impactes'] ?? 0),
                'family' => substr($indice['code'], 0, 2),
                'history' => Indices::getHistoryByCode($indice['code']),
                'famille' =>
                    preg_match('/BT\d+/i', $indice['libelle']) ? 'BT' :
                    (preg_match('/TP\d+/i', $indice['libelle']) ? 'TP' :
                    (str_contains($indice['libelle'], 'ICC') ? 'ICC' : 'AUTRE')),
            ];
        }
        
    
        $this->show('indices', [
            'indices' => $indicesData
        ]);
    }
    
    public function maxDate(): void
    {
        try {
            $maxDate = \App\Models\Indices::getMaxDate();
    
            header('Content-Type: application/json; charset=utf-8');
    
            echo json_encode([
                'success' => true,
                'max_date' => $maxDate
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
    
            header('Content-Type: application/json; charset=utf-8');
    
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération de la date maximale.'
            ]);
        }
    }
    
    public function libelles(): void
    {
        try {
            $libelles = \App\Models\Indices::getLibelles();
    
            header('Content-Type: application/json; charset=utf-8');
    
            echo json_encode([
                'success' => true,
                'libelles' => $libelles
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
    
            header('Content-Type: application/json; charset=utf-8');
    
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des libellés.'
            ]);
        }
    }
    
    public function valeur(): void
    {
        try {
            $idbank = $_GET['idbank'] ?? '';
            $date   = $_GET['date'] ?? '';
    
            if ($idbank === '' || $date === '') {
                http_response_code(400);
    
                header('Content-Type: application/json; charset=utf-8');
    
                echo json_encode([
                    'success' => false,
                    'error' => 'Les paramètres idbank et date sont obligatoires.'
                ]);
    
                return;
            }
    
            $indice = \App\Models\Indices::findValueByIdbankAndDate(
                $idbank,
                $date
            );
    
            if ($indice === null) {
                http_response_code(404);
    
                header('Content-Type: application/json; charset=utf-8');
    
                echo json_encode([
                    'success' => false,
                    'error' => 'Indice introuvable.'
                ]);
    
                return;
            }
    
            header('Content-Type: application/json; charset=utf-8');
    
            echo json_encode([
                'success' => true,
                'indice' => $indice
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
    
            header('Content-Type: application/json; charset=utf-8');
    
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération de l’indice.'
            ]);
        }
    }
    
    
}