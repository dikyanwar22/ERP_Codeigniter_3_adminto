<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Jabatan_model');
    }

    public function index() {
        $data['title'] = 'Daftar Akun';
        $data['users'] = $this->User_model->get_all();
        $this->render('akun/index', $data);
    }

    public function create() {
        // daftar akun - bisa diakses tanpa login? tapi kita buat harus login, kecuali jika belum ada user? untuk sekarang butuh login
        // supaya bisa daftar akun baru oleh admin, kita pakai MY_Controller (harus login)
        // Jika ingin publik, buat method register terpisah - kita buat create tetap butuh login admin
        $data['title'] = 'Tambah Akun';
        $data['jabatan'] = $this->Jabatan_model->get_all();
        if ($this->input->post()) {
            $this->form_validation->set_rules('nik','NIK','required|trim|min_length[16]|max_length[20]');
            $this->form_validation->set_rules('email','Email','required|valid_email|trim');
            $this->form_validation->set_rules('nama','Nama','required|trim');
            $this->form_validation->set_rules('jabatan_id','Jabatan','required|integer');
            $this->form_validation->set_rules('status','Status','required|in_list[0,1]');
            $this->form_validation->set_rules('password','Password','required');
            $this->form_validation->set_rules('passconf','Konfirmasi Password','required|matches[password]');
            if ($this->form_validation->run() === TRUE) {
                $nik = $this->input->post('nik', true);
                $email = $this->input->post('email', true);
                if ($this->User_model->cek_nik_email($nik,$email)) {
                    $this->session->set_flashdata('error','NIK atau Email sudah terdaftar');
                } else {
                    $ins = [
                        'nik' => $nik,
                        'email' => $email,
                        'nama' => $this->input->post('nama', true),
                        'jabatan_id' => $this->input->post('jabatan_id', true),
                        'status' => $this->input->post('status', true),
                        'password' => password_hash($this->input->post('password', true), PASSWORD_BCRYPT)
                    ];
                    if ($this->User_model->insert($ins)) {
                        $this->session->set_flashdata('success','Akun berhasil dibuat');
                        redirect('akun');
                    } else {
                        $this->session->set_flashdata('error','Gagal menyimpan');
                    }
                }
            }
        }
        $this->render('akun/create', $data);
    }

    // publik register tanpa login (untuk daftar akun awal) - bisa diakses /akun/register
    public function register() {
        // jika sudah login redirect
        if ($this->session->userdata('logged_in')) redirect('dashboard');
        $this->load->model('Jabatan_model');
        $data['title'] = 'Daftar Akun Baru';
        $data['jabatan'] = $this->Jabatan_model->get_all();
        if ($this->input->post()) {
            $this->form_validation->set_rules('nik','NIK','required|trim');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('nama','Nama','required');
            $this->form_validation->set_rules('jabatan_id','Jabatan','required');
            $this->form_validation->set_rules('password','Password','required');
            $this->form_validation->set_rules('passconf','Konfirmasi','required|matches[password]');
            if ($this->form_validation->run() === TRUE) {
                $nik = $this->input->post('nik', true);
                $email = $this->input->post('email', true);
                if ($this->User_model->cek_nik_email($nik,$email)) {
                    $this->session->set_flashdata('error','NIK/Email sudah ada');
                } else {
                    $ins = [
                        'nik'=>$nik,
                        'email'=>$email,
                        'nama'=>$this->input->post('nama', true),
                        'jabatan_id'=>$this->input->post('jabatan_id', true),
                        'status'=>1,
                        'password'=>password_hash($this->input->post('password', true), PASSWORD_BCRYPT)
                    ];
                    $this->User_model->insert($ins);
                    $this->session->set_flashdata('success','Akun berhasil dibuat, silakan login');
                    redirect('login');
                }
            }
        }
        $this->load->view('auth/register', $data);
    }

    public function edit($id) {
        $data['title'] = 'Edit Akun';
        $data['jabatan'] = $this->Jabatan_model->get_all();
        $data['u'] = $this->User_model->get_by_id($id);
        if (!$data['u']) show_404();
        if ($this->input->post()) {
            $this->form_validation->set_rules('nik','NIK','required|trim');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('nama','Nama','required');
            $this->form_validation->set_rules('jabatan_id','Jabatan','required');
            $this->form_validation->set_rules('status','Status','required|in_list[0,1]');
            if ($this->form_validation->run() === TRUE) {
                $nik = $this->input->post('nik', true);
                $email = $this->input->post('email', true);
                if ($this->User_model->cek_nik_email($nik,$email,$id)) {
                    $this->session->set_flashdata('error','NIK/Email sudah dipakai akun lain');
                } else {
                    $upd = [
                        'nik'=>$nik,
                        'email'=>$email,
                        'nama'=>$this->input->post('nama', true),
                        'jabatan_id'=>$this->input->post('jabatan_id', true),
                        'status'=>$this->input->post('status', true)
                    ];
                    $pass = $this->input->post('password', true);
                    if ($pass) $upd['password'] = password_hash($pass, PASSWORD_BCRYPT);
                    $this->User_model->update($id,$upd);
                    // update session if edit self
                    if ($this->user['id'] == $id) {
                        $fresh = $this->User_model->get_by_id($id);
                        $jab = $this->Jabatan_model->get_by_id($fresh->jabatan_id);
                        $this->session->set_userdata('user', [
                            'id'=>$fresh->id,'nik'=>$fresh->nik,'email'=>$fresh->email,'nama'=>$fresh->nama,
                            'jabatan_id'=>$fresh->jabatan_id,'jabatan_nama'=>$jab->nama_jabatan,'status'=>$fresh->status,'foto'=>$fresh->foto
                        ]);
                    }
                    $this->session->set_flashdata('success','Akun diupdate');
                    redirect('akun');
                }
            }
        }
        $this->render('akun/edit', $data);
    }

    public function delete($id) {
        if ($this->user['id'] == $id) {
            $this->session->set_flashdata('error','Tidak bisa hapus akun sendiri');
            redirect('akun');
        }
        $this->User_model->delete($id);
        $this->session->set_flashdata('success','Akun dihapus');
        redirect('akun');
    }
}
