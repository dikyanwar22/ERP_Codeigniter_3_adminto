<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CRUD API Pembelian dengan JWT Auth
 * Folder: application/controllers/Api/Pembelian.php
 * Tabel: ci_pembelian
 *
 * Endpoints (butuh header Authorization: Bearer <token> dari /api/auth/login):
 *  GET    /api/pembelian           -> list semua (support ?page=&limit=&status=&search=)
 *  GET    /api/pembelian/(:num)    -> detail by id  (via /api/pembelian/show/:id juga bisa)
 *  POST   /api/pembelian           -> create (atau /api/pembelian/store)
 *  PUT    /api/pembelian/(:num)    -> update
 *  DELETE /api/pembelian/(:num)    -> delete
 *
 * Alternatif CI default:
 *  GET  /api/pembelian/index
 *  GET  /api/pembelian/show/1
 *  POST /api/pembelian/store
 *  POST /api/pembelian/update/1  (pakai _method PUT jika form)
 *  POST /api/pembelian/delete/1
 */
class Pembelian extends CI_Controller {
    private $user;

    public function __construct() {
        parent::__construct();
        $this->load->library('Jwt');
        $this->load->model('Pembelian_model');
        // proteksi JWT: kecuali login handled di Auth, semua method di sini butuh token
        // biar bisa return JSON 401, jangan redirect
        $this->auth();
    }

    private function auth() {
        $payload = $this->jwt->requireAuth();
        if (!$payload) {
            $this->json(['status'=>false,'message'=>'Unauthorized. Silakan login di POST /api/auth/login dan kirim header Authorization: Bearer <token>'], 401);
        }
        $this->user = $payload;
    }

