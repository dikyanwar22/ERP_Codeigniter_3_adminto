<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Jabatan_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) redirect('dashboard');
        redirect('login');
    }

    public function login() {
        if ($this->session->userdata('logged_in')) redirect('dashboard');
        $data['title'] = 'Login - Adminto';
        if ($this->input->post()) {
            $this->form_validation->set_rules('identity','Email/NIK','required|trim');
            $this->form_validation->set_rules('password','Password','required');
            if ($this->form_validation->run() === TRUE) {
                $identity = $this->input->post('identity', true);
                $password = $this->input->post('password', true);
                $user = $this->db->where('email',$identity)->or_where('nik',$identity)->get('ci_users')->row();
                if (!$user) {
                    $user = $this->db->query("SELECT * FROM ci_users WHERE email = ? OR nik = ? LIMIT 1", [$identity,$identity])->row();
                }
                if (!$user) {
                    $this->session->set_flashdata('error','Email/NIK tidak ditemukan');
                } else {
                    if ($user->status == 0) {
                        $this->session->set_flashdata('error','Akun nonaktif (status 0) tidak bisa login. Hubungi admin.');
                    } elseif (!password_verify($password, $user->password)) {
                        $this->session->set_flashdata('error','Password salah');
                    } else {
                        $jabatan = $this->db->get_where('ci_jabatan',['id'=>$user->jabatan_id])->row();
                        $sess_user = [
                            'id' => $user->id,
                            'nik' => $user->nik,
                            'email' => $user->email,
                            'nama' => $user->nama,
                            'jabatan_id' => $user->jabatan_id,
                            'jabatan_nama' => $jabatan ? $jabatan->nama_jabatan : '-',
                            'status' => $user->status,
                            'foto' => $user->foto
                        ];
                        $this->session->set_userdata(['logged_in'=>TRUE,'user'=>$sess_user]);
                        redirect('dashboard');
                    }
                }
            }
        }
        $this->load->view('auth/login', $data);
    }

    public function check_session() {
        $logged = (bool) $this->session->userdata('logged_in');
        $u = $this->session->userdata('user');
        $userId = ($logged && is_array($u) && isset($u['id'])) ? $u['id'] : null;

        $this->output
            ->set_status_header($logged ? 200 : 401)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'logged_in'   => $logged,
                'user_id'     => $userId,
                'server_time' => date('c'),
            ]));
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
