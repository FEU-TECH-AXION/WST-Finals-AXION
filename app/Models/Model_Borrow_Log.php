<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_Borrow_Log extends Model{
    protected $table      = 'borrow_log';
   
    protected $primaryKey = 'borrow_id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // object
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'borrow_id',
        'item_id', 
        'user_id', 
        'borrow_date', 
        'expected_return_date',
        'status', 
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