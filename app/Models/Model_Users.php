<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_Users extends Model{
    protected $table      = 'tblusers';
   
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // object
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'profile',
        'username', 
        'password', 
        'fullname', 
        'email', 
        'datecreated',
        'verify_token',
        'isverified'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
}
?>