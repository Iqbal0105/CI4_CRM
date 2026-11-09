<?php

declare(strict_types=1);

namespace Modules\Master\Controllers;

use App\Controllers\BaseController;
use App\Services\AuditService;
use App\Services\KursService;
use Modules\Master\Models\KursModel;
use Modules\Master\Models\SupplierModel;

class MasterSupplierController extends BaseController
{
    protected SupplierModel $supplierModel;
    protected KursModel $kursModel;
    protected AuditService $auditService;
    protected KursService $kursService;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->kursModel = new KursModel();
        $this->auditService = new AuditService();
        $this->kursService = new KursService();
    }

    public function index()
    {
        $currencies = ['USD', 'SGD', 'CNY', 'EUR'];
        $latestRates = [];
        foreach ($currencies as $curr) {
            $latestRates[$curr] = $this->kursModel->where('mata_uang', $curr)
                ->orderBy('tanggal', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
        }

        $data = [
            'title'       => 'Master Supplier & Kurs Valas',
            'supplier'    => $this->supplierModel->orderBy('id', 'DESC')->findAll(),
            'kursList'    => $this->kursModel->orderBy('tanggal', 'DESC')->limit(20)->findAll(),
            'latestRates' => $latestRates,
        ];

        return view('Modules\Master\Views\supplier\index', $data);
    }

    public function store()
    {
        $rules = [
            'kode_supplier' => 'required|max_length[50]|is_unique[supplier.kode_supplier]',
            'nama_supplier' => 'required|max_length[200]',
            'mata_uang'     => 'required|max_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode_supplier' => strtoupper(trim((string) $this->request->getPost('kode_supplier'))),
            'nama_supplier' => trim((string) $this->request->getPost('nama_supplier')),
            'no_telp'       => trim((string) $this->request->getPost('no_telp')),
            'email'         => trim((string) $this->request->getPost('email')) ?: null,
            'alamat'        => trim((string) $this->request->getPost('alamat')) ?: null,
            'mata_uang'     => strtoupper(trim((string) $this->request->getPost('mata_uang'))),
        ];

        $this->supplierModel->insert($data);
        $id = $this->supplierModel->getInsertID();
        $this->auditService->log('create', 'supplier', $id, null, $data);

        return redirect()->to('master/supplier')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('master/supplier')->with('error', 'Supplier tidak ditemukan.');
        }

        $rules = [
            'kode_supplier' => "required|max_length[50]|is_unique[supplier.kode_supplier,id,{$id}]",
            'nama_supplier' => 'required|max_length[200]',
            'mata_uang'     => 'required|max_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kode_supplier' => strtoupper(trim((string) $this->request->getPost('kode_supplier'))),
            'nama_supplier' => trim((string) $this->request->getPost('nama_supplier')),
            'no_telp'       => trim((string) $this->request->getPost('no_telp')),
            'email'         => trim((string) $this->request->getPost('email')) ?: null,
            'alamat'        => trim((string) $this->request->getPost('alamat')) ?: null,
            'mata_uang'     => strtoupper(trim((string) $this->request->getPost('mata_uang'))),
        ];

        $this->supplierModel->update($id, $data);
        $this->auditService->log('update', 'supplier', $id, $supplier, $data);

        return redirect()->to('master/supplier')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('master/supplier')->with('error', 'Supplier tidak ditemukan.');
        }

        $this->supplierModel->delete($id);
        $this->auditService->log('delete', 'supplier', $id, $supplier, null);

        return redirect()->to('master/supplier')->with('success', 'Supplier berhasil dihapus.');
    }

    public function storeKurs()
    {
        $rules = [
            'mata_uang'  => 'required|max_length[5]',
            'nilai_kurs' => 'required|numeric',
            'tanggal'    => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'mata_uang'  => strtoupper(trim((string) $this->request->getPost('mata_uang'))),
            'nilai_kurs' => number_format((float) $this->request->getPost('nilai_kurs'), 6, '.', ''),
            'tanggal'    => $this->request->getPost('tanggal'),
        ];

        $this->kursModel->insert($data);

        return redirect()->to('master/supplier')->with('success', 'Nilai kurs valas berhasil dicatat.');
    }

    /**
     * Synchronize foreign exchange rates from free open API.
     */
    public function syncKursApi()
    {
        $result = $this->kursService->syncRatesFromApi();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('master/supplier')->with('success', $result['message']);
        }

        return redirect()->to('master/supplier')->with('error', $result['message']);
    }

    /**
     * Return latest rates via JSON for dynamic client-side forms.
     */
    public function getLatestKursAjax()
    {
        $rates = $this->kursService->getLatestRates();
        return $this->response->setJSON([
            'status' => 'success',
            'rates'  => $rates,
        ]);
    }

    public function export()
    {
        $suppliers = $this->supplierModel->where('deleted_at IS NULL')->orderBy('nama_supplier', 'ASC')->findAll();

        $headers = [
            'No', 'Kode Supplier', 'Nama Supplier', 'No. Telepon', 'Email', 
            'Alamat', 'Mata Uang', 'Tempo (Hari)', 'Status'
        ];

        $rows = [];
        foreach ($suppliers as $idx => $s) {
            $rows[] = [
                $idx + 1,
                $s['kode_supplier'],
                $s['nama_supplier'],
                $s['no_telp'] ?? '-',
                $s['email'] ?? '-',
                $s['alamat'] ?? '-',
                $s['mata_uang'] ?? 'IDR',
                $s['tempo_hari'] ?? 30,
                ucfirst($s['status'] ?? 'active'),
            ];
        }

        export_csv_file('Export_Master_Supplier_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}

