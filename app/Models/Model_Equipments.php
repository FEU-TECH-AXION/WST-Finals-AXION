<?php
namespace App\Models;

use CodeIgniter\Model;

class Model_Products extends Model{
    protected $table      = 'inventory';
   
    protected $primaryKey = 'id';
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
}
?>