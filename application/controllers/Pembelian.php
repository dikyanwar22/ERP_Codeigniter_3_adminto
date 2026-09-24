<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Contoh Modul Pembelian - terintegrasi dengan MY_Controller (cek login + akses jabatan + status show)
// Pastikan modul "Pembelian" (id 4) status=1 dan jabatan punya akses di ci_akses, baru bisa akses
class Pembelian extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Pembelian_model');
        $this->load->library('Excel');
        $this->load->library('Pdf');
    }

    // Halaman daftar PO - render view saja, data via endpoint AJAX (tidak lelet)
    public function index() {
        $data['title'] = 'Pembelian - Purchase Order';
        $this->render('pembelian/index', $data);
    }

    // Endpoint JSON untuk DataTables server-side (dipanggil via AJAX di pembelian/index.php)
    public function data() {
        // DataTables params
        $draw = (int)$this->input->get('draw');
        $start = (int)$this->input->get('start');
        $length = (int)$this->input->get('length');
        if ($length === 0) $length = 10;
        $search = $this->input->get('search');
        $searchVal = is_array($search) ? ($search['value'] ?? '') : ($this->input->get('search[value]') ?? '');
        $order = $this->input->get('order');
        $orderCol = 2; $orderDir = 'desc';
        if (is_array($order) && isset($order[0]['column'])) {
            $orderCol = (int)$order[0]['column'];
            $orderDir = strtolower($order[0]['dir']) === 'asc' ? 'asc' : 'desc';
        } else {
            // fallback via flat keys
            $oc = $this->input->get('order[0][column]');
            $od = $this->input->get('order[0][dir]');
            if ($oc !== null) $orderCol = (int)$oc;
            if ($od !== null) $orderDir = strtolower($od)==='asc'?'asc':'desc';
        }

        $columns = ['p.kode_po','p.supplier','p.tanggal','p.total','p.status','u.nama', null];
        $orderBy = $columns[$orderCol] ?? 'p.tanggal';
        if ($orderBy === null) $orderBy = 'p.created_at';

        // total tanpa filter
        $total = $this->db->count_all('ci_pembelian');

        // base query filtered
        $this->db->from('ci_pembelian p');
        $this->db->join('ci_users u','u.id=p.created_by','left');
        if ($searchVal !== '' && $searchVal !== null) {
            $this->db->group_start();
            $this->db->like('p.kode_po', $searchVal);
            $this->db->or_like('p.supplier', $searchVal);
            $this->db->or_like('p.tanggal', $searchVal);
            $this->db->or_like('p.status', $searchVal);
            $this->db->or_like('u.nama', $searchVal);
            $this->db->or_like('p.keterangan', $searchVal);
            $this->db->group_end();
        }
        // count filtered
        $filtered = $this->db->count_all_results('', false);

        // ambil data paginated + order
        $this->db->select('p.*, u.nama as pembuat');
        $this->db->order_by($orderBy, $orderDir);
        // secondary order jika orderBy bukan tanggal -> tetap order tanggal desc sebagai penentuan stabil
        if ($orderBy !== 'p.tanggal' && $orderBy !== 'p.created_at') {
            $this->db->order_by('p.tanggal','desc');
        }
        $this->db->limit($length, $start);
        $rows = $this->db->get()->result();

        $data = [];
        foreach ($rows as $p) {
            $kode = '<a href="'.base_url('pembelian/detail/'.$p->id).'" class="fw-semibold text-primary">'.htmlspecialchars($p->kode_po).'</a>';
            $supplier = htmlspecialchars($p->supplier);
            $tanggal = date('d/m/Y', strtotime($p->tanggal));
            $totalFmt = 'Rp '.number_format($p->total,0,',','.');
            if ($p->status=='draft') $badge='<span class="badge bg-secondary">Draft</span>';
            elseif ($p->status=='proses') $badge='<span class="badge bg-warning-subtle text-warning border">Proses</span>';
            elseif ($p->status=='selesai') $badge='<span class="badge bg-success-subtle text-success border">Selesai</span>';
            else $badge='<span class="badge bg-danger-subtle text-danger border">Batal</span>';
            $pembuat = '<small>'.htmlspecialchars($p->pembuat ?? '-').'</small>';
            $aksi = '<a href="'.base_url('pembelian/detail/'.$p->id).'" class="btn btn-sm btn-info" title="Detail"><i class="ri-eye-line"></i></a> '
                  . '<a href="'.base_url('pembelian/edit/'.$p->id).'" class="btn btn-sm btn-light border" title="Edit"><i class="ri-edit-line"></i></a> '
                  . '<a href="'.base_url('pembelian/pdf/'.$p->id).'" class="btn btn-sm btn-danger" title="Export PDF"><i class="ri-file-pdf-line"></i></a> '
                  . '<a href="'.base_url('pembelian/pdf_view/'.$p->id).'" target="_blank" class="btn btn-sm btn-outline-danger" title="View PDF"><i class="ri-eye-2-line"></i></a> '
                  . '<a href="'.base_url('pembelian/delete/'.$p->id).'" class="btn btn-sm btn-dark" onclick="return confirm(\'Hapus PO '.htmlspecialchars($p->kode_po,ENT_QUOTES).'?\')" title="Hapus"><i class="ri-delete-bin-line"></i></a>';
            $data[] = [$kode,$supplier,$tanggal,$totalFmt,$badge,$pembuat,$aksi];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'draw'=>$draw,
                'recordsTotal'=>$total,
                'recordsFiltered'=>$filtered,
                'data'=>$data
            ]));
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

    // ========== EXCEL / PDF ==========
    // Export semua ke Excel (library CI3 Excel, bukan DataTables)
    public function export() {
        $rows = $this->Pembelian_model->get_all();
        $this->excel->export($rows, 'pembelian');
    }

    // Download template import Excel
    public function template() {
        $this->excel->template();
    }

    // Import dari Excel (POST file)
    public function import() {
        if (!$this->input->post() && empty($_FILES)) {
            $this->session->set_flashdata('error','Pilih file Excel template terlebih dahulu.');
            redirect('pembelian');
        }
        if (empty($_FILES['file']['tmp_name'])) {
            $this->session->set_flashdata('error','File tidak ditemukan. Pastikan pilih file .xlsx');
            redirect('pembelian');
        }
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx','xls'])) {
            $this->session->set_flashdata('error','Format harus .xlsx atau .xls');
            redirect('pembelian');
        }
        $res = $this->excel->import($_FILES['file']['tmp_name']);
        if (!empty($res['errors'])) {
            $this->session->set_flashdata('error', implode('<br>', $res['errors']));
            redirect('pembelian');
        }
        $sukses = 0; $gagal = 0; $msg = [];
        foreach ($res['data'] as $d) {
            // jika kode_po kosong generate
            if (empty($d['kode_po'])) {
                $d['kode_po'] = $this->Pembelian_model->generate_kode();
            }
            // cek duplikat
            if ($this->db->get_where('ci_pembelian',['kode_po'=>$d['kode_po']])->row()) {
                $gagal++;
                $msg[]="Baris {$d['row']}: kode {$d['kode_po']} sudah ada - skip";
                continue;
            }
            $ins = [
                'kode_po' => $d['kode_po'],
                'supplier' => $d['supplier'],
                'tanggal' => $d['tanggal'],
                'total' => $d['total'],
                'status' => $d['status'],
                'keterangan' => $d['keterangan'],
                'created_by' => $this->user['id']
            ];
            if ($this->Pembelian_model->insert($ins)) $sukses++; else { $gagal++; $msg[]="Baris {$d['row']}: gagal insert"; }
        }
        $flash = "Import selesai. Sukses: $sukses, Gagal: $gagal.";
        if($msg) $flash .= "<br><small>".implode('<br>',$msg)."</small>";
        $this->session->set_flashdata($gagal ? 'error' : 'success', $flash);
        redirect('pembelian');
    }

    // Export PDF per row
    public function pdf($id) {
        $row = $this->Pembelian_model->get_by_id($id);
        if (!$row) show_404();
        // join pembuat untuk PDF
        $u = $this->db->select('nama')->get_where('ci_users',['id'=>$row->created_by])->row();
        $row->pembuat = $u ? $u->nama : '-';
        $this->pdf->pembelian($row, 'download');
    }

    // View PDF di browser (optional)
    public function pdf_view($id) {
        $row = $this->Pembelian_model->get_by_id($id);
        if (!$row) show_404();
        $u = $this->db->select('nama')->get_where('ci_users',['id'=>$row->created_by])->row();
        $row->pembuat = $u ? $u->nama : '-';
        $this->pdf->pembelian($row, 'view');
    }

    // Contoh sub menu: Penerimaan Barang (akses via modul/detail/4 -> Penerimaan)
    public function penerimaan() {
        $data['title'] = 'Penerimaan Barang';
        $data['list'] = $this->Pembelian_model->get_all(); // reuse untuk contoh
        $this->render('pembelian/penerimaan', $data);
    }
}
