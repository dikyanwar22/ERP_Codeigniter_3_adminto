<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Modul_model');
    }

    // DataTables: tampilkan semua modul top (level 1) saja
    public function index() {
        $data['title'] = 'Kelola Modul';
        $data['modul_top'] = $this->Modul_model->get_top_modul();
        $this->render('modul/index', $data);
    }

    // Detail: datatables menu yang berkaitan dengan modul tersebut
    public function detail($id) {
        $modul = $this->Modul_model->get_by_id($id);
        if (!$modul) show_404();
        $data['title'] = 'Detail: '.$modul->nama_modul;
        $data['modul'] = $modul;
        $data['breadcrumb'] = $this->Modul_model->get_breadcrumb($id);
        $data['children'] = $this->Modul_model->get_by_parent($id);
        // untuk cek apakah child punya sub-sub lagi
        foreach ($data['children'] as &$c) {
            $c->child_count = $this->Modul_model->count_children($c->id);
        }
        $this->render('modul/detail', $data);
    }

    public function create() {
        $parent_id = (int)$this->input->get('parent_id');
        $data['title'] = 'Tambah Modul';
        $data['parents'] = $this->Modul_model->get_parent_options();
        $data['preset_parent'] = $parent_id;
        if ($this->input->post()) {
            $this->form_validation->set_rules('nama_modul','Nama Modul','required|trim');
            $this->form_validation->set_rules('parent_id','Parent','required|integer');
            $this->form_validation->set_rules('status','Status','required|in_list[0,1]');
            $this->form_validation->set_rules('tipe_menu','Tipe','required|in_list[tunggal,dropdown]');
            if ($this->form_validation->run() === TRUE) {
                $tipe = $this->input->post('tipe_menu');
                $url = $this->input->post('url', true);
                if ($tipe == 'tunggal' && empty($url)) {
                    $this->session->set_flashdata('error','Menu Tunggal wajib isi URL (tidak punya dropdown)');
                    $this->render('modul/create',$data); return;
                }
                if (!empty($url)) {
                    $exists = $this->db->get_where('ci_modul',['url'=>$url])->row();
                    if ($exists) {
                        $this->session->set_flashdata('error','URL sudah dipakai: '.htmlspecialchars($exists->nama_modul).' (ID '.$exists->id.') — harus unik');
                        $this->render('modul/create',$data); return;
                    }
                }
                $parent_id = (int)$this->input->post('parent_id');
                $level = 1;
                if ($parent_id != 0) {
                    $parent = $this->Modul_model->get_by_id($parent_id);
                    if (!$parent) {
                        $this->session->set_flashdata('error','Parent tidak ditemukan');
                        $this->render('modul/create',$data); return;
                    }
                    $level = $parent->level + 1;
                    if ($level > 4) {
                        $this->session->set_flashdata('error','Maksimal 4 level (modul > menu > sub > sub-sub)');
                        $this->render('modul/create',$data); return;
                    }
                }
                $ins = [
                    'nama_modul' => $this->input->post('nama_modul', true),
                    'icon' => $this->input->post('icon', true) ?: 'ri-apps-2-line',
                    'url' => $this->input->post('url', true) ?: null,
                    'parent_id' => $parent_id,
                    'level' => $level,
                    'urutan' => (int)$this->input->post('urutan', true),
                    'status' => (int)$this->input->post('status', true),
                    'tipe' => $tipe == 'dropdown' ? 'dropdown' : 'tunggal'
                ];
                $this->Modul_model->insert($ins);
                $this->session->set_flashdata('success','Modul ditambahkan');
                // redirect ke detail parent jika ada, else ke index
                if ($parent_id != 0) redirect('modul/detail/'.$parent_id);
                else redirect('modul');
            }
        }
        $this->render('modul/create', $data);
    }

    public function edit($id) {
        $data['modul'] = $this->Modul_model->get_by_id($id);
        if (!$data['modul']) show_404();
        $data['title'] = 'Edit Modul';
        $data['parents'] = $this->Modul_model->get_parent_options();
        $data['has_children'] = $this->Modul_model->has_children($id);
        unset($data['parents'][$id]);
        if ($this->input->post()) {
            $this->form_validation->set_rules('nama_modul','Nama','required|trim');
            $this->form_validation->set_rules('parent_id','Parent','required|integer');
            $this->form_validation->set_rules('status','Status','required|in_list[0,1]');
            $this->form_validation->set_rules('tipe_menu','Tipe','required|in_list[tunggal,dropdown]');
            if ($this->form_validation->run() === TRUE) {
                $tipe = $this->input->post('tipe_menu');
                $url = $this->input->post('url', true);
                if ($tipe == 'tunggal' && empty($url)) {
                    $this->session->set_flashdata('error','Menu Tunggal wajib isi URL');
                    $this->render('modul/edit',$data); return;
                }
                if (!empty($url)) {
                    $exists = $this->db->where('url',$url)->where('id !=',$id)->get('ci_modul')->row();
                    if ($exists) {
                        $this->session->set_flashdata('error','URL sudah dipakai: '.htmlspecialchars($exists->nama_modul).' (ID '.$exists->id.') — harus unik');
                        $this->render('modul/edit',$data); return;
                    }
                }
                $parent_id = (int)$this->input->post('parent_id');
                if ($parent_id == $id) {
                    $this->session->set_flashdata('error','Parent tidak boleh diri sendiri');
                    $this->render('modul/edit',$data); return;
                }
                $level = 1;
                if ($parent_id != 0) {
                    $parent = $this->Modul_model->get_by_id($parent_id);
                    if (!$parent) {
                        $this->session->set_flashdata('error','Parent tidak ditemukan');
                        $this->render('modul/edit',$data); return;
                    }
                    if ($this->is_descendant($id, $parent_id)) {
                        $this->session->set_flashdata('error','Parent tidak boleh turunan dari modul ini');
                        $this->render('modul/edit',$data); return;
                    }
                    $level = $parent->level + 1;
                    if ($level > 4) {
                        $this->session->set_flashdata('error','Maksimal 4 level');
                        $this->render('modul/edit',$data); return;
                    }
                }
                $upd = [
                    'nama_modul' => $this->input->post('nama_modul', true),
                    'icon' => $this->input->post('icon', true),
                    'url' => $this->input->post('url', true) ?: null,
                    'parent_id' => $parent_id,
                    'level' => $level,
                    'urutan' => (int)$this->input->post('urutan', true),
                    'status' => (int)$this->input->post('status', true),
                    'tipe' => $tipe == 'dropdown' ? 'dropdown' : 'tunggal'
                ];
                $this->Modul_model->update($id,$upd);
                $this->update_children_level($id, $level);
                $this->session->set_flashdata('success','Modul diupdate');
                // redirect ke parent detail
                if ($parent_id != 0) redirect('modul/detail/'.$parent_id);
                else redirect('modul');
            }
        }
        $this->render('modul/edit', $data);
    }

    private function is_descendant($ancestor_id, $candidate_parent_id) {
        $current = $candidate_parent_id;
        while ($current != 0) {
            if ($current == $ancestor_id) return true;
            $m = $this->Modul_model->get_by_id($current);
            if (!$m) break;
            $current = $m->parent_id;
        }
        return false;
    }

    private function update_children_level($parent_id, $parent_level) {
        $children = $this->db->get_where('ci_modul',['parent_id'=>$parent_id])->result();
        foreach ($children as $c) {
            $new_level = $parent_level + 1;
            $this->db->update('ci_modul',['level'=>$new_level],['id'=>$c->id]);
            $this->update_children_level($c->id, $new_level);
        }
    }

    public function delete($id) {
        $m = $this->Modul_model->get_by_id($id);
        $parent = $m ? $m->parent_id : 0;
        $res = $this->Modul_model->delete($id);
        if ($res === false) {
            $this->session->set_flashdata('error','Gagal hapus: masih memiliki sub menu. Hapus sub menu dulu.');
        } else if ($res) {
            $this->session->set_flashdata('success','Modul dihapus');
        } else {
            $this->session->set_flashdata('error','Gagal hapus');
        }
        if ($parent != 0) redirect('modul/detail/'.$parent);
        else redirect('modul');
    }

    public function toggle($id) {
        $m = $this->Modul_model->get_by_id($id);
        $redirect = $this->input->get('from') ?: ($m && $m->parent_id ? 'modul/detail/'.$m->parent_id : 'modul');
        if ($m) {
            $this->Modul_model->update($id,['status'=> $m->status ? 0 : 1]);
            $this->session->set_flashdata('success','Status diubah jadi '.($m->status ? 'Hide' : 'Show'));
        }
        redirect($redirect);
    }
}
