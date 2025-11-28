<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_Reservation extends Model{
    protected $table      = 'reservation';
   
    protected $primaryKey = 'reservation_id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // object
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'reservation_id',
        'item_id',
        'user_id',
        'reserved_date',
        'start_time',
        'end_time',
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