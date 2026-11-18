<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lock_screen extends CI_Controller {

    const KEY = 'lock_screen_active';

    public function __construct() {
        parent::__construct();

        $this->output
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
            ->set_header('Pragma: no-cache')
            ->set_header('Expires: 0');
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        if (!$this->session->userdata(self::KEY)) {
            redirect(base_url('/'));
        }

        $user = $this->session->userdata('user');
        $this->load->view('lock_screen/index', [
            'title'     => 'Layar Terkunci - Adminto',
            'lock_user' => is_array($user) ? $user : [],
        ]);
    }

    public function lock() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->session->set_userdata([
            self::KEY        => TRUE,
            'lock_screen_at' => time(),
        ]);

        redirect('lock_screen');
    }

    public function unlock() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        if ($this->input->method() !== 'post') {
            redirect('lock_screen');
        }

        $this->form_validation->set_rules('password', 'Kata sandi', 'required|trim');

        if ($this->form_validation->run() === TRUE) {
            $user     = $this->session->userdata('user');
            $id       = (is_array($user) && isset($user['id'])) ? $user['id'] : NULL;
            $row      = $id ? $this->db->get_where('ci_users', ['id' => $id, 'status' => 1])->row() : NULL;
            $password = $this->input->post('password', TRUE);

            if ($row && password_verify($password, $row->password)) {
                $this->session->unset_userdata([self::KEY, 'lock_screen_at']);
                redirect(base_url('/'));
            }

            $this->session->set_flashdata('lock_error', 'Kata sandi salah. Silakan coba lagi.');
        } else {
            $this->session->set_flashdata('lock_error', 'Kata sandi wajib diisi.');
        }

        redirect('lock_screen');
    }
}
