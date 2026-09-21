<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan_model extends CI_Model {
    public function get_all() {
        return $this->db->order_by('id','ASC')->get('ci_jabatan')->result();
    }
    public function get_by_id($id) {
        return $this->db->get_where('ci_jabatan',['id'=>$id])->row();
    }
    public function insert($data) {
        return $this->db->insert('ci_jabatan', $data);
    }
    public function update($id,$data) {
        return $this->db->update('ci_jabatan',$data,['id'=>$id]);
    }
    public function delete($id) {
        return $this->db->delete('ci_jabatan',['id'=>$id]);
    }
}
