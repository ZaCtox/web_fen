<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('public.dashboard'); // Vista principal pública
    }
}
