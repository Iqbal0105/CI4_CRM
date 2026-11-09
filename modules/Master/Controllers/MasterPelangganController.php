<?php

declare(strict_types=1);

namespace Modules\Master\Controllers;

use App\Controllers\BaseController;
use App\Services\AuditService;
use Modules\Master\Models\PelangganModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MasterPelangganController extends BaseController
{
    protected PelangganModel $pelangganModel;
    protected AuditService $auditService;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->auditService = new AuditService();
    }

    public function index()
    {
        $data = [
            'title'     => 'Master Data Pelanggan',
            'pelanggan' => $this->pelangganModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('Modules\Master\Views\pelanggan\index', $data);
    }

    public function create()
    {
        $data = [
            'title'    => 'Tambah Pelanggan Baru',
            'autoKode' => $this->generateKodePelanggan(),
        ];

        return view('Modules\Master\Views\pelanggan\form', $data);
    }

    public function store()
    {
        $kode = strtoupper(trim((string) $this->request->getPost('kode_pelanggan')));
        if ($kode === '') {
            $kode = $this->generateKodePelanggan();
            $_POST['kode_pelanggan'] = $kode;
        }

        $rules = [
            'kode_pelanggan' => 'required|max_length[50]|is_unique[pelanggan.kode_pelanggan]',
            'nama_pelanggan' => 'required|max_length[200]',
            'no_telp'        => 'required|max_length[30]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rawLimit = str_replace('.', '', (string) $this->request->getPost('limit_kredit'));

        $data = [
            'kode_pelanggan' => strtoupper(trim((string) $this->request->getPost('kode_pelanggan'))),
            'nama_pelanggan' => trim((string) $this->request->getPost('nama_pelanggan')),
            'no_telp'        => trim((string) $this->request->getPost('no_telp')),
            'email'          => trim((string) $this->request->getPost('email')) ?: null,
            'alamat'         => trim((string) $this->request->getPost('alamat')) ?: null,
            'kota'           => trim((string) $this->request->getPost('kota')) ?: null,
            'tipe_pelanggan' => $this->request->getPost('tipe_pelanggan') ?? 'retail',
            'limit_kredit'   => money_round($rawLimit ?: 0),
            'tempo_hari'     => (int) ($this->request->getPost('tempo_hari') ?: 0),
        ];

        $this->pelangganModel->insert($data);
        $id = $this->pelangganModel->getInsertID();
        $this->auditService->log('create', 'pelanggan', $id, null, $data);

        return redirect()->to('master/pelanggan')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return redirect()->to('master/pelanggan')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $data = [
            'title'     => 'Edit Pelanggan - ' . $pelanggan['nama_pelanggan'],
            'pelanggan' => $pelanggan,
        ];

        return view('Modules\Master\Views\pelanggan\form', $data);
    }

    public function update(int $id)
    {
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return redirect()->to('master/pelanggan')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $rules = [
            'kode_pelanggan' => "required|max_length[50]|is_unique[pelanggan.kode_pelanggan,id,{$id}]",
            'nama_pelanggan' => 'required|max_length[200]',
            'no_telp'        => 'required|max_length[30]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rawLimit = str_replace('.', '', (string) $this->request->getPost('limit_kredit'));

        $data = [
            'kode_pelanggan' => strtoupper(trim((string) $this->request->getPost('kode_pelanggan'))),
            'nama_pelanggan' => trim((string) $this->request->getPost('nama_pelanggan')),
            'no_telp'        => trim((string) $this->request->getPost('no_telp')),
            'email'          => trim((string) $this->request->getPost('email')) ?: null,
            'alamat'         => trim((string) $this->request->getPost('alamat')) ?: null,
            'kota'           => trim((string) $this->request->getPost('kota')) ?: null,
            'tipe_pelanggan' => $this->request->getPost('tipe_pelanggan') ?? 'retail',
            'limit_kredit'   => money_round($rawLimit ?: 0),
            'tempo_hari'     => (int) ($this->request->getPost('tempo_hari') ?: 0),
        ];

        $this->pelangganModel->update($id, $data);
        $this->auditService->log('update', 'pelanggan', $id, $pelanggan, $data);

        return redirect()->to('master/pelanggan')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return redirect()->to('master/pelanggan')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $this->pelangganModel->delete($id);
        $this->auditService->log('delete', 'pelanggan', $id, $pelanggan, null);

        return redirect()->to('master/pelanggan')->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function export()
    {
        $customers = $this->pelangganModel->where('deleted_at IS NULL')->orderBy('nama_pelanggan', 'ASC')->findAll();

        $headers = [
            'No', 'Kode Pelanggan', 'Nama Pelanggan', 'Kategori', 'No. Telepon', 'Email', 
            'Alamat', 'Kota', 'Limit Kredit (Rp)', 'Tempo (Hari)', 'Status'
        ];

        $rows = [];
        foreach ($customers as $idx => $c) {
            $rows[] = [
                $idx + 1,
                $c['kode_pelanggan'] ?? '',
                $c['nama_pelanggan'] ?? '',
                ucfirst((string) ($c['tipe_pelanggan'] ?? 'retail')),
                $c['no_telp'] ?? '-',
                $c['email'] ?? '-',
                $c['alamat'] ?? '-',
                $c['kota'] ?? '-',
                (float) ($c['limit_kredit'] ?? $c['limit_piutang'] ?? 0),
                (int) ($c['tempo_hari'] ?? 0),
                ucfirst((string) ($c['status'] ?? 'Aktif')),
            ];
        }

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Master Pelanggan');

            $sheet->fromArray($headers, null, 'A1');

            if (!empty($rows)) {
                $sheet->fromArray($rows, null, 'A2');
            }

            $sheet->getStyle('A1:K1')->getFont()->setBold(true);
            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'Export_Master_Pelanggan_' . date('Ymd_His') . '.xlsx';

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
            export_csv_file('Export_Master_Pelanggan_' . date('Ymd_His') . '.csv', $headers, $rows);
        }
    }

    /**
     * Auto generate sequence kode pelanggan (CUST-0001, CUST-0002, dst)
     */
    protected function generateKodePelanggan(): string
    {
        $all = $this->pelangganModel
            ->withDeleted()
            ->select('kode_pelanggan')
            ->like('kode_pelanggan', 'CUST-', 'after')
            ->findAll();

        $maxNum = 0;
        foreach ($all as $item) {
            $code = (string) ($item['kode_pelanggan'] ?? '');
            if (preg_match('/CUST-(\d+)/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        return sprintf('CUST-%04d', $nextNum);
    }
}

