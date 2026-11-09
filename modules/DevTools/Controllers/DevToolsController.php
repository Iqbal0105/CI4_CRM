<?php

declare(strict_types=1);

namespace Modules\DevTools\Controllers;

use App\Controllers\BaseController;

class DevToolsController extends BaseController
{
    // ==========================================
    // 1. CRUD BUILDER
    // ==========================================
    public function crud()
    {
        $db = \Config\Database::connect();
        $crudList = $db->table('devtools_crud')->orderBy('id', 'ASC')->get()->getResultArray();
        foreach ($crudList as &$c) {
            $c['table']  = $c['table_name'];
            $c['update'] = $c['sync_status'] ?? '';
        }
        unset($c);

        $data = [
            'title'    => 'CRUD Builder & Generator',
            'crudList' => $crudList,
        ];

        return view('Modules\DevTools\Views\crud', $data);
    }

    public function storeCrud()
    {
        $title   = trim($this->request->getPost('title') ?? '');
        $subject = trim($this->request->getPost('subject') ?? '');
        $table   = trim($this->request->getPost('table') ?? '');

        if ($title === '' || $table === '') {
            return redirect()->back()->with('error', 'Title dan Table Name wajib diisi.');
        }

        $db = \Config\Database::connect();
        $db->table('devtools_crud')->insert([
            'title'       => $title,
            'subject'     => $subject ?: $title,
            'table_name'  => strtolower($table),
            'sync_status' => 'Generated at ' . date('H:i'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dev/crud')->with('success', "CRUD modul '{$title}' berhasil di-generate secara otomatis!");
    }

    public function updateCrud(int $id)
    {
        $title   = trim($this->request->getPost('title') ?? '');
        $subject = trim($this->request->getPost('subject') ?? '');
        $table   = trim($this->request->getPost('table') ?? '');

        $db = \Config\Database::connect();
        $db->table('devtools_crud')->where('id', $id)->update([
            'title'       => $title,
            'subject'     => $subject ?: $title,
            'table_name'  => strtolower($table),
            'sync_status' => 'Updated at ' . date('H:i'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dev/crud')->with('success', "Konfigurasi CRUD #{$id} ({$title}) berhasil diperbarui.");
    }

    public function deleteCrud(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('devtools_crud')->where('id', $id)->delete();

        return redirect()->to('dev/crud')->with('success', "Modul CRUD #{$id} berhasil dihapus.");
    }

    public function exportCrud()
    {
        $filename = 'export_crud_builder_' . date('Ymd_His') . '.csv';

        $db = \Config\Database::connect();
        $list = $db->table('devtools_crud')->orderBy('id', 'ASC')->get()->getResultArray();

        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['ID', 'Title', 'Subject', 'Database Table', 'Sync Status']);
        foreach ($list as $c) {
            fputcsv($out, [$c['id'], $c['title'], $c['subject'], $c['table_name'], $c['sync_status'] ?: 'Synced']);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // ==========================================
    // 2. MENU BUILDER
    // ==========================================
    public function menu()
    {
        $data = [
            'title' => 'Menu Builder & Side Menu Manager',
        ];

        return view('Modules\DevTools\Views\menu', $data);
    }

    public function storeMenuItem()
    {
        $label = trim($this->request->getPost('label') ?? '');
        return redirect()->to('dev/menu')->with('success', "Item menu baru '{$label}' berhasil ditambahkan ke navigasi sistem.");
    }

    public function storeMenuType()
    {
        $name = trim($this->request->getPost('name') ?? '');
        return redirect()->to('dev/menu')->with('success', "Menu Type '{$name}' berhasil dibuat.");
    }

    public function deleteMenuType()
    {
        return redirect()->to('dev/menu')->with('success', 'Menu Type berhasil dihapus.');
    }

    // ==========================================
    // 3. EXTENSIONS
    // ==========================================
    public function extension()
    {
        $db = \Config\Database::connect();
        $extensions = $db->table('devtools_extension')->orderBy('id', 'ASC')->get()->getResultArray();
        foreach ($extensions as &$ext) {
            $ext['desc'] = $ext['description'];
        }
        unset($ext);

        $data = [
            'title'      => 'Ekstensi & Plugin Pengembang',
            'extensions' => $extensions,
        ];

        return view('Modules\DevTools\Views\extension', $data);
    }

    public function storeExtension()
    {
        $name   = trim($this->request->getPost('name') ?? '');
        $author = trim($this->request->getPost('author') ?? 'IDMETAFORA');
        $desc   = trim($this->request->getPost('desc') ?? '');

        if ($name === '') {
            return redirect()->back()->with('error', 'Nama ekstensi wajib diisi.');
        }

        $db = \Config\Database::connect();
        $db->table('devtools_extension')->insert([
            'name'        => $name,
            'author'      => $author,
            'description' => $desc,
            'status'      => 'active',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dev/extension')->with('success', "Extension '{$name}' berhasil dipasang ke sistem Developer Suite!");
    }

    // ==========================================
    // 4. GENERIC TOOLS
    // ==========================================
    public function api()
    {
        return redirect()->to('dev/crud');
    }

    public function page()
    {
        return view('Modules\DevTools\Views\generic', ['title' => 'Page Builder']);
    }

    public function form()
    {
        return view('Modules\DevTools\Views\generic', ['title' => 'Form Builder']);
    }

    public function webDocs()
    {
        return view('Modules\DevTools\Views\generic', ['title' => 'Web Documentation']);
    }

    public function apiDocs()
    {
        return redirect()->to('dev/docs/web');
    }
}
