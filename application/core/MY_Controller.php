<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    protected $user;
    protected $jabatan_id;

    public function __construct() {
        parent::__construct();
        // cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $this->user = $this->session->userdata('user');
        $this->jabatan_id = $this->user['jabatan_id'];

        // load modul model untuk menu
        $this->load->model('Modul_model');
        $this->load->model('Akses_model');

        // cek akses url saat ini (kecuali dashboard, profile, akun)
        $uri = $this->uri->segment(1);
        $allowed_free = ['dashboard','profile','akun','auth','logout'];
        if ($uri && !in_array($uri, $allowed_free)) {
            // cari modul by url
            $modul = $this->Modul_model->get_by_url($uri.'/'.$this->uri->segment(2));
            if (!$modul) $modul = $this->Modul_model->get_by_url($uri);
            if ($modul) {
                // cek status hide
                if ($modul->status == 0) show_error('Modul disembunyikan (hide)', 403);
                // cek akses jabatan - hanya modul status 1 yang diakses
                if (!$this->Akses_model->can_access($this->jabatan_id, $modul->id)) {
                    show_error('Akses ditolak untuk jabatan Anda', 403);
                }
            }
        }
    }

    protected function render($view, $data = []) {
        // siapkan menu top sesuai jabatan & status=1
        $data['menus'] = $this->Modul_model->get_menu_for_jabatan($this->jabatan_id);
        $data['user'] = $this->user;
        $this->load->view('templates/header', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer', $data);
    }
}
