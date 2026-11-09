<?php

declare(strict_types=1);

namespace Modules\Master\Models;

use CodeIgniter\Model;

class SatuanModel extends Model
{
    protected $table            = 'satuan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kode_satuan', 'nama_satuan'];
    protected $useTimestamps    = true;
}

