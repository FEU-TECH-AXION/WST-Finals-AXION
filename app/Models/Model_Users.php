<?php
// File: app/Models/Model_users.php

namespace App\Models;

use CodeIgniter\Model;

class Model_users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = [
        'name',
        'email',
        'profile_photo',
        'password',
        'role',
        'status',
        'date_created',
        'date_updated'
    ];
    protected $useTimestamps = false;
}