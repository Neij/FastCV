<?php

namespace Controllers;


class WebController {
    public $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function displayPage() {
        require_once 'views/layout-web.phtml';
    }
}


