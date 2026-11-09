<?php

declare(strict_types=1);

namespace Modules\Master\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori_produk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_kategori', 'nama_kategori'];
    protected $useTimestamps    = true;
}

