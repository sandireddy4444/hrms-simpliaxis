<?php
namespace App\Http\Controllers;

use App\Models\User;
//use App\Models\attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.index', compact('users'));
      //  return view('attendance.index', compact('attendance'));
    }
}
