<?php
// File: app/Models/Model_password_reset.php

namespace App\Models;

use CodeIgniter\Model;

class Model_password_reset extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email',
        'token',
        'created_at',
        'expires_at',
        'used'
    ];
    protected $useTimestamps = false;
}