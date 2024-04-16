<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index', [
            'app' => Application::all(),
            'tittle' => 'Pengaturan'
        ]);
    }
}
