<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman publik (tanpa login): daftar lokasi pengunjung yang tercatat.
 * URL: http://localhost:8000/seafood-restaurant-result
 */
class Seafood_restaurant_result extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Visitor_location_model');
    }

    public function index() {
        $limit = 50;
        $page  = max(1, (int) $this->input->get('page'));
        $offset = ($page - 1) * $limit;

        $total = $this->Visitor_location_model->count_all();

        $data['title']       = 'Selection Result - Lokasi Pengunjung';
        $data['rows']        = $this->Visitor_location_model->get_all($limit, $offset);
        $data['total']       = $total;
        $data['total_today'] = $this->Visitor_location_model->count_today();
        $data['unique_ip']   = $this->Visitor_location_model->count_unique_ip();
        $data['page']        = $page;
        $data['limit']       = $limit;
        $data['total_page']  = max(1, (int) ceil($total / $limit));

        $this->output->set_header('X-Robots-Tag: noindex, nofollow');
        $this->load->view('seafood_restaurant_result/index', $data);
    }

    /**
     * POST /seafood-restaurant-result/delete/{id}
     * Hapus satu row data lokasi pengunjung.
     */
    public function delete($id = null) {
        if ($this->input->method() !== 'post') {
            redirect('seafood-restaurant-result');
        }

        $id   = ($id !== null) ? (int) $id : (int) $this->input->post('id');
        $page = max(1, (int) $this->input->post('page'));

        if ($id <= 0 || !$this->Visitor_location_model->get_by_id($id)) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan atau sudah dihapus.');
        } elseif ($this->Visitor_location_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data lokasi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }

        redirect('seafood-restaurant-result'.($page > 1 ? '?page='.$page : ''));
    }

    /**
     * POST /seafood-restaurant-result/delete_all
     * Hapus semua row data lokasi pengunjung sekaligus.
     */
    public function delete_all() {
        if ($this->input->method() !== 'post') {
            redirect('seafood-restaurant-result');
        }

        $total = $this->Visitor_location_model->count_all();

        if ($total <= 0) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk dihapus.');
        } elseif ($this->Visitor_location_model->delete_all()) {
            $this->session->set_flashdata('success', 'Semua data lokasi berhasil dihapus ('.$total.' data).');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus semua data.');
        }

        redirect('seafood-restaurant-result');
    }
}
