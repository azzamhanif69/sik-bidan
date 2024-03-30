<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {

        return view('admin.dashboard.index', [
            'app' => Application::all(),
            'tittle' => 'Dashboard'
        ]);
    }
}
