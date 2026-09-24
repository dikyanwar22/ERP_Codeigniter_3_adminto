<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }

    // GET /api/menu  (via route)
    public function index() {
        $menus = $this->Modul_model->get_menu_for_jabatan($this->jabatan_id);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($menus));
    }
}
