<?php

namespace App\Models;

use CodeIgniter\Model;

class DaftarPersetujuanDokumenModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'daftar_persetujuan_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_doc','id_user','status'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
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

    public function queryBuilder()
    {
        return $this->db->table('daftar_persetujuan_dokumen');
    }

    public function getAllFromMe()
    {
        $userId = session()->get('id');
        return $this->queryBuilder()
            ->select('*')
            ->join('dokumens', 'dokumens.id = daftar_persetujuan_dokumen.id_doc')
            ->join('users', 'users.id = daftar_persetujuan_dokumen.id_user')
            ->where("id_user = {$userId}")
            ->where('dokumens.deleted_at IS NULL')
            ->where("status = 'nope'")
            ->get()
            ->getResult()
            ;
    }

    public function deleteBulkDocId($docId)
    {
        return $this->queryBuilder()->delete("id_doc = {$docId}");
    }
}
