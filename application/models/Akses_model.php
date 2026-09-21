<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses_model extends CI_Model {
    public function can_access($jabatan_id, $modul_id) {
        return $this->db->get_where('ci_akses',['jabatan_id'=>$jabatan_id,'modul_id'=>$modul_id])->num_rows() > 0;
    }

    public function get_by_jabatan($jabatan_id) {
        $q = $this->db->get_where('ci_akses',['jabatan_id'=>$jabatan_id])->result();
        $ids = [];
        foreach ($q as $r) $ids[] = $r->modul_id;
        return $ids;
    }

    public function save_akses($jabatan_id, $modul_ids) {
        $this->db->trans_start();
        $this->db->delete('ci_akses',['jabatan_id'=>$jabatan_id]);
        foreach ((array)$modul_ids as $mid) {
            // hanya simpan modul status 1
            $modul = $this->db->get_where('ci_modul',['id'=>$mid,'status'=>1])->row();
            if ($modul) {
                $this->db->insert('ci_akses',['jabatan_id'=>$jabatan_id,'modul_id'=>$mid]);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
