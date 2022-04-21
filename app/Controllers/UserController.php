<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    private $user;

    public function __construct()
    {
        helper(['url']);
        $this->user = new UserModel();
    }

    public function index()
    {
        return view('user/v_login');
    }

    public function loginProces()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $dataUser = $this->user->where([
            'username' => $username,
        ])->first();
        if ($dataUser) {
            if (password_verify($password, $dataUser->password)) {
                session()->set([
                    'username' => $dataUser->username,
                    'name' => $dataUser->name,
                    'id' => $dataUser->id,
                    'logged_in' => TRUE
                ]);
                return redirect()->to(base_url('home'));
            } else {
                session()->setFlashdata('error', 'Username & Password Salah');
                return redirect()->to(base_url('login'));
            }
        } else {
            session()->setFlashdata('error', 'Username & Password Salah');
            return redirect()->to(base_url('login'));
        }
    }

    public function registerUserView()
    {
        return view('user/v_register');
    }

    public function storeUser()
    {
        $validate = $this->validate([
            'password' => 'required',
            'password_conf' => 'matches[password]'
        ]);
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'password_conf' => password_hash($this->request->getVar('password_conf'), PASSWORD_BCRYPT),
            'name' => $this->request->getVar('name'),
        ];
        if (!$this->user->save($data) || !$validate) {
            session()->setFlashdata('error', 'Incomplete data');
            session()->setFlashdata('errors', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        return redirect()->to(base_url('/login'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/login'));
    }
}
