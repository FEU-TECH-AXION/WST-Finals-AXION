<?php
// ==============================================
// File: app/Models/PasswordResetModel.php
// ==============================================

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'email',
        'token',
        'expiry',
        'used',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'email' => 'required|valid_email|max_length[100]',
        'token' => 'required|max_length[255]',
        'expiry' => 'required'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Delete expired tokens
     */
    public function deleteExpired()
    {
        return $this->where('expiry <', date('Y-m-d H:i:s'))->delete();
    }

    /**
     * Delete used tokens
     */
    public function deleteUsed()
    {
        return $this->where('used', 1)->delete();
    }

    /**
     * Clean up old tokens (older than 24 hours)
     */
    public function cleanup()
    {
        $yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));
        return $this->where('created_at <', $yesterday)->delete();
    }
}