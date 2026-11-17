<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor_location_model extends CI_Model {

    protected $table = 'ci_visitor_locations';

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_all($limit = 50, $offset = 0) {
        return $this->db
            ->order_by('id', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get($this->table)
            ->result();
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function count_today() {
        return $this->db
            ->where('created_at >=', date('Y-m-d').' 00:00:00')
            ->count_all_results($this->table);
    }

    public function count_unique_ip() {
        $row = $this->db->query("SELECT COUNT(DISTINCT ip_address) AS total FROM {$this->table}")->row();
        return $row ? (int) $row->total : 0;
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => (int) $id])->row();
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => (int) $id]);
    }

    /**
     * Hapus semua data lokasi pengunjung (reset auto increment).
     */
    public function delete_all() {
        return $this->db->truncate($this->table);
    }
}
