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
            ['gambar' => 'https://www.cookingclassy.com/wp-content/uploads/2018/05/grilled-salmon-3.jpg', 'nama' => 'Grilled Salmon', 'desc' => 'Salmon panggang dengan saus lemon butter dan sayuran segar.', 'harga' => 185000, 'tag' => 'Best Seller'],
            ['gambar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTNa8LRb7LPxU-Pb0FB11-F-E6FrMd6Ss1tpX90W5864aKnUjCfPNX23u4J&s=10', 'nama' => 'Kepiting Saus Padang', 'desc' => 'Kepiting segar berbalut saus padang pedas gurih.', 'harga' => 225000, 'tag' => 'Favorit'],
            ['gambar' => 'https://i0.wp.com/resepkoki.id/wp-content/uploads/2017/10/Resep-Udang-Bakar-Madu.jpg?fit=1080%2C1194&ssl=1', 'nama' => 'Udang Bakar Madu', 'desc' => 'Udang jumbo dibakar dengan olesan madu dan bawang putih.', 'harga' => 145000, 'tag' => 'Best Seller'],
            ['gambar' => 'https://www.dapurkobe.co.id/wp-content/uploads/cumi-crispy-telur-asin.jpg', 'nama' => 'Cumi Crispy', 'desc' => 'Cumi goreng tepung renyah, disajikan dengan saus tartar.', 'harga' => 85000, 'tag' => ''],
            ['gambar' => 'https://www.wholesomeyum.com/wp-content/uploads/2023/11/wholesomeyum-Lobster-Thermidor-24.jpg', 'nama' => 'Lobster Thermidor', 'desc' => 'Lobster panggang dengan saus keju creamy dan herbs.', 'harga' => 395000, 'tag' => 'Premium'],
            ['gambar' => 'https://www.dapurkobe.co.id/wp-content/uploads/fish-and-chips.jpg', 'nama' => 'Fish & Chips', 'desc' => 'Ikan dory crispy dengan kentang goreng dan saus tartar.', 'harga' => 95000, 'tag' => ''],
            ['gambar' => 'https://www.dapurkobe.co.id/wp-content/uploads/kerang-saus-tiram.jpg', 'nama' => 'Kerang Hijau Saus Tiram', 'desc' => 'Kerang hijau segar dimasak dengan saus tiram dan cabai.', 'harga' => 75000, 'tag' => ''],
            ['gambar' => 'https://storage.guirez.com/indigo-seminyak/seafood-platter-1.webp', 'nama' => 'Seafood Platter', 'desc' => 'Paket lengkap udang, cumi, kerang, dan ikan untuk 2–3 orang.', 'harga' => 325000, 'tag' => 'Sharing'],
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
