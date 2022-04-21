<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'dokumens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul_doc',
        'slug_doc',
        'summary_doc',
        'nomer_doc',
        'dibuat_oleh',
        'dibuat_tanggal',
        'periode_mulai',
        'periode_berakhir',
        'rahasia'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'judul_doc' => 'required',
        'summary_doc' => 'required',
        'nomer_doc' => 'required',
        'dibuat_oleh' => 'required',
        'dibuat_tanggal' => 'required'
    ];
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

    const MENGGATIKAN = 'mengganti';
    const DIGANTIKAN = 'digantikan';
    const DIBATALKAN = 'dibatalkan';
    const MEMBATALKAN = 'membatalkan';

    
    const MENAMBAHKAN = 'menambahkan';
    const DITAMBAHKAN = 'ditambahkan';
    const MERUJUK =  'merujuk';
    const DIRUJUK =  'dirujuk';

    private function queryBuilder()
    {
        return $this->db->table('dokumens');
    }

    public function searchDoc($query = '')
    {
        return $this->queryBuilder()
                ->select('slug_doc, dokumens.id as DCID, nomer_doc, judul_doc, periode_berakhir')
                ->like('nomer_doc', $query)
                ->orLike('judul_doc', $query)
                ->orLike('summary_doc', $query)
                ->limit(5)
                ->get()->getResultObject();
                ;
    }
    
    public function paginateSearch($filters = [])
    {
        $query = $filters['s'] ?? '';

		$currentPage = 1;
		$maxPage = 1;
		$data = 20;
        
        if (isset($filters['limit']) && (int)$filters['limit'] > 0) {
            $data = $filters['limit'];
        }
		if (isset($filters['page']) && (int)$filters['page'] > 0) {
			$currentPage = (int)$filters['page'];
		} else {
			$filters['page'] = $currentPage;
		}
		if (isset($filters['data']) && (int)$filters['data'] > 0) {
			$data = (int)$filters['data'];
		} else {
			$filters['data'] = $data;
		}

        $d = $this->queryBuilder()
            ->select('id')
            ->where('nomer_doc', $query)
            ->orLike('judul_doc', $query)
            ->orLike('summary_doc', $query)
            ->orLike('id', $query)
            ->get()->getResultArray();
        $totalData = (int)count($d); 
		$totalPage = (int)ceil($totalData/$data);

        $result = [
			'current_page' => $currentPage,
			'total_page' => $totalPage,
			'total_data' => $totalData,
			'all' => $data
		];

        $doc = [];
        if (isset($filters['page']) && (int)$filters['page'] > 0) {
            $page = $filters['page'];
            $data = 20;
            if (isset($filters['data']) && (int)$filters['data'] > 0) {
                $data = $filters['data'];
            }
            $offset = ((int)$page-1)*$data;
            $limit = $data;
            $doc = $this->queryBuilder()
                ->select('slug_doc, id, nomer_doc, judul_doc, summary_doc')
                ->where('nomer_doc', $query)
                ->orLike('judul_doc', $query)
                ->orLike('summary_doc', $query)
                ->orLike('id', $query)
                ->offset($offset)
                ->limit($limit)->get()->getResultArray();
        }

		$result['doc'] = $doc;
		return $result;
    }

    public function getTrash()
    {
        return $this->queryBuilder()
            ->select('slug_doc, id, nomer_doc, judul_doc, summary_doc')
            ->where('dibuat_oleh', session()->get('id'))
            ->where('deleted_at IS NOT NULL')
            ->get()->getResultObject();
    }

    public function getAllWithJoin($id)
    {
        $query = $this->queryBuilder()
            ->select('
                jenis_dokumens.jenis, jenis_dokumens.id as jdocId, 
                dokumens.id as doc_id, dokumens.*
            ')
            ->join('jenis_dokumens','jenis_dokumens.id_doc = dokumens.id','inner')
            ->where(sprintf('dibuat_oleh = %s', $id))
            ->get()->getResultArray();
            
        $data = array();
        foreach ($query as $item) {
            if (!isset($data[$item['doc_id']]['doc_id'])) {
                $data[$item['doc_id']]['doc_id'] = $item['doc_id'];
                $data[$item['doc_id']]['nomer_doc'] = $item['nomer_doc'];
                $data[$item['doc_id']]['judul_doc'] = $item['judul_doc'];
                $data[$item['doc_id']]['summary_doc'] = $item['summary_doc'];
                $data[$item['doc_id']]['dibuat_tanggal'] = $item['dibuat_tanggal'];
                $data[$item['doc_id']]['periode_mulai'] = $item['periode_mulai'];
                $data[$item['doc_id']]['rahasia'] = $item['rahasia'];
            }
            $data[$item['doc_id']]['jenis'][] = [
                "id" => $item['jdocId'],
                "jenis" => $item['jenis'],
            ];
        }
        $data['self'] = false;

        $data = $this->getDibuat($id, $data);
        $data = $this->getLampiran($id, $data);
        $data = $this->getSoftCopy($id, $data);
        $data = $this->getDaftarPersetujuan($id, $data);
        $data = $this->getStatusDoc($id, $data);
        return $data;
    }

    public function restoreData($id)
    {
        return $this->queryBuilder()->set('deleted_at', null, true)->where('id', $id)->update();
    }

    public function firstAllWithJoin($id)
    {
        $query = $this->queryBuilder()
            ->select('
                jenis_dokumens.jenis, jenis_dokumens.id as jdocId, 
                dokumens.id as doc_id, dokumens.*, dokumens.deleted_at as dlat
            ')
            ->join('jenis_dokumens','jenis_dokumens.id_doc = dokumens.id','inner')
            ->where('dokumens.slug_doc', $id)
            ->get()->getResultArray();
        
        if (!$query) return null;
            
        $data = array();
        foreach ($query as $item) {
            if (!isset($data['doc_id'])) {
                $data['doc_id'] = $item['doc_id'];
                $data['nomer_doc'] = $item['nomer_doc'];
                $data['slug_doc'] = $item['slug_doc'];
                $data['judul_doc'] = $item['judul_doc'];
                $data['summary_doc'] = $item['summary_doc'];
                $data['dibuat_tanggal'] = $item['dibuat_tanggal'];
                $data['periode_mulai'] = $item['periode_mulai'];
                $data['periode_berakhir'] = $item['periode_berakhir'];
                $data['deleted_at'] = $item['deleted_at'];
                $data['rahasia'] = $item['rahasia'] == 'ya' ? true : false;
            }
            $data['jenis'][] = [
                "id" => $item['jdocId'],
                "jenis" => $item['jenis'],
            ];
            $id =  $item['doc_id'];
        }
        $data['self'] = true;

        $data = $this->getDibuat($id, $data);
        $data = $this->getLampiran($id, $data);
        $data = $this->getSoftCopy($id, $data);
        $data = $this->getDaftarPersetujuan($id, $data);
        $data = $this->getStatusDoc($id, $data);
        return $data;
    }

    public function availableAssign($data, $onlyCheck = false)
    {
        if (count($data) > 0) {
            foreach ($data as $value) {
                if ($value['id_user'] == session()->get('id') && !$value['status']) {
                    return true;
                } else if ($onlyCheck && $value['id_user'] == session()->get('id')) {
                    return true;
                }
            }
        }

        return false;
    }

    public function checkValidToSelect($id, $data, $iamValid)
    {
        $checkValidDate = isset($data[$id]['status_docs_kebalikan']) && $data[$id]['status_docs_kebalikan'][0]['periode_berakhir'] !== '0000-00-00 00:00:00' ? $this->isValidDateDoc($data[$id]['status_docs_kebalikan'][0]['periode_berakhir']) : true;
        $checkValidDateStat = isset($data[$id]['status_docs']) && $data[$id]['status_docs'][0]['periode_berakhir'] !== '0000-00-00 00:00:00' ? $this->isValidDateDoc($data[$id]['status_docs'][0]['periode_berakhir']) : true;
        $validIam = isset($iamValid) && $iamValid !== '0000-00-00 00:00:00' ? $this->isValidDateDoc($iamValid) : true;
        if($validIam && isset($data[$id]['status_docs']) && $data[$id]['status_docs'][0]['status'] == self::MEMBATALKAN && $checkValidDateStat || $validIam &&isset($data[$id]['status_docs_kebalikan']) && $data[$id]['status_docs_kebalikan'][0]['status'] == self::DIBATALKAN && $checkValidDate) {
            return false;
        } else if($validIam && isset($data[$id]['status_docs_kebalikan']) && $data[$id]['status_docs_kebalikan'][0]['status'] == self::DIGANTIKAN && !$checkValidDate) {
            return true;
        } else if($validIam && isset($data[$id]['status_docs']) && $data[$id]['status_docs'][0]['status'] == self::MENGGATIKAN && $checkValidDateStat) {
            return true;
        } else if ($validIam && !isset($data[$id]['status_docs']) && !isset($data[$id]['status_docs_kebalikan'])) {
            return true;
        } else if ($validIam && isset($data[$id]['status_docs']) && $data[$id]['status_docs'][0]['status'] == self::MENAMBAHKAN && $checkValidDateStat) {
            return true;
        }
        return false;
    }

    public function isValidDateDoc($date)
    {
        return strtotime($date) >= strtotime('now');
    }

    public function isDone($data)
    {
        $done = [];
        if (count($data) > 0) {
            foreach ($data as $value) {
                $done[] = $value['status'];
            }
        }
        if (in_array(false, $done) || count($data) < 1) {
            return false;
        }

        return true;
    }

    public function editable($data)
    {
        if(isset($data['persetujuan']) && $this->isDone($data['persetujuan'])) {
            return false;
        }else if (!isset($data['status_docs']) && !isset($data['status_docs_kebalikan']) && isset($data['dibuat_oleh_user']) && session()->get('id') == $data['dibuat_oleh_user']['id']) {
            return true;
        } else if((isset($data['status_docs']) && $data['status_docs'][0]['status'] != self::MEMBATALKAN || isset($data['status_docs_kebalikan']) && $data['status_docs_kebalikan'][0]['status'] != self::DIBATALKAN) && session()->get('id') == $data['dibuat_oleh_user']['id']) {
            return true;
        } else if((isset($data['status_docs']) && $data['status_docs'][0]['status'] != self::MENGGATIKAN || isset($data['status_docs_kebalikan']) && $data['status_docs_kebalikan'][0]['status'] != self::DIGANTIKAN) && session()->get('id') == $data['dibuat_oleh_user']['id']) {
            return true;
        }
        return false;
    }
    
    public function availableForThisDocs($rahasia, $data, $owner)
    {
        $data[] = [
            'id_user' => $owner,
            'status' => false
        ];
        if ($rahasia && $this->availableAssign($data, true)) {
            return true;
        } else if(!$rahasia) {
            return true;
        }

        return false;
    }
    
    public function ownerDocs($id)
    {
        if ($id == session()->get('id')) {
            return true;
        }
        return false;
    }

    public function getLampiran($id, $data = [])
    {

        if ($data['self']) {
            $query = $this->queryBuilder()
            ->select('
                lampiran_dokumens.file, lampiran_dokumens.id as lampid, 
                users.id as id_user, users.username, 
                dokumens.id as doc_id
            ')
            ->join('lampiran_dokumens','lampiran_dokumens.id_doc = dokumens.id')
            ->join('users','users.id = lampiran_dokumens.id_user')
            ->where(sprintf('dokumens.id = %s', $id))
            ->get()->getResultArray();
        } else {
            $query = $this->queryBuilder()
                ->select('
                    lampiran_dokumens.file, lampiran_dokumens.id as lampid, 
                    users.id as id_user, users.username, 
                    dokumens.id as doc_id
                ')
                ->join('lampiran_dokumens','lampiran_dokumens.id_doc = dokumens.id')
                ->join('users','users.id = lampiran_dokumens.id_user')
                ->where(sprintf('dibuat_oleh = %s', $id))
                ->get()->getResultArray();
        }
        
        foreach ($query as $item) {
            $data[$item['doc_id']]['lampiran'][] = [
                "id" => $item['lampid'],
                "username" => $item['username'],
                "file" => $item['file'],
            ];
        }
        return $data;
    }

    public function getSoftCopy($id, $data = [])
    {

        if ($data['self']) {
            $query = $this->queryBuilder()
            ->select('
                softcopy_dokumens.file, softcopy_dokumens.id as softid, 
                users.id as id_user, users.username, 
                dokumens.id as doc_id
            ')
            ->join('softcopy_dokumens','softcopy_dokumens.id_doc = dokumens.id')
            ->join('users','users.id = softcopy_dokumens.id_user')
            ->where(sprintf('dokumens.id = %s', $id))
            ->get()->getResultArray();
        } else {
            $query = $this->queryBuilder()
                ->select('
                    softcopy_dokumens.file, softcopy_dokumens.id as softid, 
                    users.id as id_user, users.username, 
                    dokumens.id as doc_id
                ')
                ->join('softcopy_dokumens','softcopy_dokumens.id_doc = dokumens.id')
                ->join('users','users.id = softcopy_dokumens.id_user')
                ->where(sprintf('dibuat_oleh = %s', $id))
                ->get()->getResultArray();
        }
        
        foreach ($query as $item) {
            $data[$item['doc_id']]['softcopy'][] = [
                "id" => $item['softid'],
                "username" => $item['username'],
                "file" => $item['file'],
            ];
        }
        return $data;
    }

    public function getDibuat($id, $data = [])
    {
        if ($data['self']) {
            $query = $this->queryBuilder()
            ->select('
                users.name, users.id as userid, 
                dokumens.id as doc_id
            ')
            ->join('users','users.id = dokumens.dibuat_oleh')
            ->where(sprintf('dokumens.id = %s', $id))
            ->get()->getResultArray();
        } else {
            $query = $this->queryBuilder()
                ->select('
                    users.name, users.id as userid, 
                    dokumens.id as doc_id
                ')
                ->join('users','users.id = dokumens.dibuat_oleh')
                ->where(sprintf('dibuat_oleh = %s', $id))
                ->get()->getResultArray();
        }
        
        foreach ($query as $item) {
            $data[$item['doc_id']]['dibuat_oleh_user'] = [
                "id" => $item['userid'],
                "name" => $item['name'],
            ];
        }
        return $data;
    }

    public function getDaftarPersetujuan($id, $data = [])
    {
        
        if ($data['self']) {
            $query = $this->queryBuilder()
            ->select('
                daftar_persetujuan_dokumen.id_user, daftar_persetujuan_dokumen.id as dpdi, daftar_persetujuan_dokumen.status,
                dokumens.id as doc_id,
                users.id as idUser, users.*
            ')
            ->join('daftar_persetujuan_dokumen','daftar_persetujuan_dokumen.id_doc = dokumens.id')
            ->join('users','users.id = daftar_persetujuan_dokumen.id_user')
            ->where(sprintf('dokumens.id = %s', $id))
            ->get()->getResultArray();
        } else {
            $query = $this->queryBuilder()
                ->select('
                    daftar_persetujuan_dokumen.id_user, daftar_persetujuan_dokumen.id as dpdi, daftar_persetujuan_dokumen.status,
                    dokumens.id as doc_id,
                    users.id as idUser, users.*
                ')
                ->join('daftar_persetujuan_dokumen','daftar_persetujuan_dokumen.id_doc = dokumens.id')
                ->join('users','users.id = daftar_persetujuan_dokumen.id_user')
                ->where(sprintf('dibuat_oleh = %s', $id))
                ->get()->getResultArray();
        }
        
        foreach ($query as $item) {
            $data[$item['doc_id']]['persetujuan'][] = [
                "id" => $item['dpdi'],
                "id_user" => $item['id_user'],
                "username" => $item['username'],
                "status" => $item['status'] != 'nope' ? true : false,
            ];
        }
        return $data;
    }
    
    public function getStatusDoc($id, $data = [])
    {
        if ($data['self']) {
            $query = $this->queryBuilder()
                ->select('
                    status_dokumens.id_doc_dituju, status_dokumens.id as sdid, status_dokumens.status, 
                    dokumens.id as doc_id, dokumens.nomer_doc as nmDoc, dokumens.judul_doc as jdDoc, dokumens.slug_doc as slDoc, dokumens.periode_mulai, dokumens.periode_berakhir
                ')
                ->join('status_dokumens','status_dokumens.id_doc_dituju = dokumens.id')
                ->where(sprintf('status_dokumens.id_doc = %s', $id))
                ->get()->getResultArray();

            $query2 = $this->queryBuilder()
                ->select('
                    status_dokumens.id_doc, status_dokumens.id as sdid, status_dokumens.status, 
                    dokumens.id as doc_id, dokumens.nomer_doc as nmDoc, dokumens.judul_doc as jdDoc, dokumens.slug_doc as slDoc, dokumens.periode_mulai, dokumens.periode_berakhir
                ')
                ->join('status_dokumens','status_dokumens.id_doc = dokumens.id')
                ->where(sprintf('status_dokumens.id_doc_dituju = %s', $id))
                ->get()->getResultArray();
            
            foreach ($query2 as $item) {
                $data[$id]['status_docs_kebalikan'][] = [
                    "judul_doc" => $item['jdDoc'],
                    "nomer_doc" => $item['nmDoc'],
                    "slug_doc" => $item['slDoc'],
                    "periode_mulai" => $item['periode_mulai'],
                    "periode_berakhir" => $item['periode_berakhir'],
                    "status" => $this->getStatusDocs($item['status']),
                ];
            }
        } else {
            $query = $this->queryBuilder()
            ->select('
                status_dokumens.id_doc_dituju, status_dokumens.id as sdid, status_dokumens.status, 
                dokumens.id as doc_id, dokumens.nomer_doc as nmDoc, dokumens.judul_doc as jdDoc, dokumens.slug_doc as slDoc, dokumens.periode_mulai, dokumens.periode_berakhir
            ')
            ->join('status_dokumens','status_dokumens.id_doc = dokumens.id')
            ->where(sprintf('dibuat_oleh = %s', $id))
            ->get()->getResultArray();
        }
        
        foreach ($query as $item) {
            $data[$id]['status_docs'][] = [
                "judul_doc" => $item['jdDoc'],
                "nomer_doc" => $item['nmDoc'],
                "slug_doc" => $item['slDoc'],
                "periode_mulai" => $item['periode_mulai'],
                "periode_berakhir" => $item['periode_berakhir'],
                "status" => $item['status'],
            ];
        }
        return $data;
    }

    public function getStatusDocs($statu)
    {
        switch ($statu) {
            case self::MEMBATALKAN:
                return self::DIBATALKAN;
                break;
            case self::MENAMBAHKAN:
                return self::DITAMBAHKAN;
                break;
            case self::MERUJUK:
                return self::DIRUJUK;
                break;
            case self::MENGGATIKAN:
                return self::DIGANTIKAN;
                break;
            default:
                'tidak jelas statusnya';
                break;
        }
    }

    public function availableDoc()
    {
        return count($this->findAll()) > 0;
    }
    
    public static function slug($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
