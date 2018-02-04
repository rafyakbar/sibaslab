<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengaturanController extends Controller
{
    
    public function __construct()
    {
        
    }

    /**
     * Menampilkan halaman pengaturan
     * routes: halaman.pengaturan | web.php
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pengaturan');
    }

}
