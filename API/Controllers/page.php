<?php

/**
 * Static Pages
 */
class pageController extends ControllerAPI {

    public function index() {
        $this->setJsonParser($this->Model->GetAll());
    }

    public function i($ID) {
        $this->setJsonParser($this->Model->GetByID(intval($ID)));
    }

}
