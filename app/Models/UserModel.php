<?php
// ==============================================
// File: app/Models/UserModel.php
// ==============================================

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'name',
        'email',
        'profile_photo',
        'password',
        'role',
        'status',
        'date_created',
        'date_updated'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_created';
    protected $updatedField = 'date_updated';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|max_length[20]',
        'name' => 'required|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[itso,associate,student]',
        'status' => 'in_list[active,inactive]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'valid_email' => 'Please provide a valid email address.',
            'is_unique' => 'This email is already registered.'
        ],
        'user_id' => [
            'is_unique' => 'This User ID already exists.'
        ],
        'password' => [
            'min_length' => 'Password must be at least 6 characters long.'
        ]
    ];
    
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
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Get active users only
     */
    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    /**
     * Get active users by role
     */
    public function getActiveUsersByRole($role)
    {
        return $this->where('role', $role)
                    ->where('status', 'active')
                    ->findAll();
    }

    /**
     * Search users
     */
    public function searchUsers($keyword)
    {
        return $this->like('name', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('user_id', $keyword)
                    ->findAll();
    }

    /**
     * Get user count by role
     */
    public function getUserCountByRole($role)
    {
        return $this->where('role', $role)->countAllResults();
    }

    /**
     * Get active user count
     */
    public function getActiveUserCount()
    {
        return $this->where('status', 'active')->countAllResults();
    }

    /**
     * Check if email exists (excluding specific user)
     */
    public function emailExists($email, $excludeUserId = null)
    {
        $builder = $this->where('email', $email);
        
        if ($excludeUserId) {
            $builder->where('user_id !=', $excludeUserId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials($email, $password)
    {
        $user = $this->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Update user status
     */
    public function updateStatus($userId, $status)
    {
        return $this->update($userId, ['status' => $status]);
    }

    /**
     * Get recently created users
     */
    public function getRecentUsers($limit = 5)
    {
        return $this->orderBy('date_created', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get all users ordered by name
     */
    public function getAllUsersOrdered($order = 'name', $direction = 'ASC')
    {
        return $this->orderBy($order, $direction)->findAll();
    }
}