<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
    public function __construct() {
        parent::__construct();
        // MY_Controller sudah cek login & load Modul_model
    }

    // GET /api/menu -> JSON menu top untuk jabatan login (hanya status Show)
    public function menu() {
        $menus = $this->Modul_model->get_menu_for_jabatan($this->jabatan_id);
        // header JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($menus));
    }
}
