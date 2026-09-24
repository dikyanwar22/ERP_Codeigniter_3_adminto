<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Dummy untuk Pesan & Notifikasi
 * Data dummy berada di controller (tanpa database)
 * Diakses via jQuery: /notif/messages dan /notif/notifications
 */
class Notif extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }

    // GET /notif/messages -> JSON dummy pesan
    public function messages() {
        $data = [
            ['id'=>1, 'nama'=>'Budi Santoso', 'avatar'=>'https://i.pravatar.cc/100?img=15', 'waktu'=>'2m lalu', 'pesan'=>'Apakah pesanan sudah dikirim?', 'unread'=>true],
            ['id'=>2, 'nama'=>'Siti Aminah', 'avatar'=>'https://i.pravatar.cc/100?img=32', 'waktu'=>'15m lalu', 'pesan'=>'Minta approval PO #PO-2026-008', 'unread'=>true],
            ['id'=>3, 'nama'=>'Ahmad Wijaya', 'avatar'=>'https://i.pravatar.cc/100?img=8', 'waktu'=>'1 jam lalu', 'pesan'=>'Laporan stok gudang sudah diupdate', 'unread'=>true],
            ['id'=>4, 'nama'=>'Dewi Lestari', 'avatar'=>'https://i.pravatar.cc/100?img=26', 'waktu'=>'3 jam lalu', 'pesan'=>'Meeting jam 14.00 jangan lupa!', 'unread'=>false],
            ['id'=>5, 'nama'=>'Rudi Hartono', 'avatar'=>'https://i.pravatar.cc/100?img=12', 'waktu'=>'kemarin', 'pesan'=>'Revisi invoice #INV-9921', 'unread'=>false],
        ];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // GET /notif/notifications -> JSON dummy notifikasi
    public function notifications() {
        $data = [
            ['id'=>1, 'judul'=>'Pesanan baru masuk', 'deskripsi'=>'#ADM-0142 - $2,499', 'waktu'=>'5 menit lalu', 'icon'=>'ri-shopping-cart-line', 'bg'=>'success', 'unread'=>true],
            ['id'=>2, 'judul'=>'Pembayaran diterima', 'deskripsi'=>'#INV-8821 lunas - $1,200', 'waktu'=>'22 menit lalu', 'icon'=>'ri-money-dollar-circle-line', 'bg'=>'primary', 'unread'=>true],
            ['id'=>3, 'judul'=>'Stok menipis', 'deskripsi'=>'Produk "Laptop ASUS" sisa 3 unit', 'waktu'=>'1 jam lalu', 'icon'=>'ri-alarm-warning-line', 'bg'=>'warning', 'unread'=>true],
            ['id'=>4, 'judul'=>'User baru registrasi', 'deskripsi'=>'Andi Pratama mendaftar sebagai staff', 'waktu'=>'3 jam lalu', 'icon'=>'ri-user-add-line', 'bg'=>'info', 'unread'=>true],
            ['id'=>5, 'judul'=>'Backup berhasil', 'deskripsi'=>'Backup harian 24 Sep 2026 selesai', 'waktu'=>'kemarin', 'icon'=>'ri-hard-drive-3-line', 'bg'=>'secondary', 'unread'=>false],
            ['id'=>6, 'judul'=>'Approval dibutuhkan', 'deskripsi'=>'PO #PO-2026-009 menunggu persetujuan', 'waktu'=>'kemarin', 'icon'=>'ri-file-list-3-line', 'bg'=>'danger', 'unread'=>false],
        ];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
