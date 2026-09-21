<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Jabatan_model');
        $this->load->model('Modul_model');
        $this->load->model('Akses_model');
    }

    public function index() {
        $data['title'] = 'Kelola Akses Jabatan';
        $data['jabatan'] = $this->Jabatan_model->get_all();
        // default jabatan pertama atau dari GET
        $jid = $this->input->get('jabatan_id') ?: ($data['jabatan'] ? $data['jabatan'][0]->id : 0);
        $data['selected_jabatan'] = $jid;
        $data['tree'] = $this->Modul_model->get_all_tree(); // hanya status 1
        $data['akses_ids'] = $this->Akses_model->get_by_jabatan($jid);

        if ($this->input->post('jabatan_id')) {
            $jid = $this->input->post('jabatan_id');
            $mods = $this->input->post('modul_ids') ?: [];
            $this->Akses_model->save_akses($jid, $mods);
            $this->session->set_flashdata('success','Akses disimpan untuk jabatan ID '.$jid);
            redirect('akses?jabatan_id='.$jid);
        }

        $this->render('akses/index', $data);
    }
}
