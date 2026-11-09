<?php

declare(strict_types=1);

namespace Modules\Master\Controllers;

use App\Controllers\BaseController;
use Modules\Master\Models\KategoriModel;
use Modules\Master\Models\SatuanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MasterKategoriController extends BaseController
{
    protected KategoriModel $kategoriModel;
    protected SatuanModel $satuanModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->satuanModel = new SatuanModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Kategori Barang',
            'kategori' => $this->kategoriModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('Modules\Master\Views\kategori\index', $data);
    }

    public function satuan()
    {
        $data = [
            'title'  => 'Unit Barang (Satuan)',
            'satuan' => $this->satuanModel->orderBy('id', 'ASC')->findAll(),
        ];

        return view('Modules\Master\Views\satuan\index', $data);
    }

    public function storeKategori()
    {
        $rules = [
            'kode_kategori' => 'required|max_length[50]|is_unique[kategori_produk.kode_kategori]',
            'nama_kategori' => 'required|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->insert([
            'kode_kategori' => strtoupper(trim((string) $this->request->getPost('kode_kategori'))),
            'nama_kategori' => trim((string) $this->request->getPost('nama_kategori')),
        ]);

        return redirect()->to('master/kategori')->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function updateKategori(int $id)
    {
        $kat = $this->kategoriModel->find($id);
        if (!$kat) {
            return redirect()->to('master/kategori')->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'kode_kategori' => "required|max_length[50]|is_unique[kategori_produk.kode_kategori,id,{$id}]",
            'nama_kategori' => 'required|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kategoriModel->update($id, [
            'kode_kategori' => strtoupper(trim((string) $this->request->getPost('kode_kategori'))),
            'nama_kategori' => trim((string) $this->request->getPost('nama_kategori')),
        ]);

        return redirect()->to('master/kategori')->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function deleteKategori(int $id)
    {
        try {
            $this->kategoriModel->delete($id);
            return redirect()->to('master/kategori')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->to('master/kategori')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }
    }

    public function storeSatuan()
    {
        $rules = [
            'kode_satuan' => 'required|max_length[20]|is_unique[satuan.kode_satuan]',
            'nama_satuan' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->satuanModel->insert([
            'kode_satuan' => strtoupper(trim((string) $this->request->getPost('kode_satuan'))),
            'nama_satuan' => trim((string) $this->request->getPost('nama_satuan')),
        ]);

        return redirect()->to('master/satuan')->with('success', 'Satuan barang berhasil ditambahkan.');
    }

    public function updateSatuan(int $id)
    {
        $sat = $this->satuanModel->find($id);
        if (!$sat) {
            return redirect()->to('master/satuan')->with('error', 'Satuan tidak ditemukan.');
        }

        $rules = [
            'kode_satuan' => "required|max_length[20]|is_unique[satuan.kode_satuan,id,{$id}]",
            'nama_satuan' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->satuanModel->update($id, [
            'kode_satuan' => strtoupper(trim((string) $this->request->getPost('kode_satuan'))),
            'nama_satuan' => trim((string) $this->request->getPost('nama_satuan')),
        ]);

        return redirect()->to('master/satuan')->with('success', 'Satuan barang berhasil diperbarui.');
    }

    public function deleteSatuan(int $id)
    {
        try {
            $this->satuanModel->delete($id);
            return redirect()->to('master/satuan')->with('success', 'Satuan berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->to('master/satuan')->with('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh produk.');
        }
    }

    public function exportKategori()
    {
        $db = \Config\Database::connect();
        $categories = $db->table('kategori_produk kp')
            ->select('kp.id, kp.kode_kategori, kp.nama_kategori, kp.created_at, COUNT(p.id) as total_produk')
            ->join('produk p', 'p.kategori_id = kp.id AND p.deleted_at IS NULL', 'left')
            ->groupBy('kp.id, kp.kode_kategori, kp.nama_kategori, kp.created_at')
            ->orderBy('kp.nama_kategori', 'ASC')
            ->get()
            ->getResultArray();

        $headers = ['No', 'Kode Kategori', 'Nama Kategori', 'Deskripsi', 'Jumlah Produk Terkait', 'Tanggal Dibuat'];
        $rows = [];
        foreach ($categories as $idx => $c) {
            $rows[] = [
                $idx + 1,
                $c['kode_kategori'] ?? '',
                $c['nama_kategori'] ?? '',
                $c['deskripsi'] ?? 'Kategori produk standar sistem',
                (int) ($c['total_produk'] ?? 0),
                !empty($c['created_at']) ? date('d/m/Y H:i', strtotime($c['created_at'])) : '-',
            ];
        }

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Kategori Barang');

            $sheet->fromArray($headers, null, 'A1');

            if (!empty($rows)) {
                $sheet->fromArray($rows, null, 'A2');
            }

            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'Export_Master_Kategori_' . date('Ymd_His') . '.xlsx';

            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Throwable $e) {
            export_csv_file('Export_Master_Kategori_' . date('Ymd_His') . '.csv', $headers, $rows);
        }
    }

    public function exportSatuan()
    {
        $db = \Config\Database::connect();
        $satuans = $db->table('satuan s')
            ->select('s.kode_satuan, s.nama_satuan, COUNT(p.id) as total_produk')
            ->join('produk p', 'p.satuan_id = s.id AND p.deleted_at IS NULL', 'left')
            ->groupBy('s.id')
            ->orderBy('s.nama_satuan', 'ASC')
            ->get()
            ->getResultArray();

        $headers = ['No', 'Kode Satuan', 'Nama Satuan', 'Jumlah Produk Terkait'];
        $rows = [];
        foreach ($satuans as $idx => $s) {
            $rows[] = [
                $idx + 1,
                $s['kode_satuan'],
                $s['nama_satuan'],
                $s['total_produk'],
            ];
        }

        export_csv_file('Export_Master_Satuan_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}
