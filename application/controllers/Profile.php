<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

    const AVATAR_DIR = 'assets/public/avatar/';

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Jabatan_model');
        $this->load->helper('avatar');
    }

    public function index() {
        $data['title'] = 'Profile Saya';
        $data['u'] = $this->User_model->get_by_id($this->user['id']);
        $this->render('profile/index', $data);
    }

    /**
     * Upload / ganti foto profile.
     * File disimpan ke assets/public/avatar/
     */
    public function update_avatar() {
        if ($this->input->method() !== 'post') {
            redirect('profile');
        }

        $id = $this->user['id'];
        $dir = FCPATH.self::AVATAR_DIR;

        if (!is_dir($dir)) {
            @mkdir($dir, 0755, TRUE);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            $this->session->set_flashdata('error', 'Folder upload avatar tidak ditemukan atau tidak bisa ditulis: '.self::AVATAR_DIR);
            redirect('profile');
        }

        $config = [
            'upload_path'   => $dir,
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size'      => 2048, // 2 MB
            'encrypt_name'  => TRUE,
            'file_ext_tolower' => TRUE,
            'remove_spaces' => TRUE,
        ];
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            $err = trim(strip_tags($this->upload->display_errors('', '')));
            if (stripos($err, 'did not select') !== FALSE) {
                $msg = 'Pilih file foto terlebih dahulu.';
            } elseif (stripos($err, 'not allowed') !== FALSE) {
                $msg = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP.';
            } elseif (stripos($err, 'exceeds') !== FALSE || stripos($err, 'larger than') !== FALSE) {
                $msg = 'Ukuran file terlalu besar. Maksimal 2 MB.';
            } else {
                $msg = 'Gagal upload foto: '.$err;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('profile');
        }

        $file = $this->upload->data();
        $rel  = self::AVATAR_DIR.$file['file_name'];

        $old = $this->User_model->get_by_id($id);

        if (!$this->User_model->update($id, ['foto' => $rel])) {
            // batalkan: hapus file baru jika gagal simpan ke DB
            @unlink($file['full_path']);
            $this->session->set_flashdata('error', 'Gagal menyimpan foto profile.');
            redirect('profile');
        }

        // hapus foto lama (hanya jika masih di folder avatar)
        if ($old && !empty($old->foto)) {
            $oldPath = avatar_path($old->foto);
            if ($oldPath && strpos(str_replace('\\', '/', $oldPath), '/'.self::AVATAR_DIR) !== FALSE) {
                @unlink($oldPath);
            }
        }

        $this->sync_session($id);
        $this->session->set_flashdata('success', 'Foto profile berhasil diperbarui.');
        redirect('profile');
    }

    /**
     * Ubah password. Nama, NIK, dan Email tidak bisa diubah dari halaman ini.
     */
    public function update_password() {
        if ($this->input->method() !== 'post') {
            redirect('profile');
        }

        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_message('matches', '{field} tidak sama dengan Password.');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', trim(strip_tags(validation_errors('', '<br>'))));
            redirect('profile');
        }

        $id = $this->user['id'];
        $this->User_model->update($id, [
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
        ]);

        $this->session->set_flashdata('success', 'Password berhasil diperbarui.');
        redirect('profile');
    }

    /**
     * Sinkronkan data user di session agar perubahan langsung terlihat
     * di seluruh halaman tanpa perlu login ulang.
     */
    private function sync_session($id) {
        $fresh = $this->User_model->get_by_id($id);
        if (!$fresh) {
            return;
        }
        $jab = $this->Jabatan_model->get_by_id($fresh->jabatan_id);
        $this->session->set_userdata('user', [
            'id'           => $fresh->id,
            'nik'          => $fresh->nik,
            'email'        => $fresh->email,
            'nama'         => $fresh->nama,
            'jabatan_id'   => $fresh->jabatan_id,
            'jabatan_nama' => $jab ? $jab->nama_jabatan : ($this->user['jabatan_nama'] ?? '-'),
            'status'       => $fresh->status,
            'foto'         => $fresh->foto,
        ]);
    }
}
