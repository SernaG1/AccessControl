<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessLog;
use App\Models\EmployeeAccessLog;

class HomeController extends Controller
{
    public function index()
    {
        $recentActivities = AccessLog::with('visitor')->orderBy('entry_time', 'desc')->take(10)->get();
        $recentEmployeeActivities = EmployeeAccessLog::with('employee')->orderBy('entry_time', 'desc')->take(10)->get();

        $admin = auth('admin')->user();

        return view('home', compact('recentActivities', 'recentEmployeeActivities', 'admin'));
    }
}
