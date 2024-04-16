<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $patientIds = Pasien::pluck('id')->toArray();
        $results = Pasien::whereIn('id', $patientIds)->get();
        $count = $results->count();
        return view('admin.dashboard.index', [
            'app' => Application::all(),
            'tittle' => 'Dashboard',
            'totalPatient' => $count

        ]);
    }
}
