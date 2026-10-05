<?php

namespace App\Controllers;

class AlertController extends CoreController {
    public function index() {
        $this->show('alerts');
    }
}