<?php

namespace Controllers;

class AboutController extends AppController {
    public function __construct() {
        parent::__construct("about", "views/about.phtml"); 
    }
}


