<?php

namespace App\Controllers;

class UserController extends CoreController {
    public function index(){
        $this->show('settings');
    }
}