    private function json($data, $code = 200) {
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    private function getInput() {
        $raw = $this->input->raw_input_stream;
        $json = json_decode($raw, true);
        if (is_array($json) && !empty($json)) return $json;
        // fallback form-data / x-www-form-urlencoded + PUT parse
        $input = $this->input->post();
        if (!empty($input)) return $input;
        // parse PUT body
        if (in_array($this->input->method(), ['put','patch','delete'])) {
            parse_str($raw, $put);
            if (!empty($put)) return $put;
        }
        return [];
    }

    // GET /api/pembelian  atau /api/pembelian/index
    // Support query: ?page=1&limit=10&status=proses&search=PT
    public function index($id = null) {
        // jika id diberikan via /api/pembelian/(:num) -> anggap detail
        if ($id !== null && is_numeric($id)) {
            return $this->show($id);
        }

        // handle method POST di index -> create
        if ($this->input->method() === 'post') {
            return $this->store();
        }
        // handle PUT/DELETE di index dengan id query
        $method = $this->input->method();
        if ($id && in_array($method, ['put','patch'])) return $this->update($id);
        if ($id && $method === 'delete') return $this->destroy($id);

        $page = max(1, (int)($this->input->get('page') ?: 1));
        $limit = max(1, min(100, (int)($this->input->get('limit') ?: 10)));
        $status = $this->input->get('status');
        $search = $this->input->get('search');
        $offset = ($page - 1) * $limit;

        $this->db->from('ci_pembelian p');
        $this->db->join('ci_users u','u.id=p.created_by','left');
        if ($status && in_array($status, ['draft','proses','selesai','batal'])) {
            $this->db->where('p.status', $status);
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('p.kode_po', $search);
            $this->db->or_like('p.supplier', $search);
            $this->db->or_like('p.keterangan', $search);
            $this->db->group_end();
        }
        // count total
        $total = $this->db->count_all_results('', false);
        $this->db->select('p.*, u.nama as pembuat');
        $this->db->order_by('p.created_at','DESC');
        $this->db->limit($limit, $offset);
        $rows = $this->db->get()->result();

        $this->json([
            'status'=>true,
            'message'=>'List pembelian',
            'meta'=>[
                'total'=>$total,
                'page'=>$page,
                'limit'=>$limit,
                'total_page'=>ceil($total/$limit)
            ],
            'data'=>$rows
        ], 200);
    }

    // GET /api/pembelian/show/(:num)  atau GET /api/pembelian/(:num)
    public function show($id = null) {
        if (!$id) $id = $this->input->get('id');
        if (!$id) $this->json(['status'=>false,'message'=>'ID diperlukan'], 400);
        $row = $this->Pembelian_model->get_by_id($id);
        if (!$row) $this->json(['status'=>false,'message'=>'Data tidak ditemukan'], 404);

        // join pembuat
        $pembuat = $this->db->select('nama')->get_where('ci_users',['id'=>$row->created_by])->row();
        $row->pembuat = $pembuat ? $pembuat->nama : null;

        $this->json(['status'=>true,'data'=>$row], 200);
    }

    // POST /api/pembelian  atau POST /api/pembelian/store
    public function store() {
        $in = $this->getInput();
        $kode_po = trim($in['kode_po'] ?? '');
        // auto generate jika kosong
        if (empty($kode_po)) {
            $kode_po = $this->Pembelian_model->generate_kode();
        }
        $data = [
            'kode_po'   => $kode_po,
            'supplier'  => trim($in['supplier'] ?? ''),
            'tanggal'   => trim($in['tanggal'] ?? date('Y-m-d')),
            'total'     => $in['total'] ?? 0,
            'status'    => trim($in['status'] ?? 'draft'),
            'keterangan'=> trim($in['keterangan'] ?? ''),
            'created_by'=> $this->user['id'] ?? null
        ];

        // validasi
        if (empty($data['supplier'])) $this->json(['status'=>false,'message'=>'supplier wajib diisi'], 422);
        if (!strtotime($data['tanggal'])) $this->json(['status'=>false,'message'=>'tanggal tidak valid (Y-m-d)'], 422);
        if (!is_numeric($data['total'])) $this->json(['status'=>false,'message'=>'total harus numeric'], 422);
        if (!in_array($data['status'], ['draft','proses','selesai','batal'])) $this->json(['status'=>false,'message'=>'status harus draft|proses|selesai|batal'], 422);

        // cek unique kode_po
        if ($this->db->get_where('ci_pembelian',['kode_po'=>$data['kode_po']])->row()) {
            $this->json(['status'=>false,'message'=>'kode_po sudah ada, gunakan kode lain atau kosongkan untuk auto-generate'], 409);
        }

        $ok = $this->Pembelian_model->insert($data);
        if (!$ok) $this->json(['status'=>false,'message'=>'Gagal insert: '.$this->db->error()['message']], 500);

        $id = $this->db->insert_id();
        $row = $this->Pembelian_model->get_by_id($id);
        $this->json(['status'=>true,'message'=>'Berhasil dibuat','data'=>$row], 201);
    }

    // PUT /api/pembelian/(:num)  atau POST /api/pembelian/update/(:num)
    public function update($id = null) {
        if (!$id) $id = $this->input->get('id');
        if (!$id) $this->json(['status'=>false,'message'=>'ID diperlukan'], 400);
        $exists = $this->Pembelian_model->get_by_id($id);
        if (!$exists) $this->json(['status'=>false,'message'=>'Data tidak ditemukan'], 404);

        $in = $this->getInput();
        // hanya update field yang dikirim
        $allowed = ['kode_po','supplier','tanggal','total','status','keterangan'];
        $upd = [];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $in)) $upd[$f] = is_string($in[$f]) ? trim($in[$f]) : $in[$f];
        }
        if (empty($upd)) $this->json(['status'=>false,'message'=>'Tidak ada field untuk diupdate'], 400);

        if (isset($upd['kode_po']) && $upd['kode_po'] !== $exists->kode_po) {
            if ($this->db->get_where('ci_pembelian',['kode_po'=>$upd['kode_po']])->row()) {
                $this->json(['status'=>false,'message'=>'kode_po sudah dipakai'], 409);
            }
        }
        if (isset($upd['status']) && !in_array($upd['status'], ['draft','proses','selesai','batal'])) {
            $this->json(['status'=>false,'message'=>'status tidak valid'], 422);
        }
        if (isset($upd['total']) && !is_numeric($upd['total'])) {
            $this->json(['status'=>false,'message'=>'total harus numeric'], 422);
        }
        if (isset($upd['tanggal']) && !strtotime($upd['tanggal'])) {
            $this->json(['status'=>false,'message'=>'tanggal tidak valid'], 422);
        }

        $ok = $this->Pembelian_model->update($id, $upd);
        if (!$ok) $this->json(['status'=>false,'message'=>'Gagal update'], 500);

        $row = $this->Pembelian_model->get_by_id($id);
        $this->json(['status'=>true,'message'=>'Berhasil diupdate','data'=>$row], 200);
    }

    // DELETE /api/pembelian/(:num) atau GET /api/pembelian/delete/(:num) (fallback)
    public function delete($id = null) {
        return $this->destroy($id);
    }
    public function destroy($id = null) {
        if (!$id) $id = $this->input->get('id');
        if (!$id) $this->json(['status'=>false,'message'=>'ID diperlukan'], 400);
        $exists = $this->Pembelian_model->get_by_id($id);
        if (!$exists) $this->json(['status'=>false,'message'=>'Data tidak ditemukan'], 404);

        $ok = $this->Pembelian_model->delete($id);
        if (!$ok) $this->json(['status'=>false,'message'=>'Gagal hapus'], 500);
        $this->json(['status'=>true,'message'=>'Berhasil dihapus','data'=>['id'=>(int)$id]], 200);
    }

    // untuk mapping method lama: /api/pembelian/create
    public function create() { return $this->store(); }
}
