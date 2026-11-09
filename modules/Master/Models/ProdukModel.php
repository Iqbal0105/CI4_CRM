<?php

declare(strict_types=1);

namespace Modules\Master\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_produk',
        'barcode',
        'nama_produk',
        'kategori_id',
        'satuan_id',
        'harga_beli',
        'harga_jual',
        'harga_grosir',
        'harga_distributor',
        'stok_minimum',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [];

    public function getWithRelations(?int $id = null): array|object|null
    {
        $builder = $this->builder()
            ->select('produk.*, kategori_produk.nama_kategori, satuan.nama_satuan, COALESCE(stok.qty, 0) as stok_fisik, COALESCE(stok.reserved_qty, 0) as stok_reserved')
            ->join('kategori_produk', 'kategori_produk.id = produk.kategori_id', 'left')
            ->join('satuan', 'satuan.id = produk.satuan_id', 'left')
            ->join('stok', 'stok.produk_id = produk.id', 'left')
            ->where('produk.deleted_at IS NULL');

        if ($id !== null) {
            return $builder->where('produk.id', $id)->get()->getRowArray();
        }

        return $builder->orderBy('produk.id', 'DESC')->get()->getResultArray();
    }
}

