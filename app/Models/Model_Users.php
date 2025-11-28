<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_Users extends Model{
    protected $table      = 'users';
   
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // object
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'first_name', 
        'middle_name', 
        'last_name', 
        'extension_name',
        'email', 
        'user_photo',
        'password',
        'role',
        'status',
        'verify_token',
        'isverified',
        'date_created',
        'date_updated',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_updated';
}
?>