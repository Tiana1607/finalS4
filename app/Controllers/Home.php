<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('client/login');
    }
    public function index2(): string
    {
        return view('admin/login');
    }
}

