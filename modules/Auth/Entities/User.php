<?php

declare(strict_types=1);

namespace Modules\Auth\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'id'      => 'integer',
        'role_id' => 'integer',
    ];
}

