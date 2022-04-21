<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'name',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[4]|max_length[20]|is_unique[users.username]',
        'password' => 'required',
        'name' => 'required|min_length[4]|max_length[100]'
    ];
    protected $validationMessages   = [
        
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    private function queryBuilder()
    {
        return $this->db->table('users');
    }

    public function getAll()
    {
        return $this->queryBuilder()->select('username, name')->get()->getResultObject();
    }

    public function searchByName($query = '')
    {
        return $this->queryBuilder()
                ->select('id, username, name')
                ->like('name', $query)
                ->orlike('username', $query)
                ->limit(10)
                ->get()->getResultObject();
                ;
    }
}
