<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_model extends CI_Model {
    public function get_all() {
        return $this->db->order_by('urutan','ASC')->order_by('level','ASC')->get('ci_modul')->result();
    }

    public function get_by_parent($parent_id) {
        return $this->db->where('parent_id',$parent_id)->order_by('urutan','ASC')->order_by('id','ASC')->get('ci_modul')->result();
    }

    public function get_top_modul() {
        return $this->db->where('parent_id',0)->order_by('urutan','ASC')->get('ci_modul')->result();
    }

    public function has_children($id) {
        return $this->db->where('parent_id',$id)->count_all_results('ci_modul') > 0;
    }

    public function count_children($id) {
        return $this->db->where('parent_id',$id)->count_all_results('ci_modul');
    }

    public function get_breadcrumb($id) {
        $trail = [];
        $cur = $this->get_by_id($id);
        while ($cur) {
            array_unshift($trail, $cur);
            if ($cur->parent_id == 0) break;
            $cur = $this->get_by_id($cur->parent_id);
        }
        return $trail;
    }

    public function get_by_id($id) {
        return $this->db->get_where('ci_modul',['id'=>$id])->row();
    }

    public function get_by_url($url) {
        if (!$url) return null;
        return $this->db->get_where('ci_modul',['url'=>$url])->row();
    }

    public function get_parent_options() {
        // untuk dropdown parent: ambil semua modul
        $all = $this->db->order_by('level','ASC')->order_by('urutan','ASC')->get('ci_modul')->result();
        $opts = [0 => '-- Top Modul (Level 1) --'];
        foreach ($all as $m) {
            $prefix = str_repeat('-- ', $m->level - 1);
            // cegah parent level 4 tidak bisa punya anak
            if ($m->level >= 4) continue;
            $opts[$m->id] = $prefix . $m->nama_modul . ' (L'.$m->level.')';
        }
        return $opts;
    }

    public function insert($data) {
        return $this->db->insert('ci_modul', $data);
    }
    public function update($id,$data) {
        return $this->db->update('ci_modul',$data,['id'=>$id]);
    }
    public function delete($id) {
        // hapus juga children recursive? untuk safety cegah jika masih punya anak
        $has_child = $this->db->get_where('ci_modul',['parent_id'=>$id])->num_rows();
        if ($has_child > 0) return false;
        return $this->db->delete('ci_modul',['id'=>$id]);
    }

    // Ambil menu untuk jabatan sesuai status=1 dan akses
    public function get_menu_for_jabatan($jabatan_id) {
        // ambil semua modul status 1 yang diizinkan jabatan
        $this->db->select('m.*');
        $this->db->from('ci_modul m');
        $this->db->join('ci_akses a','a.modul_id=m.id','inner');
        $this->db->where('a.jabatan_id', $jabatan_id);
        $this->db->where('m.status', 1);
        $this->db->order_by('m.urutan','ASC');
        $this->db->order_by('m.level','ASC');
        $rows = $this->db->get()->result();

        // build tree
        $byParent = [];
        foreach ($rows as $r) {
            $byParent[$r->parent_id][] = $r;
        }
        // recursive build
        return $this->build_tree(0, $byParent);
    }

    private function build_tree($parent_id, &$byParent) {
        $tree = [];
        if (!isset($byParent[$parent_id])) return $tree;
        foreach ($byParent[$parent_id] as $node) {
            $children = $this->build_tree($node->id, $byParent);
            $node->children = $children;
            $tree[] = $node;
        }
        return $tree;
    }

    // untuk halaman akses checkbox: get all modul status 1 tree tanpa filter jabatan
    public function get_all_tree() {
        $rows = $this->db->where('status',1)->order_by('urutan','ASC')->order_by('level','ASC')->get('ci_modul')->result();
        // tapi akses butuh semua modul show saja yang bisa di-akses, hide tidak muncul
        $byParent = [];
        foreach ($rows as $r) $byParent[$r->parent_id][] = $r;
        return $this->build_tree(0, $byParent);
    }

    public function get_all_tree_admin() {
        // untuk CRUD modul tampilkan semua termasuk hide
        $rows = $this->db->order_by('urutan','ASC')->order_by('level','ASC')->get('ci_modul')->result();
        $byParent = [];
        foreach ($rows as $r) $byParent[$r->parent_id][] = $r;
        return $this->build_tree(0, $byParent);
    }
}
