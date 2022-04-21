<?php

namespace App\Controllers;

use App\Models\DaftarPersetujuanDokumenModel;
use App\Models\DokumenModel;

class Home extends BaseController
{
    private $dokumenModel;
    private $persetujuan;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->persetujuan = new DaftarPersetujuanDokumenModel();
    }

    public function index()
    {
        if ($this->request->getVar('q') != '') {
            $filter = [
                's' => $this->request->getVar('q') ?? '',
                'page' => base64_decode(urldecode($this->request->getVar('p'))) ?? 0
            ];
            $search_doc = $this->dokumenModel->paginateSearch($filter);
            $filter['data'] = $search_doc;
            return view('v_search', $filter);
        }
        $statusDoc = new DaftarPersetujuanDokumenModel();
        $data['countNotif'] = count($statusDoc->getAllFromMe() ?? []);
        return view('welcome_message', $data);
    }

    public function viewNotif()
    {
        $data['notif'] = $this->persetujuan->getAllFromMe();
        $data['title'] = "Notification";
        return $this->viewLayouts('v_notif', $data);
    }

    public function viewTrash()
    {
        $data['all'] = $this->dokumenModel->getTrash();
        $data['title'] = "Trash";
        // dd($data);
        return $this->viewLayouts('v_trash', $data);
    }
}
