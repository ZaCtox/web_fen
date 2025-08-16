<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Magister;
use Illuminate\Http\Request;

class PublicCourseController extends Controller
{
    public function index(Request $request)
    {
        $magisters = Magister::with('courses.period')->get();
        return view('public.courses', compact('magisters'));
    }
}
