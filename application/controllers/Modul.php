<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Modul_model');
    }

    // DataTables: tampilkan semua modul top (level 1) saja - OPTIMIZED via JSON
    public function index() {
        $data['title'] = 'Kelola Modul';
        // tidak query modul_top lagi agar tidak N+1 lelet; data diambil via AJAX json()
        $this->render('modul/index', $data);
    }

    // JSON endpoint untuk DataTables serverSide - modul top (parent_id=0)
    // GET /modul/json?draw=1&start=0&length=10&search[value]=...&order[0][column]=5&order[0][dir]=asc
    public function json() {
        // untuk DataTables: map kolom sesuai view modul/index.php
        // 0:id, 1:nama_modul, 2:icon, 3:url, 4:tipe, 5:urutan, 6:status, 7:aksi
        $colMap = [0=>'id',1=>'nama_modul',2=>'icon',3=>'url',4=>'tipe',5=>'urutan',6=>'status',7=>'id'];
        $draw   = (int)$this->input->get('draw');
        $start  = (int)$this->input->get('start');
        $length = $this->input->get('length') !== null ? (int)$this->input->get('length') : 10;
        $search = $this->input->get('search');
        $search_val = is_array($search) ? ($search['value'] ?? '') : '';
        $order  = $this->input->get('order');
        $order_col = 5; $order_dir = 'asc';
        if (is_array($order) && isset($order[0])) {
            $order_col = (int)($order[0]['column'] ?? 5);
            $order_dir = ($order[0]['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
        }
        // sanitasi length: batasi max 100 agar ringan
        if ($length > 100) $length = 100;
        if ($length < 1) $length = 10;

        $recordsTotal = $this->Modul_model->count_all_modul(0);
        $recordsFiltered = $this->Modul_model->count_filtered_modul(0, $search_val);
        $rows = $this->Modul_model->get_datatables_mapped(0, $start, $length, $search_val, $order_col, $order_dir, $colMap);

        // siapkan data untuk DataTables: kirim raw fields, render di JS
        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                'id'          => (int)$r->id,
                'nama_modul'  => $r->nama_modul,
                'icon'        => $r->icon,
                'url'         => $r->url,
                'tipe'        => $r->tipe,
                'urutan'      => (int)$r->urutan,
                'status'      => (int)$r->status,
                'child_count' => (int)$r->child_count,
                'level'       => (int)$r->level,
                'parent_id'   => (int)$r->parent_id,
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]));
    }

    // JSON endpoint untuk detail children - GET /modul/json_detail/{id}
    public function json_detail($id) {
        $id = (int)$id;
        $modul = $this->Modul_model->get_by_id($id);
        if (!$modul) {
            $this->output->set_status_header(404)->set_content_type('application/json')->set_output(json_encode(['error'=>'Modul tidak ditemukan'])); return;
        }
        // map kolom detail: 0:id,1:nama,2:icon,3:url,4:level,5:tipe,6:urutan,7:status,8:aksi
        $colMap = [0=>'id',1=>'nama_modul',2=>'icon',3=>'url',4=>'level',5=>'tipe',6=>'urutan',7=>'status',8=>'id'];
        $draw   = (int)$this->input->get('draw');
        $start  = (int)$this->input->get('start');
        $length = $this->input->get('length') !== null ? (int)$this->input->get('length') : 10;
        $search = $this->input->get('search');
        $search_val = is_array($search) ? ($search['value'] ?? '') : '';
        $order  = $this->input->get('order');
        $order_col = 6; $order_dir = 'asc';
        if (is_array($order) && isset($order[0])) {
            $order_col = (int)($order[0]['column'] ?? 6);
            $order_dir = ($order[0]['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
        }
        if ($length > 100) $length = 100;
        if ($length < 1) $length = 10;

        $recordsTotal = $this->Modul_model->count_all_modul($id);
        $recordsFiltered = $this->Modul_model->count_filtered_modul($id, $search_val);
        $rows = $this->Modul_model->get_datatables_mapped($id, $start, $length, $search_val, $order_col, $order_dir, $colMap);

        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                'id'          => (int)$r->id,
                'nama_modul'  => $r->nama_modul,
                'icon'        => $r->icon,
                'url'         => $r->url,
                'tipe'        => $r->tipe,
                'urutan'      => (int)$r->urutan,
                'status'      => (int)$r->status,
                'child_count' => (int)$r->child_count,
                'level'       => (int)$r->level,
                'parent_id'   => (int)$r->parent_id,
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]));
    }

    // Detail: halaman detail modul - data children diambil via AJAX json_detail()
    public function detail($id) {
        $modul = $this->Modul_model->get_by_id($id);
        if (!$modul) show_404();
        $data['title'] = 'Detail: '.$modul->nama_modul;
        $data['modul'] = $modul;
        $data['breadcrumb'] = $this->Modul_model->get_breadcrumb($id);
        // children tidak diquery di sini lagi (ambil via AJAX biar ringan)
        $data['child_count_total'] = $this->Modul_model->count_children($id);
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
