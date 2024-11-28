<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $headerData = [
            'title' => 'Loteamento',
        ];

        return view('index', $headerData);
    }
}
