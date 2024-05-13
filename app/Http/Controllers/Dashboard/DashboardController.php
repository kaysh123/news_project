<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\News\News;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalNews = News::count();
        return view('admin.dashboard', ['totalUsers' => $totalUsers, 'totalNews' => $totalNews,]);
    }
}
