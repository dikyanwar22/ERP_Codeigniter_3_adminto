<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf {
    /**
     * Generate PDF per row pembelian
     * @param object $row data pembelian single
     * @param string $mode 'download' atau 'view'
     */
    public function pembelian($row, $mode='download') {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $statusBadge = [
            'draft' => '<span style="background:#6c757d;color:#fff;padding:4px 10px;border-radius:12px;font-size:11px;">DRAFT</span>',
            'proses' => '<span style="background:#ffc107;color:#000;padding:4px 10px;border-radius:12px;font-size:11px;">PROSES</span>',
            'selesai' => '<span style="background:#198754;color:#fff;padding:4px 10px;border-radius:12px;font-size:11px;">SELESAI</span>',
            'batal' => '<span style="background:#dc3545;color:#fff;padding:4px 10px;border-radius:12px;font-size:11px;">BATAL</span>',
        ];
        $statusHtml = $statusBadge[$row->status] ?? $row->status;
        $totalFmt = 'Rp ' . number_format($row->total,0,',','.');
        $tanggalFmt = date('d F Y', strtotime($row->tanggal));
        $created = $row->created_at ? date('d/m/Y H:i', strtotime($row->created_at)) : '-';
        $pembuat = $row->pembuat ?? '-';

        $html = '
        <html><head><meta charset="utf-8">
        <style>
            body{font-family: Helvetica, Arial, sans-serif; color:#2c3e50; font-size:12px;}
            .header{background:#2c3e50;color:#fff;padding:18px 22px;border-radius:8px 8px 0 0;}
            .header h2{margin:0;font-size:18px;letter-spacing:1px;}
            .header small{color:#bdc3c7;}
            .card{border:1px solid #dee2e6;border-radius:8px;overflow:hidden;margin-top:12px;}
            table{width:100%;border-collapse:collapse;}
            th{width:140px;text-align:left;background:#f8f9fa;padding:10px 14px;border-bottom:1px solid #dee2e6;font-size:11px;color:#6c757d;text-transform:uppercase;}
            td{padding:10px 14px;border-bottom:1px solid #dee2e6;}
            .total{font-size:16px;font-weight:bold;color:#2c3e50;}
            .footer{text-align:center;margin-top:18px;font-size:10px;color:#95a5a6;}
            .watermark{position:fixed;top:200px;left:30%;opacity:0.06;font-size:90px;transform:rotate(-20deg);font-weight:bold;}
        </style></head><body>
        <div class="watermark">ADMINTO</div>
        <div class="header">
            <h2>ADMINTO - PURCHASE ORDER</h2>
            <small>ERP System 0.1 • Dokumen resmi pembelian</small>
        </div>
        <div class="card">
            <table>
                <tr><th>Kode PO</th><td><strong>'.htmlspecialchars($row->kode_po).'</strong></td></tr>
                <tr><th>Supplier</th><td>'.htmlspecialchars($row->supplier).'</td></tr>
                <tr><th>Tanggal</th><td>'.$tanggalFmt.'</td></tr>
                <tr><th>Total</th><td class="total">'.$totalFmt.'</td></tr>
                <tr><th>Status</th><td>'.$statusHtml.'</td></tr>
                <tr><th>Keterangan</th><td>'.nl2br(htmlspecialchars($row->keterangan ?: '-')).'</td></tr>
                <tr><th>Pembuat</th><td>'.htmlspecialchars($pembuat).' • '.$created.'</td></tr>
                <tr><th>ID</th><td>#'.$row->id.'</td></tr>
            </table>
        </div>
        <div class="footer">
            Dicetak '.date('d/m/Y H:i:s').' • ADMINTO ERP • Dokumen ini dicetak otomatis dari sistem
        </div>
        </body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'PO_'.preg_replace('/[^A-Za-z0-9\-]/','_',$row->kode_po).'.pdf';
        if($mode==='view'){
            $dompdf->stream($filename, ['Attachment'=>0]);
        } else {
            $dompdf->stream($filename, ['Attachment'=>1]);
        }
        exit;
    }
}
