<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_Equipments extends Model{
    protected $table      = 'inventory';
   
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // object
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'item_id',
        'item_name',
        'item_type',
        'parent_item_id',
        'quantity',
        'item_condition',
        'location',
        'status',
        'date_created',
        'date_updated'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_updated';
}
?>