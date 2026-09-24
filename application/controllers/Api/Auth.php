<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API Auth JWT
 * Folder: application/controllers/Api/Auth.php
 * Endpoint: POST /api/auth/login  (atau /api/auth/login via routes)
 */
class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('Jwt');
        $this->load->model('User_model');
    }

    private function json($data, $code = 200) {
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    // POST /api/auth/login
    public function login() {
        // hanya POST
        if ($this->input->method() !== 'post') {
            $this->json(['status'=>false,'message'=>'Method harus POST'], 405);
        }

        // support JSON raw body & form-data
        $input = json_decode($this->input->raw_input_stream, true);
        $identity = $this->input->post('identity') ?: ($input['identity'] ?? null);
        $password = $this->input->post('password') ?: ($input['password'] ?? null);
        // alternatif field email/nik
        if (!$identity) $identity = $this->input->post('email') ?: ($input['email'] ?? null);
        if (!$identity) $identity = $this->input->post('nik') ?: ($input['nik'] ?? null);

        if (!$identity || !$password) {
            $this->json(['status'=>false,'message'=>'identity (email/nik) dan password wajib diisi'], 400);
        }

        // cari user by email atau nik (pakai query aman)
        $user = $this->db->query("SELECT * FROM ci_users WHERE email = ? OR nik = ? LIMIT 1", [$identity,$identity])->row();
        if (!$user) {
            $this->json(['status'=>false,'message'=>'Email/NIK tidak ditemukan'], 404);
        }
        if ((int)$user->status === 0) {
            $this->json(['status'=>false,'message'=>'Akun nonaktif tidak bisa login'], 403);
        }
        if (!password_verify($password, $user->password)) {
            $this->json(['status'=>false,'message'=>'Password salah'], 401);
        }

        $jabatan = $this->db->get_where('ci_jabatan',['id'=>$user->jabatan_id])->row();
        $payload = [
            'id' => (int)$user->id,
            'nik' => $user->nik,
            'email' => $user->email,
            'nama' => $user->nama,
            'jabatan_id' => (int)$user->jabatan_id,
            'jabatan_nama' => $jabatan ? $jabatan->nama_jabatan : '-'
        ];

        $token = $this->jwt->encode($payload);
        $decoded = $this->jwt->decode($token);

        $this->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $decoded['exp'] - $decoded['iat'],
                'expires_at' => date('Y-m-d H:i:s', $decoded['exp']),
                'user' => $payload
            ]
        ], 200);
    }

    // GET /api/auth/me -> cek token
    public function me() {
        $payload = $this->jwt->requireAuth();
        if (!$payload) {
            $this->json(['status'=>false,'message'=>'Unauthorized. Token tidak valid atau expired. Gunakan header Authorization: Bearer <token>'], 401);
        }
        $this->json(['status'=>true,'data'=>$payload], 200);
    }
}
