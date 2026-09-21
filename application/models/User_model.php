<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function get_by_login($input) {
        // login bisa email atau NIK
        $this->db->where('email', $input);
        $this->db->or_where('nik', $input);
        $q = $this->db->get('ci_users');
        // karena or_where tanpa group, perlu group - lebih aman pakai manual
        // fallback: cek dua kali jika tidak ketemu
        if ($q->num_rows() == 0) return null;
        // jika ada lebih dari 1, ambil yang match
        foreach ($q->result() as $row) {
            if ($row->email === $input || $row->nik === $input) return $row;
        }
        return $q->row();
    }

    public function get_all() {
        $this->db->select('ci_users.*, ci_jabatan.nama_jabatan');
        $this->db->from('ci_users');
        $this->db->join('ci_jabatan','ci_jabatan.id=ci_users.jabatan_id','left');
        $this->db->order_by('ci_users.created_at','DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('ci_users',['id'=>$id])->row();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('ci_users', $data);
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('ci_users', $data, ['id'=>$id]);
    }

    public function delete($id) {
        return $this->db->delete('ci_users',['id'=>$id]);
    }

    public function cek_nik_email($nik, $email, $exclude_id = 0) {
        $this->db->where(" (nik='$nik' OR email='$email') ");
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->get('ci_users')->num_rows() > 0;
    }
}
