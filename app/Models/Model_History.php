<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_History extends Model{
    protected $table      = 'history';
   
    protected $primaryKey = 'history_id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // object
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'history_id',
        'item_id',
        'user_id',
        'action',
        'borrowed_date',
        'returned_date',
        'previous_status',
        'new_status',
        'date_created'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_updated';
}
?>