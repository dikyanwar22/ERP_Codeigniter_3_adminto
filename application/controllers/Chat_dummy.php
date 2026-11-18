<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data dummy "Pesan Masuk" untuk chatbox di halaman publik /seafood-restaurant.
 *
 * Dipanggil via jQuery AJAX (tanpa login):
 *   GET /api/chat-dummy
 *
 * Response:
 * {
 *   "status": true,
 *   "total": 14,
 *   "total_unread": 4,
 *   "data": [
 *     { "id":1, "nama":"...", "avatar":"...", "pesan":"...",
 *       "waktu":"2026-11-17 10:24:35", "created_at":"2026-11-17 10:24:35", "unread":true }
 *   ]
 * }
 *
 * Tidak ada data yang disimpan: murni dummy untuk tampilan (view/JS).
 */
class Chat_dummy extends CI_Controller {

    public function index() {
        if ($this->input->method() !== 'get') {
            $this->output
                ->set_status_header(405)
                ->set_header('Allow: GET')
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(['status' => false, 'message' => 'Method harus GET']));
            return;
        }

        // menit = berapa menit lalu pesan dibuat (relatif terhadap waktu sekarang)
        $rows = [
            ['nama' => 'Budi Santoso',      'avatar' => 'https://i.pravatar.cc/100?img=12', 'menit' => 4,    'unread' => true,
             'pesan' => 'Selamat siang, apakah bisa reservasi meja untuk 4 orang malam ini sekitar jam 7? Kami ingin area dekat jendela kalau tersedia.'],

            ['nama' => 'Siti Rahmawati',    'avatar' => 'https://i.pravatar.cc/100?img=45', 'menit' => 12,   'unread' => true,
             'pesan' => 'Menu kepiting saus padangnya masih ada?'],

            ['nama' => 'Agus Prasetyo',     'avatar' => 'https://i.pravatar.cc/100?img=33', 'menit' => 27,   'unread' => false,
             'pesan' => 'Terima kasih kemarin pelayanannya cepat, makanannya fresh. Nanti saya datang lagi bersama keluarga.'],

            ['nama' => 'Dewi Lestari',      'avatar' => 'https://i.pravatar.cc/100?img=20', 'menit' => 48,   'unread' => true,
             'pesan' => 'Minta info paket seafood platter untuk 3 orang, harga dan isinya apa saja ya? Apakah bisa request tanpa kerang karena ada yang alergi.'],

            ['nama' => 'Rizky Firmansyah',  'avatar' => 'https://i.pravatar.cc/100?img=8',  'menit' => 75,   'unread' => false,
             'pesan' => 'Apakah menerima pembayaran QRIS?'],

            ['nama' => 'Nur Aisyah',        'avatar' => 'https://i.pravatar.cc/100?img=49', 'menit' => 130,  'unread' => false,
             'pesan' => 'Saya sudah transfer DP untuk reservasi tanggal 20, mohon dicek ya.'],

            ['nama' => 'Hendra Wijaya',     'avatar' => 'https://i.pravatar.cc/100?img=15', 'menit' => 240,  'unread' => true,
             'pesan' => 'Tolong kirimkan lokasi lengkap dan patokan jalan menuju restoran.'],

            ['nama' => 'Maya Kusuma',       'avatar' => 'https://i.pravatar.cc/100?img=26', 'menit' => 400,  'unread' => false,
             'pesan' => 'Udaranya nyaman, cocok untuk acara keluarga besar.'],

            ['nama' => 'Fajar Nugroho',     'avatar' => 'https://i.pravatar.cc/100?img=60', 'menit' => 620,  'unread' => false,
             'pesan' => 'Ada promo hari ini?'],

            ['nama' => 'Rina Marlina',      'avatar' => 'https://i.pravatar.cc/100?img=31', 'menit' => 1500, 'unread' => false,
             'pesan' => 'Pesanan lobster thermidor saya tadi rasanya enak sekali, saus kejunya pas. Terima kasih.'],

            ['nama' => 'Yusuf Maulana',     'avatar' => 'https://i.pravatar.cc/100?img=52', 'menit' => 2800, 'unread' => false,
             'pesan' => 'Bisa kirim menu lengkap dalam bentuk PDF ke email saya?'],

            ['nama' => 'Indah Permata',     'avatar' => 'https://i.pravatar.cc/100?img=44', 'menit' => 4300, 'unread' => false,
             'pesan' => 'Saya dengar ada area outdoor, apakah tersedia meja untuk 10 orang?'],

            ['nama' => 'Tono Saputra',      'avatar' => 'https://i.pravatar.cc/100?img=57', 'menit' => 6000, 'unread' => false,
             'pesan' => 'Terima kasih sudah menyiapkan kursi bayi waktu itu.'],

            ['nama' => 'Lina Anggraini',    'avatar' => 'https://i.pravatar.cc/100?img=24', 'menit' => 8650, 'unread' => false,
             'pesan' => 'Mau tanya, jam buka hari Minggu sampai jam berapa ya?'],
        ];

        $now  = time();
        $data = [];

        foreach ($rows as $i => $r) {
            $ts     = $now - ((int) $r['menit'] * 60);
            $data[] = [
                'id'         => $i + 1,
                'nama'       => $r['nama'],
                'avatar'     => $r['avatar'],
                'pesan'      => $r['pesan'],
                'unread'     => (bool) $r['unread'],
                'created_at' => date('Y-m-d H:i:s', $ts),
                'waktu'      => date('Y-m-d H:i:s', $ts),
            ];
        }

        $unread = 0;
        foreach ($data as $row) {
            if ($row['unread']) {
                $unread++;
            }
        }

        $this->output
            ->set_status_header(200)
            ->set_header('X-Robots-Tag: noindex, nofollow')
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate')
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode([
                'status'       => true,
                'total'        => count($data),
                'total_unread' => $unread,
                'generated_at' => date('Y-m-d H:i:s'),
                'data'         => $data,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
