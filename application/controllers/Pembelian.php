<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Contoh Modul Pembelian - terintegrasi dengan MY_Controller (cek login + akses jabatan + status show)
// Pastikan modul "Pembelian" (id 4) status=1 dan jabatan punya akses di ci_akses, baru bisa akses
class Pembelian extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pembelian_model');
    }

    // DataTables: daftar PO
    public function index() {
        $data['title'] = 'Pembelian - Purchase Order';
        $data['pembelian'] = $this->Pembelian_model->get_all();
        $this->render('pembelian/index', $data);
    }

    // Detail PO
    public function detail($id) {
        $data['p'] = $this->Pembelian_model->get_by_id($id);
        if (!$data['p']) show_404();
        $data['title'] = 'Detail PO: '.$data['p']->kode_po;
        $this->render('pembelian/detail', $data);
    }

    public function create() {
        $data['title'] = 'Buat Purchase Order';
        $data['kode'] = $this->Pembelian_model->generate_kode();
        if ($this->input->post()) {
            $this->form_validation->set_rules('kode_po','Kode PO','required|trim|is_unique[ci_pembelian.kode_po]');
            $this->form_validation->set_rules('supplier','Supplier','required|trim');
            $this->form_validation->set_rules('tanggal','Tanggal','required');
            $this->form_validation->set_rules('total','Total','required|numeric');
            $this->form_validation->set_rules('status','Status','required|in_list[draft,proses,selesai,batal]');
            if ($this->form_validation->run() === TRUE) {
                $ins = [
                    'kode_po' => $this->input->post('kode_po', true),
                    'supplier' => $this->input->post('supplier', true),
                    'tanggal' => $this->input->post('tanggal', true),
                    'total' => $this->input->post('total', true),
                    'status' => $this->input->post('status', true),
                    'keterangan' => $this->input->post('keterangan', true),
                    'created_by' => $this->user['id']
                ];
                $this->Pembelian_model->insert($ins);
                $this->session->set_flashdata('success','PO berhasil dibuat');
                redirect('pembelian');
            }
        }
        $this->render('pembelian/create', $data);
    }

    public function edit($id) {
        $data['p'] = $this->Pembelian_model->get_by_id($id);
        if (!$data['p']) show_404();
        $data['title'] = 'Edit PO: '.$data['p']->kode_po;
        if ($this->input->post()) {
            $this->form_validation->set_rules('kode_po','Kode PO','required|trim');
            $this->form_validation->set_rules('supplier','Supplier','required|trim');
            $this->form_validation->set_rules('tanggal','Tanggal','required');
            $this->form_validation->set_rules('total','Total','required|numeric');
            $this->form_validation->set_rules('status','Status','required');
            if ($this->form_validation->run() === TRUE) {
                $upd = [
                    'kode_po' => $this->input->post('kode_po', true),
                    'supplier' => $this->input->post('supplier', true),
                    'tanggal' => $this->input->post('tanggal', true),
                    'total' => $this->input->post('total', true),
                    'status' => $this->input->post('status', true),
                    'keterangan' => $this->input->post('keterangan', true),
                ];
                // cek kode duplicate jika ganti
                if ($upd['kode_po'] != $data['p']->kode_po) {
                    $exists = $this->db->get_where('ci_pembelian',['kode_po'=>$upd['kode_po']])->row();
                    if ($exists) {
                        $this->session->set_flashdata('error','Kode PO sudah ada');
                        $this->render('pembelian/edit',$data); return;
                    }
                }
                $this->Pembelian_model->update($id,$upd);
                $this->session->set_flashdata('success','PO diupdate');
                redirect('pembelian');
            }
        }
        $this->render('pembelian/edit', $data);
    }

    public function delete($id) {
        $this->Pembelian_model->delete($id);
        $this->session->set_flashdata('success','PO dihapus');
        redirect('pembelian');
    }

    // Contoh sub menu: Penerimaan Barang (akses via modul/detail/4 -> Penerimaan)
    public function penerimaan() {
        $data['title'] = 'Penerimaan Barang';
        $data['list'] = $this->Pembelian_model->get_all(); // reuse untuk contoh
        $this->render('pembelian/penerimaan', $data);
    }
}
