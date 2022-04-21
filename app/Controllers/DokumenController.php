<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DaftarPersetujuanDokumenModel;
use App\Models\DokumenModel;
use App\Models\JenisDokumenModel;
use App\Models\LampiranDokumenModel;
use App\Models\SoftCopyDokumenModel;
use App\Models\StatusDokumenModel;
use App\Models\UserModel;

class DokumenController extends BaseController
{
    private $dokumenModel;
    private $jenisDocModel;
    private $users;
    private $lampiranDoc;
    private $softCopDoc;
    private $persetujuan;
    private $statusDoc;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->jenisDocModel = new JenisDokumenModel();
        $this->users = new UserModel();
        $this->lampiranDoc = new LampiranDokumenModel();
        $this->softCopDoc = new SoftCopyDokumenModel();
        $this->persetujuan = new DaftarPersetujuanDokumenModel();
        $this->statusDoc = new StatusDokumenModel();
    }

    public function index()
    {
        $data['all'] = $this->dokumenModel->select('nomer_doc, judul_doc, slug_doc, periode_berakhir')->where(['dibuat_oleh' => session()->get('id')])->findAll();
        $data['title'] = 'My Docs';
        return $this->viewLayouts('doc/v_indexDoc', $data);
    }

    public function createDocView()
    {
        $data['tagging'] = array_unique($this->jenisDocModel->getTagging());
        $data['availableDoc'] = $this->dokumenModel->availableDoc();
        $data['title'] = 'Create Docs';
        return $this->viewLayouts('doc/v_createDoc', $data);
    }

    public function detailDocs($ids)
    {
        $data = $this->dokumenModel->firstAllWithJoin($ids);
        if (is_null($data)) return redirect()->to(base_url('/'));

        $data['title'] = $data['judul_doc'];
        $data['dataAll'] = $data[$data['doc_id']];
        $persetujuan = isset($data['dataAll']['persetujuan']) ? $data['dataAll']['persetujuan'] : [];
        $data['owner'] = $this->dokumenModel->ownerDocs($data['dataAll']['dibuat_oleh_user']['id']);
        if (!$this->dokumenModel->availableForThisDocs($data['rahasia'], $persetujuan, $data['dataAll']['dibuat_oleh_user']['id'])) {
            session()->setFlashdata('error', 'You are not have access for this document');
            return redirect()->to(base_url('/'));
        }

        $data['availableForThisDocs'] = $this->dokumenModel->availableAssign($persetujuan);
        $data['isDone'] = $this->dokumenModel->isDone($persetujuan);
        $data['editable'] = $this->dokumenModel->editable($data['dataAll']);
        $data['validDocStatus'] = isset($data['dataAll']['status_docs_kebalikan']) ? $this->dokumenModel->isValidDateDoc($data['dataAll']['status_docs_kebalikan'][0]['periode_berakhir']) : false;
        $data['isBerlaku'] = isset($data['periode_berakhir']) && $data['periode_berakhir'] !== '0000-00-00 00:00:00'  ? $this->dokumenModel->isValidDateDoc($data['periode_berakhir']) : true;
        return $this->viewLayouts('doc/v_detailsDoc', $data);
    }

    public function ajaxUser()
    {
        helper(['form', 'url']);
        if ($this->request->isAJAX()) {
            $q = $this->request->getVar('q') ?? '';
            $data = [];
            $user = $this->users->searchByName($q);
            foreach ($user as $value) {
                $data[] = [
                    'value' => $value->id,
                    'text' => $value->name,
                    'disabled' => false
                ];
            }  
            echo json_encode($data);
        } else {
            echo 'iam human';
        }
    }

    public function ajaxDokumen()
    {
        helper(['form', 'url']);
        if ($this->request->isAJAX()) {
            $q = $this->request->getVar('q') ?? '';
            $data = [];
            $doc = $this->dokumenModel->searchDoc($q);
            foreach ($doc as $value) {
                $res = $this->dokumenModel->getStatusDoc($value->DCID, ['self' => true]) ?? null;
                $data[] = [
                    'value' => $value->DCID,
                    'slug' => $value->slug_doc,
                    'text' => sprintf('%s - %s', $value->nomer_doc, $value->judul_doc),
                    'disabled' => !$this->dokumenModel->checkValidToSelect($value->DCID, $res, $value->periode_berakhir)
                ];
            }  
            echo json_encode($data);
        } else {
            echo 'iam human';
        }
    }

    public function assignDoc($slug)
    {
        $validationRule = [
            'lampiran' => [
                'label' => 'Image File',
                'rules' => 'uploaded[lampiran]'
            ],
        ];

        $getDoc = $this->dokumenModel->where(['slug_doc' => $slug])->first();
        $getStatus = $this->persetujuan->where(['id_user' => session()->get('id'),'id_doc' => $getDoc->id])->first();
        $docId = $getDoc->id;

        if (!$this->validate($validationRule)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }
        
        $imgLampiran = $this->request->getFile('lampiran');
        $imgSoft = $this->request->getFile('softCop');
        $this->persetujuan->update($getStatus['id'], ['status' => 'yes']);

        $this->saveLampiranDoc($imgLampiran, $docId, 'assign');   
        $this->saveSoftDoc($imgSoft, $docId, 'assign'); 
        return redirect()->back();
        
    }

    public function editDoc($id)
    {
        
        $data = $this->dokumenModel->firstAllWithJoin($id);
        if (is_null($data)) return redirect()->to(base_url('/'));

        $data['title'] = "Edit {$data['judul_doc']}";
        $data['dataAll'] = $data[$data['doc_id']];
        $data['tagging'] = $this->jenisDocModel->getTagging($data['doc_id']);
        $data['availableDoc'] = $this->dokumenModel->availableDoc();
        $persetujuan = isset($data['dataAll']['persetujuan']) ? $data['dataAll']['persetujuan'] : [];
        $data['availableForThisDocs'] = $this->dokumenModel->availableAssign($persetujuan);
        $data['editable'] = $this->dokumenModel->editable($data['dataAll']);
        $data['owner'] = $this->dokumenModel->ownerDocs($data['dataAll']['dibuat_oleh_user']['id']);
        if (!$data['owner']) {
            session()->setFlashdata('error', 'You are not have access for this document');
            return redirect()->to(base_url('/'));
        }
        return $this->viewLayouts('doc/v_editDoc', $data);
    }

    public function editDocProcess($id)
    {
        $getId = $this->dokumenModel->firstAllWithJoin($id);
        $getId['dataAll'] = $getId[$getId['doc_id']];
        $persetujuan = isset($getId['dataAll']['persetujuan']) ? $getId['dataAll']['persetujuan'] : [];
        if (!$this->dokumenModel->availableForThisDocs($getId['rahasia'], $persetujuan, $getId['dataAll']['dibuat_oleh_user']['id'])) {
            session()->setFlashdata('error', 'You are not have access for this document');
            return redirect()->to(base_url('/'));
        }
        $mention = $this->request->getVar('ttd') ?? [];
        $tagging = $this->request->getVar('jenis_doc') ?? [];
        $option = $this->request->getVar('option');
        $surat = $this->request->getVar('no_surat') ?? [];
        $imgLampiran = $this->request->getFile('lampiran');
        $imgSoft = $this->request->getFile('softCop');
        $rahasia = $this->request->getVar('rahasia');
        $data = [
            'id' => $getId['doc_id'],
            'nomer_doc' => $this->request->getVar('nomer_doc'),
            'judul_doc' => $this->request->getVar('judul_doc'),
            'slug_doc' => $this->dokumenModel->slug($this->request->getVar('judul_doc')),
            'summary_doc' => $this->request->getVar('summary_doc'),
            'dibuat_oleh' => session()->get('id'),
            'dibuat_tanggal' => $this->request->getVar('dibuat_tanggal'),
            'periode_mulai' => $this->request->getVar('periode_mulai'),
            'periode_berakhir' => $this->request->getVar('periode_berakhir'),
            'rahasia' => $rahasia ? 'ya' : 'nope'
        ];

        $saving = $this->dokumenModel->save($data);
        if (!$saving) {
            session()->setFlashdata('errors', $this->dokumenModel->errors());
            return redirect()->to(base_url('create-doc'))->withInput();
        } else {
            $docId = $getId['doc_id']; 
            $this->saveLampiranDoc($imgLampiran, $docId,'revision');   
            $this->saveSoftDoc($imgSoft, $docId,'revision');  
            $this->saveMention($mention, $docId);
            $this->saveOption($option, $surat, $docId);
            $this->saveJenisDoc($tagging, $docId);
            
            session()->setFlashdata('success', 'Success to update dokumen');
            return redirect()->to(base_url('my-doc'));
        }
    }
    
    public function storeDocView()
    {
        $validationRule = [
            'lampiran' => [
                'label' => 'Image File',
                'rules' => 'uploaded[lampiran]'
            ],
        ];

        if (!$this->validate($validationRule)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->to(base_url('create-doc'))->withInput();
        }

        $mention = $this->request->getVar('ttd') ?? [];
        $tagging = $this->request->getVar('jenis_doc') ?? [];
        $option = $this->request->getVar('option');
        $surat = $this->request->getVar('no_surat') ?? [];
        $imgLampiran = $this->request->getFile('lampiran');
        $imgSoft = $this->request->getFile('softCop');
        $rahasia = $this->request->getVar('rahasia');
        $data = [
            'nomer_doc' => $this->request->getVar('nomer_doc'),
            'judul_doc' => $this->request->getVar('judul_doc'),
            'slug_doc' => $this->dokumenModel->slug($this->request->getVar('judul_doc')),
            'summary_doc' => $this->request->getVar('summary_doc'),
            'dibuat_oleh' => session()->get('id'),
            'dibuat_tanggal' => $this->request->getVar('dibuat_tanggal'),
            'periode_mulai' => $this->request->getVar('periode_mulai'),
            'periode_berakhir' => $this->request->getVar('periode_berakhir'),
            'rahasia' => $rahasia ? 'ya' : 'nope'
        ];

        $saving = $this->dokumenModel->save($data);
        if (!$saving) {
            session()->setFlashdata('errors', $this->dokumenModel->errors());
            return redirect()->to(base_url('create-doc'))->withInput();
        } else {
            $docId = $this->dokumenModel->getInsertID(); 
            $this->saveLampiranDoc($imgLampiran, $docId);   
            $this->saveSoftDoc($imgSoft, $docId);  
            $this->saveMention($mention, $docId);
            $this->saveOption($option, $surat, $docId);
            $this->saveJenisDoc($tagging, $docId);
            
            session()->setFlashdata('success', 'Success to create dokumen');
            return redirect()->to(base_url('my-doc'));
        }
    }

    public function destroyDoc($id)
    {
        $getId = $this->dokumenModel->firstAllWithJoin($id);
        $getId['dataAll'] = $getId[$getId['doc_id']];
        $persetujuan = isset($getId['dataAll']['persetujuan']) ? $getId['dataAll']['persetujuan'] : [];
        if (!$this->dokumenModel->availableForThisDocs($getId['rahasia'], $persetujuan, $getId['dataAll']['dibuat_oleh_user']['id'])) {
            session()->setFlashdata('error', 'You are not have access for this document');
            return redirect()->to(base_url('/'));
        }
        if (!$getId) {
            session()->setFlashdata('error', 'Document not found');
            return redirect()->to(base_url('my-doc'));
        }
        if ($this->request->getVar('trash')) {
            $this->dokumenModel->delete($getId['doc_id']);
        } else {
            $this->dokumenModel->delete($getId['doc_id'], true);
            $this->jenisDocModel->deleteBulkDocId($getId['doc_id']);
            $this->lampiranDoc->deleteBulkDocId($getId['doc_id']);
            $this->softCopDoc->deleteBulkDocId($getId['doc_id']);
            $this->statusDoc->deleteBulkDocId($getId['doc_id']);
        }
        return redirect()->to(base_url('my-doc'));

    }

    public function rollbackDoc($id)
    {
        $getId = $this->dokumenModel->where(['slug_doc' => $id])->get()->getResultArray();
        if (isset($getId[0]) && !is_null($getId[0]['deleted_at'])) {
            $this->dokumenModel->restoreData($getId[0]['id']);
        }
        return redirect()->to(base_url('my-doc'));
    }

    protected function saveLampiranDoc($img, $idDoc, $addOnName = 'original')
    {
        if (!$img->hasMoved() && $img->getSize() > 0) {
            $fileName = $addOnName.'-'.$img->getRandomName();
            $img->move(ROOTPATH.'public/assets/uploads/', $fileName);
            $this->lampiranDoc->insert([
                'id_doc' => $idDoc,
                'id_user' => session()->get('id'),
                'file' => sprintf('%s/assets/uploads/%s', base_url(), $fileName)
            ]);
        }
    }

    protected function saveSoftDoc($img, $idDoc, $addOnName = 'original')
    {
        if (!$img->hasMoved() && $img->getSize() > 0) {
            $fileName = $addOnName.'-'.$img->getRandomName();
            $img->move(ROOTPATH.'public/assets/uploads/', $fileName);
            $this->softCopDoc->insert([
                'id_doc' => $idDoc,
                'id_user' => session()->get('id'),
                'file' => sprintf('%s/assets/uploads/%s', base_url(), $fileName)
            ]);
        }
    }

    protected function saveMention($mention = [], $docId)
    {
        if (count($mention) > 0) {
            $mentionData = [];
            $this->persetujuan->deleteBulkDocId($docId); 
            foreach ($mention as $value) {
                $mentionData[] = [
                    'id_doc' => $docId,
                    'id_user' => $value,
                    'status' => 'nope'
                ];
            }
            $this->persetujuan->insertBatch($mentionData);
        }
    }

    protected function saveOption($option, $surat, $docId)
    {
        if ($option !== '' && !is_null($option)) {
            $this->statusDoc->deleteBulkDocId($docId); 
            $suratTuju = [];
            foreach ($surat as $val) {
                $suratTuju[] = [
                    'id_doc' => $docId,
                    'id_doc_dituju' => $val,
                    'status' => $option
                ];
            }
            $this->statusDoc->insertBatch($suratTuju);
            
        }
    }

    protected function saveJenisDoc($tagging = [], $idDoc)
    {
        if (count($tagging) > 0) {
            $this->jenisDocModel->deleteBulkDocId($idDoc); 
            $insert = [];
            foreach ($tagging as $key => $value) {
                $insert[] = [
                    'id_doc' => $idDoc,
                    'jenis' => $value
                ];
            }
            $this->jenisDocModel->insertBatch($insert);
        }
    }
}
