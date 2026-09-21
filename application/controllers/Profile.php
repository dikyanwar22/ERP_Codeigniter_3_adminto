<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Jabatan_model');
    }
    public function index() {
        $data['title'] = 'Profile Saya';
        $data['u'] = $this->User_model->get_by_id($this->user['id']);
        $data['jabatan'] = $this->Jabatan_model->get_all();
        if ($this->input->post()) {
            $this->form_validation->set_rules('nama','Nama','required|trim');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('nik','NIK','required|trim');
            if ($this->form_validation->run() === TRUE) {
                $id = $this->user['id'];
                $nik = $this->input->post('nik', true);
                $email = $this->input->post('email', true);
                if ($this->User_model->cek_nik_email($nik,$email,$id)) {
                    $this->session->set_flashdata('error','NIK/Email sudah dipakai');
                } else {
                    $upd = [
                        'nik'=>$nik,
                        'email'=>$email,
                        'nama'=>$this->input->post('nama', true)
                    ];
                    $pass = $this->input->post('password', true);
                    if ($pass) {
                        $upd['password'] = password_hash($pass, PASSWORD_BCRYPT);
                    }
                    $this->User_model->update($id,$upd);
                    $fresh = $this->User_model->get_by_id($id);
                    $jab = $this->Jabatan_model->get_by_id($fresh->jabatan_id);
                    $this->session->set_userdata('user', [
                        'id'=>$fresh->id,'nik'=>$fresh->nik,'email'=>$fresh->email,'nama'=>$fresh->nama,
                        'jabatan_id'=>$fresh->jabatan_id,'jabatan_nama'=>$jab->nama_jabatan,'status'=>$fresh->status,'foto'=>$fresh->foto
                    ]);
                    $this->session->set_flashdata('success','Profile diperbarui');
                    redirect('profile');
                }
            }
        }
        $this->render('profile/index', $data);
    }
}
