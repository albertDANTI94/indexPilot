<?php 

namespace App\Controllers;

class DocController extends CoreController {
      public function index(){
          $this->show('documents');
      }
}

