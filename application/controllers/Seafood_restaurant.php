<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * URL : http://localhost:8000/seafood-restaurant
 */
class Seafood_restaurant extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Visitor_location_model');
    }

    private function json($data, $code = 200) {
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function index() {
        $data['title'] = 'Seafood Restaurant';
        $data['menu'] = [
            ['nama' => 'Grilled Salmon', 'desc' => 'Salmon panggang dengan saus lemon butter dan sayuran segar.', 'harga' => 185000, 'emoji' => '🐟', 'tag' => 'Best Seller'],
            ['nama' => 'Kepiting Saus Padang', 'desc' => 'Kepiting segar berbalut saus padang pedas gurih.', 'harga' => 225000, 'emoji' => '🦀', 'tag' => 'Favorit'],
            ['nama' => 'Udang Bakar Madu', 'desc' => 'Udang jumbo dibakar dengan olesan madu dan bawang putih.', 'harga' => 145000, 'emoji' => '🦐', 'tag' => 'Best Seller'],
            ['nama' => 'Cumi Crispy', 'desc' => 'Cumi goreng tepung renyah, disajikan dengan saus tartar.', 'harga' => 85000, 'emoji' => '🦑', 'tag' => ''],
            ['nama' => 'Lobster Thermidor', 'desc' => 'Lobster panggang dengan saus keju creamy dan herbs.', 'harga' => 395000, 'emoji' => '🦞', 'tag' => 'Premium'],
            ['nama' => 'Fish & Chips', 'desc' => 'Ikan dory crispy dengan kentang goreng dan saus tartar.', 'harga' => 95000, 'emoji' => '🍟', 'tag' => ''],
            ['nama' => 'Kerang Hijau Saus Tiram', 'desc' => 'Kerang hijau segar dimasak dengan saus tiram dan cabai.', 'harga' => 75000, 'emoji' => '🐚', 'tag' => ''],
            ['nama' => 'Seafood Platter', 'desc' => 'Paket lengkap udang, cumi, kerang, dan ikan untuk 2–3 orang.', 'harga' => 325000, 'emoji' => '🍽️', 'tag' => 'Sharing'],
        ];

        $this->load->view('seafood_restaurant/index', $data);
    }

    /**
     * POST /seafood_restaurant/save_location
     * Menerima latitude, longitude, accuracy dari browser lalu simpan + IP.
     */
    public function save_location() {
        if ($this->input->method() !== 'post') {
            return $this->json(['status' => false, 'message' => 'Method harus POST'], 405);
        }

        $lat = $this->input->post('latitude');
        $lng = $this->input->post('longitude');
        $acc = $this->input->post('accuracy');

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return $this->json(['status' => false, 'message' => 'latitude dan longitude wajib diisi'], 422);
        }

        $lat = (float) $lat;
        $lng = (float) $lng;
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return $this->json(['status' => false, 'message' => 'Koordinat tidak valid'], 422);
        }

        $ua = (string) $this->input->user_agent();

        $data = [
            'ip_address' => $this->input->ip_address(),
            'latitude'   => $lat,
            'longitude'  => $lng,
            'accuracy'   => is_numeric($acc) ? (float) $acc : null,
            'user_agent' => mb_substr($ua, 0, 255),
            'page'       => 'seafood_restaurant',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $ok = $this->Visitor_location_model->insert($data);
        if (!$ok) {
            return $this->json(['status' => false, 'message' => 'Gagal menyimpan lokasi'], 500);
        }

        return $this->json([
            'status'  => true,
            'message' => 'Lokasi berhasil dicatat',
            'data'    => ['id' => $this->db->insert_id()],
        ], 201);
    }
}
