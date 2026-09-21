<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model {
    public function get_all() {
        $this->db->select('p.*, u.nama as pembuat');
        $this->db->from('ci_pembelian p');
        $this->db->join('ci_users u','u.id=p.created_by','left');
        $this->db->order_by('p.created_at','DESC');
        return $this->db->get()->result();
    }
    public function get_by_id($id) {
        return $this->db->get_where('ci_pembelian',['id'=>$id])->row();
    }
    public function insert($data) {
        return $this->db->insert('ci_pembelian',$data);
    }
    public function update($id,$data) {
        return $this->db->update('ci_pembelian',$data,['id'=>$id]);
    }
    public function delete($id) {
        return $this->db->delete('ci_pembelian',['id'=>$id]);
    }
    public function generate_kode() {
        $prefix = 'PO-'.date('Y').'-';
        $last = $this->db->like('kode_po',$prefix,'after')->order_by('id','DESC')->get('ci_pembelian',1)->row();
        if ($last) {
            $num = (int)substr($last->kode_po, -3);
            $num++;
        } else $num = 1;
        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}
