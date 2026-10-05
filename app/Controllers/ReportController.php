<?php 

namespace App\Controllers;

class ReportController extends CoreController {
    public function index() {
        $this->show('reports');
    }
}