<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Excel {
    // header untuk ci_pembelian
    private $headers = ['Kode PO', 'Supplier', 'Tanggal (YYYY-MM-DD)', 'Total', 'Status (draft/proses/selesai/batal)', 'Keterangan'];

    /**
     * Export data pembelian ke Excel .xlsx
     * @param array $rows array object dari get_all()
     * @param string $filename nama file tanpa .xlsx
     */
    public function export($rows, $filename = 'pembelian_export') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pembelian');

        // judul
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'DATA PEMBELIAN - ADMINTO');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A2', 'Diekspor: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // header row 4
        $col = 'A';
        foreach ($this->headers as $h) {
            $cell = $col . '4';
            $sheet->setCellValue($cell, $h);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2C3E50');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;
        }
        $sheet->getRowDimension('4')->setRowHeight(28);

        // data mulai row 5
        $rowNum = 5;
        foreach ($rows as $r) {
            $sheet->setCellValue('A'.$rowNum, $r->kode_po);
            $sheet->setCellValue('B'.$rowNum, $r->supplier);
            $sheet->setCellValue('C'.$rowNum, $r->tanggal);
            $sheet->getStyle('C'.$rowNum)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
            $sheet->setCellValue('D'.$rowNum, $r->total);
            $sheet->getStyle('D'.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->setCellValue('E'.$rowNum, $r->status);
            $sheet->setCellValue('F'.$rowNum, $r->keterangan);
            // border
            $sheet->getStyle('A'.$rowNum.':F'.$rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A'.$rowNum.':F'.$rowNum)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            // zebra
            if ($rowNum % 2 == 0) {
                $sheet->getStyle('A'.$rowNum.':F'.$rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8F9FA');
            }
            // status color
            $statusColors = ['draft'=>'FF6C757D','proses'=>'FFFFC107','selesai'=>'FF198754','batal'=>'FFDC3545'];
            if (isset($statusColors[$r->status])) {
                $sheet->getStyle('E'.$rowNum)->getFont()->getColor()->setARGB($statusColors[$r->status]);
                $sheet->getStyle('E'.$rowNum)->getFont()->setBold(true);
            }
            $rowNum++;
        }

        // autosize
        foreach (range('A','F') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }
        $sheet->getColumnDimension('F')->setWidth(30);
        // freeze
        $sheet->freezePane('A5');
        $sheet->setAutoFilter('A4:F4');

        // output
        $filename = $filename . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Download template import
     */
    public function template() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template');

        // instruksi
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT PEMBELIAN - ADMINTO');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Isi baris 4 dst. Jangan ubah header. Kode PO kosong = auto-generate (PO-YYYY-XXX). Tanggal format YYYY-MM-DD. Status: draft/proses/selesai/batal.');
        $sheet->getStyle('A2')->getFont()->setSize(8)->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF6C757D'));
        $sheet->getStyle('A2')->getAlignment()->setWrapText(true)->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension('2')->setRowHeight(30);

        // header
        $col='A';
        foreach ($this->headers as $h) {
            $cell=$col.'3';
            $sheet->setCellValue($cell,$h);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF3498DB');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setWrapText(true);
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;
        }
        $sheet->getRowDimension('3')->setRowHeight(28);

        // contoh data 3 baris
        $examples = [
            ['','PT Contoh Sejahtera','2026-09-24',1250000,'draft','Contoh keterangan 1'],
            ['PO-2026-999','CV Maju Jaya','2026-09-25',2500000,'proses','Contoh 2'],
            ['','UD Sumber Rejeki','2026-09-26',3000000,'selesai','Contoh 3'],
        ];
        $r=4;
        foreach ($examples as $ex) {
            $sheet->setCellValue('A'.$r, $ex[0]);
            $sheet->setCellValue('B'.$r, $ex[1]);
            $sheet->setCellValue('C'.$r, $ex[2]);
            $sheet->setCellValue('D'.$r, $ex[3]);
            $sheet->getStyle('D'.$r)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->setCellValue('E'.$r, $ex[4]);
            $sheet->setCellValue('F'.$r, $ex[5]);
            $sheet->getStyle('A'.$r.':F'.$r)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A'.$r.':F'.$r)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF95A5A6'))->setItalic(true);
            $r++;
        }
        // tambahkan validasi status dropdown
        $validation = $sheet->getDataValidation('E4:E100');
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"draft,proses,selesai,batal"');
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Status salah');
        $validation->setError('Pilih draft/proses/selesai/batal');
        // copy validation ke range
        for($i=4;$i<=100;$i++){
            $sheet->getCell('E'.$i)->setDataValidation(clone $validation);
        }

        foreach (range('A','F') as $c) $sheet->getColumnDimension($c)->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->freezePane('A4');

        $filename = 'template_import_pembelian.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Import file excel -> return array rows
     * @param string $filePath path tmp file
     * @return array ['data'=>[], 'errors'=>[]]
     */
    public function import($filePath) {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true); // assoc A-F
        $data=[]; $errors=[];
        // header di row 3, data mulai 4
        $start = 4;
        for($i=$start; $i<=count($rows); $i++){
            $row = $rows[$i] ?? null;
            if(!$row) continue;
            $kode = trim($row['A'] ?? '');
            $supplier = trim($row['B'] ?? '');
            $tanggal = trim($row['C'] ?? '');
            $total = $row['D'] ?? '';
            $status = trim(strtolower($row['E'] ?? ''));
            $ket = trim($row['F'] ?? '');
            // skip kosong total
            if($supplier==='' && $tanggal==='' && $total==='') continue;
            if($supplier===''){
                $errors[]="Baris $i: Supplier wajib diisi";
                continue;
            }
            // tanggal parsing: bisa excel date numeric atau string
            if(is_numeric($tanggal)){
                // excel date
                $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
            } else {
                $tanggal = date('Y-m-d', strtotime($tanggal) ?: time());
                if(!strtotime($row['C'])) $tanggal = date('Y-m-d');
                // coba parse lebih robust
                $ts = strtotime($row['C']);
                if($ts) $tanggal = date('Y-m-d',$ts);
            }
            if(!in_array($status, ['draft','proses','selesai','batal'])) $status='draft';
            $total = is_numeric($total) ? $total : (float)preg_replace('/[^0-9.\-]/','',$total);
            $data[] = [
                'kode_po'=> $kode,
                'supplier'=> $supplier,
                'tanggal'=> $tanggal,
                'total'=> $total,
                'status'=> $status,
                'keterangan'=> $ket,
                'row'=> $i
            ];
        }
        return ['data'=>$data,'errors'=>$errors];
    }
}
